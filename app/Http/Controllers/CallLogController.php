<?php

namespace App\Http\Controllers;

use App\Enums\ConfigurationEnum;
use App\Enums\ErrorResponseEnum;
use App\Exceptions\ErrorException;
use App\Http\Mapper\CallLogMapper;
use App\Http\Mapper\PhoneNumberMapper;
use App\Http\Requests\CallLog\CallLogFilterRequest;
use App\Http\Requests\CallLog\CreateCallLogRequest;
use App\Http\Requests\CallLog\UpdateCallLogRequest;
use App\Http\Requests\PhoneNumber\VerifyPhoneNumberRequest;
use App\Http\Responses\CallLog\CallLogResponses;
use App\Http\Responses\PhoneNumber\GetUserPhoneNumberResponses;
use App\Http\Responses\PhoneNumber\verifyPhoneNumberResponses;
use App\Http\Services\AcquirerService;
use App\Http\Services\CallLogService;
use App\Http\Services\PhoneNumberService;
use App\Models\CallLog;

class CallLogController extends Controller
{

    protected AcquirerService $acquirerService;
    protected CallLogService $callLogService;
    public function __construct(AcquirerService $acquirerService, CallLogService $callLogService)
    {
        $this->acquirerService = $acquirerService;
        $this->callLogService = $callLogService;
    }


    /**
     * @throws ErrorException
     */
    public function createCallLog(CreateCallLogRequest $createCallLogRequest)
    {
        $this->acquirerService->hasAuthorityOrThrowException("createCallLog");
        return $this->callLogService->createCallLog($createCallLogRequest);
    }

    public function updateCallLog(UpdateCallLogRequest $updateCallLogRequest, $twilio_sid)
    {
        $this->acquirerService->hasAuthorityOrThrowException("updateCallLog");
        return $this->callLogService->updateCallLog($updateCallLogRequest, $twilio_sid);

    }

    public function getCallLogList(CallLogFilterRequest $callLogFilterRequest)
    {
        $this->acquirerService->hasAuthorityOrThrowException("getCallLogList");
        return $this->callLogService->getCallLogList($callLogFilterRequest);

    }

    public function getCallLogRecording($call_sid)
    {
        $this->acquirerService->hasAuthorityOrThrowException("getCallLogRecording");
        return $this->callLogService->getCallLogRecording($call_sid);


    }


}
