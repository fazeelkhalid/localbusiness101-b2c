<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class BusinessProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'business_profiles_key', 'title', 'description', 'short_intro',
        'keywords', 'og_image', 'og_type', 'tab_title', 'font_style', 'heading_color',
        'heading_size', 'fav_icon', 'business_category_id', 'card_image_url', 'slug','website','main_page_image_url'
    ];

    public static function createBusinessProfile($business_profile, $user, $category)
    {
        $randomKey = Str::random(32);
        $businessProfileData = $business_profile;
        $businessProfile = new BusinessProfile([
            'user_id' => $user->id,
            'business_profiles_key' => $randomKey,
            'title' => $businessProfileData['title'],
            'description' => $businessProfileData['description'],
            'short_intro' => $businessProfileData['short_intro'],
            'keywords' => $businessProfileData['keywords'],
            'tab_title' => $businessProfileData['tab_title'],
            'font_style' => $businessProfileData['font_style'],
            'heading_color' => $businessProfileData['heading_color'],
            'heading_size' => $businessProfileData['heading_size'],
            'business_category_id' => $category->id,
            'card_image_url' => $businessProfileData['card_image'],
            'slug' => $businessProfileData['slug'],
            'website' => $businessProfileData['website'],
            'main_page_image_url'=> $businessProfileData['main_page_image']

        ]);
        $businessProfile->save();
        BusinessContactDetail::createBusinessContactDetails($businessProfileData['business_contact_details'], $businessProfile);
        return $businessProfile;
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'business_profile_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contactDetails()
    {
        return $this->hasMany(BusinessContactDetail::class, 'business_profile_id');
    }

    public function clientLogs()
    {
        return $this->hasMany(ClientLog::class, 'business_profile_id');
    }

    public function contactRequests()
    {
        return $this->hasMany(ContactRequest::class, 'business_profile_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'business_profile_id');
    }

    public function category()
    {
        return $this->belongsTo(BusinessCategory::class, 'business_category_id');
    }

    public static function getBusinessProfileFullDetails()
    {
        return self::with(['user.acquirer', 'contactDetails', 'ratings', 'category', 'slideImages', 'services']);
    }

    public static function getBusinessProfileFullDetailsRandomly($filter)
    {
        $random = $filter['random'] ?? 0;
        return $random ? self::getBusinessProfileFullDetails()->inRandomOrder() : self::getBusinessProfileFullDetails();
    }

    public static function getAllBusinessProfilesURLs()
    {
        $slugs = BusinessProfile::pluck('slug');
        $urls = [];
        foreach ($slugs as $slug) {
            $urls[] = env('FRONTEND_URL') . '/business-profile/' . $slug;
        }
        return $urls;
    }

    public function slideImages()
    {
        return $this->hasMany(BusinessProfileSlideImage::class, 'business_profile_id');
    }

}
