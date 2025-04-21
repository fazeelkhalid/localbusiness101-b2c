<?php

namespace App\Http\Services;

use App\Exceptions\ErrorException;
use App\Http\Mapper\AuthMapper;
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
        return $response;
    }

    public static function registerUser(array $userData)
    {
        $authServiceResponse = AuthRequestService::request('/api/signup', 'POST', $userData);

        if (!$authServiceResponse->successful()) {
            $authServiceErrorExceptionResponse = AuthMapper::mapAuthServiceErrorResponse($authServiceResponse);
            throw new ErrorException(
                "Invalid email or password.",
                $authServiceErrorExceptionResponse,
                $authServiceResponse->status()
            );
        }

        return $authServiceResponse;
    }
}
