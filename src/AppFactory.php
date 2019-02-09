<?php

namespace App;

use DI;

class AppFactory
{
    public static function buildContainer($additional = [])
    {
        $defenition = [
            \GuzzleHttp\ClientInterface::class => DI\create(\GuzzleHttp\Client::class),
            \App\GeoIpApi\ApiInterface::class => DI\autowire(\App\GeoIpApi\IpApiService::class),
        ];

        return (new DI\ContainerBuilder)
            ->useAutowiring(true)
            ->addDefinitions(array_replace_recursive($defenition, $additional))
            ->build();
    }

    public static function createGetGeoCli()
    {
        $container = static::buildContainer();

        return function ($argv) use ($container) {
            $optind = null;
            $opts = collect(getopt('j', ['json'], $optind));
            $posArgs = array_slice($argv, $optind);

            $toJson = $opts->has('j') || $opts->has('json');
            $ip = $posArgs[0] ?? null;

            $api = $container->get(\App\GeoIpApi\ApiInterface::class);

            $info = $api->requestGeoIpInfoByIp($ip);

            if ($toJson) {
                echo $info->toJson() . PHP_EOL;
            } else {
                echo $info->toString() . PHP_EOL;
            }
        };
    }
}
