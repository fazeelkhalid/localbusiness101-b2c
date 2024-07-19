<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AuthRequestService
{
    public static function request($endpoint, $method = 'GET', $data = [], $jwtToken = null)
    {
        $url = env('AUTH_SERVICE_URL') . $endpoint;

        $headers = [
            'api-key' => env('AUTH_SERVICE_KEY'),
        ];

        if ($jwtToken) {
            $headers['Authorization'] = 'Bearer ' . $jwtToken;
        }

        $response = Http::withHeaders($headers)->send($method, $url, ['json' => $data]);

        return $response->json();
    }
}
