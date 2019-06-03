<?php
namespace vipnytt\XRobotsTagParser\Tests;

use vipnytt\XRobotsTagParser;

/**
 * Class MultiTest
 *
 * @package vipnytt\XRobotsTagParser\Tests
 */
class MultiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider generateDataForTest
     * @param string $userAgent
     * @param array $headers
     */
    public function testMultipleDirectives($userAgent, $headers)
    {
        $parser = new XRobotsTagParser\Adapters\TextString($headers, $userAgent);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertTrue($parser->getRules()['all']);
        $this->assertTrue($parser->export()['']['all']);
        $this->assertTrue($parser->export()['googlebot']['all']);

        $this->assertTrue($parser->getRules()['noindex']);
        $this->assertTrue($parser->export()['']['noindex']);
        $this->assertTrue($parser->export()['googlebot']['noindex']);

        $this->assertTrue($parser->getRules()['nofollow']);
        $this->assertTrue($parser->export()['']['nofollow']);
        $this->assertTrue($parser->export()['googlebot']['nofollow']);

        $this->assertTrue($parser->getRules()['none']);
        $this->assertTrue($parser->export()['']['none']);
        $this->assertTrue($parser->export()['googlebot']['none']);

        $this->assertTrue($parser->getRules()['noarchive']);
        $this->assertTrue($parser->export()['']['noarchive']);
        $this->assertTrue($parser->export()['googlebot']['noarchive']);

        $this->assertTrue($parser->getRules()['nosnippet']);
        $this->assertTrue($parser->export()['']['nosnippet']);
        $this->assertTrue($parser->export()['googlebot']['nosnippet']);

        $this->assertTrue($parser->getRules()['noodp']);
        $this->assertTrue($parser->export()['']['noodp']);
        $this->assertTrue($parser->export()['googlebot']['noodp']);

        $this->assertTrue($parser->getRules()['notranslate']);
        $this->assertTrue($parser->export()['']['notranslate']);
        $this->assertTrue($parser->export()['googlebot']['notranslate']);

        $this->assertTrue($parser->getRules()['noimageindex']);
        $this->assertTrue($parser->export()['']['noimageindex']);
        $this->assertTrue($parser->export()['googlebot']['noimageindex']);
    }

    /**
     * Generate test data
     * @return array
     */
    public function generateDataForTest()
    {
        return [
            [
                'googlebot',
                <<<STRING
HTTP/1.1 200 OK
Date: Tue, 25 May 2010 21:42:43 GMT
X-Robots-Tag: all
X-Robots-Tag: noindex
X-Robots-Tag: nofollow
X-Robots-Tag: none
X-Robots-Tag: noarchive
X-Robots-Tag: nosnippet
X-Robots-Tag: noodp
X-Robots-Tag: notranslate
X-Robots-Tag: noimageindex
X-Robots-Tag: unavailable_after: Friday, 25 Jun 2010 15:00:00 PST
X-Robots-Tag: googlebot: all, none, nofollow,nosnippet,notranslate, unavailable_after: Friday, 25 Jun 2010 15:00:00 PST, noindex, noarchive, noodp,noimageindex
STRING
            ]
        ];
    }
}
