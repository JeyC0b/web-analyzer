<?php

namespace NickMoline\Robots\Test;

use League\Uri\Http;
use NickMoline\Robots\RobotsTxt;
use PHPUnit_Framework_TestCase;

class UriPackageUsageTest extends PHPUnit_Framework_TestCase
{
    public function testUrl()
    {
        $validator = new RobotsTxt();

        $this->assertEquals('/search', $validator->setURL('https://www.google.com/search')->getRelativeUrl());
        $this->assertEquals('https://www.google.com/robots.txt', $validator->setURL('https://www.google.com/search')->getRobotsUrl());
        $this->assertEquals('https://www.google.com/', $validator->setURL('https://www.google.com/search')->getHomepageUrl());
        $this->assertEquals(true, $validator->setURL('https://www.google.com/search/about')->validate());
        $this->assertEquals(false, $validator->setURL('https://www.google.com/search')->validate());
        $this->assertEquals('Denied by robots.txt', $validator->getReason());
    }
}
