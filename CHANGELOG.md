# CHANGELOG

A record of notable changes to the Reflect project since v1.0.0-beta.1 (released on the 2020-06-08):

<br>

<br>

## [Unreleased]

- Add Reflect update feature.
- Add *Download and use media locally* feature.

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

- Update all CSS and JS dependencies.

- Compile all JS files to be transpiled using Babel.

- Implement polyfill using [core-js](https://github.com/zloirock/core-js) and [regenerator-runtime](https://github.com/facebook/regenerator/blob/master/packages/regenerator-runtime/runtime.js).

- Change layout of elements in *Static Files Manager* page.

### Fixed

- Fix pagination appearing in *Static Files Manager*'s Pages list when published items are less than per page setting.
