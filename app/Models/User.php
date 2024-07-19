<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @method static where(string $string, mixed $email)
 * @method static create(array $array)
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table="users";

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'application_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relationship with Application model.
     */
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
