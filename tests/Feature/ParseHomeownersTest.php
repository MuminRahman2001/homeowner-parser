<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\NameParserService;

class ParseHomeownersTest extends TestCase
{
    public function testSinglePerson()
    {
        $result = NameParserService::parse("Mr John Smith");
        $this->assertCount(1, $result);
        $this->assertEquals('Mr', $result[0]['title']);
        $this->assertEquals('John', $result[0]['first_name']);
        $this->assertNull($result[0]['initial']);
        $this->assertEquals('Smith', $result[0]['last_name']);
    }

    public function testMultiplePeople()
    {
        $result = NameParserService::parse("Mr and Mrs Smith");
        $this->assertCount(2, $result);
        $this->assertEquals('Mr', $result[0]['title']);
        $this->assertEquals('Mrs', $result[1]['title']);
        $this->assertEquals('Smith', $result[0]['last_name']);
    }

    public function testInitial()
    {
        $result = NameParserService::parse("Mr J. Smith");
        $this->assertCount(1, $result);
        $this->assertEquals('J', $result[0]['initial']);
    }

    public function testInvalidFormat()
    {
        $this->expectException(\InvalidArgumentException::class);
        NameParserService::parse("Unknown Format");
    }
}
