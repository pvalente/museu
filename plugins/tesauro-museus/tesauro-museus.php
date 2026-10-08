<?php
/**
 * Plugin Name: Tesauro Museus
 * Description: Imports the Tesauro de Objetos do Patrimônio Cultural nos Museus Brasileiros (Ferrez, 2016) into Tainacan taxonomies.
 * Version: 0.1.0
 * Requires PHP: 8.0
 * License: GPLv2 or later
 * Text Domain: tesauro-museus
 */

namespace TesauroMuseus;

defined( 'ABSPATH' ) || exit;

const TERM_META_ID  = 'tesauro_id';
const TERM_META_ALT = 'tesauro_alt_label';
const TERM_META_DEF = 'tesauro_scope_note';

/**
 * Imports thesaurus terms (data/*.json, built by tools/build.py) into an existing Tainacan taxonomy.
 *
 * Idempotent: terms are matched by their stable thesaurus id (term meta), so re-running updates names,
 * definitions and synonyms instead of duplicating terms.
 *
 * @param string   $file      Path to the compiled thesaurus JSON.
 * @param string   $taxonomy  WordPress taxonomy name of the Tainacan taxonomy (e.g. tnc_tax_95).
 * @param int|null $max_level Deepest level to import (0 = classes, 1 = subclasses); null imports everything.
 * @param bool     $public_definitions Also put definitions in the term description, which Tainacan serves publicly.
 *                                     Off by default: the source only permits partial reproduction. Definitions
 *                                     are always kept in private term meta for matching.
 * @return array{created:int, updated:int}
 */
function import( string $file, string $taxonomy, ?int $max_level = null, bool $public_definitions = false ): array {
	$data = json_decode( (string) file_get_contents( $file ), true );
	if ( ! isset( $data['terms'] ) ) {
		throw new \RuntimeException( "Not a thesaurus file: $file" );
	}
	if ( ! taxonomy_exists( $taxonomy ) ) {
		throw new \RuntimeException( "Unknown taxonomy: $taxonomy" );
	}

	$existing = [];
	foreach ( get_terms( [ 'taxonomy' => $taxonomy, 'hide_empty' => false, 'meta_key' => TERM_META_ID ] ) as $term ) {
		$existing[ get_term_meta( $term->term_id, TERM_META_ID, true ) ] = $term->term_id;
	}

	$stats  = [ 'created' => 0, 'updated' => 0 ];
	$wp_ids = [];
	// Parents come before children in the file, so one pass resolves the hierarchy.
	foreach ( $data['terms'] as $t ) {
		if ( null !== $max_level && $t['level'] > $max_level ) {
			continue;
		}
		$parent = $t['parent'] ? ( $wp_ids[ $t['parent'] ] ?? 0 ) : 0;
		$args   = [
			'description' => $public_definitions ? (string) ( $t['scope_note'] ?? '' ) : '',
			'parent'      => $parent,
			'slug'        => $t['id'],
		];
		if ( isset( $existing[ $t['id'] ] ) ) {
			$term_id = $existing[ $t['id'] ];
			wp_update_term( $term_id, $taxonomy, $args + [ 'name' => $t['label'] ] );
			++$stats['updated'];
		} else {
			$result = wp_insert_term( $t['label'], $taxonomy, $args );
			if ( is_wp_error( $result ) ) {
				throw new \RuntimeException( "{$t['id']}: " . $result->get_error_message() );
			}
			$term_id = $result['term_id'];
			update_term_meta( $term_id, TERM_META_ID, $t['id'] );
			++$stats['created'];
		}
		update_term_meta( $term_id, TERM_META_DEF, (string) ( $t['scope_note'] ?? '' ) );
		delete_term_meta( $term_id, TERM_META_ALT );
		foreach ( $t['alt_labels'] as $alt ) {
			add_term_meta( $term_id, TERM_META_ALT, $alt );
		}
		$wp_ids[ $t['id'] ] = $term_id;
	}
	return $stats;
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Imports the thesaurus into a Tainacan taxonomy.
	 *
	 * ## OPTIONS
	 *
	 * <taxonomy>
	 * : Tainacan taxonomy name (e.g. "Denominação (Tesauro de Objetos)") or ID.
	 *
	 * [--file=<file>]
	 * : Compiled thesaurus JSON. Default: data/tesauro-ferrez-2016.json in this plugin.
	 *
	 * [--max-level=<level>]
	 * : Deepest level to import: 0 = classes, 1 = subclasses. Default: all levels.
	 *
	 * [--public-definitions]
	 * : Also show definitions as term descriptions (public in Tainacan). Only with the author's permission.
	 *
	 * ## EXAMPLES
	 *
	 *     wp tesauro import "Classificação (Tesauro de Objetos)" --max-level=1
	 *     wp tesauro import "Denominação (Tesauro de Objetos)"
	 */
	\WP_CLI::add_command(
		'tesauro import',
		function ( $args, $assoc ) {
			$file   = $assoc['file'] ?? __DIR__ . '/data/tesauro-ferrez-2016.json';
			$repo   = \Tainacan\Repositories\Taxonomies::get_instance();
			$tax    = is_numeric( $args[0] )
				? $repo->fetch( (int) $args[0] )
				: ( $repo->fetch( [ 'title' => $args[0], 'posts_per_page' => 1 ], 'OBJECT' )[0] ?? null );
			if ( ! $tax ) {
				\WP_CLI::error( "Tainacan taxonomy not found: {$args[0]}" );
			}
			$max   = isset( $assoc['max-level'] ) ? (int) $assoc['max-level'] : null;
			$stats = import( $file, $tax->get_db_identifier(), $max, isset( $assoc['public-definitions'] ) );
			\WP_CLI::success( sprintf( '%s: %d created, %d updated.', $tax->get_name(), $stats['created'], $stats['updated'] ) );
		}
	);
}
