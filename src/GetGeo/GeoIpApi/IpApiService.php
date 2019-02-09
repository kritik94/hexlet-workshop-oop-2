<?php

namespace App\GetGeo\GeoIpApi;

use \App\GetGeo\GeoIpInfo;
use \GuzzleHttp\ClientInterface;

class IpApiService implements ApiInterface
{
    const BASE_URL = 'http://ip-api.com/json/';

    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function requestGeoIpInfoByIp(string $ip = null): GeoIpInfo
    {
        $url = static::BASE_URL . $ip;

        $response = $this->httpClient->request('GET', $url);
        $metadata = json_decode($response->getBody(), true);

        return new GeoIpInfo($metadata);
    }
}
