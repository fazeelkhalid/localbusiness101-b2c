<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class DigitalCard extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'header_image_url',
        'profile_image_url',
        'owner_name',
        'designation',
        'website_link',
        'contact_us_url',
        'email',
        'facebook',
        'instagram',
        'phone_number',
        'gmb_links',
        'about_business',
        'office_address',
        'primary_color',
        'secondary_color',
        'slug',
        'business_name',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the office hours for the digital card.
     */
    public function officeHours(): HasMany
    {
        return $this->hasMany(OfficeHour::class);
    }

    /**
     * Get the payment methods for the digital card.
     */
    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public static function saveDigitalCard(array $data): DigitalCard
    {
        return self::create($data);
    }

    public static function getDigitalCard($slug)
    {
        return self::where('slug', $slug)
            ->with(['officeHours', 'paymentMethods'])
            ->first();
    }

    public static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $slugCount = DigitalCard::where('slug', $slug)->count();

        if ($slugCount) {
            $digitalCardCount = DigitalCard::count();
            return $slug . '-' . ($digitalCardCount);
        }
        return $slug;
    }

}
