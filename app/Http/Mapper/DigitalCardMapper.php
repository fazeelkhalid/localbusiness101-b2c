<?php

namespace App\Http\Mapper;


use App\Http\Utils\CustomUtils;

class DigitalCardMapper
{

    public static function mapCreateDigitalCardRequestToResponse($digitalCard)
    {
        // Mapping data for DigitalCard model
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
        ];

        // Mapping data for Office Hours model (flattening the structure)
        $officeHoursData = [];
        foreach ($digitalCard['office_hours'] as $day => $hours) {
            $officeHoursData[] = [
                'day_of_week' => $day,
                'open_time' => $hours['open_time'] ?? null,
                'close_time' => $hours['close_time'] ?? null,
                'is_off' => $hours['is_off'] ?? false,
            ];
        }

        // Mapping data for Payment Methods model
        $paymentMethodsData = [];
        foreach ($digitalCard['payment_methods'] as $payment) {
            $paymentMethodsData[] = [
                'method_name' => $payment['method_name'],
                'description' => $payment['description'] ?? null,
                'payment_identifier' => $payment['payment_identifier'] ?? null,
                'qr_code_image_url' => $payment['qr_code_image_url'] ?? null,
            ];
        }

        return [
            'digitalCardData' => $digitalCardData,
            'officeHoursData' => $officeHoursData,
            'paymentMethodsData' => $paymentMethodsData,
        ];
    }

}
