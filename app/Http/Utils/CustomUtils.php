<?php

namespace App\Http\Utils;

use App\Http\Mapper\AuthMapper;
use App\Models\ApplicationConfiguration;
use App\Models\BusinessProfile;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomUtils
{
    public static function getCountryFromIp($ip)
    {
        $client = new Client();
        $response = $client->get('http://ip-api.com/json/' . $ip);
        $data = json_decode($response->getBody(), true);

        return $data['country'] ?? 'Unknown';
    }

    public static function getBrowser($userAgent): string
    {
        if (str_contains($userAgent, 'Firefox')) {
            return 'Firefox';
        } elseif (str_contains($userAgent, 'Chrome')) {
            return 'Chrome';
        } elseif (str_contains($userAgent, 'Safari')) {
            return 'Safari';
        } elseif (str_contains($userAgent, 'MSIE') || str_contains($userAgent, 'Trident')) {
            return 'Internet Explorer';
        } else {
            return 'Unknown';
        }
    }

    public static function getDeviceType($userAgent): string
    {
        if (str_contains($userAgent, 'Mobile')) {
            return 'Mobile';
        } elseif (str_contains($userAgent, 'Tablet')) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }

    public static function isAssoc(array $arr)
    {
        if ([] === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public static function uploadProfileImage($folder='', $image, $filename)
    {
        $imagePath = $image->storeAs('images/business_profiles'.$folder, $filename, 'public');
        return Storage::url($imagePath);
    }


    public static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $slugCount = BusinessProfile::where('slug', $slug)->count();

        if($slugCount){
            $profileCount= BusinessProfile::count();
            return $slug . '-' . ($profileCount);
        }
        return $slug;
    }

    public static function setMessageTraceUUID($response, $message_trace_uuid): void
    {
        $responseData = json_decode($response->getContent(), true);
        $responseData['message_trace_uuid'] = $message_trace_uuid;
        $response->setContent(json_encode($responseData));
    }

    public static function setMessageIfServerErrorOccur($response): void
    {
        if ($response->getStatusCode() >= 500 && !env('APP_DEBUG')) {
            $responseData = json_decode($response->getContent(), true);
            $responseData = AuthMapper::mapServerErrorResponseToAPIResponse($responseData);
            $response->setContent(json_encode($responseData));
        }
    }
    static function calculateMaxLinksPerColumn(array $links): int
    {
        $totalLinks = count($links);
        $columns = 3;
        $linksPerColumn = intdiv($totalLinks, $columns);
        $remainder = $totalLinks % $columns;
        return $linksPerColumn + ($remainder > 0 ? 1 : 0);
    }

    public static function getInvoiceEmailBody($payment)
    {
        $email_body = ApplicationConfiguration::getApplicationConfiguration("invoice_email_body");
        $formattedDate = $payment->updated_at->format('F j, Y');
        $email_body = str_replace('%NAME%', $payment->client_name, $email_body);
        $email_body = str_replace('%EMAIL%', $payment->client_email, $email_body);
        $email_body = str_replace('%INVOICE_NO%', $payment->payment_id, $email_body);
        $email_body = str_replace('%CURRENT_DATE%', $formattedDate, $email_body);
        $email_body = str_replace('%PRICE%', $payment->amount, $email_body);
        return str_replace('%DESCRIPTION%', $payment->description, $email_body);
    }

    public static function parseURlAndGetLastIndex($url)
    {
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'] ?? '';
        $segments = explode('/', trim($path, '/'));
        return end($segments); // Return the last part of the URL path
    }

    private static function getAnalyticsReportURLSection($urls)
    {
        if ($urls) {
            $html = '';
            foreach ($urls as $url) {
                $urlTitle = CustomUtils::parseURlAndGetLastIndex($url);
                $html .= '<a href="' . htmlspecialchars($url) . '" style="display: inline-block; padding: 10px 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; text-decoration: none; color: #1a73e8; font-size: 14px;">';
                $html .= htmlspecialchars($urlTitle);
                $html .= '</a>';
            }
            return $html;
        }
        return '';
    }
    private static function getAnalyticsReportAreaSection($areas)
    {
        if ($areas) {
            $html = '';
            foreach ($areas as $area) {
                $html .= '<div style="display: inline-block; padding: 10px 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; font-size: 14px; color: #333;">';
                $html .= htmlspecialchars($area);
                $html .= '</div>';
            }
            return $html;
        }
        return '';
    }

    private static function getAnalyticsReportKeyWordSection($keywords)
    {
        if ($keywords) {
            $html = '';
            foreach ($keywords as $keyword) {
                $html .= '<div style="display: inline-block; padding: 10px 15px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; font-size: 14px; color: #333;">';
                $html .= htmlspecialchars($keyword);
                $html .= '</div>';
            }
            return $html;
        }
        return '';
    }


    public static function makeAnalyticsReport($userBusinessProfileAnalytics)
    {
        $report_analytics = ApplicationConfiguration::getApplicationConfiguration("BUSINESS_PROFILE_ANALYTIC_REPORT");
        $report_analytics = str_replace('%NO_OF_DAYS%',$userBusinessProfileAnalytics['days'], $report_analytics);
        $report_analytics = str_replace('%TOTAL_CLICKS%', $userBusinessProfileAnalytics['total_click'], $report_analytics);
        $report_analytics = str_replace('%TOTAL_IMPRESSIONS%', $userBusinessProfileAnalytics['total_impressions'], $report_analytics);
        $report_analytics = str_replace('%AVERAGE_CTR%', $userBusinessProfileAnalytics['average_ctr'], $report_analytics);
        $report_analytics = str_replace('%AVERAGE_BOUNCE_RATE%', $userBusinessProfileAnalytics['average_bounce_rate'], $report_analytics);
        $report_analytics = str_replace('%AVERAGE_TIME_ON_PAGE%', $userBusinessProfileAnalytics['average_time_on_page'], $report_analytics);
        $report_analytics = str_replace('%TOP_KEYWORD%', $userBusinessProfileAnalytics['top_keyword'], $report_analytics);
        $report_analytics = str_replace('%TOP_AREA%', $userBusinessProfileAnalytics['top_area'], $report_analytics);

        $report_analytics = str_replace('%URL_SECTION%', CustomUtils::getAnalyticsReportURLSection($userBusinessProfileAnalytics['urls']), $report_analytics);
        $report_analytics = str_replace('%AREAS_SECTION%', CustomUtils::getAnalyticsReportAreaSection($userBusinessProfileAnalytics['areas']), $report_analytics);
        $report_analytics = str_replace('%KEYWORDS_SECTION%', CustomUtils::getAnalyticsReportKeyWordSection($userBusinessProfileAnalytics['top_keywords']), $report_analytics);

        $report_analytics = str_replace('%AREA_ANALYSIS_GRAPH_URL%', $userBusinessProfileAnalytics['click_by_area_graph_url'], $report_analytics);
        $report_analytics = str_replace('%KEYWORD_COUNT_ANALYSIS_GRAPH_URL%', $userBusinessProfileAnalytics['search_keyword_counts_graph_url'], $report_analytics);
        $report_analytics = str_replace('%CTR_GRAPH_URL%', $userBusinessProfileAnalytics['ctr_graph_url'], $report_analytics);
        $report_analytics = str_replace('%KEYWORD_RANKING_ANALYSIS_GRAPH_URL%', $userBusinessProfileAnalytics['average_google_search_ranking_graph_url'], $report_analytics);
        return str_replace('%VISITOR_ANALYSIS_GRAPH_URL%', $userBusinessProfileAnalytics['website_visitors_by_url_graph_url'], $report_analytics);
    }

}
