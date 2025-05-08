<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAllowedPhoneNumber extends Model
{
    use HasFactory;

    protected $table = 'user_allowed_phone_numbers';

    protected $fillable = [
        'user_id',
        'phone_number_id',
        'is_active',
        'assigned_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function phoneNumber()
    {
        return $this->belongsTo(PhoneNumber::class);
    }
}

