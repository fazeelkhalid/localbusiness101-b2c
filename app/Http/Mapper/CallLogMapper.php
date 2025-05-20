<?php

namespace App\Http\Mapper;


use App\Models\CallLog;

class CallLogMapper
{


    public static function createCallLogRequestToCallLogVM($callLogRequest)
    {
        return[
            'from'=>$callLogRequest["from"],
            'to' => $callLogRequest["to"],
            'twilio_sid' => $callLogRequest["twilio_sid"],
        ];
    }



    private static function mapCallLogToVM(CallLog $callLog): array
    {
        return [
            'from' => $callLog->phoneNumber->phone_number ?? null,
            'to' => $callLog->receiver_number,
            'talk_time' => $callLog->talk_time,
            'call_status' => $callLog->call_status,
            'call_start_time' => $callLog->call_start_time,
            'call_end_time' => $callLog->call_end_time,
            'recording_url' => $callLog->recording_url,
            'call_direction' => $callLog->call_direction,
        ];
    }

    public static function mapCallLogsCollectionToVM($callLogs): array
    {
        return $callLogs->map(function ($callLog) {
            return self::mapCallLogToVM($callLog);
        })->toArray();
    }
}
