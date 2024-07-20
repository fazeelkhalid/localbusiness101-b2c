<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AcquirerAllowedAPI extends Pivot
{
    protected $table = 'acquirer_allowed_api';

    protected $fillable = [
        'acquirer_id',
        'api_id',
        'is_active',
    ];

    public $timestamps = true;

    /**
     * Define relationships with Acquirer and API models.
     */
    public function acquirer()
    {
        return $this->belongsTo(Acquirer::class, 'acquirer_id');
    }

    public function api()
    {
        return $this->belongsTo(Api::class, 'api_id');
    }
}
