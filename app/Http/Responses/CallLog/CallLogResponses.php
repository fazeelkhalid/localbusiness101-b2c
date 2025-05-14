<?php
namespace App\Http\Responses\CallLog;

use Illuminate\Contracts\Support\Responsable;

class CallLogResponses implements Responsable
{
    protected $callLog;
    protected $status;
    protected $message;

    public function __construct($callLog, $message, int $status = 200)
    {
        $this->callLog = $callLog;
        $this->status = $status;
        $this->message = $message;
    }

    public function toResponse($request)
    {
        $response = [
            'message' => $this->message,
        ];

        if (!is_null($this->callLog)) {
            $response['call_log'] = $this->callLog;
        }

        return response()->json($response, $this->status);
    }

}
