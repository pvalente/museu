<?php
/**
 * Plugin Name: Museu AI fixes
 * Description: Makes Tainacan AI send images inline (base64) instead of as public URLs, and prefer Sonnet 5.5.
 */

// Tainacan AI 0.2.0 sends an image's URL instead of its bytes when a HEAD request to it returns 200,
// but the Anthropic provider only accepts inline images ("only supports inline files for non-document
// types"). Failing that self-check for our own uploads makes it fall back to base64, which also keeps
// the AI provider from fetching files off this (private) site.
add_filter( 'pre_http_request', function ( $response, $args, $url ) {
	if ( ( $args['method'] ?? '' ) === 'HEAD' && str_starts_with( $url, wp_get_upload_dir()['baseurl'] . '/' ) ) {
		return new WP_Error( 'museu_inline_uploads', 'Uploads are sent inline, not by URL.' );
	}
	return $response;
}, 10, 3 );

// Image analysis: prefer Claude Sonnet 5.5; the AI plugin's own list (Sonnet 5 first) stays as fallback.
add_filter( 'wpai_preferred_vision_models', function ( $models ) {
	return array_merge( array( array( 'anthropic', 'claude-sonnet-5-5' ) ), $models );
} );
