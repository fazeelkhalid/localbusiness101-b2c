<?php

namespace App\Http\Requests\CallLog;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CallLogFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => 'integer|min:1|max:100',
            'call_status' => 'nullable|string|in:completed,failed,ringing,busy,no-answer',
            'receiver_number' => 'nullable|string',
            'caller_number' => 'nullable|string',
            'call_direction' => 'nullable|string|in:inbound,outbound',
            'sort_by_talk_time' => 'nullable|string|in:asc,desc',
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',

            'days' => 'nullable|integer|min:1|max:90',
            'talk_time_less_than' => 'nullable|integer|min:0',
            'user_name' => 'nullable|string',
            'group_by' => 'nullable|in:daily,monthly,yearly',
        ];
    }

    public function messages(): array
    {
        return [
            'call_status.in' => 'Invalid call status. Allowed: completed, failed, ringing, busy, no-answer.',
            'call_direction.in' => 'Invalid direction. Allowed values: inbound or outbound.',
            'sort_by_talk_time.in' => 'Sort order must be either asc or desc.',
            'start_date.before_or_equal' => 'The start date must be before or equal to the end date.',
            'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
            'group_by.in' => 'Group by must be one of: daily, monthly, yearly.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
