<?php

namespace App\Http\Requests\CallLog;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCallLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'twilio_recording_sid' => 'nullable|string|max:64|unique:call_logs,twilio_recording_sid',
        ];
    }

    public function messages(): array
    {
        return [
            'twilio_recording_sid.string' => 'Recording SID must be a valid string.',
            'twilio_recording_sid.max' => 'Recording SID must not exceed 64 characters.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
