<?php

namespace App\Http\Requests\UserBusinessProfile;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BusinessProfileAnalyticsReportRequest extends FormRequest
{
    public function rules()
    {
        return [
            'days' => 'required|integer|min:1',
            'total_click' => 'required|integer|min:0',
            'total_impressions' => 'required|integer|min:0',
            'average_ctr' => 'required|numeric|min:0',
            'average_time_on_page' => 'required|numeric|min:0',
            'average_bounce_rate' => 'required|numeric|min:0',
            'top_keyword' => 'required|string|max:255',
            'top_area' => 'required|string|max:255',
            'urls' => 'required|array',
            'urls.*' => 'required|string|url',
            'areas' => 'required|array',
            'areas.*' => 'required|string|max:255',
            'top_keywords' => 'required|array',
            'top_keywords.*' => 'required|string|max:255',
            'click_by_area_graph' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'search_keyword_counts_graph' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ctr_graph' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'average_google_search_ranking_graph' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website_visitors_by_url_graph' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'days.required' => 'The number of days is required.',
            'total_click.required' => 'The total clicks field is required.',
            'total_impressions.required' => 'The total impressions field is required.',
            'average_ctr.required' => 'The average click-through rate is required.',
            'average_bounce_rate.required' => 'The average bounce rate is required.',
            'average_time_on_page.required' => 'The average time on page is required.',
            'top_keyword.required' => 'The top keyword field is required.',
            'top_area.required' => 'The top area field is required.',
            'urls.required' => 'The URLs field is required.',
            'urls.*.url' => 'Each URL must be a valid URL.',
            'areas.required' => 'The areas field is required.',
            'top_keywords.required' => 'The top keywords field is required.',
            'click_by_area_graph.required' => 'The click by area graph image is required.',
            'click_by_area_graph.file' => 'The click by area graph must be a file.',
            'click_by_area_graph.mimes' => 'The click by area graph must be an image file (jpeg, png, jpg, gif, svg).',
            'click_by_area_graph.max' => 'The click by area graph must not exceed 2MB.',
            'search_keyword_counts_graph.required' => 'The search keyword counts graph image is required.',
            'search_keyword_counts_graph.file' => 'The search keyword counts graph must be a file.',
            'search_keyword_counts_graph.mimes' => 'The search keyword counts graph must be an image file (jpeg, png, jpg, gif, svg).',
            'search_keyword_counts_graph.max' => 'The search keyword counts graph must not exceed 2MB.',
            'ctr_graph.required' => 'The CTR graph image is required.',
            'ctr_graph.file' => 'The CTR graph must be a file.',
            'ctr_graph.mimes' => 'The CTR graph must be an image file (jpeg, png, jpg, gif, svg).',
            'ctr_graph.max' => 'The CTR graph must not exceed 2MB.',
            'average_google_search_ranking_graph.required' => 'The average Google search ranking graph image is required.',
            'average_google_search_ranking_graph.file' => 'The average Google search ranking graph must be a file.',
            'average_google_search_ranking_graph.mimes' => 'The average Google search ranking graph must be an image file (jpeg, png, jpg, gif, svg).',
            'average_google_search_ranking_graph.max' => 'The average Google search ranking graph must not exceed 2MB.',
            'website_visitors_by_url_graph.required' => 'The website visitors by URL graph image is required.',
            'website_visitors_by_url_graph.file' => 'The website visitors by URL graph must be a file.',
            'website_visitors_by_url_graph.mimes' => 'The website visitors by URL graph must be an image file (jpeg, png, jpg, gif, svg).',
            'website_visitors_by_url_graph.max' => 'The website visitors by URL graph must not exceed 2MB.',
        ];
    }
    public function prepareForValidation()
    {
        $this->merge([
            'average_ctr' => round($this->input('average_ctr'), 1),
            'average_bounce_rate' => round($this->input('average_bounce_rate'), 1),
            'average_time_on_page' => round($this->input('average_time_on_page'), 1),
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
