<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserBusinessProfile\BusinessProfileFilterRequest;
use App\Http\Requests\UserBusinessProfile\CreateUserBusinessProfileRequest;
use App\Http\Requests\UserBusinessProfile\UpdateUserBusinessProfileRequest;
use App\Http\Services\AcquirerService;
use App\Http\Services\UserBusinessProfileService;

class UserBusinessProfileController extends Controller
{
    protected AcquirerService $acquirerService;
    protected UserBusinessProfileService $businessProfileService;

    public function __construct(AcquirerService $acquirerService, UserBusinessProfileService $businessProfileService)
    {
        $this->acquirerService = $acquirerService;
        $this->businessProfileService = $businessProfileService;
    }

    public function createUserBusinessProfile(CreateUserBusinessProfileRequest $userBusinessProfileRequest)
    {
        $this->acquirerService->hasAuthorityOrThrowException("createUserBusinessProfile");
        return $this->businessProfileService->createUserBusinessProfile($userBusinessProfileRequest);
    }


    public function updateUserBusinessProfile(UpdateUserBusinessProfileRequest $userBusinessProfileRequest, $business_profiles_slug)
    {
        $this->acquirerService->hasAuthorityOrThrowException("updateUserBusinessProfile");
        return $this->businessProfileService->updateUserBusinessProfile($userBusinessProfileRequest, $business_profiles_slug);
    }

    public function getUserBusinessProfile($business_profiles_key)
    {
        return $this->businessProfileService->getUserBusinessProfile($business_profiles_key);
    }

    public function getUserBusinessProfileBySlugs($business_profiles_slugs)
    {
        return $this->businessProfileService->getUserBusinessProfileBySlugs($business_profiles_slugs);
    }

    public function getUserBusinessProfileList(BusinessProfileFilterRequest $businessProfileFilterRequest)
    {
        return $this->businessProfileService->getUserBusinessProfileList($businessProfileFilterRequest);
    }

}
