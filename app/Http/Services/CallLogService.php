<?php

namespace App\Http\Services;

use App\Enums\ConfigurationEnum;
use App\Enums\ErrorResponseEnum;
use App\Exceptions\ErrorException;
use App\Http\Filters\CallLogFilter;
use App\Http\Mapper\CallLogMapper;
use App\Http\Pagination\Pagination;
use App\Http\Requests\CallLog\CallLogFilterRequest;
use App\Http\Requests\CallLog\CreateCallLogRequest;
use App\Http\Requests\CallLog\UpdateCallLogRequest;
use App\Http\Responses\CallLog\CallLogResponses;
use App\Http\Responses\CallLog\GetCallLogsResponses;
use App\Models\CallLog;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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


    public function getCallLogList(CallLogFilterRequest $callLogFilterRequest)
    {
        $acquirer = $this->acquirerService->get("acquirer");
        $callLogsQuery = CallLog::getCallLogs($acquirer->user->id);

        $callLogsFilteredQuery = CallLogFilter::applyFilters($callLogsQuery, $callLogFilterRequest->all());
        $paginatedCallLogs = Pagination::set($callLogFilterRequest, $callLogsFilteredQuery);

        $mapPaginatedCallLogsVM = CallLogMapper::mapCallLogsCollectionToVM($paginatedCallLogs);
        return new GetCallLogsResponses($mapPaginatedCallLogsVM, $paginatedCallLogs, 200);
    } 

    public function getCallLogRecording($call_sid)
    {
        $acquirer = $this->acquirerService->get("acquirer");
        $callLog = CallLog::getCallLogByCallId($call_sid, $acquirer->user->id);
        $relativePath = $callLog->recording_url;

        if (!Storage::disk('local')->exists($relativePath)) {
            throw new ErrorException('Recording not found', null, 404);
        }
        $stream = Storage::disk('local')->readStream($relativePath);

        return new StreamedResponse(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type' => 'audio/mpeg',
            'Content-Disposition' => 'inline; filename="' . basename($relativePath) . '"',
        ]);
    }

}
