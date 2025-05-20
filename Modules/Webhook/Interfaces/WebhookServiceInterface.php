<?php
// Modules/Webhook/Interfaces/WebhookServiceInterface.php

namespace Modules\Webhook\Interfaces;

use App\Models\WebhookLog;
use Illuminate\Http\Request;

interface WebhookServiceInterface
{
    public function handle(Request $request);

    public function execute(WebhookLog $webhookLog);
}
