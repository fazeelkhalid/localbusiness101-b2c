<?php

namespace App\Http\Mapper;


class PhoneNumberMapper
{

    public static function mapUserPhoneNumberDomainToUserPhoneNumberVM($userPhoneNumber)
    {
        return $userPhoneNumber->map(function ($phone) {
            return [
                'phone_number' => $phone->phone_number,
                'dialing_regex' => $phone->dialing_regex,
            ];
        })->toArray();
    }

    public static function mapVerifyPhoneNumberDomainToVM($allowCallRecording)
    {

        return[
            'is_recording' => (bool)$allowCallRecording,
        ];
    }


}
