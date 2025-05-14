<?php

namespace App\Http\Mapper;


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


}
