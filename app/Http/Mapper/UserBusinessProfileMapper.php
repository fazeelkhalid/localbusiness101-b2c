<?php

namespace App\Http\Mapper;

use App\Http\Requests\UserBusinessProfile\CreateUserBusinessProfileRequest;


class UserBusinessProfileMapper
{

    public static function mapUserBusinessProfileRequestToUserBusinessProfileResponse($userBusinessProfileRequest, $acquirer, $businessProfile)
    {
        return [
            'user' => [
                'name' => $userBusinessProfileRequest["user"]['name'],
                'email' => $userBusinessProfileRequest["user"]['email'],
                'password' => $userBusinessProfileRequest["user"]['password'],
            ],
            'acquirer' => [
                'name' => $acquirer->name,
                'key' => $acquirer->key
            ],
            'business_profiles_key' => $businessProfile->business_profiles_key,
            'title' => $userBusinessProfileRequest["business_profile"]['title'],
            'description' => $userBusinessProfileRequest["business_profile"]['description'],
            'short_intro' => $userBusinessProfileRequest["business_profile"]['short_intro'],
            'keywords' => $userBusinessProfileRequest["business_profile"]['keywords'],
            'tab_title' => $userBusinessProfileRequest["business_profile"]['tab_title'],
            'font_style' => $userBusinessProfileRequest["business_profile"]['font_style'],
            'heading_color' => $userBusinessProfileRequest["business_profile"]['heading_color'],
            'heading_size' => $userBusinessProfileRequest["business_profile"]['heading_size'],
            'business_contact_details' => array_map(function ($contact) {
                return [
                    'email' => $contact['email'],
                    'phone' => $contact['phone'],
                    'address' => $contact['address'],
                ];
            }, $userBusinessProfileRequest["business_profile"]['business_contact_details']),

        ];
    }

    public static function mapUserBusinessProfileToUpdateUserBusinessProfileResponse($userBusinessProfileRequest)
    {
        return [
            'acquirer' => [
                'name' => $userBusinessProfileRequest->user->acquirer->name,
                'key' => $userBusinessProfileRequest->user->acquirer->key
            ],
            'business_profiles_key' => $userBusinessProfileRequest->business_profiles_key,
            'title' => $userBusinessProfileRequest->title,
            'description' => $userBusinessProfileRequest->description,
            'short_intro' => $userBusinessProfileRequest->short_intro,
            'keywords' => $userBusinessProfileRequest->keywords,
            'tab_title' => $userBusinessProfileRequest->tab_title,
            'font_style' => $userBusinessProfileRequest->font_style,
            'heading_color' => $userBusinessProfileRequest->heading_color,
            'heading_size' => $userBusinessProfileRequest->heading_size,
            'business_contact_details' => array_map(function ($contact) {
                return [
                    'email' => $contact['business_email'],
                    'phone' => $contact['business_phone'],
                    'address' => $contact['business_address'],
                ];
            }, $userBusinessProfileRequest->contactDetails->toArray()),
        ];
    }


    public static function mapUserBusinessProfileToGetUserBusinessProfileResponse($userBusinessProfileRequest)
    {
        return [
            'user' =>[
                "name"=>$userBusinessProfileRequest->user->name,
                "email"=>$userBusinessProfileRequest->user->email,
            ],
            'acquirer' => [
                'name' => $userBusinessProfileRequest->user->acquirer->name,
                'key' => $userBusinessProfileRequest->user->acquirer->key
            ],
            'business_profiles_key' => $userBusinessProfileRequest->business_profiles_key,
            'title' => $userBusinessProfileRequest->title,
            'description' => $userBusinessProfileRequest->description,
            'short_intro' => $userBusinessProfileRequest->short_intro,
            'keywords' => $userBusinessProfileRequest->keywords,
            'tab_title' => $userBusinessProfileRequest->tab_title,
            'font_style' => $userBusinessProfileRequest->font_style,
            'heading_color' => $userBusinessProfileRequest->heading_color,
            'heading_size' => $userBusinessProfileRequest->heading_size,
            'business_contact_details' => array_map(function ($contact) {
                return [
                    'email' => $contact['business_email'],
                    'phone' => $contact['business_phone'],
                    'address' => $contact['business_address'],
                ];
            }, $userBusinessProfileRequest->contactDetails->toArray()),
            'reviews' => array_map(function ($review) {
                return [
                    "id"=>$review["id"],
                    "email"=>$review["email"],
                    "review"=>$review["review"]??"",
                    "rating"=>$review["rating"]??0
                ];
            },$userBusinessProfileRequest->ratings->toArray()),
            'avg_rating' => $userBusinessProfileRequest->ratings->avg("rating")??0,
        ];
    }

    public static function mapDBStatetoReponse($country, $browser, $devices, $perDayUserCount)
    {
        return [
            'country' => $country,
            'browser' => $browser,
            'device_type' => $devices,
            'per_day_user_count' => $perDayUserCount
        ];

    }

}
