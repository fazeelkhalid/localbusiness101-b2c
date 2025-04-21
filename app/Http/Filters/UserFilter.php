<?php
namespace App\Http\Filters;

class UserFilter
{
    public static function applyFilters($query, $filters)
    {
        $name = $filters['name'] ?? null;
        $email = $filters['email'] ?? null;
        $search = $filters['search'] ?? null;

        if ($name || $email || $search) {
            $query->where(function ($q) use ($name, $email, $search) {
                if ($name) {
                    $q->where('name', 'LIKE', '%' . $name . '%');
                }

                if ($email) {
                    $q->where('email', 'LIKE', '%' . $email . '%');
                }

                if ($search) {
                    $q->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%')
                            ->orWhere('email', 'LIKE', '%' . $search . '%');
                    });
                }
            });
        }

        return $query;
    }
}
