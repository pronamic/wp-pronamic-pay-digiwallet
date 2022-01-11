# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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

[Unreleased]: https://github.com/wp-pay-gateways/digiwallet/compare/3.0.0...HEAD
[3.0.0]: https://github.com/wp-pay-gateways/digiwallet/compare/2.0.0...3.0.0
[2.0.0]: https://github.com/wp-pay-gateways/digiwallet/compare/1.0.1...2.0.0
[1.0.1]: https://github.com/wp-pay-gateways/digiwallet/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/wp-pay-gateways/digiwallet/releases/tag/1.0.0
