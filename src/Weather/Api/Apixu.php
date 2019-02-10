<?php

namespace App\Weather\Api;

use \GuzzleHttp\ClientInterface;
use \GuzzleHttp\Exception\ClientException;
use \App\Weather\WeatherInfo;

class Apixu implements ApiInterface
{
    const API_URL = 'http://api.apixu.com/v1/current.json';

    private $httpClient;
    private $apiKey;

    public function __construct($apiKey, ClientInterface $httpClient)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = $httpClient;
    }

    public function requestWeatherInfoByCity(string $city): WeatherInfo
    {
        try {
            $weatherResponse = $this->httpClient->request(
                'get',
                static::API_URL,
                [
                    'query' => [
                        'key' => $this->apiKey,
                        'q' => $city
                    ]
                ]
            );
        } catch (ClientException $exception) {
            if (!$exception->hasResponse()) {
                throw $exception;
            }

            $errorJson = json_decode($exception->getResponse()->getBody(), true);

            switch ($errorJson['error']['code'] ?? null) {
                case null:
                    throw $exception;
                case 1006:
                    throw new CityNotFoundException("Location '$city' not found");
                default:
                    throw new Exception($errorJson['error']['message']);
            }
        }

        $weatherJson = json_decode($weatherResponse->getBody(), true);

        $weatherNow = $weatherJson['current'];

        return new WeatherInfo([
            'temperature' => $weatherNow['temp_c'] ?? null,
            'airPressure' => $weatherNow['pressure_mb'] ?? null,
            'time' => $weatherNow['last_updated'] ?? null,
        ]);
    }
}
