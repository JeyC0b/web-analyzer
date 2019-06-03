<?php
namespace vipnytt\XRobotsTagParser\Tests;

use vipnytt\XRobotsTagParser;

/**
 * Class ExportTest
 *
 * @package vipnytt\XRobotsTagParser\Tests
 */
class ExportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider generateDataForTest
     * @param string $userAgent
     * @param array $headers
     */
    public function testExport($userAgent, $headers)
    {
        $parser = new XRobotsTagParser($userAgent, $headers);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertTrue($parser->export()['googlebot']['noindex']);
        $this->assertTrue($parser->export()['googlebot']['noarchive']);

        $this->assertTrue($parser->export()['bingbot']['noindex']);
        $this->assertTrue($parser->export()['bingbot']['noodp']);

        $this->assertTrue($parser->export()['']['noindex']);
        $this->assertTrue($parser->export()['']['noodp']);
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
                [
                    'X-Robots-Tag: googlebot: noindex, noarchive',
                    'X-Robots-Tag: bingbot: noindex, noodp',
                    'X-Robots-Tag: noindex, noodp'
                ]
            ]
        ];
    }
}
