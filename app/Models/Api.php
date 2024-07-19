<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class API extends Model
{
    protected $table="apis";

    protected $fillable = [
        'api_code',
        'name',
    ];

    /**
     * Relationship with AcquirerAllowedAPI pivot model.
     */
    public function acquirerAllowedAPIs()
    {
        return $this->hasMany(AcquirerAllowedAPI::class, 'api_id');
    }
}
