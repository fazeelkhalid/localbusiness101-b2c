<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Acquirer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
    ];

    public static function createAcquirer($name)
    {
        $randomKey = Str::random(32);
        return self::create([
            'name' => $name,
            'key' => $randomKey,
        ]);
    }

    /**
     * Relationship with API model through AcquirerAllowedAPI pivot model.
     */
    public function allowedAPIs()
    {
        return $this->belongsToMany(Api::class, 'acquirer_allowed_api', 'acquirer_id', 'api_id')
            ->using(AcquirerAllowedAPI::class)
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function configurations()
    {
        return $this->hasMany(Configuration::class, 'acquirer_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'acquirer_id');
    }
}
