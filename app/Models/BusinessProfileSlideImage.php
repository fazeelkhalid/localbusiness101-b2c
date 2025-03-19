<?php

namespace App\Models;

use App\Http\Utils\CustomUtils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProfileSlideImage extends Model
{
    use HasFactory;

    protected $fillable = ['business_profile_id', 'image_url'];

    public function businessProfile()
    {
        return $this->belongsTo(BusinessProfile::class);
    }

    public static function saveSlidesimages($slug, $businessProfileId, $slideImages)
    {
        $folder = '/'.$slug;
        if (!empty($slideImages)) {
            foreach ($slideImages as $image) {
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                $imageUrl = CustomUtils::uploadProfileImage($folder, $image, $filename);
                self::create([
                    'business_profile_id' => $businessProfileId,
                    'image_url' => $imageUrl
                ]);
            };
        }
    }
}
