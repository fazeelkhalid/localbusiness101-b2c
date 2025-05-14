<?php
namespace App\Http\Responses\CallLog;

use Illuminate\Contracts\Support\Responsable;

class CreateCallLogResponses implements Responsable
{
    protected $phoneNumbers;
    protected $status;
    protected $message;

    public function __construct($phoneNumbers, $message, int $status = 200)
    {
        $this->phoneNumbers = $phoneNumbers;
        $this->status = $status;
        $this->message = $message;
    }

    public function toResponse($request)
    {
        return response()->json([
            'message' => $this->message,
            'call_log' => $this->phoneNumbers
        ], $this->status);
    }
}
