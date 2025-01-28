# Changelog

All notable changes to `CMSMS` will be documented in this file

## [4.0.0] - 2025-02-??
#### Changed
- Moved from XML to JSON for the request body
- Changed CM endpoint
#### Added
- Added config value for encoding detection type
- Two events for success and failure: `SMSSentSuccessfullyEvent` and `SMSSendingFailedEvent`

## [3.3.0] - 2024-03-22
#### Added
- Laravel 11 support

## [3.3.0] - 2024-03-22
#### Changed
- Update CM endpoint by @marventhieme

## [3.2.0] - 2023-03-29
#### Changed
- Added support for Laravel 10.0 (#17) by @charleskoko

## [3.1.0] - 2022-07-07
#### Changed
- Add custom creator alias to ChannelManager (#16) by @mabdullahsari

## [3.0.0] - 2022-07-05
#### Changed
- General cleanup of package. Added return types.
- Added support for Laravel 9.0
- Added support for PHP 8.1
- Dropped support for Laravel 8 and below.
- Dropped support for PHP 8.0 and below.

## [2.2.0] - 2020-09-09
#### Changed
- Added support for Laravel 8.0

## [2.1.0] - 2020-03-04
#### Changed
- Added support for Laravel 7.0

## [2.0.0] - 2019-09-13
#### Changed
- Added support for Laravel > 5.5 & 6.0
- Make the `CmsmsMessage` constructor private to enforce static factory usage
- Dropped support for PHP 7.0 & 7.1

## [1.0.0] - 2016-09-29
#### Changed
- Added support for Laravel 5.4 and 5.5
- Dropped support for PHP 5.6
- Added support for Laravel 5.5 package autoloader

## [0.0.5] - 2016-09-29
#### Fixed
- Allow an empty `tariff` to be set.

## [0.0.4] - 2016-09-13
#### Fixed
- Moved the `tariff` field under the correct XML child.

## [0.0.3] - 2016-09-12
#### Added
- Added `multipart` method for `CmsmsMessage`.

## [0.0.2] - 2016-09-04
#### Added
- Added `tariff` method for `CmsmsMessage`.

## [0.0.1] - 2016-09-01
- Experimental release
