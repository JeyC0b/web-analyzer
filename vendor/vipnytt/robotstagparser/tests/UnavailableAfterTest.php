<?php
namespace vipnytt\XRobotsTagParser\Tests;

use vipnytt\XRobotsTagParser;

/**
 * Class UnavailableAfterTest
 *
 * @package vipnytt\XRobotsTagParser\Tests
 */
class UnavailableAfterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider generateDataForTest
     * @param string $userAgent
     * @param array $headers
     */
    public function testUnavailableAfter($userAgent, $headers)
    {
        $parser = new XRobotsTagParser($userAgent, $headers);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertEquals(['unavailable_after' => 'Saturday, 01-Jul-00 07:00:00 PST'], $parser->getRules());
        $this->assertEquals(['unavailable_after' => 'Tuesday, 31-Dec-30 23:00:00 PST'], $parser->export()['']);
        $this->assertEquals(['unavailable_after' => 'Saturday, 01-Jul-00 07:00:00 PST'], $parser->export()['googlebot']);

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
                    'X-Robots-Tag: unavailable_after: Tuesday, 31-Dec-30 23:00:00 PST',
                    'X-Robots-Tag: googlebot: unavailable_after: Saturday, 01-Jul-00 07:00:00 PST'
                ]
            ],
            [
                'googlebot',
                [
                    'X-Robots-Tag: unavailable_after: 31 Dec 2030 23:00:00 PST',
                    'X-Robots-Tag: googlebot: unavailable_after: 01 Jul 2000 07:00:00 PST'
                ]
            ]
        ];
    }
}
