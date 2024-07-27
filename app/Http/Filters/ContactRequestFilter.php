<?php

namespace App\Http\Filters;

class ContactRequestFilter
{
    public static function applyFilters($query, $businessProfile_id, $getContactFormListRequest)
    {
        $query->where('business_profile_id', $businessProfile_id);
        $search = $getContactFormListRequest->search;
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('phone_number', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('subject', 'like', '%' . $search . '%')
                    ->orWhere('message', 'like', '%' . $search . '%');
            });
        }
    }
}
