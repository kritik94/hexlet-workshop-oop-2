<?php

namespace App\GeoIpApi;

use \App\GeoIpInfo;

interface ApiInterface
{
    public function requestGeoIpInfoByIp(string $ip): GeoIpInfo;
}
