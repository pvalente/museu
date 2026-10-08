<?php
/**
 * Plugin Name: Museu AI fixes
 * Description: Works around Tainacan AI 0.2.0 bugs (images sent as URLs, system prompt dropped) and prefers Sonnet 5.5.
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

// Image analysis: prefer Claude Sonnet 5.5; the AI plugin's own list (Sonnet 5 first) stays as fallback.
add_filter( 'wpai_preferred_vision_models', function ( $models ) {
	return array_merge( array( array( 'anthropic', 'claude-sonnet-5-5' ) ), $models );
} );
