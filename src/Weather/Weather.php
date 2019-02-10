<?php

namespace App\Weather;

use DI;
use \GuzzleHttp\ClientInterface;
use \GuzzleHttp\Client;
use \App\Weather\Api\ApiInterface;
use \App\Weather\Api\IpApiService;
use Stringy\Stringy;

class Weather
{
    const DEFAULT_SERVICE = 'metaweather';

    private $container;

    public function __construct($additional = [])
    {
        $defenition = [
            ClientInterface::class => DI\create(Client::class),
            ApiInterface::class => DI\autowire(IpApiService::class),
            'ApiService\*' => DI\autowire('\App\Weather\Api\*'),
            'ApiService\Apixu' => DI\autowire(\App\Weather\Api\Apixu::class)
                ->constructorParameter('apiKey', DI\env('APIXU_API_KEY', '')),
        ];

        $this->container = (new DI\ContainerBuilder())
                         ->useAutowiring(true)
                         ->addDefinitions(array_replace_recursive($defenition, $additional))
                         ->build();
    }

    public function getInfoByCity($city, $options = [])
    {
        $service = $options['service'] ?? static::DEFAULT_SERVICE;
        $serviceName = (string) Stringy::create($service)
            ->toTitleCase()
            ->ensureLeft('ApiService\\');

        $api = $this->container->get($serviceName);

        $weatherInfo = $api->requestWeatherInfoByCity($city);

        return $weatherInfo;
    }
}
