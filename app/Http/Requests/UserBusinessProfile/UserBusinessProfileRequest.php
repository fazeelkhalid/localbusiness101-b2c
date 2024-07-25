<?php

namespace App\Http\Requests\UserBusinessProfile;

use App\Http\Rules\UniqueUserEmail;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserBusinessProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user.name' => 'required|string|max:255',
            'user.email' => ['required', 'string', 'email', 'max:255', new UniqueUserEmail()],
            'user.password' => 'required|string|min:8',
            'acquirer_name' => 'required|string',
            'business_profile.title' => 'required|string|max:255',
            'business_profile.description' => 'required|string',
            'business_profile.short_intro' => 'required|string',
            'business_profile.keywords' => 'required|string',
            'business_profile.tab_title' => 'required|string|max:255',
            'business_profile.font_style' => 'required|string|max:255',
            'business_profile.heading_color' => 'required|string|max:255',
            'business_profile.heading_size' => 'required|string|max:255',
            'business_profile.business_contact_details.*.email' => 'required|email',
            'business_profile.business_contact_details.*.phone' => 'required|string|max:15',
            'business_profile.business_contact_details.*.address' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'user.name.required' => 'The user name is required.',
            'user.email.required' => 'The user email is required.',
            'user.email.unique' => 'The user email must be unique.',
            'user.password.required' => 'The user password is required.',
            'user.password.min' => 'The user password must be at least 8 characters.',
            'business_profile.business_profiles_key.required' => 'The business profile key is required.',
            'business_profile.title.required' => 'The business profile title is required.',
            'business_profile.description.required' => 'The business profile description is required.',
            'business_profile.short_intro.required' => 'The business profile short intro is required.',
            'business_profile.keywords.required' => 'The business profile keywords are required.',
            'business_profile.tab_title.required' => 'The business profile tab title is required.',
            'business_profile.font_style.required' => 'The business profile font style is required.',
            'business_profile.heading_color.required' => 'The business profile heading color is required.',
            'business_profile.heading_size.required' => 'The business profile heading size is required.',
            'business_profile.business_contact_details.*.email.required' => 'The business contact email is required.',
            'business_profile.business_contact_details.*.phone.required' => 'The business contact phone is required.',
            'business_profile.business_contact_details.*.address.required' => 'The business contact address is required.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
