<?php

namespace Modules\Webhook\Resolvers;

use App\Exceptions\ErrorException;
use Illuminate\Http\Request;
use Modules\Webhook\Validators\TwilioSignatureValidator;
use Modules\Webhook\Services\TwilioWebhookService;
use Modules\Webhook\Interfaces\WebhookServiceInterface;

class WebhookServiceResolver
{
    /**
     * @throws ErrorException
     */
    public static function resolve(Request $request)
    {
        if ($request->hasHeader('x-twilio-signature')) {
            return new TwilioWebhookService($request, new TwilioSignatureValidator());
        } else {
            Throw new ErrorException('Unrecognized or unsupported webhook service', null, 400);
        }

    }

    public static function resolveFromServiceName(string $serviceName): WebhookServiceInterface
    {
        return match (strtolower($serviceName)) {
            'twilio' => new TwilioWebhookService(),
            default => throw new ErrorException("Unsupported service name: {$serviceName}", null, 400),
        };
    }

}
