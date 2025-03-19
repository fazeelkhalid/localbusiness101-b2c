<?php

namespace App\Http\Requests\DigitalCard;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateDigitalCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'header_image' => 'required|image|mimes:jpg,jpeg,png|max:1024',
            'profile_image' => 'required|image|mimes:jpg,jpeg,png|max:1024',
            'owner_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'website_link' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'phone_number' => 'nullable|string|max:20',
            'gmb_links' => 'nullable|string',
            'about_business' => 'required|string',
            'office_address' => 'nullable|string',
            'primary_color' => 'required|string|max:15',
            'secondary_color' => 'required|string|max:15',
            'business_name' => 'required|string|max:255',

            // Office Hours validation - nested structure
            'office_hours' => 'required|array',
            'office_hours.Monday' => 'required|array',
            'office_hours.Tuesday' => 'required|array',
            'office_hours.Wednesday' => 'required|array',
            'office_hours.Thursday' => 'required|array',
            'office_hours.Friday' => 'required|array',
            'office_hours.Saturday' => 'required|array',
            'office_hours.Sunday' => 'required|array',

            // For each day, validate open/close times or is_off
            'office_hours.*.open_time' => 'nullable|date_format:H:i',
            'office_hours.*.close_time' => 'nullable|date_format:H:i',
            'office_hours.*.is_off' => 'boolean',

            // Payment Methods validation
            'payment_methods' => 'nullable|array',
            'payment_methods.*.method_name' => 'required_with:payment_methods|string|max:255',
            'payment_methods.*.description' => 'required_with:payment_methods|string|max:255',
            'payment_methods.*.payment_identifier' => 'nullable|string|max:255',
            'payment_methods.*.qr_code_image' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate that closing time is after opening time for each day
            $officeHours = $this->input('office_hours');
            $paymentMethods = $this->input('payment_methods');
            $paymentFiles = $this->file('payment_methods');

            if (is_array($officeHours)) {
                foreach ($officeHours as $day => $hours) {
                    // Check if the day is marked as not off
                    if (isset($hours['is_off']) && $hours['is_off'] === false) {
                        // Validate that both open and close times are provided
                        if (empty($hours['open_time']) || empty($hours['close_time'])) {
                            $validator->errors()->add(
                                "office_hours.$day",
                                "Opening and closing times are required for $day when it is not marked as off."
                            );
                        } else {
                            // If both times are provided, validate that closing is after opening
                            $openTime = \DateTime::createFromFormat('H:i', $hours['open_time']);
                            $closeTime = \DateTime::createFromFormat('H:i', $hours['close_time']);

                            if ($openTime && $closeTime && $closeTime <= $openTime) {
                                $validator->errors()->add(
                                    "office_hours.$day.close_time",
                                    "The closing time must be after the opening time for $day."
                                );
                            }
                        }
                    }
                }
            }

            if (is_array($paymentMethods)) {

                foreach ($paymentMethods as $index => $payment) {
                    $hasQrCode = isset($paymentFiles[$index]['qr_code_image']) &&
                        $paymentFiles[$index]['qr_code_image']->isValid();
                    $hasIdentifier = !empty($payment['payment_identifier']);

                    if (!$hasIdentifier && !$hasQrCode) {
                        $validator->errors()->add(
                            "payment_methods.$index",
                            "Either payment_identifier or qr_code_image must be provided."
                        );
                    }

                    if ($hasIdentifier && $hasQrCode) {
                        $validator->errors()->add(
                            "payment_methods.$index",
                            "Only one of payment_identifier or qr_code_image should be provided, not both."
                        );
                    }
                }

            }
        });
    }

    public function messages(): array
    {
        return [
            'primary_color.regex' => 'The primary color must be a valid HEX color code (e.g., #FF5733).',
            'secondary_color.regex' => 'The secondary color must be a valid HEX color code (e.g., #33FF57).',
            'office_hours.required' => 'Office hours information is required.',
            'office_hours.*.open_time.date_format' => 'Opening time must be in 24-hour format (e.g., 10:00 or 22:00).',
            'office_hours.*.close_time.date_format' => 'Closing time must be in 24-hour format (e.g., 18:00 or 22:00).',
            'payment_methods.*.method_name.required_with' => 'Payment method name is required when payment methods are provided.',
            'payment_methods.*.description.required_with' => 'A description is required when a payment method is provided.',
            'payment_methods.*.payment_identifier.required_without' => 'Either Payment Identifier or QR Code Image must be provided.',
            'payment_methods.*.qr_code_image.required_without' => 'Either QR Code Image or Payment Identifier must be provided.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

}

