<?php

namespace App\Http\Mapper;


class AuthMapper
{
    public static function mapLoginResponse($requestResponse)
    {
        $data = json_decode($requestResponse, true);

        $mappedResponse = [
            'auth_token' =>[
                'token' => $data['login']['authorisation']['token'],
                'type' => $data['login']['authorisation']['type'],
            ],
            'user' => [
                'name' => $data['login']['user']['name'],
                'email' => $data['login']['user']['email'],
            ],
            'message_trace_uuid' => $data['message_trace_uuid'],
        ];
        return $mappedResponse;
    }
}
