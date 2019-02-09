<?php

namespace App\GetGeo;

use DI;
use \GuzzleHttp\ClientInterface;
use \GuzzleHttp\Client;
use \App\GetGeo\GeoIpApi\ApiInterface;
use \App\GetGeo\GeoIpApi\IpApiService;

class GetGeo
{
    private $container;

    public function __construct($additional = [])
    {
        $defenition = [
            ClientInterface::class => DI\create(Client::class),
            ApiInterface::class => DI\autowire(IpApiService::class),
        ];

        $this->container = (new DI\ContainerBuilder())
            ->useAutowiring(true)
            ->addDefinitions(array_replace_recursive($defenition, $additional))
            ->build();
    }

    public function getInfoByIp($ip, $options = [])
    {
        $format = $options['format'] ?? null;
        $api = $this->container->get(ApiInterface::class);

        $info = $api->requestGeoIpInfoByIp($ip);

        if ($format) {
            return $info->toFormat($format);
        }

        return $info;
    }
}
