const ignoreParse = require( 'parse-gitignore' );
const loadGruntTasks = require( 'load-grunt-tasks' );

module.exports = function ( grunt ) {
	// Load all Grunt plugins.
	loadGruntTasks( grunt );

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

		copy: {
			dist: {
				src: [ '**' ].concat( distignore ),
				dest: '<%= dist_dir %>',
				expand: true,
			},
		},
	} );

	grunt.registerTask( 'build', [ 'clean', 'copy' ] );
};
