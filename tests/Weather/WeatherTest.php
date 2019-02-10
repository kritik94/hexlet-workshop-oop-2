<?php

namespace App\Tests\GetGeo;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use \App\Weather\Weather;

class WeatherTest extends TestCase
{
    protected $container;

    protected function buildHttpClient(array $bodies)
    {
        $responses = collect($bodies)->map(function ($body) {
            return new Response(200, [], $body);
        })->toArray();

        return new Client(['handler' => new MockHandler($responses)]);
    }

    public function testMetaweather()
    {
        $city = 'london';
        $httpClient = $this->buildHttpClient([
            file_get_contents(__DIR__ . '/json/metaweather-search.json'),
            file_get_contents(__DIR__ . '/json/metaweather-weather.json'),
        ]);

        $weatherApp = new Weather([
            ClientInterface::class => $httpClient
        ]);

        $weatherInfo = $weatherApp->getInfoByCity($city, [
            'service' => 'metaweather'
        ]);

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'time' => '2019-02-10 04:45:02',
                'temperature' => 8.2,
                'airPressure' => 1000.815,
            ]),
            $weatherInfo->toJson()
        );
    }

    public function testOpenweathermap()
    {
        $city = 'london';
        $httpClient = $this->buildHttpClient([
            file_get_contents(__DIR__ . '/json/apixu.json'),
        ]);

        $weatherApp = new Weather([
            ClientInterface::class => $httpClient
        ]);

        $now = date('Y-m-d H:i:s', 1485789600);
        $weatherInfo = $weatherApp->getInfoByCity($city, [
            'service' => 'apixu'
        ]);

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'time' => '2019-02-10 12:45:00',
                'temperature' => 6,
                'airPressure' => 994,
            ]),
            $weatherInfo->toJson()
        );
    }
}
