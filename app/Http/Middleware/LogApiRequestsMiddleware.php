<?php

namespace App\Http\Middleware;

use App\Http\Utils\CustomUtils;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ApiReqRespLog;
use Ramsey\Uuid\Uuid;

class LogApiRequestsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $startTIme = microtime(true);
        $response = $next($request);
        $endTime = microtime(true);

        $data = [
            'message_trace_uuid' => Uuid::uuid4()->toString(),
            'request_header' => json_encode($request->header()),
            'payload' => json_encode($request->all()),
            'complete_url' => $request->fullUrl(),
            'http_endpoint' => $request->getPathInfo(),
            'http_method' => $request->method(),
            'http_status_code' => $response->getStatusCode(),
            'response_header' => json_encode($response->headers->all()),
            'response_body' => $response->getContent(),
            ' response_time' => round(($endTime - $startTIme) * 1000),
            'source_ip' => $request->ip(),
            'source_port' => $request->getPort(),
        ];

        ApiReqRespLog::create($data);
        CustomUtils::setMessageTraceUUID($response, $data['message_trace_uuid']);
        CustomUtils::setMessageIfServerErrorOccur($response);
        return $response;
    }
}
