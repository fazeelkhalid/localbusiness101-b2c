<?php

namespace App\Http\Responses\CallLog;

use Illuminate\Contracts\Support\Responsable;

class GetCallLogsStatsResponses implements Responsable
{
    protected $callLogsStatsResponses;
    protected $status;

    public function __construct($callLogsStatsResponses, int $status = 200)
    {
        $this->callLogsStatsResponses = $callLogsStatsResponses;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            "stats" => $this->callLogsStatsResponses,
        ], $this->status);
    }
}
