# Change Log

All notable changes to this project will be documented in this file. This project adheres
to [Semantic Versioning](http://semver.org/). For change log format,
use [Keep a Changelog](http://keepachangelog.com/).

## [Unreleased]

### Added

- 133 new emoji from the GitHub list
- `composer update-emoji` to generate the emoji list from GitHub gemoji

### Changed

- Full Unicode sequences (ZWJ and variation selectors)
- Emoji codes converted when not glued to a letter or a digit
- Emoji list checked against GitHub API in both directions
- Development files excluded from archives
- Hardcoded version removed from `composer.json`
- CI workflows and README updated

### Fixed

- Wrong emoji for `:beetle:`, `:email:`, `:bride_with_veil:` and `:man_in_tuxedo:`

## [2.0.0] - 2021-08-23

### Changed

- Upgrade extension for `league/commonmark` 2.x

## [1.0.0] - 2020-08-13

Initial development
