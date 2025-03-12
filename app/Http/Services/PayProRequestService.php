<?php

namespace App\Http\Services;

use App\Exceptions\ErrorException;
use App\Http\Mapper\PayProMapper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PayProRequestService
{
    public static function request($endpoint, $method = 'GET', $data = [], $headers = [])
    {
        try {
            $baseUrl = env('PAY_PRO_BASE_URL');

            if (!$baseUrl) {
                throw new ErrorException('PayPro base URL is not configured', null, 500);
            }

            $url = $baseUrl . $endpoint;

            $defaultHeaders = [
                'Content-Type' => 'application/json',
            ];

            $requestHeaders = array_merge($defaultHeaders, $headers);

            Log::info('PayPro Request', [
                'url' => $url,
                'method' => $method,
                'data' => $data,
                'headers' => $requestHeaders
            ]);


            try {
                $response = Http::withHeaders($requestHeaders)
                    ->timeout(30)
                    ->send($method, $url, ['json' => $data]);

                Log::info('PayPro Response', [
                    'url' => $url,
                    'status' => $response->status(),
                    'body' => $response->json() ?: $response->body(),
                    'headers' => $response->headers()
                ]);

                if ($response->failed()) {
                    $responseBody = $response->json() ?: $response->body();
                    $errorMessage = 'PayPro API Error: ' . ($responseBody['error'] ?? 'HTTP error ' . $response->status());
                    throw new ErrorException(
                        $errorMessage,
                        PayProMapper::mapErrorResponseT0ErrorExceptionBody($url, $method, null),
                        $response->status()
                    );
                }

                return $response;

            } catch (Exception $e) {
                if ($e instanceof ErrorException) {
                    throw $e;
                }

                Log::error('PayPro HTTP Request Failed', [
                    'url' => $url,
                    'method' => $method,
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                throw new ErrorException(
                    'Failed to connect to PayPro API: ' . $e->getMessage(),
                    PayProMapper::mapErrorResponseT0ErrorExceptionBody($url, $method, $e),
                    500
                );
            }

        } catch (Exception $e) {
            if (!($e instanceof ErrorException)) {
                Log::error('PayPro Service Error', [
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                throw new ErrorException(
                    'PayPro Service Error: ' . $e->getMessage(),
                    null,
                    500
                );
            }

            throw $e;
        }
    }

}
