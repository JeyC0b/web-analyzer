<?php
/**
 * vipnytt/UserAgentParser
 *
 * @link https://github.com/VIPnytt/UserAgentParser
 * @license https://github.com/VIPnytt/UserAgentParser/blob/master/LICENSE The MIT License (MIT)
 */

namespace vipnytt\UserAgentParser\Tests;

use PHPUnit\Framework;
use vipnytt\UserAgentParser;

/**
 * Class TruncateInvalidTest
 *
 * @package vipnytt\UserAgentParser\Tests
 */
class TruncateInvalidTest extends Framework\TestCase
{
    /**
     * @requires PHPUnit 6.0
     * @dataProvider generateDataForTest
     * @param string $product
     * @param int|string|null $version
     */
    public function testTruncateInvalid($product, $version)
    {
        $this->expectException(Framework\Error\Notice::class);
        new UserAgentParser($product, $version);
    }

    /**
     * Generate test data
     * @return array
     */
    public function generateDataForTest()
    {
        return [
            [
                'MyÇustomWebCrawler',
                '2.0',
            ],
            [
                'MyCøstomWebCræwler',
                '2.0',
            ],
            [
                'mybot 2.0',
                null,
            ],
            [
                'my crawler',
                null,
            ],
            [
                'æøå',
                '2.0',
            ],
        ];
    }
}
