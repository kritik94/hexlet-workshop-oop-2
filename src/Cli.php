<?php

namespace App;

use DI;
use \GuzzleHttp\ClientInterface;
use \GuzzleHttp\Client;
use \App\GeoIpApi\ApiInterface;
use \App\GeoIpApi\IpApiService;

class Cli
{
    public static function createGetGeoCli()
    {
        return function ($argv) {
            $optind = null;
            $opts = collect(getopt('j', ['json'], $optind));
            $posArgs = array_slice($argv, $optind);

            $toJson = $opts->has('j') || $opts->has('json');
            $ip = $posArgs[0] ?? null;

            $getGeo = new GetGeo();

            echo $getGeo->getInfoByIp($ip, [
                'format' => $toJson ? 'json' : 'string'
            ]) . PHP_EOL;
        };
    }
}
