<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProfileAnalyticsReport extends Model
{
    use HasFactory;
    protected $table='business_profile_analytics_report';

    protected $fillable = [
        'days', 'total_click', 'total_impressions', 'average_ctr',
        'average_bounce_rate', 'average_time_on_page', 'top_keyword',
        'top_area', 'urls', 'areas', 'top_keywords', 'record_generated',
        'click_by_area_graph_url',
        'search_keyword_counts_graph_url',
        'ctr_graph_url',
        'average_google_search_ranking_graph_url',
        'website_visitors_by_url_graph_url',
        'business_profile_id',
    ];

    public static function createAnalysis($analysisData, $businessProfileId)
    {
        return BusinessProfileAnalyticsReport::create([
            'business_profile_id' => $businessProfileId,
            'days' => $analysisData['days'],
            'total_click' => $analysisData['total_click'],
            'total_impressions' => $analysisData['total_impressions'],
            'average_ctr' => round($analysisData['average_ctr'], 1),
            'average_bounce_rate' => round($analysisData['average_bounce_rate'], 1),
            'average_time_on_page' => round($analysisData['average_time_on_page'], 1),
            'top_keyword' => $analysisData['top_keyword'],
            'top_area' => $analysisData['top_area'],
            'urls' => json_encode($analysisData['urls']),
            'areas' => json_encode($analysisData['areas']),
            'top_keywords' => json_encode($analysisData['top_keywords']),
//            'record_generated' => $analysisData['record_generated'],
            'click_by_area_graph_url' => $analysisData['click_by_area_graph_url'],
            'search_keyword_counts_graph_url' => $analysisData['search_keyword_counts_graph_url'],
            'ctr_graph_url' => $analysisData['ctr_graph_url'],
            'average_google_search_ranking_graph_url' => $analysisData['average_google_search_ranking_graph_url'],
            'website_visitors_by_url_graph_url' => $analysisData['website_visitors_by_url_graph_url'],
        ]);
    }

    public function businessProfiles()
    {
        return $this->hasMany(BusinessProfile::class, 'analytics_report_id');
    }
}
