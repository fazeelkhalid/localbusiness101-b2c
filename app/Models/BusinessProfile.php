<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'business_profiles_key', 'title', 'description', 'short_intro',
        'keywords', 'og_image', 'og_type', 'tab_title', 'font_style', 'heading_color',
        'heading_size', 'fav_icon'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contactDetails()
    {
        return $this->hasOne(BusinessContactDetail::class, 'business_profile_id');
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
