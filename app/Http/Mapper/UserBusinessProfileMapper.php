<?php

namespace App\Http\Mapper;

use App\Http\Requests\UserBusinessProfile\UserBusinessProfileRequest;



class UserBusinessProfileMapper{

    public static function mapUserBusinessProfileRequestToUserBusinessProfileResponse($userBusinessProfileRequest, $acquirer, $businessProfile){
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
            'business_profile' => [
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
            ],
        ];
    }
}
