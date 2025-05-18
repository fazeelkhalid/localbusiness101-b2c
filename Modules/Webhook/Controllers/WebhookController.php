<?php

namespace Modules\Webhook\Controllers;

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
}
