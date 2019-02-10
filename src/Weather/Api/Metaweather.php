<?php

namespace App\Weather\Api;

use \GuzzleHttp\ClientInterface;
use \App\Weather\WeatherInfo;

class Metaweather implements ApiInterface
{
    const API_SEARCH_URL = 'https://www.metaweather.com/api/location/search/';
    const API_LOCATION_URL = 'https://www.metaweather.com/api/location/';

    private $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function requestWeatherInfoByCity(string $city): WeatherInfo
    {
        $searchResponse = $this->httpClient->request('GET', static::API_SEARCH_URL, [
            'query' => ['query' => $city]
        ]);

        $searchJson = json_decode($searchResponse->getBody(), true);
        $locationId = collect($searchJson)->first()['woeid'] ?? null;

        if (empty($locationId)) {
            throw new CityNotFoundException("Location '$city' not found");
        }

        $weatherResponse = $this->httpClient->request(
            'get',
            static::API_LOCATION_URL . $locationId
        );

        $weatherJson = json_decode($weatherResponse->getBody(), true);
        $weatherNow = collect($weatherJson['consolidated_weather'])
            ->first();

        return new WeatherInfo([
            'temperature' => $weatherNow['the_temp'] ?? null,
            'airPressure' => $weatherNow['air_pressure'] ?? null,
            'time' => $weatherNow['created'] ?? null,
        ]);
    }
}
