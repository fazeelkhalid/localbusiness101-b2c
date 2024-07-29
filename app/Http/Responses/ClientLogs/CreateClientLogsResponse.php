<?php
namespace App\Http\Responses\ClientLogs;
use Illuminate\Contracts\Support\Responsable;

class CreateClientLogsResponse implements Responsable
{
    protected $message;
    protected $status;

    public function __construct($message, int $status = 200)
    {
        $this->message = $message;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'message' => $this->message
        ], $this->status);
    }
}
