<?php
namespace App\Http\Pagination;


class Pagination
{
    public static function set($request, $query)
    {
        $perPage = $request->input('per_page', 10);
        return $query->paginate($perPage)->appends(collect($request->all())->filter()->toArray());
    }

    public static function setDefault($query)
    {
        $businessProfiles = $query->paginate(10);
        return $businessProfiles;
    }
}
