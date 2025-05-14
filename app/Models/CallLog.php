<?php

namespace App\Models;

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
}
