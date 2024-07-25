<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserBusinessProfile\UserBusinessProfileRequest;
use App\Services\UserBusinessProfileService;

class UserBusinessProfileController extends Controller
{
    protected UserBusinessProfileService $businessProfileService;

    public function __construct(UserBusinessProfileService $businessProfileService)
    {
        $this->businessProfileService = $businessProfileService;
    }

    public function createUserBusinessProfileController(UserBusinessProfileRequest $userBusinessProfileRequest)
    {
        return $this->businessProfileService->createUserBusinessProfile($userBusinessProfileRequest);
    }

}
