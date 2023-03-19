const ignoreParse = require( 'parse-gitignore' );
const loadGruntTasks = require( 'load-grunt-tasks' );

module.exports = function ( grunt ) {
	// Load all Grunt plugins.
	loadGruntTasks( grunt );

	// TODO: Move to own Grunt plugin.
	grunt.registerTask( 'readmeMdToTxt', 'Log some stuff.', function () {
		const formatReadme = function ( content ) {
			const replaceRules = {
				'#': '=== $1 ===',
				'##': '== $1 ==',
				'#{3,}': '= $1 =',
			};

			// Replace Markdown headings with WP.org style headings
			Object.keys( replaceRules ).forEach( function ( pattern ) {
				const patternRegExp = [ '^', pattern, '\\s(.+)$' ].join( '' );

				content = content.replace(
					new RegExp( patternRegExp, 'gm' ),
					replaceRules[ pattern ]
				);
			} );

			return content;
		};

		const options = this.options( {
			src: 'readme.md',
			dest: 'readme.txt',
		} );

		const srcFile = grunt.file.read( options.src );

		// Write the readme.txt.
		grunt.file.write( options.dest, formatReadme( srcFile ) );
	} );

	// Get a list of all the files and directories to exclude from the distribution.
	const distignore = ignoreParse( '.distignore', {
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
			},
		},

		compress: {
			main: {
				options: {
					archive: 'code-prettify.zip',
				},
				files: [
					{
						cwd: 'dist',
						src: [ '**/*' ],
						dest: 'code-prettify',
					},
				],
			},
		},

		wp_deploy: {
			options: {
				plugin_slug: 'code-prettify',
				build_dir: '<%= dist_dir %>',
				assets_dir: 'assets/wporg',
			},
			all: {
				options: {
					deploy_tag: true,
					deploy_trunk: true,
				},
			},
			ci: {
				options: {
					assets_dir:
						'true' === process.env.DEPLOY_TAG &&
						'true' === process.env.DEPLOY_TRUNK
							? 'assets/wporg'
							: null,
					skip_confirmation:
						'true' === process.env.DEPLOY_SKIP_CONFIRMATION,
					svn_user: process.env.DEPLOY_SVN_USERNAME,
					deploy_tag: 'true' === process.env.DEPLOY_TAG,
					deploy_trunk: 'true' === process.env.DEPLOY_TRUNK,
				},
			},
		},
	} );

	grunt.registerTask( 'build', [ 'clean', 'readmeMdToTxt', 'copy' ] );
	grunt.registerTask( 'deploy', [ 'build', 'wp_deploy:all' ] );
	grunt.registerTask( 'deploy-ci', [ 'build', 'wp_deploy:ci' ] );
	grunt.registerTask( 'package', [ 'build', 'compress' ] );
};
