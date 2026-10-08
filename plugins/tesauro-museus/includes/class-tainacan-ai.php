<?php
/**
 * Optional integration with the Tainacan AI plugin. Active only when Tainacan AI is installed.
 *
 * - Thesaurus taxonomies are too big to list in the prompt (Tainacan AI lists up to ~100 terms), so their
 *   allowed values are hidden: the AI names the object freely and the name comes back as a "new term".
 * - After each analysis, that name (or else the start of the suggested title) is resolved with Matcher to a
 *   thesaurus term, and a classification field fed by the same thesaurus gets the term's class/subclass.
 *
 * Fields are recognized by their taxonomy: one whose imported terms include objects (kind "term") is a name
 * field (e.g. INBCM Denominação); one with only classes/subclasses is a classification field.
 */

namespace TesauroMuseus;

defined( 'ABSPATH' ) || exit;

class Tainacan_AI {

	public static function init(): void {
		add_filter( 'tainacan_ai_allowed_value_options', [ self::class, 'hide_name_options' ], 10, 2 );
		// Runs for HTTP and internal (rest_do_request) calls alike, unlike rest_post_dispatch.
		add_filter( 'rest_request_after_callbacks', [ self::class, 'resolve_analysis' ], 10, 3 );
	}

	/** "name" (objects), "class" (classes/subclasses only) or null (not a thesaurus taxonomy). */
	public static function role( string $taxonomy ): ?string {
		static $roles = [];
		if ( ! array_key_exists( $taxonomy, $roles ) ) {
			$has   = fn( $kind ) => (bool) get_terms( [ 'taxonomy' => $taxonomy, 'hide_empty' => false, 'number' => 1, 'fields' => 'ids', 'meta_key' => TERM_META_KIND, 'meta_value' => $kind ] );
			$roles[ $taxonomy ] = $has( 'term' ) ? 'name' : ( $has( 'class' ) ? 'class' : null );
		}
		return $roles[ $taxonomy ];
	}

	public static function hide_name_options( $options, $taxonomy_id ) {
		$taxonomy = \Tainacan\Repositories\Taxonomies::get_instance()->get_db_identifier_by_id( (int) $taxonomy_id );
		return $taxonomy && 'name' === self::role( $taxonomy ) ? [] : $options;
	}

	public static function resolve_analysis( $response, $handler, $request ) {
		if ( '/tainacan-ai/v1/analyze' !== $request->get_route() || is_wp_error( $response ) ) {
			return $response;
		}
		$response = rest_ensure_response( $response );
		if ( 200 !== $response->get_status() ) {
			return $response;
		}
		$data = $response->get_data();
		$col  = \Tainacan\Repositories\Collections::get_instance()->fetch( (int) $request->get_param( 'collection_id' ) );
		if ( ! isset( $data['result']['ai_metadata'] ) || ! $col instanceof \Tainacan\Entities\Collection ) {
			return $response;
		}
		$meta = &$data['result']['ai_metadata'];

		// Thesaurus fields in this collection, by role.
		$fields = [];
		foreach ( \Tainacan\Repositories\Metadata::get_instance()->fetch_by_collection( $col, [], 'OBJECT' ) as $m ) {
			$tax_id   = (int) ( $m->get_metadata_type_options()['taxonomy_id'] ?? 0 );
			$taxonomy = $tax_id ? \Tainacan\Repositories\Taxonomies::get_instance()->get_db_identifier_by_id( $tax_id ) : '';
			$role     = $taxonomy ? self::role( $taxonomy ) : null;
			if ( $role ) {
				$fields[ $role ] = [ 'slug' => $m->get_slug(), 'taxonomy' => $taxonomy, 'multiple' => 'yes' === $m->get_multiple() ];
			}
		}
		if ( ! isset( $fields['name'] ) ) {
			return $response;
		}

		// Candidate names: what the AI proposed for the name field, then the suggested title.
		$entry = $meta[ $fields['name']['slug'] ] ?? [];
		$names = array_column( $entry['pending_new_terms'] ?? [], 'label' );
		foreach ( (array) ( $entry['label'] ?? $entry['value'] ?? [] ) as $v ) {
			if ( is_string( $v ) && ! is_numeric( $v ) ) {
				$names[] = $v;
			}
		}
		$title_slug = $col->get_core_title_metadatum()->get_slug();
		if ( is_string( $meta[ $title_slug ]['value'] ?? null ) ) {
			$names[] = $meta[ $title_slug ]['value'];
		}

		$matcher = new Matcher( $fields['name']['taxonomy'] );
		$best    = [];
		foreach ( $names as $name ) {
			$found = $matcher->match( $name );
			if ( $found && ( ! $best || $found[0]['score'] > $best[0]['score'] ) ) {
				$best = $found;
			}
		}
		$data['result']['tesauro'] = [ 'names' => $names, 'candidates' => $best ];
		if ( ! $best ) {
			$response->set_data( $data );
			return $response;
		}

		$top  = $best[0];
		$alts = array_map( fn( $c ) => $c['label'] . ' — ' . self::path( $c['term_id'], $fields['name']['taxonomy'] ), array_slice( $best, 1, 3 ) );
		$meta[ $fields['name']['slug'] ] = [
			'value'    => $fields['name']['multiple'] ? [ $top['term_id'] ] : $top['term_id'],
			'label'    => $fields['name']['multiple'] ? [ $top['label'] ] : $top['label'],
			'evidence' => sprintf( 'Tesauro (%s: "%s"): %s', $top['method'], $top['matched'], self::path( $top['term_id'], $fields['name']['taxonomy'] ) )
				. ( $alts ? '. Alternativas: ' . implode( '; ', $alts ) : '' ),
		];

		if ( isset( $fields['class'] ) ) {
			$class_term = self::classification( $top['term_id'], $fields['name']['taxonomy'], $fields['class']['taxonomy'] );
			if ( $class_term ) {
				$meta[ $fields['class']['slug'] ] = [
					'value'    => $fields['class']['multiple'] ? [ $class_term->term_id ] : $class_term->term_id,
					'label'    => $fields['class']['multiple'] ? [ $class_term->name ] : $class_term->name,
					'evidence' => 'Tesauro: classe de ' . $top['label'],
				];
			}
		}
		$response->set_data( $data );
		return $response;
	}

	/** "Class › Subclass › Term" for a term in a thesaurus taxonomy. */
	public static function path( int $term_id, string $taxonomy ): string {
		$ids = array_reverse( get_ancestors( $term_id, $taxonomy, 'taxonomy' ) );
		$ids[] = $term_id;
		return implode( ' › ', array_map( fn( $id ) => get_term( $id, $taxonomy )->name, $ids ) );
	}

	/** The nearest subclass (or class) above a name term, looked up in the classification taxonomy. */
	public static function classification( int $term_id, string $name_taxonomy, string $class_taxonomy ): ?\WP_Term {
		foreach ( get_ancestors( $term_id, $name_taxonomy, 'taxonomy' ) as $ancestor ) {
			if ( in_array( get_term_meta( $ancestor, TERM_META_KIND, true ), [ 'subclass', 'class' ], true ) ) {
				$found = get_terms( [ 'taxonomy' => $class_taxonomy, 'hide_empty' => false, 'number' => 1, 'meta_key' => TERM_META_ID, 'meta_value' => get_term_meta( $ancestor, TERM_META_ID, true ) ] );
				return $found ? $found[0] : null;
			}
		}
		return null;
	}
}
