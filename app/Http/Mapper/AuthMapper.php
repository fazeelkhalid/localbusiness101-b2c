<?php

namespace App\Http\Mapper;


use App\Http\Utils\CustomUtils;

class AuthMapper
{
    public static function mapLoginResponse($requestResponse, $userAcquirerKey)
    {
        $data = json_decode($requestResponse, true);

        $mappedResponse = [
            'auth_token' =>[
                'token' => $data['login']['authorisation']['token'],
                'type' => $data['login']['authorisation']['type'],
            ],
            'user' => [
                'key' => $userAcquirerKey,
                'name' => $data['login']['user']['name'],
                'email' => $data['login']['user']['email'],
            ],
            'message_trace_uuid' => $data['message_trace_uuid'],
        ];
        return $mappedResponse;
    }

    public static function mapAuthServiceErrorResponse($requestResponse)
    {
        $data = json_decode($requestResponse, true);
        $mappedResponse = [];

        $extractValues = function ($data, &$mappedResponse, $prefix = '') use (&$extractValues) {
            foreach ($data as $key => $value) {
                $newKey = $prefix ? $prefix . '.' . $key : $key;
                if (is_array($value) && CustomUtils::isAssoc($value)) {
                    $extractValues($value, $mappedResponse, $newKey);
                } else {
                    $mappedResponse[$newKey] = $value;
                }
            }
        };

        $extractValues($data, $mappedResponse);

        return $mappedResponse;
    }


    public static function mapServerErrorResponseToAPIResponse(array $data): array
    {
        return[
            "message" => "Please contact support with message trace ID '" . $data['message_trace_uuid'] . "'",
            "message_trace_uuid"=> $data['message_trace_uuid']
        ];
    }

}
