<?php

namespace App\Http\Controllers;

use App\Models\BusinessProfile;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    public function index()
    {

        $urls = BusinessProfile::getAllBusinessProfilesURLs();
        $urls[] = URL::to('/');
        $urls[] = URL::to('/about');
        $urls[] = URL::to('/contact');
        $urls[] = URL::to('/Testimonials');
        $urls[] = URL::to('/business-profile-search');
        $urls[] = URL::to('/business-profiles');

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
