<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsefulLink extends Model
{
    use HasFactory;

    protected $table = 'business_profile_useful_link';

    protected $fillable = [
        'business_profile_id',
        'links',
        'tags_title',
    ];

    public function businessProfile()
    {
        return $this->belongsTo(BusinessProfile::class, 'business_profile_id');
    }
}
