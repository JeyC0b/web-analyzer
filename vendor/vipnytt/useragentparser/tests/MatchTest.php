<?php
/**
 * vipnytt/UserAgentParser
 *
 * @link https://github.com/VIPnytt/UserAgentParser
 * @license https://github.com/VIPnytt/UserAgentParser/blob/master/LICENSE The MIT License (MIT)
 */

namespace vipnytt\UserAgentParser\Tests;

use PHPUnit\Framework\TestCase;
use vipnytt\UserAgentParser;

/**
 * Class MatchTest
 *
 * @package vipnytt\UserAgentParser\Tests
 */
class MatchTest extends TestCase
{
    /**
     * @dataProvider generateDataForTest
     * @param string $userAgent
     * @param string $match
     * @param array $array
     */
    public function testMatch($userAgent, $match, $array)
    {
        $parser = new UserAgentParser($userAgent);
        $this->assertInstanceOf('vipnytt\UserAgentParser', $parser);
        $this->assertEquals($match, $parser->getMostSpecific($array));
    }

    /**
     * Generate test data
     * @return array
     */
    public function generateDataForTest()
    {
        return [
            [
                'googlebot-news/2.1',
                'googlebot-news',
                [
                    'googlebot-news',
                    'googlebot-images',
                    'googlebot-news/2.0',
                    'googlebot',
                ]
            ],
            [
                'mybot/2.1',
                'mybot/2',
                [
                    'mybot/1.0',
                    'mybot/2.0',
                    'mybot/2.2',
                    'mybot/2',
                ],
            ],
            [
                'mybot/2.1',
                'mybot/2.1.0',
                [
                    'mybot',
                    'mybot/1.0',
                    'mybot/2.0',
                    'mybot/2.1.0',
                    'mybot/2.2',
                ]
            ],
            [
                'h3lloBot/2.1',
                'h3lloBot/2',
                [
                    'h3lloBot',
                    'h3lloBot/2',
                ]
            ],
            [
                'googlebot',
                false,
                [
                    'googlebot-news',
                    'googlebot-images',
                    'googlebot/2.0',
                ]
            ],
            [
                'UpperCase-LowerCase',
                'UPPERCASE-lowercase',
                [
                    'UPPERCASE-lowercase',
                    'UPPERCASE-lowercase/1.0',
                ]
            ],
        ];
    }
}
