<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    protected $table = 'phone_numbers';

    use HasFactory;

    protected $fillable = [
        'phone_number',
        'dialing_regex',
    ];

    public function allowedUsers()
    {
        return $this->belongsToMany(User::class, 'user_allowed_phone_numbers');
    }

}
