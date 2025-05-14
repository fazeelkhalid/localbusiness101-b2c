<?php

namespace App\Http\Services;

use App\Enums\ConfigurationEnum;
use App\Enums\ErrorResponseEnum;
use App\Exceptions\ErrorException;
use App\Http\Mapper\CallLogMapper;
use App\Http\Requests\CallLog\CreateCallLogRequest;
use App\Http\Requests\CallLog\UpdateCallLogRequest;
use App\Http\Responses\CallLog\CallLogResponses;
use App\Models\CallLog;

class CallLogService
{
    protected AcquirerService $acquirerService;
    protected PhoneNumberService $phoneNumberService;
    protected ConfigurationService $configurationService;

    public function __construct(AcquirerService $acquirerService, PhoneNumberService $phoneNumberService, ConfigurationService $configurationService)
    {
        $this->acquirerService = $acquirerService;
        $this->phoneNumberService = $phoneNumberService;
        $this->configurationService = $configurationService;
    }


    /**
     * @throws ErrorException
     */
    public function createCallLog(CreateCallLogRequest $createCallLogRequest)
    {
        $createCallLogRequest = $createCallLogRequest->validated();
        $fromNumber = $createCallLogRequest["from"];
        $toNumber = $createCallLogRequest["to"];
        $twilioSid = $createCallLogRequest["twilio_sid"];


        $matchedPhone = $this->phoneNumberService->validateAndGetUserPhoneNumber($fromNumber);

        $acquirer = $this->acquirerService->get("acquirer");
        CallLog::saveCallLogs($acquirer->user->id, $matchedPhone->id, $toNumber, $twilioSid);

        $createCallLogRequest = CallLogMapper::createCallLogRequestToCallLogVM($createCallLogRequest);
        return new CallLogResponses($createCallLogRequest, "Call Log created Successfully", 201);

    }

    /**
     * @throws ErrorException
     */
    public function updateCallLog(UpdateCallLogRequest $updateCallLogRequest, $twilio_sid)
    {
        $createCallLogRequest = $updateCallLogRequest->validated();
        $acquirer = $this->acquirerService->get("acquirer");

        $callLog = CallLog::verifyAndGetCallLogByTwilioSid($twilio_sid, $acquirer);
        $talkTime = $callLog->created_at->diffInSeconds(now());

        $twilioRecordingSid = null;
        $allowCallRecording = $this->configurationService->getConfigurationValueByKey(ConfigurationEnum::$ALLOW_CALL_RECORDING);

        print_r($allowCallRecording);

        if ($allowCallRecording == 1) {
            if (empty($createCallLogRequest['twilio_recording_sid'])) {
                return ErrorResponseEnum::$TWILIO_RECORDING_SID_MISSING_422;
            }
            $twilioRecordingSid = $createCallLogRequest['twilio_recording_sid'];
        }

        CallLog::updateCallLog($twilio_sid, $talkTime, $twilioRecordingSid);

        return new CallLogResponses(null, "Call Log Updated", 200);
    }

}
