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
 * Class ExportTest
 *
 * @package vipnytt\UserAgentParser\Tests
 */
class ExportTest extends TestCase
{
    /**
     * @dataProvider generateDataForTest
     * @param string $userAgent
     * @param string[] $userAgents
     * @param string[] $products
     * @param string[] $versions
     */
    public function testExport($userAgent, $userAgents, $products, $versions)
    {
        $parser = new UserAgentParser($userAgent);
        $this->assertInstanceOf('vipnytt\UserAgentParser', $parser);

        $this->assertEquals(trim($userAgents[0], '.0'), $parser->getUserAgent());
        $this->assertEquals($products[0], $parser->getProduct());
        $this->assertEquals(trim($versions[0], '.0'), $parser->getVersion());

        $this->assertEquals($userAgents, $parser->getUserAgents());
        $this->assertEquals($products, $parser->getProducts());
        $this->assertEquals($versions, $parser->getVersions());
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
                [
                    'googlebot-news/2.1.0',
                    'googlebot-news/2.1',
                    'googlebot-news/2',
                    'googlebot-news',
                    'googlebotnews',
                    'googlebot',
                ],
                [
                    'googlebot-news',
                    'googlebotnews',
                    'googlebot',
                ],
                [
                    '2.1.0',
                    '2.1',
                    '2',
                ]
            ]
        ];
    }
}
