<?php

namespace App;

class AppFactory
{
    public static function createGetGeoCli()
    {
        return function ($argv) {
            $optind = null;
            $opts = collect(getopt('j', ['json'], $optind));
            $posArgs = array_slice($argv, $optind);

            $toJson = $opts->has('j') || $opts->has('json');

            $httpClient = new \GuzzleHttp\Client();
            $getGeo = new GetGeo($httpClient);

            $info = $getGeo->getInfoByIp($posArgs[0] ?? null);

            if ($toJson) {
                echo $info->toJson() . PHP_EOL;
            } else {
                echo $info->toString() . PHP_EOL;
            }
        };
    }
}
