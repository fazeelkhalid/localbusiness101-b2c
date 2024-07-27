<?php

namespace App\Http\Requests\UserBusinessProfile;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'business_profiles_key' => 'nullable|string',
            'per_page' => 'integer|min:1|max:100',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

}
