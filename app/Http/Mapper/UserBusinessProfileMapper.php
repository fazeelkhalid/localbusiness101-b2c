<?php

namespace App\Http\Mapper;


use App\Http\Utils\CustomUtils;

class UserBusinessProfileMapper
{

    public static function mapCreateUserBusinessProfileRequestToUserBusinessProfileResponse($user, $userBusinessProfileRequest, $acquirer, $businessProfile, $category)
    {
        return [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
//                'password' => $user->password,
            ],
            'acquirer' => [
                'name' => $acquirer->name,
                'key' => $acquirer->key
            ],
            'category' => $category->category_name,
            'slug' => $userBusinessProfileRequest["business_profile"]['slug'],
            'business_profile_url' => env('FRONTEND_URL') . '/business-profile/' . $userBusinessProfileRequest["business_profile"]['slug'],
            'card_image_url' => $userBusinessProfileRequest["business_profile"]['card_image'],
            'main_page_image_url' => $userBusinessProfileRequest["business_profile"]['main_page_image'] ?? "",
            'logo_image_url' => $userBusinessProfileRequest["business_profile"]['logo_image'] ?? "",
            'about_image_url' => $userBusinessProfileRequest["business_profile"]['about_image'] ?? "",
            'website' => $userBusinessProfileRequest["business_profile"]['website'] ?? "",
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
                    'map_location' => $contact['map_location_url'] ?? "",
                ];
            }, $userBusinessProfileRequest["business_profile"]['business_contact_details']),
            'business_services' => isset($userBusinessProfileRequest["business_profile"]["services"])
                ? array_map(function ($service) {
                    return [
                        'title' => $service['title'],
                        'description' => $service['description'],
                    ];
                }, $userBusinessProfileRequest["business_profile"]["services"])
                : [],
        ];
    }

    public static function mapUpdateUserBusinessProfileToUserBusinessProfileVm($userBusinessProfileRequest, $category)
    {
        return [
            'category' => $category->category_name,
            'slug' => $userBusinessProfileRequest["business_profile"]['slug'],
            'business_profile_url' => env('FRONTEND_URL') . '/business-profile/' . $userBusinessProfileRequest["business_profile"]['slug'],
            'card_image_url' => $userBusinessProfileRequest["business_profile"]['card_image'],
            'main_page_image_url' => $userBusinessProfileRequest["business_profile"]['main_page_image'] ?? "",
            'logo_image_url' => $userBusinessProfileRequest["business_profile"]['logo_image'] ?? "",
            'about_image_url' => $userBusinessProfileRequest["business_profile"]['about_image'] ?? "",
            'website' => $userBusinessProfileRequest["business_profile"]['website'] ?? "",
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
                    'map_location' => $contact['map_location_url'] ?? "",
                ];
            }, $userBusinessProfileRequest["business_profile"]['business_contact_details']),
            'business_services' => isset($userBusinessProfileRequest["business_profile"]["services"])
                ? array_map(function ($service) {
                    return [
                        'title' => $service['title'],
                        'description' => $service['description'],
                    ];
                }, $userBusinessProfileRequest["business_profile"]["services"])
                : [],
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
            'slug' => $userBusinessProfileRequest->slug,
            'business_profile_url' => env('FRONTEND_URL') . '/business-profile/' . $userBusinessProfileRequest->slug,
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


    public static function mapUserBusinessProfileToGetUserBusinessProfileResponse($userBusinessProfileRequest, $userAllProfilesDomain)
    {
        $avgRating = $userBusinessProfileRequest->ratings->avg("rating") ?? 0;
        $avgRating = $avgRating != 0 ? number_format($avgRating, 1) : $avgRating;

        $otherProfiles = array_map(function ($usefulLink) {
            return [
                'link' => $usefulLink['links'],
                'title' => $usefulLink['tags_title'],
            ];
        }, $userBusinessProfileRequest->usefulLinks->toArray());

        foreach ($userAllProfilesDomain as $profile) {
            $otherProfiles[] = [
                'link' => env('FRONTEND_URL') . '/business-profile/' . $profile->slug,
                'title' => $profile->slug,
            ];
        }

        $maxLinksPerColumn = CustomUtils::calculateMaxLinksPerColumn($userBusinessProfileRequest->usefulLinks->toArray());

        return [
            'acquirer' => [
                'name' => $userBusinessProfileRequest->user->acquirer->name,
                'key' => $userBusinessProfileRequest->user->acquirer->key
            ],
            'category' => $userBusinessProfileRequest->category->category_name,
            'slug' => $userBusinessProfileRequest->slug,
            'business_profile_url' => env('FRONTEND_URL') . '/business-profile/' . $userBusinessProfileRequest->slug,
            'card_image_url' => $userBusinessProfileRequest->card_image_url,
            'main_page_image_url' => $userBusinessProfileRequest->main_page_image_url ?? "",
            'logo_image_url' => $userBusinessProfileRequest->logo_image_url ?? "",
            'about_image_url' => $userBusinessProfileRequest->about_image_url ?? "",
            'theme' => $userBusinessProfileRequest->theme ?? "",
            'website' => $userBusinessProfileRequest->website ?? "",
            'business_profiles_key' => $userBusinessProfileRequest->business_profiles_key,
            'title' => $userBusinessProfileRequest->title,
            'description' => $userBusinessProfileRequest->description,
            'short_intro' => $userBusinessProfileRequest->short_intro,
            'keywords' => $userBusinessProfileRequest->keywords,
            'tab_title' => $userBusinessProfileRequest->tab_title,
            'font_style' => $userBusinessProfileRequest->font_style,
            'heading_color' => $userBusinessProfileRequest->heading_color,
            'heading_size' => $userBusinessProfileRequest->heading_size,
            'about_cta_button_text' => $userBusinessProfileRequest->about_cta_button_text,
            'google_ads_tracking_code' => $userBusinessProfileRequest->google_ads_tracking_code,
            'business_contact_details' => array_map(function ($contact) {
                return [
                    'email' => $contact['business_email'],
                    'phone' => $contact['business_phone'],
                    'address' => $contact['business_address'],
                    'map_location' => $contact['map_location_url'] ?? "",
                ];
            }, $userBusinessProfileRequest->contactDetails->toArray()),
            'slide_images' => array_map(function ($slideImage) {
                return $slideImage['image_url'];
            }, $userBusinessProfileRequest->slideImages->toArray()),
            'gallery_images' => array_map(function ($galleryImage) {
                return $galleryImage['image_url'];
            }, $userBusinessProfileRequest->galleryImages->toArray()),
            'usefull_link' => [
                'other_profile' => $otherProfiles,
                'max_links_per_column' => $maxLinksPerColumn,
            ],
            'reviews' => array_map(function ($review) {
                return [
                    "id" => $review["id"],
                    "email" => $review["email"],
                    "review" => $review["review"] ?? "",
                    "rating" => $review["rating"] ?? 0
                ];
            }, array_slice($userBusinessProfileRequest->ratings->toArray(), 0, 10)),
            'avg_rating' => $avgRating,
            'business_services' => array_map(function ($contact) {
                return [
                    'title' => $contact['name'],
                    'description' => $contact['description'],
                ];
            }, $userBusinessProfileRequest->services->toArray()),
        ];
    }


    public static function mapUserBusinessProfileListToGetUserBusinessProfileListResponse($userBusinessProfileRequest)
    {
        $avgRating = $userBusinessProfileRequest->ratings->avg("rating") ?? 0;
        $avgRating = $avgRating != 0 ? number_format($avgRating, 1) : $avgRating;
        return [
//            'category' => $userBusinessProfileRequest->category->category_name,
            'slug' => $userBusinessProfileRequest->slug,
            'business_profile_url' => env('FRONTEND_URL') . '/business-profile/' . $userBusinessProfileRequest->slug,
            'card_image_url' => $userBusinessProfileRequest->card_image_url,
//            'main_page_image_url' => $userBusinessProfileRequest->main_page_image_url ?? "",
//            'logo_image_url' => $userBusinessProfileRequest->logo_image_url ?? "",
//            'about_image_url' => $userBusinessProfileRequest->about_image_url ?? "",
//            'theme' => $userBusinessProfileRequest->theme ?? "",
//            'website' => $userBusinessProfileRequest->website ?? "",
//            'business_profiles_key' => $userBusinessProfileRequest->business_profiles_key,
            'title' => $userBusinessProfileRequest->title,
//            'description' => $userBusinessProfileRequest->description,
//            'short_intro' => $userBusinessProfileRequest->short_intro,
//            'keywords' => $userBusinessProfileRequest->keywords,
//            'tab_title' => $userBusinessProfileRequest->tab_title,
//            'font_style' => $userBusinessProfileRequest->font_style,
//            'heading_color' => $userBusinessProfileRequest->heading_color,
//            'heading_size' => $userBusinessProfileRequest->heading_size,
            'business_contact_details' => array_map(function ($contact) {
                return [
//                    'email' => $contact['business_email'],
//                    'phone' => $contact['business_phone'],
                    'address' => $contact['business_address'],
                    'map_location' => $contact['map_location_url'] ?? "",
                ];
            }, $userBusinessProfileRequest->contactDetails->toArray()),
//            'business_services' => array_map(function ($contact) {
//                return [
//                    'title' => $contact['name'],
//                    'description' => $contact['description'],
//                ];
//            }, $userBusinessProfileRequest->services->toArray()),

//            'slide_images' => array_map(function ($slideImage) {
//                return $slideImage['image_url'];
//            }, $userBusinessProfileRequest->slideImages->toArray()),
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


    public static function mapAnalyticsReportToCreateAnalyticResponse($analyticsReport)
    {
        return [
            "days" => $analyticsReport["days"],
            "total_click" => $analyticsReport["total_click"],
            "total_impressions" => $analyticsReport["total_impressions"],
            "average_ctr" => $analyticsReport["average_ctr"],
            "average_bounce_rate" => $analyticsReport["average_bounce_rate"],
            "average_time_on_page" => $analyticsReport["average_time_on_page"],
            "top_keyword" => $analyticsReport["top_keyword"],
            "top_area" => $analyticsReport["top_area"],
            "urls" => json_decode($analyticsReport["urls"], true),
            "areas" => json_decode($analyticsReport["areas"], true),
            "top_keywords" => json_decode($analyticsReport["top_keywords"], true),
            "click_by_area_graph_url" => $analyticsReport["click_by_area_graph_url"],
            "search_keyword_counts_graph_url" => $analyticsReport["search_keyword_counts_graph_url"],
            "ctr_graph_url" => $analyticsReport["ctr_graph_url"],
            "average_google_search_ranking_graph_url" => $analyticsReport["average_google_search_ranking_graph_url"],
            "website_visitors_by_url_graph_url" => $analyticsReport["website_visitors_by_url_graph_url"]
        ];
    }


}
