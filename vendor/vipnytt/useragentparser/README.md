[![Build Status](https://travis-ci.org/VIPnytt/UserAgentParser.svg?branch=master)](https://travis-ci.org/VIPnytt/UserAgentParser)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/VIPnytt/UserAgentParser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/VIPnytt/UserAgentParser/?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/319c474eb3a681c50ba3/maintainability)](https://codeclimate.com/github/VIPnytt/UserAgentParser/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/319c474eb3a681c50ba3/test_coverage)](https://codeclimate.com/github/VIPnytt/UserAgentParser/test_coverage)
[![License](https://poser.pugx.org/VIPnytt/UserAgentParser/license)](https://github.com/VIPnytt/UserAgentParser/blob/master/LICENSE)
[![Packagist](https://img.shields.io/packagist/v/vipnytt/useragentparser.svg)](https://packagist.org/packages/vipnytt/useragentparser)

# User-Agent parser for robot rule sets
Parser and group determiner optimized for ``robots.txt``, ``X-Robots-tag`` and ``Robots-meta-tag`` usage cases.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1386c14c-546c-4c42-ac55-91ea3a3a1ae1/big.png)](https://insight.sensiolabs.com/projects/1386c14c-546c-4c42-ac55-91ea3a3a1ae1)

#### Requirements:
- PHP 5.5+ or 7.0+

## Installation
The library is available for install via [Composer](https://getcomposer.org). Just add this to your `composer.json` file:
```json
{
    "require": {
        "vipnytt/useragentparser": "^1.0"
    }
}
```
Then run `php composer update`.

## Features
- Stripping of the version tag.
- List any _rule groups_ the User-Agent belongs to.
- Determine the correct group of records by finding the group with the most specific User-agent that still matches.

### When to use it?
- When parsing `robots.txt` rule sets, for robots online.
- When parsing the ``X-Robots-Tag`` HTTP header.
- When parsing ``Robots meta tags`` in HTML / XHTML documents.

Note: _Full User-agent strings, like them sent by eg. web-browsers, is not compatible, this is by design._
Supported User-agent string formats are ``UserAgentName/version`` with or without the version tag. Eg. ``MyWebCrawler/2.0`` or just ``MyWebCrawler``.


## Getting Started

### Strip the version tag.
```php
use vipnytt\UserAgentParser;

$parser = new UserAgentParser('googlebot/2.1');
$product = $parser->getProduct()); // googlebot
```

### List different groups the User-agent belongs to
```php
use vipnytt\UserAgentParser;

$parser = new UserAgentParser('googlebot-news/2.1');
$userAgents = $parser->getUserAgents());

array(
    'googlebot-news/2.1',
    'googlebot-news/2',
    'googlebot-news',
    'googlebotnews',
    'googlebot'
);
```

### Determine the correct group
Determine the correct group of records by finding the group with the most specific User-agent that still matches your rule sets.
```php
use vipnytt\UserAgentParser;

$parser = new UserAgentParser('googlebot-news');
$match = $parser->getMostSpecific(['googlebot/2.1', 'googlebot-images', 'googlebot'])); // googlebot
```

### Cheat sheet
```php
$parser = new UserAgentParser('MyCustomCrawler/1.2');

// Determine the correct rule set (robots.txt / robots meta tag / x-robots-tag)
$parser->getMostSpecific($array); // string

// Parse
$parser->getUserAgent(); // string 'MyCustomCrawler/1.2'
$parser->getProduct(); // string 'MyCustomCrawler'
$parser->getVersion(); // string '1.2'

// Crunch the data into groups, from most to less specific
$parser->getUserAgents(); // array
$parser->getProducts(); // array
$parser->getVersions(); // array
```

## Specifications
- [x] [Google Robots.txt specifications](https://developers.google.com/webmasters/control-crawl-index/docs/robots_txt)
- [x] [Google Robots meta tag and X-Robots-Tag HTTP header specifications](https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag)
- [x] [Yandex robots.txt specifications](https://yandex.com/support/webmaster/controlling-robot/robots-txt.xml)
- [x] [RFC 7231](https://tools.ietf.org/html/rfc7231), [~~2616~~](https://tools.ietf.org/html/rfc2616)
- [x] [RFC 7230](https://tools.ietf.org/html/rfc7230), [~~2616~~](https://tools.ietf.org/html/rfc2616)
