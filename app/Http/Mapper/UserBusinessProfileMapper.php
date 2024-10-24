<?php

namespace App\Http\Mapper;

use App\Http\Requests\UserBusinessProfile\CreateUserBusinessProfileRequest;


class UserBusinessProfileMapper
{

    public static function mapCreateUserBusinessProfileRequestToUserBusinessProfileResponse($userBusinessProfileRequest, $acquirer, $businessProfile, $category)
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
            'category' => $category->category_name,
            'slug'=>$userBusinessProfileRequest["business_profile"]['slug'],
            'business_profile_url'=>env('FRONTEND_URL').'/business-profile/'.$userBusinessProfileRequest["business_profile"]['slug'],
            'card_image_url'=>$userBusinessProfileRequest["business_profile"]['card_image'],
            'main_page_image_url'=>$userBusinessProfileRequest["business_profile"]['main_page_image'],
            'website'=>$userBusinessProfileRequest["business_profile"]['website'],
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
            'business_services' => array_map(function ($service) {
                return [
                    'title' => $service['title'],
                    'description' => $service['description'],
                ];
            }, $userBusinessProfileRequest["business_profile"]['services']),

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
            'slug'=>$userBusinessProfileRequest->slug,
            'business_profile_url'=>env('FRONTEND_URL').'/business-profile/'.$userBusinessProfileRequest->slug,
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
        $avgRating = $userBusinessProfileRequest->ratings->avg("rating") ?? 0;
        $avgRating = $avgRating != 0 ? number_format($avgRating, 1) : $avgRating;
        return [
            'acquirer' => [
                'name' => $userBusinessProfileRequest->user->acquirer->name,
                'key' => $userBusinessProfileRequest->user->acquirer->key
            ],
            'category'=>$userBusinessProfileRequest->category->category_name,
            'slug'=>$userBusinessProfileRequest->slug,
            'business_profile_url'=>env('FRONTEND_URL').'/business-profile/'.$userBusinessProfileRequest->slug,
            'card_image_url'=>$userBusinessProfileRequest->card_image_url,
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
            'slide_images' => array_map(function ($slideImage) {
                return $slideImage['image_url'];
            }, $userBusinessProfileRequest->slideImages->toArray()),
            'reviews' => array_map(function ($review) {
                return [
                    "id" => $review["id"],
                    "email" => $review["email"],
                    "review" => $review["review"] ?? "",
                    "rating" => $review["rating"] ?? 0
                ];
            }, array_slice($userBusinessProfileRequest->ratings->toArray(), 0, 10)),
            'avg_rating' => $avgRating
        ];
    }


    public static function mapUserBusinessProfileListToGetUserBusinessProfileListResponse($userBusinessProfileRequest)
    {
        $avgRating = $userBusinessProfileRequest->ratings->avg("rating") ?? 0;
        $avgRating = $avgRating != 0 ? number_format($avgRating, 1) : $avgRating;
        return [
            'category'=>$userBusinessProfileRequest->category->category_name,
            'slug'=>$userBusinessProfileRequest->slug,
            'business_profile_url'=>env('FRONTEND_URL').'/business-profile/'.$userBusinessProfileRequest->slug,
            'card_image_url'=>$userBusinessProfileRequest->card_image_url,
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
            'slide_images' => array_map(function ($slideImage) {
                return $slideImage['image_url'];
            }, $userBusinessProfileRequest->slideImages->toArray()),
            'reviews' => array_map(function ($review) {
                return [
                    "id" => $review["id"],
                    "email" => $review["email"],
                    "review" => $review["review"] ?? "",
                    "rating" => $review["rating"] ?? 0
                ];
            }, array_slice($userBusinessProfileRequest->ratings->toArray(), 0, 10)),
            'avg_rating' => $avgRating
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
