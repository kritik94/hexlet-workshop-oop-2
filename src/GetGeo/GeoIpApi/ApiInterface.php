<?php

namespace App\GetGeo\GeoIpApi;

use \App\GetGeo\GeoIpInfo;

interface ApiInterface
{
    public function requestGeoIpInfoByIp(string $ip): GeoIpInfo;
}
