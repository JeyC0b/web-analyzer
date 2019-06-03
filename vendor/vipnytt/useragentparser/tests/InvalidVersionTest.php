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
 * Class InvalidVersionTest
 *
 * @package vipnytt\UserAgentParser\Tests
 */
class InvalidVersionTest extends TestCase
{
    /**
     * @requires PHPUnit 5.2
     * @dataProvider generateDataForTest
     * @param string $product
     * @param int|string|null $version
     */
    public function testInvalidVersion($product, $version)
    {
        $this->expectException(UserAgentParser\Exceptions\VersionException::class);
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
                'mybot/2,1',
                null,
            ],
            [
                'mycrawler/2.1-beta',
                '2.1',
            ],
            [
                'mycrawler/2.1alpha1',
                null,
            ],
            [
                'mycrawler',
                '2.1-alpha.3',
            ],
        ];
    }
}
