<?php

namespace App\Http\Mapper;


use App\Http\Utils\CustomUtils;

class DigitalCardMapper
{

    public static function mapCreateDigitalCardRequestToResponse($digitalCard)
    {
        $digitalCardData = [
            'header_image_url' => $digitalCard['header_image_url'],
            'profile_image_url' => $digitalCard['profile_image_url'],
            'owner_name' => $digitalCard['owner_name'],
            'designation' => $digitalCard['designation'],
            'website_link' => $digitalCard['website_link'],
            'email' => $digitalCard['email'],
            'facebook' => $digitalCard['facebook'],
            'instagram' => $digitalCard['instagram'],
            'phone_number' => $digitalCard['phone_number'],
            'gmb_links' => $digitalCard['gmb_links'],
            'about_business' => $digitalCard['about_business'],
            'office_address' => $digitalCard['office_address'],
            'primary_color' => $digitalCard['primary_color'],
            'secondary_color' => $digitalCard['secondary_color'],
            'slug' => $digitalCard['slug'],
            'business_name' => $digitalCard['business_name'],
            'office_hours' => array_map(function ($day, $hours) {
                return [
                    'day_of_week' => $day,
                    'open_time' => $hours['open_time'] ?? null,
                    'close_time' => $hours['close_time'] ?? null,
                    'is_off' => $hours['is_off'] ?? false,
                ];
            }, array_keys($digitalCard['office_hours']), $digitalCard['office_hours']),
            'payment_methods' => isset($digitalCard['payment_methods'])
                ? array_map(function ($payment) {
                    return [
                        'method_name' => $payment['method_name'],
                        'description' => $payment['description'] ?? null,
                        'payment_identifier' => $payment['payment_identifier'] ?? null,
                        'qr_code_image_url' => $payment['qr_code_image_url'] ?? null,
                    ];
                }, $digitalCard['payment_methods'])
                : [],
        ];

        return $digitalCardData;
    }

    public static function mapDigitalCardDBToResponse($digitalCard)
    {
        $mappedData = [
            'business_name' => $digitalCard->business_name,
            'owner_name' => $digitalCard->owner_name,
            'designation' => $digitalCard->designation,
            'slug' => $digitalCard->slug,
            'images' => [
                'header_image_url' => $digitalCard->header_image_url,
                'profile_image_url' => $digitalCard->profile_image_url,
            ],
            'colors' => [
                'primary' => $digitalCard->primary_color,
                'secondary' => $digitalCard->secondary_color,
            ],
            'contact' => [
                'contact_us_url' => $digitalCard->contact_us_url,
                'email' => $digitalCard->email,
                'phone' => $digitalCard->phone_number,
                'address' => $digitalCard->office_address,
            ],
            'social' => [
                'website' => $digitalCard->website_link,
                'facebook' => $digitalCard->facebook,
                'instagram' => $digitalCard->instagram,
                'gmb' => $digitalCard->gmb_links,
            ],
            'about' => $digitalCard->about_business,
        ];

        $mappedData['office_hours'] = [];
        if (isset($digitalCard->officeHours) && !empty($digitalCard->officeHours)) {
            foreach ($digitalCard->officeHours as $hour) {
                $mappedData['office_hours'][$hour->day_of_week] = [
                    'is_off' => (bool) $hour->is_off,
                    'open_time' => $hour->is_off ? null : CustomUtils::formatTimeToHHMM($hour->open_time),
                    'close_time' => $hour->is_off ? null : CustomUtils::formatTimeToHHMM($hour->close_time),
                ];
            }
        }

        // Map payment methods
        $mappedData['payment_methods'] = [];
        if (isset($digitalCard->paymentMethods) && !empty($digitalCard->paymentMethods)) {
            foreach ($digitalCard->paymentMethods as $method) {
                $mappedData['payment_methods'][] = [
                    'name' => $method->method_name,
                    'description' => $method->description,
                    'identifier' => $method->payment_identifier,
                    'qr_code_image_url' => $method->qr_code_image_url,
                ];
            }
        }

        return $mappedData;
    }

}
