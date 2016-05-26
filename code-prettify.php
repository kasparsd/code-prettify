<?php
/*
	Plugin Name: Code Prettify
	Plugin URI: https://github.com/kasparsd/code-prettify
	GitHub URI: https://github.com/kasparsd/code-prettify
	Description: Automatic code syntax highlighter
	Version: 1.3.4
	Author: Kaspars Dambis
	Author URI: http://kaspars.net
*/

add_action( 'wp_enqueue_scripts', 'add_prettify_scripts' );

function add_prettify_scripts() {
	$ver = '1.3.4';
	$script = 'run_prettify.js';

	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		$script = 'run_prettify-src.js';
	}

	$script_url = plugins_url( sprintf( 'prettify/%s', $script ), __FILE__ );

	$skin = apply_filters( 'prettify_skin', null );

	if ( $skin ) {
		$script_url = add_query_arg( 'skin', $skin, $script_url );
	}

	$script_url = apply_filters( 'code-prettify-js-url', $script_url );

	wp_enqueue_script(
		'code-prettify',
		$script_url,
		false,
		$ver,
		true
	);

	wp_localize_script(
		'code-prettify',
		'code_prettify_settings',
		array(
			'base_url' => plugins_url( 'prettify', __FILE__ )
		)
	);
}
