<?php
/**
 * Plugin Name: Code Prettify
 * Plugin URI: https://github.com/kasparsd/code-prettify
 * GitHub URI: https://github.com/kasparsd/code-prettify
 * Description: Automatic code syntax highlighter
 * Version: 1.5.1
 * Author: Kaspars Dambis
 * Author URI: https://kaspars.net
 *
 * @package code-prettify
 */

add_action( 'wp_enqueue_scripts', 'add_prettify_scripts' );

/**
 * Enqueue the prettify scripts.
 *
 * Don't try to be smart about doing this when <pre> or <code> tags are found
 * since that might not be reliable. This also allows CSS bundles to be the
 * same on all pages taking advatange of browser cache.
 *
 * @return void
 */
function add_prettify_scripts() {
	$script_url = plugins_url( 'prettify/run_prettify.js', __FILE__ );

	$prettify_query_params = array_filter(
		array(
			'skin' => apply_filters( 'prettify_skin', null ),
		)
	);

	if ( ! empty( $prettify_query_params ) ) {
		$script_url = add_query_arg( $prettify_query_params, $script_url );
	}

	wp_enqueue_script(
		'code-prettify',
		apply_filters( 'code-prettify-js-url', $script_url ), // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		array(),
		'1.4.0',
		true
	);

	wp_add_inline_script(
		'code-prettify',
		sprintf(
			'var codePrettifyLoaderBaseUrl = %s;',
			wp_json_encode( plugins_url( 'prettify', __FILE__ ) )
		),
		'before'
	);

	add_action( 'wp_head', 'prettify_preload_code_styles' );
}

/**
 * Preload the prettify CSS.
 *
 * @return void
 */
function prettify_preload_code_styles() {
	printf(
		'<link rel="preload" as="style" href="%s" />',
		esc_url( plugins_url( 'prettify/prettify.css', __FILE__ ) )
	);
}
