<?php

namespace App\Http\Requests\UserBusinessProfile;

use App\Http\Rules\UniqueUserEmail;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateUserBusinessProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'user.name' => 'required|string|max:255',
            'user.email' => ['required', 'string', 'email', 'max:255', new UniqueUserEmail()],
            'user.password' => 'required|string|min:8',
            'acquirer_name' => 'required|string',
            'business_profile.card_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'business_profile.category' => 'required|string|max:255|exists:business_categories,category_name',
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
//            'business_profile.business_contact_details.*.map_location_url' => 'required|string',
            'business_profile.slide_images.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
//            'business_profile.main_page_image' => 'required|image|mimes:jpg,jpeg,png|max:2048|dimensions:min_width=1500,min_height=900',
//            'business_profile.logo_image' => 'required|image|mimes:jpg,jpeg,png|max:1024',
            'business_profile.theme' => 'required|string|in:basic,advance',
//            'business_profile.services.*.description' => 'required|string',
//            'business_profile.services.*.title' => 'required|string|max:255',
//            'business_profile.about_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ];

        if ($this->input('business_profile.theme') === 'advance') {
            $rules = array_merge($rules, [
                'business_profile.website' => 'required|string|url',
                'business_profile.business_contact_details.*.map_location_url' => 'required|string',
                'business_profile.main_page_image' => 'required|image|mimes:jpg,jpeg,png|max:2048|dimensions:min_width=1500,min_height=900',
                'business_profile.logo_image' => 'required|image|mimes:jpg,jpeg,png|max:1024',
                'business_profile.about_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'business_profile.services.*.title' => 'required|string|max:255',
                'business_profile.services.*.description' => 'required|string',
            ]);
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'user.name.required' => 'The user name is required.',
            'user.email.required' => 'The user email is required.',
            'user.email.unique' => 'The user email must be unique.',
            'user.password.required' => 'The user password is required.',
            'user.password.min' => 'The user password must be at least 8 characters.',
            'business_profile.card_image.required' => 'The business profile card image is required.',
            'business_profile.card_image.image' => 'The file must be an image.',
            'business_profile.card_image.mimes' => 'The image must be a file of type: jpg, jpeg, png.',
            'business_profile.card_image.max' => 'The image may not be greater than 2MB.',
            'business_profile.category.required' => 'The category is required.',
            'business_profile.category.exists' => 'Invalid category selected.',
            'business_profile.theme.required' => 'The business profile theme is required.',
            'business_profile.theme.in' => 'The theme must be either basic or advance.',
            'business_profile.title.required' => 'The business profile title is required.',
            'business_profile.description.required' => 'The business profile description is required.',
            'business_profile.short_intro.required' => 'The business profile short intro is required.',
            'business_profile.keywords.required' => 'The business profile keywords are required.',
            'business_profile.tab_title.required' => 'The business profile tab title is required.',
            'business_profile.font_style.required' => 'The business profile font style is required.',
            'business_profile.heading_color.required' => 'The business profile heading color is required.',
            'business_profile.heading_size.required' => 'The business profile heading size is required.',
            'business_profile.website.required' => 'The website URL is required for advanced themes.',
            'business_profile.business_contact_details.*.map_location_url.required' => 'The map location URL is required for advanced themes.',
            'business_profile.main_page_image.required' => 'The main page image is required for advanced themes.',
            'business_profile.main_page_image.image' => 'The main page image must be an image file.',
            'business_profile.main_page_image.mimes' => 'The main page image must be a file of type: jpg, jpeg, png.',
            'business_profile.logo_image.required' => 'The logo image is required for advanced themes.',
            'business_profile.about_image.required' => 'The about image is required for advanced themes.',
            'business_profile.services.*.title.required' => 'The service title is required for advanced themes.',
            'business_profile.services.*.description.required' => 'The service description is required for advanced themes.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
