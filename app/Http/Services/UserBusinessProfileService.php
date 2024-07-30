<?php

namespace App\Http\Services;

use App\Enums\ErrorResponseEnum;
use App\Exceptions\ErrorException;
use App\Http\Filters\UserBusinessProfileFilter;
use App\Http\Mapper\AuthMapper;
use App\Http\Mapper\UserBusinessProfileMapper;
use App\Http\Pagination\Pagination;
use App\Http\Requests\UserBusinessProfile\BusinessProfileFilterRequest;
use App\Http\Requests\UserBusinessProfile\CreateUserBusinessProfileRequest;
use App\Http\Requests\UserBusinessProfile\UpdateUserBusinessProfileRequest;
use App\Http\Responses\UserBusinessProfile\CreateUserBusinessProfileResponses;
use App\Http\Responses\UserBusinessProfile\GetUserBusinessProfileResponses;
use App\Http\Responses\UserBusinessProfile\GetUserBusinessProfilesResponses;
use App\Http\Responses\UserBusinessProfile\UpdateUserBusinessProfileResponses;
use App\Models\Acquirer;
use App\Models\BusinessProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserBusinessProfileService
{
    public function createUserBusinessProfile(CreateUserBusinessProfileRequest $userBusinessProfileRequest)
    {
        DB::beginTransaction();

        try {
            $userBusinessProfileRequest = $userBusinessProfileRequest->validated();
            $acquirer = Acquirer::createAcquirer($userBusinessProfileRequest['acquirer_name']);
            $user = User::createUser($userBusinessProfileRequest['user'], $acquirer);
            $businessProfile = BusinessProfile::createBusinessProfile($userBusinessProfileRequest['business_profile'], $user);
            $authServiceResponse = AuthRequestService::request('/api/signup', 'POST', $userBusinessProfileRequest['user']);
            if (!$authServiceResponse->successful()) {
                $authServiceErrorExceptionResponse= AuthMapper::mapAuthServiceErrorResponse($authServiceResponse);
                throw new ErrorException("Invalid email or password.", $authServiceErrorExceptionResponse, $authServiceResponse->status());
            }
            DB::commit();
            $userBusinessProfileResponse = UserBusinessProfileMapper::mapUserBusinessProfileRequestToUserBusinessProfileResponse($userBusinessProfileRequest, $acquirer, $businessProfile);
            $userBusinessProfileResponseMessage = "";
            if (isset($authServiceResponse['user']) && isset($authServiceResponse['user']['email_confirmation_message'])) {
                $userBusinessProfileResponseMessage = $authServiceResponse['user']['email_confirmation_message'];
            } else {
                $userBusinessProfileResponseMessage = "User and business profile created successfully";
            }
            return new CreateUserBusinessProfileResponses($userBusinessProfileResponseMessage, $userBusinessProfileResponse, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateUserBusinessProfile(UpdateUserBusinessProfileRequest $userBusinessProfileRequest, $business_profiles_key)
    {
        $validatedData = $userBusinessProfileRequest->validated();

        $businessProfile = BusinessProfile::with([
            'user.acquirer',
            'contactDetails'
        ])->where('business_profiles_key', $business_profiles_key)->first();


        if(!$businessProfile){
            return ErrorResponseEnum::$BPNF404;
        }

        $businessProfile->update([
            'title' => $validatedData['business_profile']['title'] ?? $businessProfile->description,
            'description' => $validatedData['business_profile']['description'] ?? $businessProfile->description,
            'short_intro' => $validatedData['business_profile']['short_intro'] ?? $businessProfile->short_intro,
            'keywords' => $validatedData['business_profile']['keywords'] ?? $businessProfile->keywords,
            'tab_title' => $validatedData['business_profile']['tab_title'] ?? $businessProfile->tab_title,
            'font_style' => $validatedData['business_profile']['font_style'] ?? $businessProfile->font_style,
            'heading_color' => $validatedData['business_profile']['heading_color'] ?? $businessProfile->heading_color,
            'heading_size' => $validatedData['business_profile']['heading_size'] ?? $businessProfile->heading_size,
        ]);


        $businessProfile->user->acquirer->update([
            "key" => $validatedData['business_profile']['acquirer']['key'] ?? $businessProfile->user->acquirer->key,
            "name" => $validatedData['business_profile']['acquirer']['name'] ?? $businessProfile->user->acquirer->name,
        ]);
        $updateBusinessProfileResponse = UserBusinessProfileMapper::mapUserBusinessProfileToUpdateUserBusinessProfileResponse($businessProfile);

        return new UpdateUserBusinessProfileResponses("Business Profile updated successfully", $updateBusinessProfileResponse, 200);
    }

    public function getUserBusinessProfile($business_profiles_key)
    {
        $businessProfile = BusinessProfile::with([
            'user.acquirer',
            'contactDetails'
        ])->where('business_profiles_key', $business_profiles_key)->first();

        if(!$businessProfile){
            return ErrorResponseEnum::$BPNF404;
        }
        $businessProfile = UserBusinessProfileMapper::mapUserBusinessProfileToGetUserBusinessProfileResponse($businessProfile);

        return new GetUserBusinessProfileResponses($businessProfile, 200);
    }

    public function getUserBusinessProfileList(BusinessProfileFilterRequest $businessProfileFilterRequest)
    {
        $query = BusinessProfile::with(['user.acquirer', 'contactDetails']);
        UserBusinessProfileFilter::applyFilters($query, $businessProfileFilterRequest->validated());

        $businessProfiles = Pagination::set($businessProfileFilterRequest, $query);

        $mappedBusinessProfiles = $businessProfiles->map(function ($businessProfile) {
            return UserBusinessProfileMapper::mapUserBusinessProfileToGetUserBusinessProfileResponse($businessProfile);
        });

        return new GetUserBusinessProfilesResponses($mappedBusinessProfiles, ['current_page' => $businessProfiles->currentPage(), 'last_page' => $businessProfiles->lastPage(), 'per_page' => $businessProfiles->perPage(), 'total' => $businessProfiles->total(), 'next_page_url' => $businessProfiles->nextPageUrl(), 'prev_page_url' => $businessProfiles->previousPageUrl()],200);
    }
}
