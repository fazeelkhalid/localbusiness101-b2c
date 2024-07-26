<?php

namespace App\Http\Requests\UserBusinessProfile;

use Illuminate\Foundation\Http\FormRequest;

class BusinessProfileFilterRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all users to access this request (modify as needed)
    }

    public function rules()
    {
        return [
            'user_name' => 'nullable|string',
            'user_email' => 'nullable|email',
            'title' => 'nullable|string',
            'keywords' => 'nullable|string',
            'business_profiles_key' => 'nullable|string', // Add validation for business_profiles_key
        ];
    }
}
