<?php
namespace App\Http\Pagination;


class UserBusinessProfilePagination
{
    public static function getUserBusinessProfilePagination($businessProfileFilterRequest, $query)
    {
        $perPage = $businessProfileFilterRequest->input('per_page', 10);
        $businessProfiles = $query->paginate($perPage);
        return $businessProfiles;
    }
}
