<?php

namespace App\Http\Requests\UserBusinessProfile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserBusinessProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'business_profile.title' => 'nullable|string|max:255',
            'business_profile.description' => 'nullable|string',
            'business_profile.short_intro' => 'nullable|string|max:255',
            'business_profile.keywords' => 'nullable|string|max:255',
            'business_profile.tab_title' => 'nullable|string|max:255',
            'business_profile.font_style' => 'nullable|string|max:255',
            'business_profile.heading_color' => 'nullable|string|max:7',
            'business_profile.heading_size' => 'nullable|string|max:10',
            'business_profile.acquirer.name' => 'nullable|string|max:255',
            'business_profile.acquirer.key' => 'nullable|string|max:255',
            'business_profile.business_contact_details.*.email' => 'nullable|email|max:255',
            'business_profile.business_contact_details.*.phone' => 'nullable|string|max:20',
            'business_profile.business_contact_details.*.address' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'business_profile.description.nullable' => 'The business profile description is nullable.',
            'business_profile.short_intro.nullable' => 'The business profile short intro is nullable.',
            'business_profile.keywords.nullable' => 'The business profile keywords are nullable.',
            'business_profile.tab_title.nullable' => 'The business profile tab title is nullable.',
            'business_profile.font_style.nullable' => 'The business profile font style is nullable.',
            'business_profile.heading_color.nullable' => 'The business profile heading color is nullable.',
            'business_profile.heading_size.nullable' => 'The business profile heading size is nullable.',
            'business_profile.business_contact_details.*.email.nullable' => 'The business contact email is nullable.',
            'business_profile.business_contact_details.*.phone.nullable' => 'The business contact phone is nullable.',
            'business_profile.business_contact_details.*.address.nullable' => 'The business contact address is nullable.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
