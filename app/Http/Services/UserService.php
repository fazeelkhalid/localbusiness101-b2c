<?php

namespace App\Http\Services;

use App\Http\Mapper\UserMapper;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Responses\User\CreateUserResponses;
use App\Models\Acquirer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function createUser(CreateUserRequest $createUserRequest)
    {
        DB::beginTransaction();

        try {
            $createUserRequest = $createUserRequest->validated();
            $acquirer = Acquirer::createAcquirer($createUserRequest['name']);
            User::createUser($createUserRequest, $acquirer);

            $authServiceResponse = AuthRequestService::registerUser($createUserRequest);
            DB::commit();

            $userBusinessProfileResponseMessage = "";
            if (isset($authServiceResponse['user']) && isset($authServiceResponse['user']['email_confirmation_message'])) {
                $userBusinessProfileResponseMessage = $authServiceResponse['user']['email_confirmation_message'];
            } else {
                $userBusinessProfileResponseMessage = "User created successfully";
            }

            $userBusinessProfileResponse = UserMapper::mapCreateUserRequestToCreateUserResponse($createUserRequest, $acquirer);
            return new CreateUserResponses($userBusinessProfileResponseMessage, $userBusinessProfileResponse, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


}
