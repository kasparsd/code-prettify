# Code Prettify for WordPress

100% automatic code highlighting using the [Prettify library](https://github.com/kasparsd/code-prettify-lib/pull/1). No shortcodes, no classnames, fully automatic and pure awesome.

- **WordPress.org page:** https://wordpress.org/plugins/code-prettify/
- **Author:** [Kaspars Dambis](https://kaspars.net) — all of [my open source work](https://github.com/kasparsd)

## What it does

The plugin applies code highlighting automatically to all `<pre>` tags on the page. Compatible with Gutenberg and doesn't lock you in with specific markup requirements.

## Installation

Install it from your WordPress admin, or [download it from WordPress.org](https://wordpress.org/plugins/code-prettify/). It works out of the box and doesn't have any configuration options.

## Development

Requires Node.js 22 (see `.nvmrc`) and PHP 7.4+ with Composer.

```bash
npm install          # Installs PHP dependencies via Composer too
npm run lint         # PHP + JS linting
npm run zip          # Packages the repo into a versioned ZIP for a quick check
npm run env          # Spin up a wp-env environment for testing
```

There is no separate build step. Release packaging, checksums, signing and the WordPress.org SVN deploy are all handled by [`@wpelevator/wp-release`](https://www.npmjs.com/package/@wpelevator/wp-release), which reads the repo directly and selects files via `.distignore`. A local dry-run of the full deploy:

```bash
npx wp-release deploy-svn . --slug code-prettify --trunk --svn-tag <version> \
  --assets-dir assets/wporg --dry-run
```

## Release verification

Every release ships with a checksums manifest (`checksums.json`, in the WordPress.org plugin-checksums format) and Ed25519 signatures:

- The GitHub release assets include the ZIP and the manifest, each with a detached `.sig` signature.
- The manifest pair is included inside the plugin build itself (on WordPress.org and in the ZIP), so installed copies can be verified too.

The signing key public part:

```
ahCXGA0hozzxgvg+GjQ08HgCMCykMs+sKaXYqbMdGVU=
```

To verify a release with [`@wpelevator/wp-release`](https://www.npmjs.com/package/@wpelevator/wp-release):

```bash
npx @wpelevator/wp-release verify <plugin-directory-or-zip> \
  --checksums checksums.json \
  --public-key 'ahCXGA0hozzxgvg+GjQ08HgCMCykMs+sKaXYqbMdGVU='
```

For an installed copy, `verify` picks up the manifest embedded at the plugin root automatically. For a download, point it at the ZIP with `--zip <file>`.

## Contributing

1. Fork and clone the repo.
2. Create a feature branch.
3. Make your changes and run `npm run lint`.
4. Add a changeset that describes the release impact (see below).
5. Open a pull request against `master`.

### Changesets

Every pull request that changes the plugin needs a changeset under `.changeset/`, either by hand or with the interactive helper:

```bash
npx wp-release changeset-add
```

In a terminal it lists the packages, asks for the bump (`patch`, `minor` or `major`) and copies your release note into `.changeset/<name>.md`. For a change that should ship without a version bump (docs, tests, refactoring), use an empty changeset:

```bash
npx wp-release changeset-add --empty
```

The [changeset check](https://github.com/kasparsd/code-prettify/actions/workflows/test.yml) fails PRs that are missing one. Merging to `master` doesn't release anything directly:

1. The **Prepare Release** workflow applies the pending changesets and opens a release pull request with the version bumps and changelog entries.
2. Review it; merging it creates the release tag, a GitHub release with the notes from the changesets, and triggers the [WordPress.org deploy](https://github.com/kasparsd/code-prettify/actions/workflows/deploy.yml) with the signed checksums.

Released versions and their changes are listed in [`readme.txt`](readme.txt) (WordPress.org format), [`CHANGELOG.md`](CHANGELOG.md), and on the [releases page](https://github.com/kasparsd/code-prettify/releases).

## Credits

Cover photo by [Yuri Samoilov](https://www.flickr.com/photos/110751683@N02/13334080323/)

## License

GPL-2.0-or-later.
