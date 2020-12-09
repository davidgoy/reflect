# CHANGELOG

A record of notable changes to the Reflect project since v1.0.0-beta.1 (released on the 2020-06-08):

<br><br>

## [Unreleased]

- Add ability to download media from WordPress and serve the media directly from Reflect site.

<br><br>

## [1.0.0-beta.7] - 2020-12-09

### Fixed

- Delete backup files and folders created by the previous Reflect update process before renaming files and folders during the current update process.

<br><br>

## [1.0.0-beta.6] - 2020-12-08

### Changed

- Allow user to define *From* name and email address in the email header to reduce the chance of emails sent by Reflect Form Mailer addon from triggering spam filter.

- Remove *Reply To* line from email header.

<br><br>

## [1.0.0-beta.5] - 2020-12-01

### Added

- Add auto update feature for Reflect core and all bundled, official addons and themes.
- Improve *Settings* page and *Authentication* page UI

### Fixed

- Fix *Skip Config Check* feature.

<br><br>

## [1.0.0-beta.4] - 2020-06-29

### Added

- Add CSRF prevention safeguard. 

<br><br>

## [1.0.0-beta.3] - 2020-06-16

### Added

- Add [unfetch](https://github.com/developit/unfetch) polyfill.

### Fixed

- Fix forms not submitting on Internet Explorer 11.  

<br><br>

## [1.0.0-beta.2] - 2020-06-15

### Added

- Add WordPress API connection check feature to *Settings* page.

- Add *Under Maintenance* template to Default theme.

### Changed

- Change app folder structure.

- Compile all JS files to be transpiled using Babel.

- Implement polyfill using [core-js](https://github.com/zloirock/core-js) and [regenerator-runtime](https://github.com/facebook/regenerator/blob/master/packages/regenerator-runtime/runtime.js).

- Change layout of elements in *Static Files Manager* page.

### Fixed

- Fix pagination appearing in *Static Files Manager*'s Pages list when published items are less than per page setting.
