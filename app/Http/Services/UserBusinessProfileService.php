<?php

namespace App\Http\Services;

use App\Enums\ErrorResponseEnum;
use App\Http\Filters\UserBusinessProfileFilter;
use App\Http\Mapper\UserBusinessProfileMapper;
use App\Http\Pagination\Pagination;
use App\Http\Requests\UserBusinessProfile\BusinessProfileFilterRequest;
use App\Http\Requests\UserBusinessProfile\CreateUserBusinessProfileRequest;
use App\Http\Requests\UserBusinessProfile\UpdateUserBusinessProfileRequest;
use App\Http\Responses\UserBusinessProfile\CreateUserBusinessProfileResponses;
use App\Http\Responses\UserBusinessProfile\GetUserBusinessProfileResponses;
use App\Http\Responses\UserBusinessProfile\GetUserBusinessProfilesResponses;
use App\Http\Responses\UserBusinessProfile\UpdateUserBusinessProfileResponses;
use App\Http\Utils\CustomUtils;
use App\Models\Acquirer;
use App\Models\BusinessCategory;
use App\Models\BusinessContactDetail;
use App\Models\BusinessProfile;
use App\Models\BusinessProfileGallery;
use App\Models\BusinessProfileSlideImage;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserBusinessProfileService
{
    public function createUserBusinessProfile(CreateUserBusinessProfileRequest $userBusinessProfileRequest)
    {
        DB::beginTransaction();

        try {
            $userBusinessProfileRequest = $userBusinessProfileRequest->validated();

            $cardImage = $userBusinessProfileRequest['business_profile']['card_image'];
            $cardImageFilename = 'card_image-' . time() . '.' . $cardImage->getClientOriginalExtension();

            if (isset($userBusinessProfileRequest['user_id'])) {
                $user = User::with('acquirer')->find($userBusinessProfileRequest['user_id']);
                $acquirer = $user->acquirer;
            } else {
                $acquirer = Acquirer::createAcquirer($userBusinessProfileRequest['acquirer_name']);
                $user = User::createUser($userBusinessProfileRequest['user'], $acquirer);
            }

            $category = BusinessCategory::findCategoryByName($userBusinessProfileRequest['business_profile']['category']);

            $userBusinessProfileRequest['business_profile']['slug'] = CustomUtils::generateUniqueSlug($userBusinessProfileRequest['business_profile']['title']);
            $slug = $userBusinessProfileRequest['business_profile']['slug'];
            $userBusinessProfileRequest['business_profile']['card_image'] = CustomUtils::uploadProfileImage('/' . $slug, $cardImage, $cardImageFilename);

            if ($userBusinessProfileRequest['business_profile']['theme'] === 'advance') {
                $userBusinessProfileRequest = $this->UploadMainPageAndLogoAndAboutImage($userBusinessProfileRequest, $slug);
            }

            $businessProfile = BusinessProfile::createBusinessProfile($userBusinessProfileRequest['business_profile'], $user, $category);
            if ($userBusinessProfileRequest['business_profile']['theme'] === 'advance' && isset($userBusinessProfileRequest['business_profile']['gallery_images'])) {
                BusinessProfileGallery::saveGalleryImages($slug, $businessProfile->id, $userBusinessProfileRequest['business_profile']['gallery_images']);
            }
            BusinessProfileSlideImage::saveSlidesimages($slug, $businessProfile->id, $userBusinessProfileRequest['business_profile']['slide_images']);

            if ($businessProfile['theme'] === 'advance') {
                Service::saveServices($userBusinessProfileRequest['business_profile']['services'], $businessProfile->id);
            }

            $userBusinessProfileResponseMessage = "User and business profile created successfully";
            if (!isset($userBusinessProfileRequest['user_id']) && isset($userBusinessProfileRequest['user'])) {
                $authServiceResponse = AuthRequestService::registerUser($userBusinessProfileRequest['user']);
                if (isset($authServiceResponse['user']) && isset($authServiceResponse['user']['email_confirmation_message'])) {
                    $userBusinessProfileResponseMessage = $authServiceResponse['user']['email_confirmation_message'];
                }
            }

            $userBusinessProfileResponse = UserBusinessProfileMapper::mapCreateUserBusinessProfileRequestToUserBusinessProfileResponse($user, $userBusinessProfileRequest, $acquirer, $businessProfile, $category);
            DB::commit();

            return new CreateUserBusinessProfileResponses($userBusinessProfileResponseMessage, $userBusinessProfileResponse, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateUserBusinessProfile(UpdateUserBusinessProfileRequest $updateUserBusinessProfileRequest, $slug)
    {
        DB::beginTransaction();

        try {
            $updateUserBusinessProfileRequest = $updateUserBusinessProfileRequest->validated();
            $businessProfile = BusinessProfile::getBusinessProfileFullDetails()->where('slug', $slug)->first();

            if (!$businessProfile) {
                return ErrorResponseEnum::$BPNF404;
            }

            $updateUserBusinessProfileRequest["business_profile"]['slug'] = $businessProfile->slug;
            $category = BusinessCategory::findCategoryByName($updateUserBusinessProfileRequest['business_profile']['category']);
            $userId = $updateUserBusinessProfileRequest['user_id'];

            $cardImage = $updateUserBusinessProfileRequest['business_profile']['card_image'];
            $cardImageFilename = 'card_image-' . time() . '.' . $cardImage->getClientOriginalExtension();
            $updateUserBusinessProfileRequest['business_profile']['card_image'] = CustomUtils::uploadProfileImage('/' . $slug, $cardImage, $cardImageFilename);

            if ($updateUserBusinessProfileRequest['business_profile']['theme'] === 'advance') {
                $updateUserBusinessProfileRequest = $this->UploadMainPageAndLogoAndAboutImage($updateUserBusinessProfileRequest, $slug);
            }
            else{
                $updateUserBusinessProfileRequest['business_profile']['main_page_image'] = "";
                $updateUserBusinessProfileRequest['business_profile']['logo_image'] = "";
                $updateUserBusinessProfileRequest['business_profile']['about_image'] = "";
            }

            BusinessProfile::updateBusinessProfile($businessProfile, $userId, $updateUserBusinessProfileRequest['business_profile'], $category);

            $contactDetails = $updateUserBusinessProfileRequest['business_profile']['business_contact_details'];
            if (isset($contactDetails) && is_array($contactDetails)) {
                BusinessContactDetail::where('business_profile_id', $businessProfile->id)->delete();
                BusinessContactDetail::createBusinessContactDetails($contactDetails, $businessProfile);
            }

            if ($updateUserBusinessProfileRequest['business_profile']['theme'] === 'advance' && isset($updateUserBusinessProfileRequest['business_profile']['gallery_images'])) {
                BusinessProfileGallery::where('business_profile_id', $businessProfile->id)->delete();
                BusinessProfileGallery::saveGalleryImages($slug, $businessProfile->id, $updateUserBusinessProfileRequest['business_profile']['gallery_images']);
            }

            BusinessProfileSlideImage::where('business_profile_id', $businessProfile->id)->delete();
            BusinessProfileSlideImage::saveSlidesimages($slug, $businessProfile->id, $updateUserBusinessProfileRequest['business_profile']['slide_images']);

            if ($businessProfile['theme'] === 'advance') {
                Service::where('business_profile_id', $businessProfile->id)->delete();
                Service::saveServices($updateUserBusinessProfileRequest['business_profile']['services'], $businessProfile->id);
            }
            else{
                Service::where('business_profile_id', $businessProfile->id)->delete();
            }

            $userBusinessProfileVm = UserBusinessProfileMapper::mapUpdateUserBusinessProfileToUserBusinessProfileVm( $updateUserBusinessProfileRequest, $category);
            DB::commit();

            return new UpdateUserBusinessProfileResponses("Business Profile Updated", $userBusinessProfileVm, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getUserBusinessProfile($business_profiles_key)
    {
        $businessProfile = BusinessProfile::getBusinessProfileFullDetails()->where('business_profiles_key', $business_profiles_key)->first();

        if (!$businessProfile) {
            return ErrorResponseEnum::$BPNF404;
        }
        $userAllProfilesDomain = BusinessProfile::getBusinessProfilesByUserId($businessProfile->user->id);
        $businessProfile = UserBusinessProfileMapper::mapUserBusinessProfileToGetUserBusinessProfileResponse($businessProfile, $userAllProfilesDomain);

        return new GetUserBusinessProfileResponses($businessProfile, 200);
    }

    public function getUserBusinessProfileBySlugs($business_profiles_slugs)
    {
        $businessProfile = BusinessProfile::getBusinessProfileFullDetails()->where('slug', $business_profiles_slugs)->first();

        if (!$businessProfile) {
            return ErrorResponseEnum::$BPNF404;
        }

        $userAllProfilesDomain = BusinessProfile::getBusinessProfilesByUserId($businessProfile->user->id);
        $businessProfile = UserBusinessProfileMapper::mapUserBusinessProfileToGetUserBusinessProfileResponse($businessProfile, $userAllProfilesDomain);

        return new GetUserBusinessProfileResponses($businessProfile, 200);
    }

    public function getUserBusinessProfileList(BusinessProfileFilterRequest $businessProfileFilterRequest)
    {
        list($businessProfiles, $mappedBusinessProfiles) = $this->filterAndMapBusinessProfiles($businessProfileFilterRequest);
        return new GetUserBusinessProfilesResponses($mappedBusinessProfiles, ['current_page' => $businessProfiles->currentPage(), 'last_page' => $businessProfiles->lastPage(), 'per_page' => $businessProfiles->perPage(), 'total' => $businessProfiles->total(), 'next_page_url' => $businessProfiles->nextPageUrl(), 'prev_page_url' => $businessProfiles->previousPageUrl()], 200);
    }

    public static function filterAndMapBusinessProfiles(BusinessProfileFilterRequest $businessProfileFilterRequest): array
    {
        $filters = $businessProfileFilterRequest->validated();
        $query = BusinessProfile::getBusinessProfileFullDetailsRandomly($filters);
        UserBusinessProfileFilter::applyFilters($query, $filters);

        $businessProfiles = Pagination::set($businessProfileFilterRequest, $query);


        $mappedBusinessProfiles = $businessProfiles->map(function ($businessProfile) {
            return UserBusinessProfileMapper::mapUserBusinessProfileListToGetUserBusinessProfileListResponse($businessProfile);
        });
        return array($businessProfiles, $mappedBusinessProfiles);
    }

    public function UploadMainPageAndLogoAndAboutImage($userBusinessProfileRequest, $slug)
    {
        $mainPageImage = $userBusinessProfileRequest['business_profile']['main_page_image'];
        $logoImage = $userBusinessProfileRequest['business_profile']['logo_image'];
        $aboutImage = $userBusinessProfileRequest['business_profile']['about_image'];

        $mainPageImageFileName = 'main_page_image-' . time() . '.' . $mainPageImage->getClientOriginalExtension();
        $logoImageFileName = 'logo_image-' . time() . '.' . $logoImage->getClientOriginalExtension();
        $aboutImageFileName = 'about_image-' . time() . '.' . $aboutImage->getClientOriginalExtension();

        $userBusinessProfileRequest['business_profile']['main_page_image'] = CustomUtils::uploadProfileImage('/' . $slug, $mainPageImage, $mainPageImageFileName);
        $userBusinessProfileRequest['business_profile']['logo_image'] = CustomUtils::uploadProfileImage('/' . $slug, $logoImage, $logoImageFileName);
        $userBusinessProfileRequest['business_profile']['about_image'] = CustomUtils::uploadProfileImage('/' . $slug, $aboutImage, $aboutImageFileName);
        return $userBusinessProfileRequest;
    }

}
