import {
	composerJsonVersion,
	defineConfig,
	wordpressPluginVersion,
	wordpressStableTag,
} from '@wpelevator/wp-release';

// Single-package repository: the plugin lives at the root, so release tags
// are plain versions (the package path placeholder stays empty). Merge a
// release pull request to master and the tag workflow runs the release.
export default defineConfig( {
	baseBranch: 'master',
	readmeChangelog: true,
	// Without a marker, the plugin readme uses the "== Changelog ==" heading
	// that is already in readme.txt.
	packages: [
		{
			glob: '.',
			version: {
				from: wordpressPluginVersion(),
				to: [
					wordpressPluginVersion(),
					wordpressStableTag(),
					composerJsonVersion( { ifPresent: true } ),
				],
			},
		},
	],
	// Release integrity defaults for `zip`: an unversioned checksums manifest
	// embedded (with its signature) at the package root of every build.
	integrity: {
		file: 'checksums.json',
		embed: true,
		sign: { formats: [ 'wordpress' ] },
	},
} );
