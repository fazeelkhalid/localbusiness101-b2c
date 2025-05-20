<?php

namespace App\Http\Responses\CallLog;

use Illuminate\Contracts\Support\Responsable;

class GetCallLogsResponses implements Responsable
{
    protected $callLogsListResponses;
    protected $status;
    protected $pagination;

    public function __construct($callLogsListResponses, $pagination, int $status = 200)
    {
        $this->callLogsListResponses = $callLogsListResponses;
        $this->status = $status;
        $this->pagination = $pagination;
    }

    public function toResponse($request)
    {
        return response()->json([
            "call_logs" => $this->callLogsListResponses,
            "pagination" => [
                'current_page' => $this->pagination->currentPage(),
                'last_page' => $this->pagination->lastPage(),
                'per_page' => $this->pagination->perPage(),
                'total' => $this->pagination->total(),
                'next_page_url' => $this->pagination->nextPageUrl(),
                'prev_page_url' => $this->pagination->previousPageUrl()
            ],
        ], $this->status);
    }
}
