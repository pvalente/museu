<?php
/**
 * Creates or updates a Tainacan collection from a JSON blueprint (fields, types, mapper mapping, AI guidance,
 * taxonomies). Idempotent: looks things up by name/slug and only fills in what's missing or changed.
 *
 * Usage: wp eval-file apply-blueprint.php <blueprint.json>
 *
 * Blueprint shape: see inbcm-museologico.json. Field "type" is a Tainacan metadata type class name without the
 * namespace (Text, Selectbox, Taxonomy, ...); "core": "title"|"description" reuses the collection's core field.
 * Terms are only created for taxonomies that declare "terms"; anything else (e.g. a thesaurus) is imported elsewhere.
 */

use Tainacan\Entities;
use Tainacan\Repositories;

$bp = json_decode( file_get_contents( $args[0] ), true );
if ( ! $bp ) {
	WP_CLI::error( 'Could not read blueprint ' . $args[0] );
}

function museu_bp_save( $entity, $repo, $label ) {
	if ( ! $entity->validate() ) {
		WP_CLI::error( "$label: " . wp_json_encode( $entity->get_errors(), JSON_UNESCAPED_UNICODE ) );
	}
	return $entity->get_id() ? $repo->update( $entity ) : $repo->insert( $entity );
}

function museu_bp_term( $tax, $name, $parent ) {
	$existing = term_exists( $name, $tax->get_db_identifier(), $parent );
	if ( $existing ) {
		return (int) $existing['term_id'];
	}
	$term = new Entities\Term();
	$term->set_name( $name );
	$term->set_taxonomy( $tax->get_db_identifier() );
	$term->set_parent( $parent );
	$saved = museu_bp_save( $term, Repositories\Terms::get_instance(), "term $name" );
	return $saved->get_id();
}

// Taxonomies.
$tax_repo  = Repositories\Taxonomies::get_instance();
$term_repo = Repositories\Terms::get_instance();
$tax_ids   = [];
foreach ( $bp['taxonomies'] ?? [] as $key => $def ) {
	$found = $tax_repo->fetch( [ 'title' => $def['name'], 'posts_per_page' => 1 ], 'OBJECT' );
	$tax   = $found ? $found[0] : new Entities\Taxonomy();
	$tax->set_name( $def['name'] );
	$tax->set_description( $def['description'] ?? '' );
	$tax->set_allow_insert( $def['allow_insert'] ?? 'no' );
	$tax->set_status( 'publish' );
	$tax = museu_bp_save( $tax, $tax_repo, "taxonomy $key" );
	$tax_ids[ $key ] = $tax->get_id();

	foreach ( $def['terms'] ?? [] as $parent_name => $children ) {
		$parent_id = museu_bp_term( $tax, $parent_name, 0 );
		foreach ( $children as $child ) {
			museu_bp_term( $tax, $child, $parent_id );
		}
	}
	WP_CLI::log( "taxonomy {$def['name']} (#{$tax->get_id()})" );
}


// Collection.
$col_repo = Repositories\Collections::get_instance();
$found    = $col_repo->fetch( [ 'title' => $bp['collection']['name'], 'posts_per_page' => 1 ], 'OBJECT' );
$col      = $found ? $found[0] : new Entities\Collection();
$col->set_name( $bp['collection']['name'] );
$col->set_description( $bp['collection']['description'] ?? '' );
$col->set_status( $bp['collection']['status'] ?? 'publish' );
$col = museu_bp_save( $col, $col_repo, 'collection' );
WP_CLI::log( "collection {$col->get_name()} (#{$col->get_id()})" );

// Fields.
$meta_repo = Repositories\Metadata::get_instance();
$mapper    = $bp['collection']['mapper'] ?? null;
$order     = [];
foreach ( $bp['fields'] as $f ) {
	$slug = sanitize_title( str_replace( ':', '-', $f['inbcm'] ?? $f['name'] ) );
	if ( isset( $f['core'] ) ) {
		$method = 'get_core_' . $f['core'] . '_metadatum';
		$m      = $col->$method();
	} else {
		// Find the field by its mapper mapping (stable), falling back to its slug; Tainacan may rewrite slugs.
		$m = null;
		foreach ( $meta_repo->fetch_by_collection( $col, [ 'include_disabled' => true ], 'OBJECT' ) as $candidate ) {
			$mapping = (array) $candidate->get_exposer_mapping();
			if ( ( $mapper && isset( $f['inbcm'] ) && ( $mapping[ $mapper ] ?? null ) === $f['inbcm'] ) || $candidate->get_slug() === $slug ) {
				$m = $candidate;
				break;
			}
		}
		$m = $m ?? new Entities\Metadatum();
		if ( ! $m->get_id() ) {
			$m->set_metadata_type( 'Tainacan\\Metadata_Types\\' . $f['type'] );
			$m->set_slug( $slug );
			$m->set_collection_id( $col->get_id() );
		}
		$m->set_name( $f['name'] );
		$m->set_status( 'publish' );
		$m->set_multiple( empty( $f['multiple'] ) ? 'no' : 'yes' );
		$options = $f['options'] ?? [];
		if ( isset( $f['taxonomy'] ) ) {
			$options['taxonomy_id'] = $tax_ids[ $f['taxonomy'] ];
		}
		if ( $options ) {
			$m->set_metadata_type_options( array_merge( (array) $m->get_metadata_type_options(), $options ) );
		}
	}
	$m->set_description( $f['description'] ?? '' );
	$m->set_placeholder( $f['placeholder'] ?? '' );
	if ( $mapper && isset( $f['inbcm'] ) ) {
		$m->set_exposer_mapping( array_merge( (array) $m->get_exposer_mapping(), [ $mapper => $f['inbcm'] ] ) );
	}
	$m = museu_bp_save( $m, $meta_repo, "field {$f['inbcm']}" );
	// Tainacan AI's per-field opt-out.
	update_post_meta( $m->get_id(), 'tainacan_ai_exclude', empty( $f['ai_exclude'] ) ? '0' : '1' );
	$order[] = [ 'id' => $m->get_id(), 'enabled' => true ];
	WP_CLI::log( sprintf( '  %-24s #%-4d %s', $m->get_name(), $m->get_id(), $f['inbcm'] ?? '' ) );
}

// Field order as in the blueprint.
$col->set_metadata_order( $order );
museu_bp_save( $col, $col_repo, 'collection order' );
WP_CLI::success( 'Applied ' . basename( $args[0] ) );
