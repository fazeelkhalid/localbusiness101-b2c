<?php

namespace App\Http\Utils;

use GuzzleHttp\Client;

class CustomUtils
{
    public static function getCountryFromIp($ip)
    {
        $client = new Client();
        $response = $client->get('http://ip-api.com/json/' . $ip);
        $data = json_decode($response->getBody(), true);

        return $data['country'] ?? 'Unknown';
    }

    public static function getBrowser($userAgent): string
    {
        if (str_contains($userAgent, 'Firefox')) {
            return 'Firefox';
        } elseif (str_contains($userAgent, 'Chrome')) {
            return 'Chrome';
        } elseif (str_contains($userAgent, 'Safari')) {
            return 'Safari';
        } elseif (str_contains($userAgent, 'MSIE') || str_contains($userAgent, 'Trident')) {
            return 'Internet Explorer';
        } else {
            return 'Unknown';
        }
    }

    public static function getDeviceType($userAgent): string
    {
        if (str_contains($userAgent, 'Mobile')) {
            return 'Mobile';
        } elseif (str_contains($userAgent, 'Tablet')) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }

}
