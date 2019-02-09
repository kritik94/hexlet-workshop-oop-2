<?php

namespace App;

class AppFactory
{
    public static function createGetGeoCli()
    {
        return function($argv) {
            $optind = null;
            $opts = getopt('', [], $optind);
            $posArgs = array_slice($argv, $optind);

            $httpClient = new \GuzzleHttp\Client();
            $getGeo = new GetGeo($httpClient);

            $info = $getGeo->getInfoByIp($posArgs[0] ?? null);
            $prettyInfo = collect($info)
                ->only(['city', 'country', 'lat', 'lon'])
                ->map(function ($value, $info) {
                    return "$info: $value";
                })
                ->implode(PHP_EOL);

            echo $prettyInfo . PHP_EOL;
        };
    }
}
