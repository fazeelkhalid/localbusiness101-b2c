<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ApplicationConfiguration extends Model
{
    use HasFactory;

    protected $table = 'application_configuration';

    protected $fillable = [
        'name',
        'value',
    ];

    public static function getApplicationConfiguration($name, int $cacheTimeout = 3600)
    {
        $config = self::where('name', $name)->first();
        return $config ? $config->value : "";
    }
}
