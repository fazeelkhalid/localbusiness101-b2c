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
            'recording_url' => $callLog->recording_url !== null ? '/call-log/' . $callLog->id . '/recording' : null,
            'call_direction' => $callLog->call_direction,
        ];
    }

    public static function mapCallLogsCollectionToVM($callLogs): array
    {
        return $callLogs->map(function ($callLog) {
            return self::mapCallLogToVM($callLog);
        })->toArray();
    }

    public static function mapGroupedStats($groupedStats): array
    {
        $result = [];

        foreach ($groupedStats as $entry) {
            $period = $entry['period'];

            $result[$period??"unknown"] = [
                'total_talk_time' => (String)$entry['total_talk_time'] ?? "0",
                'total_dialed' => (String)$entry['total_dialed'] ?? "0",
                'total_inbound' => (String)$entry['total_inbound'] ?? "0",
                'total_outbound' => (String)$entry['total_outbound'] ?? "0",
                'completed' => (String)$entry['completed'] ?? "0",
                'failed' => (String)$entry['failed'] ?? "0",
                'ringing' => (String)$entry['ringing'] ?? "0",
                'busy' => (String)$entry['busy'] ?? "0",
                'no_answer' => (String)$entry['no_answer'] ?? "0",
            ];
        }

        return $result;
    }
}
