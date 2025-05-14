<?php

namespace App\Models;

use App\Exceptions\ErrorException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
