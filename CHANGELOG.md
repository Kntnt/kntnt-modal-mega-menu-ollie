# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/), and this project adheres to [Semantic Versioning](https://semver.org/). While the major version is `0`, the project makes no backwards-compatibility commitments: breaking changes can land in any release.

## [Unreleased]

### Added

- Modal behaviour for Ollie Menu Designer's Dropdown Menu: while a menu is open the page behind it is locked, so trying to scroll the menu no longer scrolls the page instead. The lock is keyed to any open menu and lifts the moment the last one closes. The plugin adds no classes and no styling of its own.
- An internal scroll for a desktop menu taller than the viewport, so its lower part can be reached instead of spilling past the bottom of the screen with no way to get to it. The panel's height is measured live, so this composes with any plugin that repositions the panel. This is a temporary workaround for a gap in Ollie Menu Designer, which caps the panel on mobile but not on desktop; it can be dropped once upstream does the same on desktop. Reported to OllieWP.
- `Requires Plugins: ollie-menu-designer` header, so WordPress refuses to activate the plugin without Ollie Menu Designer and deactivates it if Ollie Menu Designer goes away.
- A PHP version guard in the main plugin file, as a second line of defence for installs that load the plugin outside the normal activation path.
- Self-updating from the project's GitHub releases: the plugin appears under *Dashboard → Updates* like any other, checking the repository named by its own `Plugin URI` header at most once every six hours. A release is only offered when it carries a ZIP asset served from GitHub's own download hosts, so a tampered header cannot redirect the update installer at an attacker's package.
- `build-release-zip.sh`, which builds a release zip containing only runtime files, and can create or update the GitHub release and upload the asset. The asset name carries no version segment, keeping the `latest/download` URL stable.
