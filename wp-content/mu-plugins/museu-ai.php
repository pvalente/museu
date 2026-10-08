<?php
/**
 * Plugin Name: Museu AI fixes
 * Description: Works around Tainacan AI 0.2.0 bugs (images sent as URLs, system prompt dropped), downscales images sent for analysis, prefers Sonnet 5.5.
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

// Tainacan AI 0.2.0 sets the system instruction (preamble, rules, field guidance) and max tokens only if
// method_exists() on the prompt builder, but WP 7's builder proxies them through __call, so they're silently
// dropped and the model only sees "Analyze the attached image...". Capture the composed prompt and put it
// back on the model config just before the request.
add_filter( 'tainacan_ai_analysis_prompt', function ( $prompt ) {
	$GLOBALS['museu_ai_system_prompt'] = $prompt;
	return $prompt;
} );
add_action( 'wp_ai_client_before_generate_result', function ( $event ) {
	$prompt = $GLOBALS['museu_ai_system_prompt'] ?? '';
	if ( $prompt === '' ) {
		return;
	}
	unset( $GLOBALS['museu_ai_system_prompt'] );
	$config = $event->getModel()->getConfig();
	if ( ! $config->getSystemInstruction() ) {
		$config->setSystemInstruction( $prompt );
	}
	$max_tokens = (int) ( get_option( 'tainacan_ai_options' )['max_tokens'] ?? 0 );
	if ( $max_tokens > 0 && ! $config->getMaxTokens() ) {
		$config->setMaxTokens( $max_tokens );
	}
} );

// Downscale images before they're sent for analysis: the image is a large share of each request's tokens, and
// detail beyond what the model needs is paid for and thrown away. Tainacan AI reads the file through
// get_attached_file(), so during an analyze request that returns a cached copy whose long edge is at most
// museu_ai_image_max_edge px (option, or the filter of the same name). 0 = send the original.
add_filter( 'rest_request_before_callbacks', function ( $response, $handler, $request ) {
	if ( '/tainacan-ai/v1/analyze' === $request->get_route() ) {
		$GLOBALS['museu_ai_analyzing'] = true;
	}
	return $response;
}, 10, 3 );
add_filter( 'rest_request_after_callbacks', function ( $response ) {
	unset( $GLOBALS['museu_ai_analyzing'] );
	return $response;
} );
add_filter( 'get_attached_file', function ( $file, $attachment_id ) {
	$max = (int) apply_filters( 'museu_ai_image_max_edge', (int) get_option( 'museu_ai_image_max_edge', 0 ) );
	// Check the MIME type directly: wp_attachment_is_image() calls get_attached_file() and would recurse
	// into this filter forever (under wp-cli's unlimited memory_limit that looks like a hang).
	if ( empty( $GLOBALS['museu_ai_analyzing'] ) || $max <= 0 || ! $file
		|| ! str_starts_with( (string) get_post_mime_type( $attachment_id ), 'image/' ) ) {
		return $file;
	}
	$size = @wp_getimagesize( $file );
	if ( ! $size || max( $size[0], $size[1] ) <= $max ) {
		return $file;
	}
	$small = preg_replace( '/(\.[^.\/]+)$/', "-ai{$max}$1", $file );
	if ( ! file_exists( $small ) ) {
		$editor = wp_get_image_editor( $file );
		if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $max, $max ) ) || is_wp_error( $editor->save( $small ) ) ) {
			return $file;
		}
	}
	return $small;
}, 10, 2 );
// Remove the downscaled copies with their attachment.
add_action( 'delete_attachment', function ( $attachment_id ) {
	$file = get_attached_file( $attachment_id, true );
	if ( $file ) {
		foreach ( glob( preg_replace( '/(\.[^.\/]+)$/', '-ai*$1', $file ) ) ?: [] as $copy ) {
			wp_delete_file( $copy );
		}
	}
} );

// Image analysis: prefer Claude Sonnet 5.5; the AI plugin's own list (Sonnet 5 first) stays as fallback.
add_filter( 'wpai_preferred_vision_models', function ( $models ) {
	return array_merge( array( array( 'anthropic', 'claude-sonnet-5-5' ) ), $models );
} );
