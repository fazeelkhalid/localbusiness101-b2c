<?php

namespace App\Http\Requests\PhoneNumber;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VerifyPhoneNumberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from' => 'required|regex:/^\+1\d{10}$/',
            'to' => 'required|regex:/^\+1\d{10}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'from.required' => 'Calling from number is required.',
            'from.regex' => 'Calling from must be a valid US number starting with +1 and followed by 10 digits.',
            'to.required' => 'Calling to is required.',
            'to.regex' => 'Calling to must be a valid US number starting with +1 and followed by 10 digits.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
