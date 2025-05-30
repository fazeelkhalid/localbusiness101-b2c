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
use Illuminate\Support\Facades\Storage;

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
            'Content-Type' => 'audio/mpeg',
            'Content-Length' => $fileSize,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Accept-Ranges' => 'bytes',
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

    public function getCallLogStatsByUserId(CallLogFilterRequest $callLogFilterRequest, $user)
    {
        $baseQuery = CallLog::getCallLogs($user->id);
        $filteredQuery = CallLogFilter::applyFilters($baseQuery, $callLogFilterRequest->all());
        $callLogsStats = CallLog::getCallLogsStats($filteredQuery, $callLogFilterRequest, $user->name);
        return $callLogsStats;
    }

    public function getCallLogsStatsForAllUsers(CallLogFilterRequest $callLogFilterRequest)
    {
        $filters = $callLogFilterRequest->all();
        $filterUserName = $filters['user_name'] ?? null;

        $isAdminStatsEnabled = $this->configurationService->getConfigurationValueByKey(ConfigurationEnum::$IS_ADMIN_STATS_UNABLE);

        $acquirer = $this->acquirerService->get("acquirer");

        $users = collect();
        if ($isAdminStatsEnabled) {
            $users = $acquirer->user->adminOf()->get();
        }
        $users->push($acquirer->user);
        $callLogsStats = [];
        foreach ($users as $user) {
            if ($filterUserName !== null && $user->name !== $filterUserName) {
                continue;
            }
            $callLogsStats[] = $this->getCallLogStatsByUserId($callLogFilterRequest, $user);
        }
        return new GetCallLogsStatsResponses($callLogsStats, 200);
    }


}
