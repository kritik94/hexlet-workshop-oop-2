<?php

namespace App\Weather;

use \Datetime;

class WeatherInfo
{
    private $time;
    private $temperature;
    private $airPressure;

    public function __construct($params)
    {
        $this->time = new Datetime($params['time'] ?? null);
        $this->temperature = $params['temperature'] ?? null;
        $this->airPressure = $params['airPressure'] ?? null;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getTemperature()
    {
        return $this->temperature;
    }

    public function getAirPressure()
    {
        return $this->airPressure;
    }

    public function toArray()
    {
        return [
            'time' => $this->getTime()->format('Y-m-d H:i:s'),
            'temperature' => $this->getTemperature(),
            'airPressure' => $this->getAirPressure(),
        ];
    }

    public function toString()
    {
        return collect($this->toArray())
            ->map(function ($value, $info) {
                return "$info: $value";
            })
            ->implode(PHP_EOL);
    }

    public function toJson()
    {
        return collect($this->toArray())->toJson(JSON_PRETTY_PRINT);
    }

    public function toFormat($format)
    {
        switch ($format) {
            case 'string':
                return $this->toString();
            case 'json':
                return $this->toJson();
            default:
                throw new \Exception("format '$format' not implement");
        }
    }
}
