<?php
/**
 * Plugin Name: Museu – Blocksy tweaks
 * Description: Neutral grey accents and a plain footer while the site is not public. Only acts when Blocksy is active.
 */

if (get_template() !== 'blocksy') {
	return;
}

// Footer: site name and year instead of the theme credit.
add_filter('blocksy:footer:copyright:default-value', fn() => '© {current_year} {site_title}');

// Swap Blocksy's default blue accents for greys.
add_action('wp_head', function () {
	echo '<style id="museu-neutral">:root{--theme-palette-color-1:#3f3f46;--theme-palette-color-2:#18181b;}</style>';
}, 100);
