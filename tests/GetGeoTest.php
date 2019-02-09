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

        $this->assertNotEmpty($info->getCity());
        $this->assertNotEmpty($info->getCountry());
        $this->assertNotEmpty($info->getLatitude());
        $this->assertNotEmpty($info->getLongitude());
    }

    public function testWithIp()
    {
        $ip = '8.8.8.8';
        $expectInfo = new \App\GeoIpInfo([
            'city' => 'Mountain View',
            'country' => 'United States',
            'lat' => 37.4229,
            'lon' => -122.085,
        ]);

        $info = $this->getGeo->getInfoByIp($ip);

        $this->assertEquals($expectInfo, $info);
    }
}
