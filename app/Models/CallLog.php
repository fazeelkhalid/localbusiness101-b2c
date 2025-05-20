<?php

namespace App\Models;

use App\Exceptions\ErrorException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CallLog extends Model
{
    use HasFactory;

    protected $table = "call_logs";

    protected $fillable = [
        'user_id',
        'caller_number_id',
        'receiver_number',
        'talk_time',
        'twilio_sid',
        'twilio_recording_sid',
        'recording_url',
        'call_status',
        'call_start_time',
        'call_end_time',
        'call_direction'
    ];

    public function caller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function phoneNumber()
    {
        return $this->belongsTo(PhoneNumber::class, 'caller_number_id');
    }

    public static function saveCallLogs($userId, $numberId, $receiverNumber, $twilio_sid)
    {

        return self::create([
            'user_id' => $userId,
            'caller_number_id' => $numberId,
            'receiver_number' => $receiverNumber,
            'twilio_sid' => $twilio_sid
        ]);
    }

    public static function verifyAndGetCallLogByTwilioSid($twilioSid, $acquirer)
    {
        $callLog = self::where('twilio_sid', $twilioSid)->where('talk_time', '=', 0)->first();

        if (!$callLog) {
            throw new ErrorException("Invalid Twilio Sid", null, 422);
        } else if ($callLog->user_id !== $acquirer->user->id) {
            throw new ErrorException("Invalid Twilio Sid", null, 422);
        }
        return $callLog;
    }

    public static function updateCallLog($twilioSid, $talkTime, $twilioRecordingSid)
    {
        return self::where('twilio_sid', $twilioSid)->update([
            'talk_time' => $talkTime,
            'twilio_recording_sid' => $twilioRecordingSid,
        ]);
    }

    public static function updateCallLogFromTwilioData($twilioData): bool
    {
        if(!$twilioData){
            throw new ErrorException("Twilio return Object Is Empty", null, 422);
        }

        if(!isset($twilioData['sid'])){
            throw new ErrorException("Sid is null or not set", null, 422);
        }

        return self::where('twilio_sid', $twilioData['sid'])->update([
            'talk_time' =>  $twilioData['duration'] ?? 0,
            'call_status' => $twilioData['status'] ?? null,
            'call_direction' => $twilioData['direction'] ?? null,
            'call_start_time' => isset($twilioData['start_time']) ? date('Y-m-d H:i:s', strtotime($twilioData['start_time'])) : null,
            'call_end_time' => isset($twilioData['end_time']) ? date('Y-m-d H:i:s', strtotime($twilioData['end_time'])) : null,
        ]);

    }
    public static function updateCallLogForRecording($twilioCallRecordingData, $callSid): void
    {
        if(!$twilioCallRecordingData){
            return;
        }

        if(!$callSid){
            throw new ErrorException("Invalid Sid", null, 422);
        }

        if(!isset($twilioCallRecordingData['sid'])){
            Log::info("Twilio call recording Data: " . json_encode($twilioCallRecordingData));

            throw new ErrorException("Twilio Call Recording Sid is null or not set", $twilioCallRecordingData, 422);
        }

        $updated = self::where('twilio_sid', $callSid)->update([
            'twilio_recording_sid' => $twilioCallRecordingData['sid'],
        ]);

        if ($updated === 0) {
            throw new ErrorException("Invalid Twilio call SID", null, 404);
        }


    }

}
