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
            $log->response_body = $response->json() ?? ['raw' => $response->body()];
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
