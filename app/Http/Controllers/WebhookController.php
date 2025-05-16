<?php

namespace App\Http\Controllers;

use App\Enums\ErrorResponseEnum;
use Illuminate\Http\Request;
use App\Models\WebhookLog;
use Illuminate\Support\Carbon;

class WebhookController extends Controller
{
    public function dumpWebhook(Request $request)
    {
        try {
            $webhookLog = WebhookLog::create([
                'request_headers' => $request->headers->all(),
                'payload' => $request->all(),
                'received_at' => Carbon::now(),
                'ip_address' => $request->ip(),
                'status' => 'Pending',
                'url' => $request->fullUrl(),
            ]);

            return response()->json(['message' => 'Webhook received successfully.',], 201);
        } catch (\Exception $e) {
            return ErrorResponseEnum::$ERROR_DUMPING_WEBHOOK_500;
        }
    }
}
