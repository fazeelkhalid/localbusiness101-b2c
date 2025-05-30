<?php

namespace App\Http\Services;

use App\Http\Filters\UserFilter;
use App\Http\Mapper\UserMapper;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UserListRequest;
use App\Http\Responses\User\CreateUserResponses;
use App\Http\Responses\User\GetUserResponses;
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
            $user = User::createUser($createUserRequest, $acquirer);

            $authServiceResponse = AuthRequestService::registerUser($createUserRequest);
            DB::commit();

            $userBusinessProfileResponseMessage = "";
            if (isset($authServiceResponse['user']) && isset($authServiceResponse['user']['email_confirmation_message'])) {
                $userBusinessProfileResponseMessage = $authServiceResponse['user']['email_confirmation_message'];
            } else {
                $userBusinessProfileResponseMessage = "User created successfully";
            }

            $userBusinessProfileResponse = UserMapper::mapCreateUserRequestToCreateUserResponse($createUserRequest, $acquirer, $user);
            return new CreateUserResponses($userBusinessProfileResponseMessage, $userBusinessProfileResponse, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getUserList(UserListRequest $userListRequest){

        $params = $userListRequest->searchParams();
        $query = User::query();

        $query = UserFilter::applyFilters($query, $params);
        $users = $query->get();
        $userListVm = UserMapper::mapUserDomainListToUserVmList($users);

        return new GetUserResponses($userListVm);
    }


}
