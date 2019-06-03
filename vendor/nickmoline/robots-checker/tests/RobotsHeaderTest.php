<?php

namespace NickMoline\Robots\Test;

use PHPUnit_Framework_TestCase;
use NickMoline\Robots\Header as RobotsHeader;
use Curl\CaseInsensitiveArray;

class RobotsHeaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test that if no X-Robots-Tag header is present, the url is shown as allowed
     */
    public function testNoHeader()
    {
        $noHeaders = new CaseInsensitiveArray(["Content-Type" => "text/html"]);

        $noHeader = new RobotsHeader("http://www.example.com/ambiguous");
        $noHeader->setResponseHeaders($noHeaders)->setFetched();

        $this->assertTrue($noHeader->validate());
    }

    /**
     * Test that if no X-Robots-Tag header set to index, follow, the url is shown as allowed
     */
    public function testAllowedHeader()
    {
        $allowedHeaders = new CaseInsensitiveArray(["Content-Type" => "text/html"]);
        $allowedHeaders['X-Robots-Tag'] = "index, follow";

        $allowedHeader = new RobotsHeader("http://www.example.com/allowed");
        $allowedHeader->setResponseHeaders($allowedHeaders)->setFetched();

        $this->assertTrue($allowedHeader->validate());
    }

    /**
     * Test that if no X-Robots-Tag header set to noindex, nofollow, the url is shown as denied
     */
    public function testBlockedHeader()
    {
        $blockedHeaders = new CaseInsensitiveArray(["Content-Type" => "text/html"]);
        $blockedHeaders['X-Robots-Tag'] = "noindex, nofollow";

        $blockedHeader = new RobotsHeader("http://www.example.com/blocked");
        $blockedHeader->setResponseHeaders($blockedHeaders)->setFetched();

        $this->assertFalse($blockedHeader->validate());
    }

    /**
     * Test that if no X-Robots-Tag header has specific instructions for one
     * user agent, that those rules are respected
     */
    public function testSpecificHeader()
    {
        $specificHeaders = new CaseInsensitiveArray(["Content-Type" => "text/html"]);
        $specificHeaders['X-Robots-Tag'] = "index, follow\nGooglebot: noindex, nofollow";

        $specificHeader = new RobotsHeader("http://www.example.com/blockgoogle");
        $specificHeader->setResponseHeaders($specificHeaders)->setFetched();


        $this->assertFalse($specificHeader->validate());
        $this->assertTrue($specificHeader->setUserAgent("bingbot")->validate());
    }

    /**
     * Test that if no X-Robots-Tag header has specific instructions for one
     * user agent, that those rules are respected
     */
    public function testSpecificHeaderAllowedOverride()
    {
        $specificHeaders = new CaseInsensitiveArray(["Content-Type" => "text/html"]);
        $specificHeaders['X-Robots-Tag'] = "noindex, nofollow\nGooglebot: index, follow";

        $specificHeader = new RobotsHeader("http://www.example.com/blockgoogle");
        $specificHeader->setResponseHeaders($specificHeaders)->setFetched();


        $this->assertTrue($specificHeader->validate());
        $this->assertFalse($specificHeader->setUserAgent("bingbot")->validate());
    }

}
