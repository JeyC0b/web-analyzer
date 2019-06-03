<?php
namespace vipnytt\XRobotsTagParser\Tests;

use vipnytt\XRobotsTagParser;

/**
 * Class GetRulesTest
 *
 * @package vipnytt\XRobotsTagParser\Tests
 */
class GetRulesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider generateDataForTest
     * @param string $userAgent
     * @param array $headers
     */
    public function testGetRules($userAgent, $headers)
    {
        $parser = new XRobotsTagParser($userAgent, $headers);
        $this->assertInstanceOf('vipnytt\XRobotsTagParser', $parser);

        $this->assertTrue($parser->getRules()['noindex']);
        $this->assertTrue($parser->getRules()['noarchive']);
        $this->assertTrue($parser->getRules()['noodp']);
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
                    'X-Robots-Tag: noindex, noodp',
                ]
            ]
        ];
    }
}
