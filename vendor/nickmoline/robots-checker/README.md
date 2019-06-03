# Robots Exclusion Protocol Checking Classes v1.0.5 [![Build Status](https://travis-ci.org/nickmoline/robots-checker.svg?branch=master)](https://travis-ci.org/nickmoline/robots-checker)

These classes allow you to check all of the different ways you can exclude a URL from search engines.

##

## Classes

You can instantiate the following classes:

* `NickMoline\Robots\RobotsTxt` : Checks the corresponding robots.txt file for a url
* `NickMoline\Robots\Status` : Checks the HTTP Status code for an indexable URL
* `NickMoline\Robots\Header` : Checks the HTTP `X-Robots-Tag` Header
* `NickMoline\Robots\Meta` : Checks the `<meta name="robots">` tag (as well as bot specific tags)
* `NickMoline\Robots\All` : Wrapper class that will run all of the above checks

## Example Usage

```php
<?php

require NickMoline\Robots\RobotsTxt;
require NickMoline\Robots\Header as RobotsHeader;
require NickMoline\Robots\All as RobotsAll;

$checker = new RobotsTxt("http://www.example.com/test.html");
$allowed = $checker->verify();                              // By default it checks Googlebot
$allowed = $checker->setUserAgent("bingbot")->verify();     // Checks to see if blocked for bingbot by robots.txt file

echo $checker->getReason();              // Get the reason the url is allowed or denied

$checker2 = new RobotsHeader("http://www.example.com/test.html");
$allowed = $checker->verify();           // Same as above but will test the X-Robots-Tag HTTP headers

$checkerAll = new RobotsAll("http://www.example.com/test.html");
$allowed = $checker->verify();           // This one runs all of the available tests
```
