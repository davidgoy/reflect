# CHANGELOG

A record of notable changes to the Reflect project since v1.0.0-beta.1 (released on the 2020-06-08):

<br><br>

## [1.0.0-beta.16] - 2021-12-29

### Changed

- Update dependencies.
  
<br><br>  

## [1.0.0-beta.15] - 2021-08-22

### Changed

- Update dependencies.
- Update all views and Default template to use Bootstrap 5

<br><br>

## [1.0.0-beta.14] - 2021-07-05

### Changed

- Update PHP dependencies.

<br><br>

## [1.0.0-beta.13] - 2021-05-31

### Changed

- Update PHP and JS dependencies.

<br><br>

## [1.0.0-beta.12] - 2021-04-20

### Fixed

- Fix bug that prevented data from being retrieved from a WordPress site that uses self-signed SSL certificate.

<br><br>

## [1.0.0-beta.11] - 2021-04-06

### Added

- Allow data to be retrieved from a WordPress site that uses self-signed SSL certificate.

<br><br>

## [1.0.0-beta.10] - 2021-03-18

### Fixed

- Automatically remove any reference to CMS in anchor links when rendering pages.

<br><br>

## [1.0.0-beta.9] - 2021-03-17

### Changed

- Updated PHP and JS dependencies.

<br><br>

## [1.0.0-beta.8] - 2021-01-10

### Added

- Append Reflect version number to the end of all JS and CSS dependency link tags. 

<br><br>

## [1.0.0-beta.7] - 2020-12-08

### Fixed

- Delete backup files and folders created by the previous Reflect update process before renaming files and folders during the current update process.

<br><br>

## [1.0.0-beta.6] - 2020-12-07

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
