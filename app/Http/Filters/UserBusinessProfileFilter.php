<?php
namespace App\Http\Filters;

class UserBusinessProfileFilter{

    public static function applyFilters($query, $filters)
    {
        // Extract filters
        $userName = $filters['user_name'] ?? null;
        $userEmail = $filters['user_email'] ?? null;
        $title = $filters['title'] ?? null;
        $keywords = $filters['keywords'] ?? null;
        $businessProfilesKey = $filters['business_profiles_key'] ?? null; // New filter

        // Apply filters
        if ($userName || $userEmail || $title || $keywords || $businessProfilesKey) {
            $query->where(function ($q) use ($userName, $userEmail, $title, $keywords, $businessProfilesKey) {
                // Filter by user name
                if ($userName) {
                    $q->whereHas('user', function ($q) use ($userName) {
                        $q->where('name', 'LIKE', '%' . $userName . '%');
                    });
                }

                // Filter by user email
                if ($userEmail) {
                    $q->whereHas('user', function ($q) use ($userEmail) {
                        $q->where('email', 'LIKE', '%' . $userEmail . '%');
                    });
                }

                // Filter by title
                if ($title) {
                    $q->where('title', 'LIKE', '%' . $title . '%');
                }

                // Filter by keywords in title, description, and short_intro
                if ($keywords) {
                    $q->where(function ($q) use ($keywords) {
                        $q->where('title', 'LIKE', '%' . $keywords . '%')
                            ->orWhere('description', 'LIKE', '%' . $keywords . '%')
                            ->orWhere('short_intro', 'LIKE', '%' . $keywords . '%');
                    });
                }

                // Filter by business_profiles_key
                if ($businessProfilesKey) {
                    $q->where('business_profiles_key', $businessProfilesKey);
                }
            });
        }
    }
}
