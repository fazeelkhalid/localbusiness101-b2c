<?php

namespace App\Http\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;

class UniqueUserEmail implements Rule
{
    public function passes($attribute, $value)
    {
        return !User::where('email', $value)->exists();
    }

    public function message()
    {
        return 'The user email must be unique.';
    }
}
