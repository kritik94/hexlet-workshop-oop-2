<?php

namespace App\Tests\GeoIpApi;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

use \App\GeoIpApi\ApiInterface;
use \App\GeoIpInfo;

class IpApiService extends TestCase
{
    protected $container;

    protected function setUp(): void
    {
        $this->container = \App\AppFactory::buildContainer();
    }

    protected function buildHttpClient(string $body)
    {
        $mockHandler = new MockHandler([
            new Response(200, [], $body)
        ]);
        return new Client(['handler' => $mockHandler]);
    }

    public function test()
    {
        $ip = '8.8.8.8';
        $data = [
            'city' => 'Mountain View',
            'country' => 'United States',
            'lat' => 37.4229,
            'lon' => -122.085,
        ];
        $expectInfo = new GeoIpInfo($data);
        $this->container->set(
            ClientInterface::class,
            $this->buildHttpClient(json_encode($data))
        );
        $api = $this->container->get(ApiInterface::class);

        $info = $api->requestGeoIpInfoByIp($ip);

        $this->assertEquals($expectInfo, $info);
    }
}
