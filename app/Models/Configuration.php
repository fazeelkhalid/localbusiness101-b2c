<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $fillable = [
        'acquirer_id',
        'config_code',
        'value',
    ];

    public function acquirer()
    {
        return $this->belongsTo(Acquirer::class, 'acquirer_id');
    }
}
