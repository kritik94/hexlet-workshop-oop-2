<?php

namespace App;

class GeoIpInfo
{
    private $country;
    private $city;
    private $latitude;
    private $longitude;

    public function __construct($params)
    {
        $this->country = $params['country'] ?? null;
        $this->city = $params['city'] ?? null;
        $this->latitude = $params['lat'] ?? null;
        $this->longitude = $params['lon'] ?? null;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function toArray()
    {
        return [
            'country' => $this->getCountry(),
            'city' => $this->getCity(),
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude()
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
}
