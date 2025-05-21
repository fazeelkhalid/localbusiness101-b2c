<?php

namespace App\Http\Middleware;

use App\Http\Utils\CustomUtils;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ApiReqRespLog;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LogApiRequestsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $response = $next($request);
        $endTime = microtime(true);
    
        $messageTraceUUID = Uuid::uuid4()->toString();
        $responseContentType = $response->headers->get('Content-Type');
        $isTextBased = str_contains($responseContentType, 'application/json') 
                    || str_contains($responseContentType, 'text') 
                    || str_contains($responseContentType, 'xml');
    
        try {
            if ($isTextBased) {
                $responseBody = $response->getContent();
            } else {
                $responseBody = '[Non-text response: ' . $responseContentType . ']';
            }
        } catch (\Exception $e) {
            $responseBody = '[Error fetching response body]';
        }
    
        ApiReqRespLog::create([
            'message_trace_uuid' => $messageTraceUUID,
            'request_header' => json_encode($request->header()),
            'payload' => json_encode($request->all()),
            'complete_url' => $request->fullUrl(),
            'http_endpoint' => $request->getPathInfo(),
            'http_method' => $request->method(),
            'http_status_code' => $response->getStatusCode(),
            'response_header' => json_encode($response->headers->all()),
            'response_body' => $responseBody,
            'response_time' => round(($endTime - $startTime) * 1000),
            'source_ip' => $request->ip(),
            'source_port' => $request->getPort(),
        ]);
    
        if ($isTextBased) {
            CustomUtils::setMessageTraceUUID($response, $messageTraceUUID);
        }
    
        CustomUtils::setMessageIfServerErrorOccur($response);
    
        return $response;
    }
}
