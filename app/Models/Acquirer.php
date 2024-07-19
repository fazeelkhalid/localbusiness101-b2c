<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acquirer extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'name',
        'key',
    ];

    /**
     * Relationship with Application model.
     */
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    /**
     * Relationship with API model through AcquirerAllowedAPI pivot model.
     */
    public function allowedAPIs()
    {
        return $this->belongsToMany(API::class, 'acquirer_allowed_api', 'acquirer_id', 'api_id')
            ->using(AcquirerAllowedAPI::class)
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function configurations()
    {
        return $this->hasMany(Configuration::class, 'acquirer_id');
    }
}
