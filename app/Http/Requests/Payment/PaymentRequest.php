<?php

namespace App\Http\Requests\Payment;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'method' => 'required|string|in:paypro,stripe',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:1000',
            'currency' => 'required|string|size:3',
            'client_email' => 'required|email|max:255',
            'client_name' => 'required|string|max:255',
            'client_phone_number' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'method.required' => 'The payment method is required.',
            'method.in' => 'The payment method must be either paypro or stripe.',
            'amount.required' => 'The payment amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be greater than zero.',
            'description.required' => 'The description is required.',
            'description.max' => 'The description cannot exceed 1000 characters.',
            'currency.required' => 'The currency is required.',
            'currency.size' => 'The currency must be a valid 3-character ISO code.',
            'client_email.required' => 'The client email is required.',
            'client_email.email' => 'The client email must be a valid email.',
            'client_email.max' => 'The client email cannot exceed 255 characters.',
            'client_name.required' => 'The client name is required.',
            'client_name.max' => 'The client name cannot exceed 255 characters.',
            'client_phone_number.max' => 'The client phone number cannot exceed 20 characters.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
