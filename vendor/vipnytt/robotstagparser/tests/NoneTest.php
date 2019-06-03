<?php
namespace vipnytt\XRobotsTagParser\Tests;

use vipnytt\XRobotsTagParser;

/**
 * Class NoneTest
 *
 * @package vipnytt\XRobotsTagParser\Tests
 */
class NoneTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider generateDataForTest
     * @param string $userAgent
     * @param array $headers
     */
    public function testNone($userAgent, $headers)
    {
        $parser = new XRobotsTagParser($userAgent, $headers);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertTrue($parser->getRules()['none']);
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
                    'X-Robots-Tag: none'
                ]
            ]
        ];
    }
}
