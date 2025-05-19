<?php

namespace Modules\Webhook\Controllers;

use App\Http\Services\Client\TwilioHTTPHandler;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Webhook\Resolvers\WebhookServiceResolver;

class WebhookController extends Controller
{
    public function dumpWebHook(Request $request)
    {
        $service = WebhookServiceResolver::resolve($request);
        return $service->handle($request);
    }

    public function dumptest(){
        try {
            $twilioService = new TwilioHTTPHandler();

            $response = $twilioService->sendRequest("/Accounts/{$twilioService->accountSid}/Calls.json");

            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
