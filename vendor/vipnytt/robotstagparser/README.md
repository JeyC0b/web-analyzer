[![Build Status](https://travis-ci.org/VIPnytt/RobotsTagParser.svg?branch=master)](https://travis-ci.org/VIPnytt/RobotsTagParser)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/VIPnytt/RobotsTagParser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/VIPnytt/RobotsTagParser/?branch=master)
[![Code Climate](https://codeclimate.com/github/VIPnytt/RobotsTagParser/badges/gpa.svg)](https://codeclimate.com/github/VIPnytt/RobotsTagParser)
[![Test Coverage](https://codeclimate.com/github/VIPnytt/RobotsTagParser/badges/coverage.svg)](https://codeclimate.com/github/VIPnytt/RobotsTagParser/coverage)
[![License](https://poser.pugx.org/VIPnytt/RobotsTagParser/license)](https://github.com/VIPnytt/RobotsTagParser/blob/master/LICENSE)
[![Packagist](https://img.shields.io/packagist/v/vipnytt/robotstagparser.svg)](https://packagist.org/packages/vipnytt/robotstagparser)
[![Gitter](https://badges.gitter.im/VIPnytt/RobotsTagParser.svg)](https://gitter.im/VIPnytt/RobotsTagParser)

# X-Robots-Tag HTTP header parser
PHP class to parse X-Robots-Tag HTTP headers according to [Google X-Robots-Tag HTTP header specifications](https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag#using-the-x-robots-tag-http-header).

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b89a070f-07d3-490a-841a-0ae995574158/big.png)](https://insight.sensiolabs.com/projects/b89a070f-07d3-490a-841a-0ae995574158)

#### Requirements:
- PHP [>=5.6](http://php.net/supported-versions.php)
- PHP [mbstring](http://php.net/manual/en/book.mbstring.php) extension

Note: HHVM support is planned once [facebook/hhvm#4277](https://github.com/facebook/hhvm/issues/4277) is fixed.

## Installation
The library is available via [Composer](https://getcomposer.org). Add this to your `composer.json` file:

```json
{
    "require": {
        "vipnytt/robotstagparser": "~0.2"
    }
}
```
Then run `composer update`.

## Getting Started

### Basic example
Get all rules affecting _you_, this includes the following:
- All generic rules
- Rules specific to _your_ User-Agent (if there is any)
```php
use vipnytt\XRobotsTagParser;

$headers = [
    'X-Robots-Tag: noindex, noodp',
    'X-Robots-Tag: googlebot: noindex, noarchive',
    'X-Robots-Tag: bingbot: noindex, noarchive, noimageindex'
];

$parser = new XRobotsTagParser('myUserAgent', $headers);
$rules = $parser->getRules(); // <-- returns an array of rules
```

### Different approaches:

#### Get the HTTP headers by requesting an URL
```php
use vipnytt\XRobotsTagParser;

$parser = new XRobotsTagParser\Adapters\Url('http://example.com/', 'myUserAgent');
$rules = $parser->getRules();
```

#### Use your existing GuzzleHttp request
```php
use vipnytt\XRobotsTagParser;
use GuzzleHttp\Client;

$client = new GuzzleHttp\Client();
$response = $client->request('GET', 'http://example.com/');

$parser = new XRobotsTagParser\Adapters\GuzzleHttp($response, 'myUserAgent');
$array = $parser->getRules();
```

#### Provide HTTP headers as an string
```php
use vipnytt\XRobotsTagParser;

$string = <<<STRING
HTTP/1.1 200 OK
Date: Tue, 25 May 2010 21:42:43 GMT
X-Robots-Tag: noindex
X-Robots-Tag: nofollow
STRING;

$parser = new XRobotsTagParser\Adapters\TextString($string, 'myUserAgent');
$array = $parser->getRules();
```

### Export all rules
Returns an array containing _all_ rules for _any_ User-Agent.
```php
use vipnytt\XRobotsTagParser;

$parser = new XRobotsTagParser('myUserAgent', $headers);
$array = $parser->export();
```

## Directives:
- [x] `all` - There are no restrictions for indexing or serving.
- [x] `none` - Equivalent to `noindex` and `nofollow`.
- [x] `noindex` - Do not show this page in search results and do not show a "Cached" link in search results.
- [x] `nofollow` - Do not follow the links on this page.
- [x] `noarchive` - Do not show a "Cached" link in search results.
- [x] `nosnippet` - Do not show a snippet in the search results for this page.
- [x] `noodp` - Do not use metadata from the [Open Directory project](http://dmoz.org/) for titles or snippets shown for this page.
- [x] `notranslate` - Do not offer translation of this page in search results.
- [x] `noimageindex` - Do not index images on this page.
- [x] `unavailable_after` - Do not show this page in search results after the specified date/time.

Source: [https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag](https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag#valid-indexing--serving-directives)
