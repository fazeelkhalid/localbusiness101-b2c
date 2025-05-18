<?php

namespace Modules\Webhook\Services;

use App\Exceptions\ErrorException;
use App\Models\WebhookLog;
use Illuminate\Http\Request;
use Modules\Webhook\Interfaces\WebhookServiceInterface;
use Modules\Webhook\Mappers\WebhookLogMapper;
use Modules\Webhook\Validators\TwilioSignatureValidator;

class TwilioWebhookService implements WebhookServiceInterface
{
    private TwilioSignatureValidator $twilioSignatureValidator;
    private Request $request;

    /**
     * @throws ErrorException
     */
    public function __construct(Request $request, TwilioSignatureValidator $twilioSignatureValidator)
    {
        $this->twilioSignatureValidator = $twilioSignatureValidator;
        if (!$this->twilioSignatureValidator->isValid($request)) {
            throw new ErrorException("Invalid Twilio Signature", null, 401);
        }

        $this->request = $request;
    }

    public function handle(Request $request)
    {
        $request = $request ?? $this->request;

        $webhookLogDomain = WebhookLogMapper::MapWebhookRequestToWebhookLogDomain($request, "twilio");
        WebhookLog::logWebhook($webhookLogDomain);
        return response()->json(['status' => 'success']);
    }
}
