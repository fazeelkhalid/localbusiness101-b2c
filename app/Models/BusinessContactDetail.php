<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessContactDetail extends Model
{
    use HasFactory;

    protected $fillable = ['business_profile_id', 'business_phone', 'business_email', 'business_address', 'map_location_url'];

    public static function createBusinessContactDetails($business_contact_details, $businessProfile): void
    {
        foreach ($business_contact_details as $contactDetail) {
            $businessContactDetail = new BusinessContactDetail([
                'business_profile_id' => $businessProfile->id,
                'business_email' => $contactDetail['email'],
                'business_phone' => $contactDetail['phone'],
                'business_address' => $contactDetail['address'],
            ]);

            if ($businessProfile->theme === 'advance') {
                $businessContactDetail->map_location_url = $contactDetail['map_location_url'];
            }

            $businessContactDetail->save();
        }
    }

    public function businessProfile()
    {
        return $this->belongsTo(BusinessProfile::class, 'business_profile_id');
    }
}
