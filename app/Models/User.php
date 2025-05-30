<?php

namespace App\Models;

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
        'acquirer_id',
        'admin'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function createUser($userData, $acquirer){
        return User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'acquirer_id'=>$acquirer->id
        ]);
    }

    public function acquirer()
    {
        return $this->belongsTo(Acquirer::class, 'acquirer_id');
    }

    public function businessProfile()
    {
        return $this->hasOne(BusinessProfile::class, 'user_id');
    }

    public function allowedPhoneNumbers()
    {
        return $this->belongsToMany(PhoneNumber::class, 'user_allowed_phone_numbers');
    }

    public function callLogs()
    {
        return $this->hasMany(CallLog::class);
    }

    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin');
    }

    public function adminOf()
    {
        return $this->hasMany(User::class, 'admin');
    }


}
