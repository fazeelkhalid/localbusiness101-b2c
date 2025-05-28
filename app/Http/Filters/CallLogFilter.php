<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class CallLogFilter
{
    public static function applyFilters(Builder $query, array $filters, ?Carbon $userCreatedAt = null): Builder
    {
        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;
        $callStatus = $filters['call_status'] ?? null;
        $callDirection = $filters['call_direction'] ?? null;
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;
        $sortByTalkTime = $filters['sort_by_talk_time'] ?? null;
        $talkTimeLessThan = $filters['talk_time_less_than'] ?? null;
        $days = $filters['days'] ?? 30;
        $userName = $filters['user_name'] ?? null;

        if ($userName) {
            $query->whereHas('caller', function ($q) use ($userName) {
                $q->where('name', 'LIKE', '%' . $userName . '%');
            });
        }

        if (!in_array(strtolower($sortByTalkTime), ['asc', 'desc'])) {
            $query->orderBy('created_at', 'desc');
        }

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

        if ($days) {
            $start = Carbon::now()->subDays((int) $days)->startOfDay();
            $end = Carbon::now()->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);

        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);

        } elseif ($startDate) {
            $query->whereBetween('created_at', [Carbon::parse($startDate), Carbon::now()->endOfDay()]);

        } elseif ($endDate && $userCreatedAt) {
            $query->whereBetween('created_at', [$userCreatedAt->startOfDay(), Carbon::parse($endDate)]);
        }

        if ($talkTimeLessThan) {
            $query->where('talk_time', '<', (int) $talkTimeLessThan);
        }

        if (in_array(strtolower($sortByTalkTime), ['asc', 'desc'])) {
            $query->orderBy('talk_time', $sortByTalkTime);
        }

        return $query;
    }
}
