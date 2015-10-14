<?php
/*
	Plugin Name: Code Prettify
	Plugin URI: https://github.com/kasparsd/code-prettify
	GitHub URI: https://github.com/kasparsd/code-prettify
	Description: Automatic code syntax highlighter
	Version: 1.4
	Author: Kaspars Dambis
	Author URI: http://kaspars.net
*/



// include options page class and instantiate
// then hook the updater
if ( is_admin() ){
	include 'options-page-lib/class.php';
	new Code_Prettify_Plugin_Settings_Page( __FILE__ );
}




// then business as usual
add_action( 'wp_enqueue_scripts', 'add_prettify_scripts' );

function add_prettify_scripts() {
	$ver = '1.4';

	if ( defined( 'WP_DEBUG' ) && WP_DEBUG )
		$script = 'run_prettify-src.js';
	else
		$script = 'run_prettify.js';

	$script_url = plugins_url( sprintf( 'prettify/%s', $script ), __FILE__ );
	
	// add the skin
	$skin = basename(  get_option( 'code_prettify_skin_select', 'prettify.css' ) ,  '.css'  );
	$skin = apply_filters( 'prettify_skin', $skin );

	if ( $skin )
		$script_url = add_query_arg( 'skin', $skin, $script_url );

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

