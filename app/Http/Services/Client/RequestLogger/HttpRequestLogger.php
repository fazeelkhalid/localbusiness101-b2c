<?php

namespace App\Http\Services\Client\RequestLogger;

use App\Exceptions\ErrorException;
use Illuminate\Support\Facades\Http;
use App\Models\ProcessorApiReqRespLog;
use Carbon\Carbon;
use Throwable;

class HttpRequestLogger
{
    public static function send(string $method, string $url, array $options = [])
    {
        $log = new ProcessorApiReqRespLog();
        $log->method = strtoupper($method);
        $log->url = $url;
        $log->requested_at = Carbon::now();

        $log->request_headers = $options['headers'] ?? [];
        $log->request_body = $options['body'] ?? ($options['json'] ?? []);

        try {
            $response = Http::withOptions($options)->send($method, $url, $options);

            $log->http_status_code = $response->status();

            $responseBody = $response->body();
            $decodedJson = json_decode($responseBody, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $log->response_body = $decodedJson;
            } else {
                $log->response_body = [
                    'raw_base64' => base64_encode($responseBody),
                    'note' => 'Binary content (e.g., MP3) base64 encoded',
                ];
            }

            $log->responded_at = Carbon::now();
            $log->save();

            return $response;
        } catch (Throwable $e) {
            $log->http_status_code = $e->getCode();
            $log->exception = $e->getMessage();
            $log->responded_at = Carbon::now();
            $log->save();

            throw new ErrorException("Error occurred while making {$method} call to {$url}: " . $e->getMessage(), 200);
        }
    }
}
