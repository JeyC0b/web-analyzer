# Change Log

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [v1.0.5] - 2018-04-03
### Changed
- Updated Requirements to support PHP 7.2 - [@edgarcano]
- Added test to make sure changes to leage/uri doesn't cause problems - [@edgarcano]
- Documentation Tweaks - [@nickmoline]

## [v1.0.4] - 2017-01-27
### Changed
- Updated `tomverran/robots-txt-checker` to 1.14 - [@nickmoline]

## [v1.0.3] - 2016-12-30
### Fixed
- Missing chain returns in `Robots\Status` - [@nickmoline]

## [v1.0.2] - 2016-12-29 [YANKED]
### Changed
- Set explicit allowed reason in `Robots\Meta` if no META Robots tag is present - [@nickmoline]
- Set explicit allowed reason in `Robots\Header` if no `X-Robots-Tag` HTTP Header is present - [@nickmoline]
### Added
- Added Release Dates to releases in CHANGELOG - [@nickmoline]
- Added getters and setters for Redirects and Original URL to `Robots\Status` - [@nickmoline]
- Passed redirects and original url to `::createFromExisting` for `Robots\Status` - [@nickmoline]

## [v1.0.1] - 2016-12-27
### Fixed
- [#5](https://github.com/nickmoline/robots-checker/issues/5) Redirects not being followed and seen as a loop - [@nickmoline]
### Added
- Added MIT License File [LICENSE.txt](https://github.com/nickmoline/robots-checker/blob/master/LICENSE.txt) (was already specified in composer.json)  - [@nickmoline]
- Added [CONTRIBUTING.md](https://github.com/nickmoline/robots-checker/blob/master/CONTRIBUTING.md) for Contribution Instructions  - [@nickmoline]

## [v1.0.0] - 2016-12-26
### Added
- Initial Commits of base project - [@nickmoline]
- Classes for checking urls against various methods of robots exclusion - [@nickmoline]
- php-unit tests - [@nickmoline]

[Unreleased]: https://github.com/nickmoline/robots-checker
[v1.0.5]: https://github.com/nickmoline/robots-checker/releases/tag/v1.0.5
[v1.0.4]: https://github.com/nickmoline/robots-checker/releases/tag/v1.0.4
[v1.0.3]: https://github.com/nickmoline/robots-checker/releases/tag/v1.0.3
[v1.0.2]: https://github.com/nickmoline/robots-checker/releases/tag/v1.0.2
[v1.0.1]: https://github.com/nickmoline/robots-checker/releases/tag/v1.0.1
[v1.0.0]: https://github.com/nickmoline/robots-checker/releases/tag/v1.0.0

[@nickmoline]: https://nickmoline.com
[@edgarcano]: https://github.com/edgarcano
