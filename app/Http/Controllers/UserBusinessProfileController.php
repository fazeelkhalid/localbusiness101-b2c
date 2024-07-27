<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserBusinessProfile\BusinessProfileFilterRequest;
use App\Http\Requests\UserBusinessProfile\CreateUserBusinessProfileRequest;
use App\Http\Requests\UserBusinessProfile\UpdateUserBusinessProfileRequest;
use App\Http\Services\UserBusinessProfileService;

class UserBusinessProfileController extends Controller
{
    protected UserBusinessProfileService $businessProfileService;

    public function __construct(UserBusinessProfileService $businessProfileService)
    {
        $this->businessProfileService = $businessProfileService;
    }

    public function createUserBusinessProfileController(CreateUserBusinessProfileRequest $userBusinessProfileRequest)
    {
        return $this->businessProfileService->createUserBusinessProfile($userBusinessProfileRequest);
    }


    public function updateUserBusinessProfileController(UpdateUserBusinessProfileRequest $userBusinessProfileRequest, $business_profiles_key)
    {
        return $this->businessProfileService->updateUserBusinessProfileController($userBusinessProfileRequest, $business_profiles_key);
    }

    public function getUserBusinessProfileController($business_profiles_key)
    {
        return $this->businessProfileService->getUserBusinessProfileController($business_profiles_key);
    }

    public function getUserBusinessProfileListController(BusinessProfileFilterRequest $businessProfileFilterRequest)
    {
        return $this->businessProfileService->getUserBusinessProfileListController($businessProfileFilterRequest);
    }

}
