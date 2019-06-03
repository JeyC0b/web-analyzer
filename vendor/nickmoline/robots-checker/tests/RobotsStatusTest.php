<?php

namespace NickMoline\Robots\Test;

use PHPUnit_Framework_TestCase;
use NickMoline\Robots\Status as RobotsStatus;
use Curl\CaseInsensitiveArray;

class RobotsStatusTest extends PHPUnit_Framework_TestCase
{
    private $robots;

    public function setUp()
    {
        $headers = new CaseInsensitiveArray(["Content-Type" => "text/html"]);

        $this->robots = new RobotsStatus("http://www.example.com/");
        $this->robots->setResponseHeaders($headers)->setFetched();
    }

    public function test200()
    {
        $this->robots->setStatusCode(200);

        $this->assertTrue($this->robots->validate());
    }

    public function test204()
    {
        $this->robots->setStatusCode(204);

        $this->assertTrue($this->robots->validate());
    }

    public function test304()
    {
        $this->robots->setStatusCode(304);

        $this->assertTrue($this->robots->validate());
    }

    public function test401()
    {
        $this->robots->setStatusCode(401);

        $this->assertFalse($this->robots->validate());
    }

    public function test403()
    {
        $this->robots->setStatusCode(403);

        $this->assertFalse($this->robots->validate());
    }

    public function test404()
    {
        $this->robots->setStatusCode(404);

        $this->assertFalse($this->robots->validate());

    }

    public function test410()
    {
        $this->robots->setStatusCode(410);

        $this->assertFalse($this->robots->validate());
    }

}
