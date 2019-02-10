<?php

namespace App;

use DI;
use \GuzzleHttp\ClientInterface;
use \GuzzleHttp\Client;
use Stringy\Stringy;
use \App\GetGeo\GetGeo;
use \App\Weather\Weather;
use \App\Weather\Api\CityNotFoundException;
use \App\Dom\SingleTag;
use \App\Dom\PairTag;;

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

    public static function createWeatherCli()
    {
        return function ($argv) {
            $optind = null;
            $opts = collect(getopt('s:', ['service:'], $optind));
            $posArgs = array_slice($argv, $optind);

            $city = $posArgs[0] ?? null;
            $service = $opts['s'] ?? $opts['service'] ?? null;

            $weatherApp = new Weather();

            try {
                $weatherInfo = $weatherApp->getInfoByCity($city, [
                    'service' => $service
                ]);
            } catch (CityNotFoundException $e) {
                exit($e->getMessage() . PHP_EOL);
            }

            echo $weatherInfo->toString() . PHP_EOL;
        };
    }


    public static function createDomCli()
    {
        return function ($argv) {
            $t1 = new PairTag('div', ['class' => 'row'], 'content');
            $t2 = new SingleTag('ht');

            dump($t1->toString());
            dump($t2->toString());
            dump($t1->isShort());
            dump($t2->isShort());
        };
    }
}
