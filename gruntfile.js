module.exports = function( grunt ) {

	// Load all Grunt plugins.
	require( 'load-grunt-tasks' )( grunt );

	// TODO: Move to own Grunt plugin.
	grunt.registerTask( 'readmeMdToTxt', 'Log some stuff.', function() {

		var formatReadme = function( content ) {
			var replaceRules = {
				'#': '=== $1 ===',
				'##': '== $1 ==',
				'#{3,}': '= $1 =',
			};

			// Replace Markdown headings with WP.org style headings
			Object.keys( replaceRules ).forEach( function( pattern ) {
				var patternRegExp = [ '^', pattern, '\\s(.+)$' ].join('');

				content = content.replace(
					new RegExp( patternRegExp, 'gm' ),
					replaceRules[ pattern ]
				);
			} );

			return content;
		};

		var path = require('path');
		var pkgConfig = grunt.config.get( 'pkg' );

		var options = this.options( {
			src: 'readme.md',
			dest: 'readme.txt',
		} );

		var srcFile = grunt.file.read( options.src );
		var destDir = path.dirname( options.dest );

		// Write the readme.txt.
		grunt.file.write( options.dest, formatReadme( srcFile ) );

	});

	var ignoreParse = require( 'parse-gitignore' );

	// Get a list of all the files and directories to exclude from the distribution.
	var distignore = ignoreParse( '.distignore', {
		invert: true,
	} );

	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),

		dist_dir: 'dist',

		clean: {
			build: [ '<%= dist_dir %>' ],
		},

		readmeMdToTxt: {
			options: {
				src: 'readme.md',
				dest: 'readme.txt',
			},
		},

		copy: {
			dist: {
				src: [ '**' ].concat( distignore ),
				dest: '<%= dist_dir %>',
				expand: true,
			}
		},

		compress: {
			main: {
				options: {
					archive: 'code-prettify.zip'
				},
				files: [
					{
						cwd: 'dist',
						src: [ '**/*' ],
						dest: 'code-prettify',
					},
				]
			}
		},

		wp_deploy: {
			options: {
				plugin_slug: 'code-prettify',
				build_dir: '<%= dist_dir %>',
				assets_dir: 'assets/wporg',
			},
			trunk: {
				options: {
					deploy_tag: false,
				}
			}
		},
	} );

	grunt.registerTask(
		'build', [
			'clean',
			'readmeMdToTxt',
			'copy',
		]
	);

	grunt.registerTask(
		'deploy', [
			'build',
			'wp_deploy:trunk',
		]
	);

	grunt.registerTask(
		'package', [
			'build',
			'compress',
		]
	);

};
