<?php

namespace App\Http\Requests\Review;

use App\Http\Services\AcquirerService;
use App\Models\Rating;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreReviewRequest extends FormRequest
{

    protected AcquirerService $acquirerService;

    public function __construct(AcquirerService $acquirerService)
    {
        parent::__construct();
        $this->acquirerService = $acquirerService;
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $businessProfile = $this->acquirerService->get("businessProfile");
                    if ($businessProfile && Rating::where('business_profile_id', $businessProfile->id)->where('email', $value)->exists()) {
                        $fail('You have already submitted a review for '. $businessProfile->title);
                    }
                },
                ],
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
