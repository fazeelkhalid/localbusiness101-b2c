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
use App\Http\Responses\CallLog\GetCallLogsStatsResponses;
use App\Models\CallLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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

        $fullPath = storage_path('app/' . $relativePath);
        if (!file_exists($fullPath)) {
            throw new ErrorException('Recording not found', null, 404);
        }

        $fileSize = filesize($fullPath);
        $fileName = basename($fullPath);

        return response()->stream(function () use ($fullPath) {
            $stream = fopen($fullPath, 'rb');
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type'        => 'audio/mpeg',
            'Content-Length'      => $fileSize,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Accept-Ranges'       => 'bytes',
        ]);
    }



//    public function getCallLogStats(CallLogFilterRequest $callLogFilterRequest)
//    {
//        $acquirer = $this->acquirerService->get("acquirer");
//        $callLogsQuery = CallLog::getCallLogs($acquirer->user->id);
//
//        $callLogsFilteredQuery = CallLogFilter::applyFilters($callLogsQuery, $callLogFilterRequest->all());
//        $paginatedCallLogs = Pagination::set($callLogFilterRequest, $callLogsFilteredQuery);
//
//        $mapPaginatedCallLogsVM = CallLogMapper::mapCallLogsCollectionToVM($paginatedCallLogs);
//        return new GetCallLogsResponses($mapPaginatedCallLogsVM, $paginatedCallLogs, 200);
//    }

    public function getCallLogStats(CallLogFilterRequest $callLogFilterRequest)
    {
        $acquirer = $this->acquirerService->get("acquirer");

        $baseQuery = CallLog::getCallLogs($acquirer->user->id);
        $filteredQuery = CallLogFilter::applyFilters($baseQuery, $callLogFilterRequest->all());
        $callLogsStats = CallLog::getCallLogsStats($filteredQuery, $callLogFilterRequest, $acquirer);

        return new GetCallLogsStatsResponses($callLogsStats, 200);
    }

    public function getCallLogsStatsForAllUsers(CallLogFilterRequest $callLogFilterRequest)
    {
        $isAdminStatsEnabled = $this->configurationService->getConfigurationValueByKey("IS_ADMIN_STATS_UNABLED");

        $acquirer = $this->acquirerService->get("acquirer");
        $acquirerUserId = $acquirer->user->id ?? null;

        $userIdsQuery = CallLog::query()->distinct()->pluck('user_id');

        $response = [];

        foreach ($userIdsQuery as $userId) {
            $user = User::find($userId);
            if (!$user) continue;

            if ($isAdminStatsEnabled && $user->admin !== $acquirerUserId) {
                continue;
            }

            $baseQuery = CallLog::getCallLogs($userId);
            $filteredQuery = CallLogFilter::applyFilters($baseQuery, $callLogFilterRequest->all());
            $stats = CallLog::getCallLogsStats($filteredQuery, $callLogFilterRequest, $acquirer);

            $response[$user->name] = $stats;
        }

        return $response;
    }




}
