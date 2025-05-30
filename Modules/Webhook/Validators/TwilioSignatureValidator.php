<?php

namespace Modules\Webhook\Validators;

use Illuminate\Http\Request;
use Twilio\Security\RequestValidator;
use Modules\Webhook\Interfaces\WebhookValidatorInterface;

class TwilioSignatureValidator implements WebhookValidatorInterface
{
    protected string $authToken;

    public function __construct()
    {
        $this->authToken = env("TWILIO_AUTH_TOKEN_2");
    }

    public function isValid(Request $request): bool
    {
        $signature = $request->header('x-twilio-signature');
        $url = $request->fullUrl();
        $params = $request->all();

        $twilioRequestValidator = new RequestValidator($this->authToken);
        return $twilioRequestValidator->validate($signature, $url, $params);
    }
}
