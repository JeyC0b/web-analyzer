<?php
namespace vipnytt\XRobotsTagParser\Tests;

use vipnytt\XRobotsTagParser;

/**
 * Class NoindexTest
 *
 * @package vipnytt\XRobotsTagParser\Tests
 */
class NoindexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider generateDataForTest
     * @param string $userAgent
     * @param array $headers
     */
    public function testNoIndex($userAgent, $headers)
    {
        $parser = new XRobotsTagParser($userAgent, $headers);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertTrue($parser->getRules()['noindex']);
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
                    'X-Robots-Tag: noindex'
                ]
            ]
        ];
    }
}
