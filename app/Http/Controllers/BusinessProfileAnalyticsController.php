<?php

namespace App\Http\Controllers;

use App\Enums\ErrorResponseEnum;
use App\Http\Mapper\UserBusinessProfileMapper;
use App\Http\Requests\UserBusinessProfile\BusinessProfileAnalyticsReportRequest;
use App\Http\Responses\UserBusinessProfile\BusinessProfileAnalyticsResponses;
use App\Http\Services\AcquirerService;
use App\Http\Services\Client\HttpNotificationService;
use App\Http\Utils\CustomUtils;
use App\Models\ApplicationConfiguration;
use App\Models\BusinessProfile;
use App\Models\BusinessProfileAnalyticsReport;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

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
        $businessProfile->html_report = CustomUtils::makeAnalyticsReport($userBusinessProfileAnalytics);
        $businessProfile->analytics_report_id = $analyticsReport->id;
        $businessProfile->save();
        $analyticsReport = UserBusinessProfileMapper::mapAnalyticsReportToCreateAnalyticResponse($analyticsReport);
        return new BusinessProfileAnalyticsResponses("Analytics generated successfully", $analyticsReport, 201);
    }

    public function sendAnalyticsReport($slug)
    {
        $businessProfile = BusinessProfile::where('slug', $slug)->first();

        if (!$businessProfile) {
            return ErrorResponseEnum::$BPNF404;
        }

        $htmlReport = $businessProfile->html_report;
        $emailTitle = ApplicationConfiguration::getApplicationConfiguration("analytics_report_email_subject");

        $requestBody = [
            'receiver_email' => 'support@fastdevlabs.com',
            'email_title' => $emailTitle,
            'email_body' => $htmlReport,
            'message_trace_uuid' => Uuid::uuid4()->toString(),
        ];
        $email = HttpNotificationService::sendEmail($requestBody, ApplicationConfiguration::getApplicationConfiguration("NOTIFICATION_SERVICE_INVOICE_API_KEY"));
        if ($email->successful()) {
            return new BusinessProfileAnalyticsResponses("Analytics report sent successfully", $email->status());
        } else {
            return new BusinessProfileAnalyticsResponses("Failed to send the analytics report.", $email->status());
        }
    }

    public function saveGoogleAnalyticsGraphImages($clickByAreaGraphImage, $slug)
    {
        $clickByAreaGraphImageFilename = 'graph-' . Str::random(32) . time() . '.' . $clickByAreaGraphImage->getClientOriginalExtension();
        $url = CustomUtils::uploadProfileImage('/' . $slug . '/analytics', $clickByAreaGraphImage, $clickByAreaGraphImageFilename);
        return $url;
    }
}
