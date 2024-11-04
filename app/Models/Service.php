<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_profile_id',
        'name',
        'description',
    ];

    /**
     * Relationship to the BusinessProfile model
     * A service belongs to one business profile.
     */
    public function businessProfile()
    {
        return $this->belongsTo(BusinessProfile::class, 'business_profile_id');
    }

    public static function saveServices($servicesList, $businessProfileId)
    {
        foreach ($servicesList as $service) {
            self::create([
                'business_profile_id' => $businessProfileId,
                'name' => $service['title'],
                'description' => $service['description'],
            ]);
        }
    }
}
