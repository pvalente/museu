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
const TERM_META_KIND = 'tesauro_kind';

require_once __DIR__ . '/includes/class-matcher.php';
require_once __DIR__ . '/includes/class-tainacan-ai.php';

add_action( 'plugins_loaded', function () {
	if ( defined( 'TAINACAN_AI_VERSION' ) ) {
		Tainacan_AI::init();
	}
} );

/**
 * Imports thesaurus terms (data/*.json, built by tools/build.py) into an existing Tainacan taxonomy.
 *
 * Idempotent: terms are matched by their stable thesaurus id (term meta), so re-running updates names,
 * definitions and synonyms instead of duplicating terms.
 *
 * @param string   $file      Path to the compiled thesaurus JSON.
 * @param string   $taxonomy  WordPress taxonomy name of the Tainacan taxonomy (e.g. tnc_tax_95).
 * @param string[] $kinds     Kinds to import (class, subclass, term); empty imports everything.
 * @param bool     $public_definitions Also put definitions in the term description, which Tainacan serves publicly.
 *                                     Off by default: the source only permits partial reproduction. Definitions
 *                                     are always kept in private term meta for matching.
 * @param bool     $prune     Delete thesaurus terms in the taxonomy that this import didn't include.
 * @return array{created:int, updated:int, deleted:int}
 */
function import( string $file, string $taxonomy, array $kinds = [], bool $public_definitions = false, bool $prune = false ): array {
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

	$stats  = [ 'created' => 0, 'updated' => 0, 'deleted' => 0 ];
	$wp_ids = [];
	// Parents come before children in the file, so one pass resolves the hierarchy.
	foreach ( $data['terms'] as $t ) {
		if ( $kinds && ! in_array( $t['kind'], $kinds, true ) ) {
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
		update_term_meta( $term_id, TERM_META_KIND, $t['kind'] );
		delete_term_meta( $term_id, TERM_META_ALT );
		foreach ( $t['alt_labels'] as $alt ) {
			add_term_meta( $term_id, TERM_META_ALT, $alt );
		}
		$wp_ids[ $t['id'] ] = $term_id;
	}
	if ( $prune ) {
		foreach ( array_diff_key( $existing, $wp_ids ) as $term_id ) {
			wp_delete_term( $term_id, $taxonomy );
			++$stats['deleted'];
		}
	}
	wp_cache_delete( 'matcher_' . $taxonomy, 'tesauro_museus' );
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
	 * [--kinds=<kinds>]
	 * : Comma-separated kinds to import: class, subclass, term. Default: all.
	 *
	 * [--prune]
	 * : Delete thesaurus terms in the taxonomy that this import didn't include.
	 *
	 * [--public-definitions]
	 * : Also show definitions as term descriptions (public in Tainacan). Only with the author's permission.
	 *
	 * ## EXAMPLES
	 *
	 *     wp tesauro import "Classificação (Tesauro de Objetos)" --kinds=class,subclass --prune
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
			$kinds = array_filter( explode( ',', $assoc['kinds'] ?? '' ) );
			$stats = import( $file, $tax->get_db_identifier(), $kinds, isset( $assoc['public-definitions'] ), isset( $assoc['prune'] ) );
			\WP_CLI::success( sprintf( '%s: %d created, %d updated, %d deleted.', $tax->get_name(), $stats['created'], $stats['updated'], $stats['deleted'] ) );
		}
	);

	/**
	 * Matches free text to thesaurus terms, for testing.
	 *
	 * ## OPTIONS
	 *
	 * <taxonomy>
	 * : Tainacan taxonomy name or ID.
	 *
	 * <text>...
	 * : One or more names or titles to match.
	 *
	 * ## EXAMPLES
	 *
	 *     wp tesauro match "Denominação (Tesauro de Objetos)" "Escudela" "Bule de metal prateado com monograma"
	 */
	\WP_CLI::add_command(
		'tesauro match',
		function ( $args ) {
			$repo = \Tainacan\Repositories\Taxonomies::get_instance();
			$name = array_shift( $args );
			$tax  = is_numeric( $name ) ? $repo->fetch( (int) $name ) : ( $repo->fetch( [ 'title' => $name, 'posts_per_page' => 1 ], 'OBJECT' )[0] ?? null );
			if ( ! $tax ) {
				\WP_CLI::error( "Tainacan taxonomy not found: $name" );
			}
			$matcher = new Matcher( $tax->get_db_identifier() );
			foreach ( $args as $text ) {
				$found = $matcher->match( $text );
				\WP_CLI::log( "$text" );
				foreach ( $found ?: [] as $c ) {
					\WP_CLI::log( sprintf( '  %.2f %-8s %s', $c['score'], $c['method'], Tainacan_AI::path( $c['term_id'], $tax->get_db_identifier() ) ) );
				}
				if ( ! $found ) {
					\WP_CLI::log( '  (no match)' );
				}
			}
		}
	);
}
