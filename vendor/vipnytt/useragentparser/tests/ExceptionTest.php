<?php
/**
 * vipnytt/UserAgentParser
 *
 * @link https://github.com/VIPnytt/UserAgentParser
 * @license https://github.com/VIPnytt/UserAgentParser/blob/master/LICENSE The MIT License (MIT)
 */

namespace vipnytt\UserAgentParser\Tests;

use PHPUnit\Framework\TestCase;
use vipnytt\UserAgentParser\Exceptions;

/**
 * Class ExceptionTest
 *
 * @package vipnytt\UserAgentParser\Tests
 */
class ExceptionTest extends TestCase
{
    /**
     * @requires PHPUnit 5.2
     * @throws Exceptions\FormatException
     */
    public function testFormat()
    {
        $this->expectException(\InvalidArgumentException::class);
        throw new Exceptions\FormatException();
    }

    /**
     * @requires PHPUnit 5.2
     * @throws Exceptions\ProductException
     */
    public function testProduct()
    {
        $this->expectException(Exceptions\FormatException::class);
        throw new Exceptions\ProductException();
    }

    /**
     * @requires PHPUnit 5.2
     * @throws Exceptions\VersionException
     */
    public function testVersion()
    {
        $this->expectException(Exceptions\FormatException::class);
        throw new Exceptions\VersionException();
    }

    /**
     * @requires PHPUnit 5.2
     * @throws Exceptions\UserAgentParserException
     */
    public function testUserAgentParser()
    {
        $this->expectException(\Exception::class);
        throw new Exceptions\UserAgentParserException();
    }
}
