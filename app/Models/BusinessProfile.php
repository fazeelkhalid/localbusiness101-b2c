<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BusinessProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'business_profiles_key', 'title', 'description', 'short_intro',
        'keywords', 'og_image', 'og_type', 'tab_title', 'font_style', 'heading_color',
        'heading_size', 'fav_icon'
    ];

    public static function createBusinessProfile($business_profile, $user)
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
        ]);
        $businessProfile->save();
        BusinessContactDetail::createBusinessContactDetails($businessProfileData['business_contact_details'], $businessProfile);
        return $businessProfile;
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
}
