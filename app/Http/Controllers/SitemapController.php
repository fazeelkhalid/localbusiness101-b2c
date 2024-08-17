<?php

namespace App\Http\Controllers;

use App\Models\BusinessProfile;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    public function index()
    {

        $urls = BusinessProfile::getAllBusinessProfilesURLs();
        $urls[] =  env('FRONTEND_URL');
        $urls[] = env('FRONTEND_URL').'/about';
        $urls[] = env('FRONTEND_URL').'/contact';
        $urls[] = env('FRONTEND_URL').'/Testimonials';
//        $urls[] = env('FRONTEND_URL').'/business-profile-search';
        $urls[] = env('FRONTEND_URL').'/business-profiles';

        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>';
        $xmlContent .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $xmlContent .= '<url>';
            $xmlContent .= '<loc>' . $url . '</loc>';
            $xmlContent .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
            $xmlContent .= '<changefreq>daily</changefreq>';
            $xmlContent .= '<priority>1</priority>';
            $xmlContent .= '</url>';
        }

        $xmlContent .= '</urlset>';

        return response($xmlContent, 200)
            ->header('Content-Type', 'application/xml');
    }
}
