<?php
// Modules/Webhook/Interfaces/WebhookValidatorInterface.php

namespace Modules\Webhook\Interfaces;

use Illuminate\Http\Request;

interface WebhookValidatorInterface
{
    /**
     * Validate the authenticity of the webhook request.
     */
    public function isValid(Request $request): bool;
}
