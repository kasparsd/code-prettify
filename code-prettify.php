<?php
/*
	Plugin Name: Code Prettify
	Plugin URI: https://github.com/kasparsd/code-prettify
	GitHub URI: https://github.com/kasparsd/code-prettify
	Description: Automatic code syntax highlighter
	Version: 1.3.3
	Author: Kaspars Dambis
	Author URI: http://kaspars.net
*/

add_action( 'wp_enqueue_scripts', 'add_prettify_scripts' );

function add_prettify_scripts() {
	$ver = '1.3.3';

	if ( defined( 'WP_DEBUG' ) && WP_DEBUG )
		$script = 'run_prettify-src.js';
	else
		$script = 'run_prettify.js';

	$script_url = plugins_url( sprintf( 'prettify/%s', $script ), __FILE__ );
	$skin = apply_filters( 'prettify_skin', null );

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

function moonWalk($x, $leadingSpaces = 0) {
  //Make sure we don't start or endwith new lines
  $x = trim($x, "\r");
  $x = trim($x, "\n");

  // Find how many leading spaces are in the first line
  $spacesToRemove = strlen($x) - strlen(ltrim($x)) - $leadingSpaces;
  // Break up by new lines
  $lines = explode("\n", $x);
  
  // Remove that many leading spaces from the beginning of each string
  for($x = 0; $x < sizeof($lines); $x++) {
    // Remove each space
    $lines[$x] = preg_replace('/\s/', "", $lines[$x], $spacesToRemove);
  }
  // Put back into string on seperate lines
  return implode("\n", $lines);
}

function pre_moonwalk($content) {
  return preg_replace_callback(
    '#(<pre.*?prettyprint.*?>)(.*?)(</pre>)#imsu',
    create_function(
      '$i',
      'return $i[1].moonWalk($i[2]).$i[3];'
    ),
    $content
  );
}

add_filter( 'the_content', 'pre_moonwalk');
