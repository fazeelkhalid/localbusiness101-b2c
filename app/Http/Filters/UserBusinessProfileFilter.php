<?php
namespace App\Http\Filters;

class UserBusinessProfileFilter{

    public static function applyFilters($query, $filters)
    {
        $userName = $filters['user_name'] ?? null;
        $userEmail = $filters['user_email'] ?? null;
        $title = $filters['title'] ?? null;
        $keywords = $filters['keywords'] ?? null;
        $businessProfilesKey = $filters['business_profiles_key'] ?? null;
        $category = $filters['category'] ?? null;
        $country = $filters['country'] ?? null;
        $city_or_state = $filters['city_or_state'] ?? null;
        $search = $filters['search'] ?? null;
        $theme = $filters['theme'] ?? null;

        if ($userName || $userEmail || $title || $keywords || $businessProfilesKey|| $category|| $city_or_state || $country || $search || $theme) {
            $query->where(function ($q) use ($userName, $userEmail, $title, $keywords, $businessProfilesKey, $category, $city_or_state, $country, $search, $theme) {
                if ($theme) {
                    $q->where(function ($q) use ($theme) {
                        $q->where('theme', '=', $theme);
                    });
                }

                if ($userName) {
                    $q->whereHas('user', function ($q) use ($userName) {
                        $q->where('name', 'LIKE', '%' . $userName . '%');
                    });
                }

                if ($userEmail) {
                    $q->whereHas('user', function ($q) use ($userEmail) {
                        $q->where('email', 'LIKE', '%' . $userEmail . '%');
                    });
                }

                if ($title) {
                    $q->where('title', 'LIKE', '%' . $title . '%');
                }

                if ($keywords) {
                    $q->where(function ($q) use ($keywords) {
                        $q->where('title', 'LIKE', '%' . $keywords . '%')
                            ->orWhere('description', 'LIKE', '%' . $keywords . '%')
                            ->orWhere('short_intro', 'LIKE', '%' . $keywords . '%');
                    });
                }
                if ($businessProfilesKey) {
                    $q->where('business_profiles_key', $businessProfilesKey);
                }

                if ($category) {
                    $q->whereHas('category', function ($q) use ($category) {
                        $q->where('category_name', 'LIKE', '%' . $category . '%');
                    });
                }

                if ($search) {
                    $q->where(function ($q) use ($search) {
                        $q->where('title', 'LIKE', '%' . $search . '%')
                            ->orWhere('keywords', 'LIKE', '%' . $search . '%')
                            ->orWhere('short_intro', 'LIKE', '%' . $search . '%')
                            ->orWhereHas('services', function ($q) use ($search) {
                                $q->where('name', 'LIKE', '%' . $search . '%')
                                    ->orWhere('description', 'LIKE', '%' . $search . '%');
                            })
                        ;
                    });
                }
            });
        }
    }
}
