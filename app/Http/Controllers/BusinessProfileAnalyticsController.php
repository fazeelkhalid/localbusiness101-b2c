<?php

namespace App\Http\Controllers;

use App\Enums\ErrorResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserBusinessProfile\BusinessProfileAnalyticsReportRequest;
use App\Http\Services\AcquirerService;
use App\Http\Utils\CustomUtils;
use App\Models\BusinessProfile;
use App\Models\BusinessProfileAnalyticsReport;
use Illuminate\Http\Request;

class BusinessProfileAnalyticsController extends Controller
{
    protected AcquirerService $acquirerService;

    public function __construct(AcquirerService $acquirerService)
    {
        $this->acquirerService = $acquirerService;
    }

    public function generateAnalytics(BusinessProfileAnalyticsReportRequest $request, $slug)
    {
        $businessProfile = BusinessProfile::where('slug', $slug)->first();

        if (!$businessProfile) {
            return ErrorResponseEnum::$BPNF404;
        }

        $userBusinessProfileAnalytics = $request->validated();

        $userBusinessProfileAnalytics['click_by_area_graph_url'] = $this->saveGoogleAnalyticsGraphImages($userBusinessProfileAnalytics['click_by_area_graph'], $slug);
        $userBusinessProfileAnalytics['search_keyword_counts_graph_url'] = $this->saveGoogleAnalyticsGraphImages($userBusinessProfileAnalytics['search_keyword_counts_graph'], $slug);
        $userBusinessProfileAnalytics['ctr_graph_url'] = $this->saveGoogleAnalyticsGraphImages($userBusinessProfileAnalytics['ctr_graph'], $slug);
        $userBusinessProfileAnalytics['average_google_search_ranking_graph_url'] = $this->saveGoogleAnalyticsGraphImages($userBusinessProfileAnalytics['average_google_search_ranking_graph'], $slug);
        $userBusinessProfileAnalytics['website_visitors_by_url_graph_url'] = $this->saveGoogleAnalyticsGraphImages($userBusinessProfileAnalytics['website_visitors_by_url_graph'], $slug);

        $analyticsReport = BusinessProfileAnalyticsReport::createAnalysis($userBusinessProfileAnalytics, $businessProfile->id);
        $businessProfile->analytics_report_id = $analyticsReport->id;
        $businessProfile->save();

        return response()->json([
            'message' => 'Analytics generated successfully',
            'data' => $analyticsReport
        ], 201);

    }

    /**
     * @param mixed $clickByAreaGraphImage
     * @param $slug
     * @return void
     */
    public function saveGoogleAnalyticsGraphImages( $clickByAreaGraphImage, $slug)
    {
        $clickByAreaGraphImageFilename = 'card_image-' . time() . '.' . $clickByAreaGraphImage->getClientOriginalExtension();
        $url = url('/') . CustomUtils::uploadProfileImage('/' . $slug.'/analytics', $clickByAreaGraphImage, $clickByAreaGraphImageFilename);
        return $url;
    }
}
