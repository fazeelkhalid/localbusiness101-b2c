<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'host_ip',
        'host_port',
        'hash_key'
    ];

    /**
     * Relationship with Acquirer model.
     */
    public function acquirers()
    {
        return $this->hasMany(Acquirer::class, 'application_id');
    }

    /**
     * Relationship with User model.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'application_id');
    }
}
