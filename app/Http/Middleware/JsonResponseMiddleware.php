<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;

class JsonResponseMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $contentType = $response->headers->get('Content-Type');
        if (strpos($contentType, 'text/html') !== false) {
            $response->header('Content-Type', 'application/json');

            $exception = $response->exception;

            $errorMessage = '';
            $errorTrace = [];

            if ($exception) {
                $errorMessage = $exception->getMessage();
                $errorTrace = $this->formatExceptionTrace($exception);
            }

            $response->setContent(json_encode([
                'status_code' => $response->status(),
                'status_text' => $response->statusText(),
                'error' => [
                    'message' => $errorMessage,
                    'trace' => $errorTrace,
                ],
            ]));
        }

        return $response;
    }

    protected function formatExceptionTrace($exception)
    {
        return collect($exception->getTrace())->map(function ($trace) {
            return [
                'file' => Arr::get($trace, 'file', 'unknown'),
                'line' => Arr::get($trace, 'line', 'unknown'),
                'function' => Arr::get($trace, 'function', 'unknown'),
            ];
        })->toArray();
    }
}
