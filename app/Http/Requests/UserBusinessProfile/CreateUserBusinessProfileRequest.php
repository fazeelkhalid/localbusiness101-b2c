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
            'user_id' => 'nullable|exists:users,id',
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
            'business_profile.business_contact_details.*.email' => 'required|email|max:255',
            'business_profile.business_contact_details.*.phone' => 'required|string|max:15',
            'business_profile.business_contact_details.*.address' => 'required|string|max:255',
            'business_profile.slide_images.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'business_profile.theme' => 'required|string|in:basic,advance',
        ];

        if (empty($this->input('user_id'))) {
            $rules['user.name'] = 'required|string|max:255';
            $rules['user.email'] = ['required', 'string', 'email', 'max:255', new UniqueUserEmail()];
            $rules['user.password'] = 'required|string|min:8';
            $rules['acquirer_name'] = 'required|string';
        }

        if ($this->input('business_profile.theme') === 'advance') {
            $advancedRules = [
                'business_profile.website' => 'required|string|url',
                'business_profile.business_contact_details.*.map_location_url' => 'required|string|max:400',
                'business_profile.main_page_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'business_profile.logo_image' => 'required|image|mimes:jpg,jpeg,png|max:1024',
                'business_profile.about_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'business_profile.services.*.title' => 'required|string|max:255',
                'business_profile.services.*.description' => 'required|string',
                'business_profile.gallery_images' => 'required|array|min:1',
                'business_profile.gallery_images.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ];

            $rules = array_merge($rules, $advancedRules);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'The specified user does not exist.',
            'user.name.required' => 'The user name is required.',
            'user.email.required' => 'The user email is required.',
            'user.email.unique' => 'The user email must be unique.',
            'user.password.required' => 'The user password is required.',
            'user.password.min' => 'The user password must be at least 8 characters.',
            'acquirer_name.required' => 'The acquirer name is required.',
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
            'business_profile.main_page_image.required' => 'The home page image is required for advanced themes.',
            'business_profile.main_page_image.image' => 'The home page image must be an image file.',
            'business_profile.main_page_image.mimes' => 'The home page image must be a file of type: jpg, jpeg, png.',
            'business_profile.main_page_image.dimensions' => 'The home page image must be at least 1500x900 pixels.',
            'business_profile.logo_image.required' => 'The logo image is required for advanced themes.',
            'business_profile.about_image.required' => 'The about image is required for advanced themes.',
            'business_profile.services.*.title.required' => 'The service title is required for advanced themes.',
            'business_profile.services.*.description.required' => 'The service description is required for advanced themes.',
            'business_profile.gallery_images.required' => 'At least one gallery image is required.',
            'business_profile.gallery_images.array' => 'The gallery images must be provided as an array.',
            'business_profile.gallery_images.min' => 'You must upload at least five gallery image.',
            'business_profile.gallery_images.*.required' => 'Each gallery image is required.',
            'business_profile.gallery_images.*.image' => 'Each gallery image must be a valid image file.',
            'business_profile.gallery_images.*.mimes' => 'Each gallery image must be a file of type: jpg, jpeg, png.',
            'business_profile.gallery_images.*.max' => 'Each gallery image may not be greater than 2MB.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
