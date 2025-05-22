<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class CallLogFilter
{
    public static function applyFilters(Builder $query, array $filters): Builder
    {
        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;
        $callStatus = $filters['call_status'] ?? null;
        $callDirection = $filters['call_direction'] ?? null;
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;
        $sortByTalkTime = $filters['sort_by_talk_time'] ?? null;

        if ($from) {
            $query->whereHas('phoneNumber', function ($q) use ($from) {
                $q->where('phone_number', 'LIKE', '%' . $from . '%');
            });
        }

        if ($to) {
            $query->where('receiver_number', 'LIKE', '%' . $to . '%');
        }

        if ($callStatus) {
            $query->where('call_status', $callStatus);
        }

        if ($callDirection) {
            $query->where('call_direction', $callDirection);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if (in_array(strtolower($sortByTalkTime), ['asc', 'desc'])) {
            $query->orderBy('talk_time', $sortByTalkTime);
        }

        return $query;
    }
}

