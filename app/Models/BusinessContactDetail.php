<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessContactDetail extends Model
{
    use HasFactory;

    protected $fillable = ['business_profile_id', 'business_phone', 'business_email', 'business_address'];

    public function businessProfile()
    {
        return $this->belongsTo(BusinessProfile::class, 'business_profile_id');
    }
}
