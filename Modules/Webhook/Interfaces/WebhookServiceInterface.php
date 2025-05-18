<?php
// Modules/Webhook/Interfaces/WebhookServiceInterface.php

namespace Modules\Webhook\Interfaces;

use Illuminate\Http\Request;

interface WebhookServiceInterface
{
    public function handle(Request $request);
}
