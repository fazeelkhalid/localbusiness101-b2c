<?php

namespace App\Http\Mapper;


use App\Http\Utils\CustomUtils;

class UserMapper
{

    public static function mapCreateUserRequestToCreateUserResponse($creatUserRequest, $acquirer, $user)
    {
        return [
            'id' => $user->id,
            'name' => $creatUserRequest['name'],
            'email' => $creatUserRequest['email'],
            'password' => $creatUserRequest['password'],

            'acquirer' => [
                'name' => $acquirer->name,
                'key' => $acquirer->key
            ]
        ];
    }


}
