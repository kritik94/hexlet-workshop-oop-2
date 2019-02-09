<?php

namespace App;

class GetGeo
{
    const BASE_URL = 'http://ip-api.com/json/';

    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    public function __construct(\GuzzleHttp\Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getInfoByIp(string $ip = null)
    {
        $url = static::BASE_URL . $ip;

        $response = $this->httpClient->get($url);
        $metadata = json_decode($response->getBody(), true);

        return $metadata;
    }
}
