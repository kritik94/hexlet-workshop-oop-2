<?php

namespace App\Weather\Api;

use \App\Weather\WeatherInfo;

interface ApiInterface
{
    public function requestWeatherInfoByCity(string $city): WeatherInfo;
}
