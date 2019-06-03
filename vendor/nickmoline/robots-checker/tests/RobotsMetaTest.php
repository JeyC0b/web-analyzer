<?php

namespace NickMoline\Robots\Test;

use PHPUnit_Framework_TestCase;
use NickMoline\Robots\Meta as RobotsMeta;

class RobotsMetaTest extends PHPUnit_Framework_TestCase
{
    private $baseHTML;

    public function setUp()
    {
        $this->baseHTML = '<!DOCTYPE html>
<html>
<head>
    <title>Meta Title Test Text</title>
    <meta itemprop="something" content="Hello!" />
    <meta name="Generator" content="A Test Generator" />
    **METATAGS**
</head>
<body>
    <h1>Meta Tag Test</h1>

    <p>This is a test page</p>
</body>
</html>';
    }
    /**
     * Test that if no meta robots or no meta [botname] tag exist
     */
    public function testNoTag()
    {
        $robots = new RobotsMeta("http://www.example.com/ambiguous");
        $html = $this->baseHTML;
        $html = str_replace('**METATAGS**', '', $html);

        $robots->setContents($html)->setFetched();

        $this->assertTrue($robots->validate());
    }

    /**
     * Test that if meta robots is set to index, follow that indexing is allowed
     */
    public function testAllowedTag()
    {
        $robots = new RobotsMeta("http://www.example.com/allowed");
        $html = $this->baseHTML;
        $html = str_replace('**METATAGS**', '<meta name="robots" contents="index, follow" />', $html);

        $robots->setContents($html)->setFetched();

        $this->assertTrue($robots->validate());
    }

    /**
     * Test that if no X-Robots-Tag header set to noindex, nofollow, the url is shown as denied
     */
    public function testBlockedTag()
    {
        $robots = new RobotsMeta("http://www.example.com/blocked");
        $html = $this->baseHTML;
        $html = str_replace('**METATAGS**', '<meta name="robots" content="noindex, nofollow" />', $html);

        $robots->setContents($html)->setFetched();
        
        $this->assertFalse($robots->validate());
    }

    /**
     * Test that if no X-Robots-Tag header has specific instructions for one
     * user agent, that those rules are respected
     */
    public function testSpecificTag()
    {
        $robots = new RobotsMeta("http://www.example.com/blockgoogle");
        $html = $this->baseHTML;
        $html = str_replace('**METATAGS**', '<meta name="Googlebot" content="noindex, nofollow" />' . "\n" .
                                            '<meta name="robots" content="index, follow" />', $html);

        $robots->setContents($html)->setFetched();

        $this->assertFalse($robots->validate());
        $this->assertTrue($robots->setUserAgent("bingbot")->validate());
    }

    /**
     * Test that if no X-Robots-Tag header has specific instructions for one
     * user agent, that those rules are respected
     */
    public function testSpecificTagAllowedOverride()
    {
        $robots = new RobotsMeta("http://www.example.com/blockgoogle");
        $html = $this->baseHTML;
        $html = str_replace('**METATAGS**', '<meta name="Googlebot" content="index, follow" />' . "\n" .
                                            '<meta name="robots" content="noindex, nofollow" />', $html);

        $robots->setContents($html)->setFetched();

        $this->assertTrue($robots->validate());
        $this->assertFalse($robots->setUserAgent("bingbot")->validate());
    }

}
