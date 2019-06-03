<?php

namespace NickMoline\Robots\Test;

use PHPUnit_Framework_TestCase;
use NickMoline\Robots\RobotsTxt;

class RobotsTxtTest extends PHPUnit_Framework_TestCase
{
    private $robotsFileContents;

    public function setUp()
    {
        $this->robotsFileContents = trim("
User-agent: ia_archiver
Disallow: /

User-agent: bingbot
Disallow: /blocked
Disallow: /blockbing
Allow: /allowgoogle

User-agent: *
Disallow: /blocked
Disallow: /allowbing
Disallow: /*.txt
Disallow: /blockonly$
Allow: /banana/split.txt
Allow: /

Sitemap: http://www.example.com/sitemap.xml");
    }

    /**
     * Test that if no robots.txt file is present, the url is shown as allowed
     */
    public function testAmbiguous()
    {
        $robots = new RobotsTxt("http://www.example.com/ambiguous");
        $robots->setRobotsEmpty();

        $this->assertTrue($robots->validate());
    }

    /**
     * Test that if robots.txt file hits final Allow: /
     */
    public function testAllowed()
    {

        $robots = new RobotsTxt("http://www.example.com/allowed");
        $robots->setRobotsHandler($this->robotsFileContents);

        $this->assertTrue($robots->validate());
    }

    /**
     * Test that if robots.txt is set to Disallow globally
     */
    public function testBlocked()
    {
        $robots = new RobotsTxt("http://www.example.com/blocked");
        $robots->setRobotsHandler($this->robotsFileContents);

        $this->assertFalse($robots->validate());
    }

    /**
     * Test that if robots.txt Disallows to one user agent but allows to others
     */
    public function testSpecificDenied()
    {
        $robots = new RobotsTxt("http://www.example.com/blockbing");
        $robots->setRobotsHandler($this->robotsFileContents);


        $this->assertTrue($robots->validate());
        $this->assertFalse($robots->setUserAgent("bingbot")->validate());
    }

    /**
     * Test that if robots.txt Allows to one user agent but disallows to others
     */
    public function testSpecificAllowed()
    {
        $robots = new RobotsTxt("http://www.example.com/allowbing");
        $robots->setRobotsHandler($this->robotsFileContents);


        $this->assertFalse($robots->validate());
        $this->assertTrue($robots->setUserAgent("bingbot")->validate());
    }

    /**
     * Test that wildcard block lines in the robots.txt file are blocked
     */
    public function testBlockedWildcard()
    {
        $robots = new RobotsTxt("http://www.example.com/banana.txt");
        $robots->setRobotsHandler($this->robotsFileContents);

        $this->assertFalse($robots->validate());
    }

    /**
     * Test that exceptions to the wildcard blocked lines (specific allow) are allowed
     */
    public function testWildcardException()
    {
        $robots = new RobotsTxt("http://www.example.com/banana/split.txt");
        $robots->setRobotsHandler($this->robotsFileContents);

        $this->assertTrue($robots->validate());
    }

    /**
     * Test that files in subpaths of a blocked line are blocked as well
     */
    public function testSubpath()
    {
        $robots = new RobotsTxt("http://www.example.com/blocked/2.html");
        $robots->setRobotsHandler($this->robotsFileContents);

        $this->assertFalse($robots->validate());
    }

    /**
     * Test that lines ending in $ don't block subpaths
     */
    public function testSubpathFixedEnding()
    {
        $robots = new RobotsTxt("http://www.example.com/blockonly/2.html");
        $robots->setRobotsHandler($this->robotsFileContents);

        $this->assertTrue($robots->validate());

    }

}
