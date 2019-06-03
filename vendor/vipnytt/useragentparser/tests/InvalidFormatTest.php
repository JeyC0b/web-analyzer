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
 * Class InvalidFormatTest
 *
 * @package vipnytt\UserAgentParser\Tests
 */
class InvalidFormatTest extends TestCase
{
    /**
     * @requires PHPUnit 5.2
     * @dataProvider generateDataForTest
     * @param string $product
     * @param int|string|null $version
     */
    public function testInvalidFormat($product, $version)
    {
        $this->expectException(UserAgentParser\Exceptions\FormatException::class);
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
                'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36',
                null,
            ],
            [
                'mycrawler/2.1',
                '2.1',
            ],
            [
                'mycrawler/2.1alpha1',
                null,
            ],
            [
                'mycrawler',
                '2.1-alpha',
            ],
            [
                '',
                '2.0',
            ],
        ];
    }
}
