<?php

namespace App\Models;

use App\Http\Utils\CustomUtils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProfileGallery extends Model
{
    use HasFactory;

    protected $table = 'businessprofile_gallery';

    protected $fillable = [
        'business_profile_id',
        'image_url',
    ];


    public function businessProfile()
    {
        return $this->belongsTo(BusinessProfile::class, 'business_profile_id');
    }


    public static function saveGalleryImages($slug, $businessProfileId, $galleryImages)
    {
        $folder = '/'.$slug.'/gallery';
        if (!empty($galleryImages)) {
            foreach ($galleryImages as $image) {
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                $imageUrl = url('/') . CustomUtils::uploadProfileImage($folder, $image, $filename);
                self::create([
                    'business_profile_id' => $businessProfileId,
                    'image_url' => $imageUrl
                ]);
            };
        }
    }
}
