<?php

namespace App\Http\Services;

use App\Enums\ConfigurationEnum;
use App\Enums\ErrorResponseEnum;
use App\Exceptions\ErrorException;
use App\Http\Mapper\CallLogMapper;
use App\Http\Mapper\PhoneNumberMapper;
use App\Http\Requests\CallLog\CreateCallLogRequest;
use App\Http\Requests\PhoneNumber\VerifyPhoneNumberRequest;
use App\Http\Responses\CallLog\CreateCallLogResponses;
use App\Http\Responses\PhoneNumber\GetUserPhoneNumberResponses;
use App\Http\Responses\PhoneNumber\verifyPhoneNumberResponses;
use App\Models\CallLog;

class CallLogService
{
    protected AcquirerService $acquirerService;
    protected PhoneNumberService $phoneNumberService;
    public function __construct(AcquirerService $acquirerService, PhoneNumberService $phoneNumberService)
    {
        $this->acquirerService = $acquirerService;
        $this->phoneNumberService = $phoneNumberService;
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
        return new CreateCallLogResponses( $createCallLogRequest,"Call Log created Successfully", 201);

    }
}
