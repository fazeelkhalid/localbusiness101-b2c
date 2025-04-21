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
    public static function mapUserDomainListToUserVmList($users)
    {
        $mappedUsers = [];
        foreach ($users as $user) {
            $mappedUsers[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
        }

        return $mappedUsers;
    }

}
