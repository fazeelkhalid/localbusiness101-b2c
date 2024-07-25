<?php

namespace App\Services;

use App\Exceptions\ErrorException;
use App\Http\Mapper\UserBusinessProfileMapper;
use App\Http\Requests\UserBusinessProfile\UserBusinessProfileRequest;
use App\Http\Responses\UserBusinessProfile\UserBusinessProfileResponses;
use App\Models\Acquirer;
use App\Models\BusinessProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserBusinessProfileService
{
    public function createUserBusinessProfile(UserBusinessProfileRequest $userBusinessProfileRequest)
    {
        DB::beginTransaction();

        try {
            $userBusinessProfileRequest = $userBusinessProfileRequest->validated();
            $acquirer = Acquirer::createAcquirer($userBusinessProfileRequest['acquirer_name']);
            $user = User::createUser($userBusinessProfileRequest['user'], $acquirer);
            $businessProfile = BusinessProfile::createBusinessProfile($userBusinessProfileRequest['business_profile'], $user);
            $authServiceResponse = AuthRequestService::request('/api/signup', 'POST', $userBusinessProfileRequest['user']);
            if (!$authServiceResponse->successful()) {
                $responseData = json_encode($authServiceResponse->json());
                throw new ErrorException($responseData, $authServiceResponse->status());
            }
            DB::commit();
            $userBusinessProfileResponse = UserBusinessProfileMapper::mapUserBusinessProfileRequestToUserBusinessProfileResponse($userBusinessProfileRequest, $acquirer, $businessProfile);
            $userBusinessProfileResponseMessage = "";
            if (isset($authServiceResponse['user']) && isset($authServiceResponse['user']['email_confirmation_message'])) {
                $userBusinessProfileResponseMessage = $authServiceResponse['user']['email_confirmation_message'];
            } else {
                $userBusinessProfileResponseMessage = "User and business profile created successfully";
            }
            return new UserBusinessProfileResponses($userBusinessProfileResponseMessage, $userBusinessProfileResponse, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
