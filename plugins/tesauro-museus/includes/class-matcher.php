<?php
/**
 * Resolves free text (an object name, or a catalogue title such as "Bule de metal prateado com monograma")
 * to thesaurus terms in one taxonomy imported by this plugin.
 *
 * Strategies, strongest first:
 *   exact    the normalized text equals a preferred term ("castiçais" → Castiçal)
 *   synonym  it equals a non-preferred term (Escudela → Tigela)
 *   prefix   the longest leading word sequence that names a term ("Bule de metal prateado…" → Bule)
 *   variant  masculine/feminine of the same name (cesto → Cesta)
 * Normalization ignores case, accents, hyphens, punctuation, simple Portuguese plurals and qualifiers in parentheses,
 * so homonyms ("Cravo (prego)", "Cravo" the instrument) come back as several candidates.
 */

namespace TesauroMuseus;

defined( 'ABSPATH' ) || exit;

class Matcher {

	const SCORES = [ 'exact' => 1.0, 'synonym' => 0.95, 'prefix' => 0.85, 'variant' => 0.75 ];

	/** @var array<string, array<int, string>> normalized key => [term_id => method] */
	private array $index = [];

	/** @var array<string, array<int, string>> the same keys with spaces removed */
	private array $compact = [];

	/** @var array<int, string> term_id => label */
	private array $labels = [];

	public function __construct( private string $taxonomy ) {
		$cached = wp_cache_get( 'matcher_' . $taxonomy, 'tesauro_museus' );
		if ( is_array( $cached ) ) {
			[ $this->index, $this->compact, $this->labels ] = $cached;
			return;
		}
		$terms = get_terms( [ 'taxonomy' => $taxonomy, 'hide_empty' => false, 'meta_key' => TERM_META_ID ] );
		foreach ( is_array( $terms ) ? $terms : [] as $term ) {
			$this->labels[ $term->term_id ] = $term->name;
			$this->add( $term->name, $term->term_id, 'exact' );
			foreach ( get_term_meta( $term->term_id, TERM_META_ALT ) as $alt ) {
				$this->add( $alt, $term->term_id, 'synonym' );
			}
		}
		foreach ( $this->index as $key => $terms ) {
			$this->compact[ str_replace( ' ', '', $key ) ] = $terms + ( $this->compact[ str_replace( ' ', '', $key ) ] ?? [] );
		}
		wp_cache_set( 'matcher_' . $taxonomy, [ $this->index, $this->compact, $this->labels ], 'tesauro_museus', HOUR_IN_SECONDS );
	}

	/**
	 * @return array<int, array{term_id:int, label:string, method:string, score:float, matched:string}>
	 *         Best first. Several entries with the same score mean an ambiguous name (homonyms or polyhierarchy).
	 */
	public function match( string $text, int $limit = 5 ): array {
		$key = self::normalize( $text );
		if ( '' === $key ) {
			return [];
		}
		$found = $this->lookup( $key, $text );
		if ( ! $found ) {
			// Longest leading phrase that names a term: titles start with the object's name.
			$words = explode( ' ', $key );
			for ( $n = count( $words ) - 1; $n >= 1 && ! $found; $n-- ) {
				$phrase = implode( ' ', array_slice( $words, 0, $n ) );
				foreach ( $this->lookup( $phrase, $phrase ) as $c ) {
					$found[] = [ 'method' => 'prefix', 'score' => $c['score'] - self::SCORES[ $c['method'] ] + self::SCORES['prefix'] ] + $c;
				}
			}
		}
		if ( ! $found ) {
			// Gender variants, on the whole text, then on its leading phrases ("Cesto oval…" → Cesta).
			$words = explode( ' ', $key );
			for ( $n = count( $words ); $n >= 1 && ! $found; $n-- ) {
				$found = $this->variant( implode( ' ', array_slice( $words, 0, $n ) ) );
			}
		}
		usort( $found, fn( $a, $b ) => $b['score'] <=> $a['score'] ?: strcmp( $a['label'], $b['label'] ) );
		return array_slice( $found, 0, $limit );
	}

	public function label( int $term_id ): string {
		return $this->labels[ $term_id ] ?? '';
	}

	private function lookup( string $key, string $matched ): array {
		$out = [];
		// Spaces/hyphens are interchangeable: "guarda chuva", "guarda-chuva", "guardachuva".
		$terms = $this->index[ $key ] ?? $this->compact[ str_replace( ' ', '', $key ) ] ?? [];
		foreach ( $terms as $term_id => $method ) {
			$out[] = [
				'term_id' => $term_id,
				'label'   => $this->labels[ $term_id ],
				'method'  => $method,
				// "Bule" over "Bule (samovar)" when the text didn't ask for the qualifier.
				'score'   => self::SCORES[ $method ] - ( str_contains( $this->labels[ $term_id ], '(' ) ? 0.02 : 0 ),
				'matched' => $matched,
			];
		}
		return $out;
	}

	/** Masculine/feminine of the same object ("cesto" ↔ Cesta), word by word. Open-ended edit distance
	 *  was dropped: it can't tell a typo from a different word (Cartão → Cartaz). */
	private function variant( string $key ): array {
		$words = explode( ' ', $key );
		$out   = [];
		foreach ( $words as $i => $word ) {
			$swap = preg_replace_callback( '/[oa]$/', fn( $m ) => 'o' === $m[0] ? 'a' : 'o', $word );
			if ( $swap === $word || strlen( $word ) < 4 ) {
				continue;
			}
			$candidate = implode( ' ', array_replace( $words, [ $i => $swap ] ) );
			foreach ( $this->lookup( $candidate, $candidate ) as $c ) {
				$out[] = [ 'method' => 'variant', 'score' => $c['score'] - self::SCORES[ $c['method'] ] + self::SCORES['variant'] ] + $c;
			}
		}
		return $out;
	}

	private function add( string $text, int $term_id, string $method ): void {
		// normalize() drops qualifiers, so "Cravo (prego)" and "Cravo" (instrument) share the key "cravo".
		$key = self::normalize( $text );
		// Keep the strongest method if a term answers to the same key twice.
		if ( '' !== $key && ( ! isset( $this->index[ $key ][ $term_id ] ) || 'exact' === $method ) ) {
			$this->index[ $key ][ $term_id ] = $method;
		}
	}

	public static function normalize( string $text ): string {
		$text = mb_strtolower( remove_accents( $text ) );
		$text = preg_replace( '/\([^)]*\)/', ' ', $text );
		$text = str_replace( '-', ' ', $text );
		$text = preg_replace( '/[^a-z0-9 ]+/', ' ', $text );
		$words = preg_split( '/\s+/', trim( $text ), -1, PREG_SPLIT_NO_EMPTY );
		return implode( ' ', array_map( [ self::class, 'singular' ], $words ) );
	}

	/** Rough Portuguese singular, accents already removed. Good enough for object names. */
	private static function singular( string $w ): string {
		if ( strlen( $w ) <= 3 || in_array( $w, [ 'de', 'do', 'da', 'dos', 'das', 'e', 'com', 'para', 'por', 'em' ], true ) ) {
			return $w;
		}
		$rules = [ '/oes$/' => 'ao', '/aes$/' => 'ao', '/ais$/' => 'al', '/eis$/' => 'el', '/ois$/' => 'ol', '/uis$/' => 'ul',
			'/ns$/' => 'm', '/(r|z)es$/' => '$1', '/([^s])s$/' => '$1' ];
		foreach ( $rules as $pattern => $replacement ) {
			if ( preg_match( $pattern, $w ) ) {
				return preg_replace( $pattern, $replacement, $w );
			}
		}
		return $w;
	}
}
