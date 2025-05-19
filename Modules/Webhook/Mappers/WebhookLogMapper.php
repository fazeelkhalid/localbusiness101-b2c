<?php

namespace Modules\Webhook\Mappers;

use Illuminate\Http\Request;

class WebhookLogMapper
{
    public static function MapWebhookRequestToWebhookLogDomain(Request $request,  $serviceName = 'Unknown'): array
    {
        return [
            'service_name'     => $serviceName,
            'request_headers'  => $request->headers->all(),
            'request_payload'  => $request->all(),
            'received_at'      => now(),
            'ip_address'       => $request->ip(),
            'url'              => $request->fullUrl(),
        ];
    }
}
