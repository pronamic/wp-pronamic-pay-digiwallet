# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.3.4] - 2023-07-12

### Commits

- Updated composer.json ([f61887a](https://github.com/pronamic/wp-pronamic-pay-digiwallet/commit/f61887ad57b0d432a02dbe2212c16a99d19882c9))
- Created .pronamic-build-ignore ([70b8113](https://github.com/pronamic/wp-pronamic-pay-digiwallet/commit/70b81132cccae32be9a0a76123f29281f362664e))

Full set of changes: [`3.3.3...3.3.4`][3.3.4]

[3.3.4]: https://github.com/pronamic/wp-pronamic-pay-digiwallet/compare/v3.3.3...v3.3.4

## [3.3.3] - 2023-06-01

### Commits

- Switch from `pronamic/wp-deployer` to `pronamic/pronamic-cli`. ([bffd1d1](https://github.com/pronamic/wp-pronamic-pay-digiwallet/commit/bffd1d126a881ab924049f2928f1a0378be0f660))
- Fixed typo. ([62bc234](https://github.com/pronamic/wp-pronamic-pay-digiwallet/commit/62bc2349597ee98a337b0188a52cad9fa146f2b6))
- Updated .gitattributes ([87839a7](https://github.com/pronamic/wp-pronamic-pay-digiwallet/commit/87839a756cf138b5dff0866128b2c90d17e6a432))

Full set of changes: [`3.3.2...3.3.3`][3.3.3]

[3.3.3]: https://github.com/pronamic/wp-pronamic-pay-digiwallet/compare/v3.3.2...v3.3.3

## [3.3.2] - 2023-03-27

### Commits

- Set Composer type to `wordpress-plugin`. ([e4ee9ce](https://github.com/pronamic/wp-pronamic-pay-digiwallet/commit/e4ee9cee352da8b7d55d5c97f2ab02637b4a54ad))
- Updated .gitattributes ([8468b1f](https://github.com/pronamic/wp-pronamic-pay-digiwallet/commit/8468b1f16a58b4b88e9d76cec57aa8fe8ffcdc7b))
- Requires PHP: 7.4. ([2681e67](https://github.com/pronamic/wp-pronamic-pay-digiwallet/commit/2681e678da78770f847e12b365fd82870e226904))

Full set of changes: [`3.3.1...3.3.2`][3.3.2]

[3.3.2]: https://github.com/pronamic/wp-pronamic-pay-digiwallet/compare/v3.3.1...v3.3.2

## [3.3.1] - 2023-01-31
### Composer

- Changed `php` from `>=8.0` to `>=7.4`.
Full set of changes: [`3.3.0...3.3.1`][3.3.1]

[3.3.1]: https://github.com/pronamic/wp-pronamic-pay-digiwallet/compare/v3.3.0...v3.3.1

## [3.3.0] - 2022-12-29

### Commits

- Added support for https://github.com/WordPress/wp-plugin-dependencies. ([8160ad7](https://github.com/pronamic/wp-pronamic-pay-digiwallet/commit/8160ad7bfb634d75bbd9a3e0d078448783233988))
- No longer use deprecated `FILTER_SANITIZE_STRING`. ([58f431a](https://github.com/pronamic/wp-pronamic-pay-digiwallet/commit/58f431a3fd19e062e0ab8e3db4eb55b3780b60f9))

### Composer

- Changed `php` from `>=5.6.20` to `>=8.0`.
- Changed `wp-pay/core` from `^4.4` to `v4.6.0`.
	Release notes: https://github.com/pronamic/wp-pay-core/releases/tag/v4.6.0
Full set of changes: [`3.2.1...3.3.0`][3.3.0]

[3.3.0]: https://github.com/pronamic/wp-pronamic-pay-digiwallet/compare/v3.2.1...v3.3.0

## [3.2.1] - 2022-09-27
- Require `wp-pay/core` version `^4.4`.
- Update plugin version to `3.2.1`. 

## [3.2.0] - 2022-09-26
- Updated payment methods registration.

## [3.1.0] - 2022-04-11
- Coding standards.
- Set mode.

## [3.0.0] - 2022-01-11
### Changed
- Updated to https://github.com/pronamic/wp-pay-core/releases/tag/4.0.0.
- Added support for other RTLO meta keys.

## [2.0.0] - 2021-08-05
- Updated to `pronamic/wp-pay-core`  version `3.0.0`.
- Updated to `pronamic/wp-money`  version `2.0.0`.
- Changed `TaxedMoney` to `Money`, no tax info.
- Switched to `pronamic/wp-coding-standards`.
- Added PayPal support.
- Use full response body as exception message, will change "Unknown internal error" to "DW_IE_0001 Unknown internal error".
- Allow empty transaction ID in start response.

## [1.0.1] - 2021-05-28
- Add readme.

## [1.0.0] - 2021-05-28
- First release.

[Unreleased]: https://github.com/pronamic/wp-pronamic-pay-digiwallet/compare/3.2.1...HEAD
[3.2.1]: https://github.com/pronamic/wp-pronamic-pay-digiwallet/compare/3.2.0...3.2.1
[3.2.0]: https://github.com/pronamic/wp-pronamic-pay-digiwallet/compare/3.1.0...3.2.0
[3.1.0]: https://github.com/pronamic/wp-pronamic-pay-digiwallet/compare/3.0.0...3.1.0
[3.0.0]: https://github.com/pronamic/wp-pronamic-pay-digiwallet/compare/2.0.0...3.0.0
[2.0.0]: https://github.com/pronamic/wp-pronamic-pay-digiwallet/compare/1.0.1...2.0.0
[1.0.1]: https://github.com/pronamic/wp-pronamic-pay-digiwallet/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/pronamic/wp-pronamic-pay-digiwallet/releases/tag/1.0.0
