<?php

namespace App;

use DI;
use \GuzzleHttp\ClientInterface;
use \GuzzleHttp\Client;
use \App\GeoIpApi\ApiInterface;
use \App\GeoIpApi\IpApiService;
use Stringy\Stringy;

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

    public static function createPipe()
    {
        return function ($argv) {
            $optind = null;
            $opts = collect(getopt('', [], $optind));
            $posArgs = array_slice($argv, $optind);

            $directoryPath = $posArgs[0] ?? '.';

            $sortedFiles = collect(scandir($directoryPath))
                ->filter(function (string $name) {
                    return true;
                })
                ->sort();

            $medianIndex = round($sortedFiles->count() / 2);
            $medianFilename = Stringy::create($sortedFiles[$medianIndex])
                ->ensureRight('s')
                ->toUpperCase();

            echo $medianFilename . PHP_EOL;
        };
    }
}
