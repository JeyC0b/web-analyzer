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
 * Class InvalidProductTest
 *
 * @package vipnytt\UserAgentParser\Tests
 */
class InvalidProductTest extends TestCase
{
    /**
     * @requires PHPUnit 5.2
     * @dataProvider generateDataForTest
     * @param string $product
     * @param int|string|null $version
     */
    public function testInvalidProduct($product, $version)
    {
        $this->expectException(UserAgentParser\Exceptions\ProductException::class);
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
                '',
                '2.0',
            ],
        ];
    }
}
