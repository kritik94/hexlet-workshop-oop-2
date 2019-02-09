<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class GetGeoTest extends TestCase
{
    protected $getGeo;

    protected function setUp(): void
    {
        $httpClient = new \GuzzleHttp\Client();
        $this->getGeo = new \App\GetGeo($httpClient);
    }

    public function testWithoutIp()
    {
        $info = $this->getGeo->getInfoByIp();

        $this->assertArrayHasKey('city', $info);
        $this->assertArrayHasKey('country', $info);
        $this->assertArrayHasKey('lat', $info);
        $this->assertArrayHasKey('lon', $info);
    }

    public function testWithIp()
    {
        $ip = '8.8.8.8';
        $expectCity = 'Mountain View';
        $expectCountry = 'United States';
        $expectLat = 37.4229;
        $expectLon = -122.085;

        $info = $this->getGeo->getInfoByIp($ip);

        $this->assertEquals($expectCity, $info['city']);
        $this->assertEquals($expectCountry, $info['country']);
        $this->assertEquals($expectLat, $info['lat']);
        $this->assertEquals($expectLon, $info['lon']);
    }
}
