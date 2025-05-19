<?php

namespace Modules\Webhook\Services;

use App\Enums\WebhookSenderTypeEnum;
use App\Enums\WebhookStatusEnum;
use App\Exceptions\ErrorException;
use App\Http\Services\Client\TwilioHTTPHandler;
use App\Models\CallLog;
use App\Models\WebhookLog;
use Grpc\Call;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    public function __construct(Request $request = null, TwilioSignatureValidator $twilioSignatureValidator = null)
    {
        if ($request && $twilioSignatureValidator) {
            $this->twilioSignatureValidator = $twilioSignatureValidator;

            if (!$this->twilioSignatureValidator->isValid($request)) {
                throw new ErrorException("Invalid Twilio Signature", null, 401);
            }

            $this->request = $request;
        }
    }

    public function handle(Request $request)
    {
        $request = $request ?? $this->request;

        $webhookLogDomain = WebhookLogMapper::MapWebhookRequestToWebhookLogDomain($request, WebhookSenderTypeEnum::TWILIO);
        WebhookLog::logWebhook($webhookLogDomain);
        return response()->json(['status' => 'success']);
    }

    /**
     * @throws ErrorException
     * @throws ConnectionException
     */
    public function processPendingCallCompletionWebhook(WebhookLog $webhookLog): void
    {
        if (!$webhookLog) {
            throw new ErrorException("Webhook log is null", 400);
        }

        $payload = $webhookLog->request_payload;

        if (empty($payload)) {
            throw new ErrorException("Webhook payload is null or empty", 400);
        }

        if ($webhookLog->status !== WebhookStatusEnum::IN_PROGRESS ) {
            throw new ErrorException("Webhook payload 'status' is null or status is not \'IN_PROGRESS\'", 422);
        }

        if (!isset($payload['CallSid'])) {
            throw new ErrorException("Webhook payload 'CallSid' is null or missing", 422);
        }

        $twilioService = new TwilioHTTPHandler();
        $twilioCallData = $twilioService->getCallDataBySid($payload['CallSid']);
        Log::info("Twilio SID Data, {$twilioCallData} ");

        CallLog::updateCallLogFromTwilioData($twilioCallData);

        $twilioCallRecordingData = $twilioService->getCallRecording($payload['CallSid']);

        Log::info("Twilio call recording Data, {$twilioCallRecordingData} ");

        if( isset($twilioCallRecordingData['recordings']) && !empty($twilioCallRecordingData['recordings'])){
            CallLog::updateCallLogForRecording($twilioCallRecordingData, $payload['CallSid']);
        }

        Log::info("CallLog updated successfully for CallSid: {$payload['CallSid']}");

    }
}
