<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use \App\GetGeo;

class GetGeoTest extends TestCase
{
    protected $container;

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
        $getGeo = new GetGeo([
            ClientInterface::class => $this->buildHttpClient(json_encode($data))
        ]);

        $result = $getGeo->getInfoByIp($ip, ['format' => 'json']);

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'city' => $data['city'],
                'country' => $data['country'],
                'latitude' => $data['lat'],
                'longitude' => $data['lon'],
            ]),
            $result
        );
    }
}
