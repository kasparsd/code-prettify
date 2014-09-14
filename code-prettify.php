<?php
/*
	Plugin Name: Code Prettify
	Plugin URI: https://github.com/kasparsd/code-prettify
	GitHub URI: https://github.com/kasparsd/code-prettify
	Description: Automatic code syntax highlighter
	Version: 1.3.1
	Author: Kaspars Dambis
	Author URI: http://kaspars.net
*/

add_action( 'wp_enqueue_scripts', 'add_prettify_scripts' );

function add_prettify_scripts() {
	if ( WP_DEBUG )
		$script = 'prettify-src.js';
	else
		$script = 'prettify.js';

	wp_enqueue_script(
		'code-prettify',
		sprintf( '%s/%s/%s', plugins_url(), basename( __DIR__ ), $script ),
		false,
		'1.0',
		true
	);

	wp_localize_script(
		'code-prettify',
		'code_prettify_settings',
		array(
			'base_url' => sprintf( '%s/%s', plugins_url(), basename( __DIR__ ) ),
			'skin' => apply_filters( 'prettify_skin', false )
		)
	);
}

