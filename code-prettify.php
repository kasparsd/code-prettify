<?php
/*
	Plugin Name: Code Prettify
	Plugin URI: https://github.com/kasparsd/code-prettify
	Description: Automatic Syntax Highlighter
	Version: 1.0
	Author: Kaspars Dambis
	Author URI: http://konstruktors.com
*/

add_action( 'wp_enqueue_scripts', 'add_prettify_scripts' );

function add_prettify_scripts() {
    wp_enqueue_script( 'code-prettify', '//google-code-prettify.googlecode.com/svn/loader/run_prettify.js' );
}
