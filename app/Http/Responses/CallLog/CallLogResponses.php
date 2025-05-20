<?php

namespace App\Http\Responses\CallLog;

use Illuminate\Contracts\Support\Responsable;

class CallLogResponses implements Responsable
{
    protected $callLog;
    protected int $status;
    protected ?string $message;
    protected ?string $rootOBJName;

    public function __construct($callLog, ?string $message = null, int $status = 200, ?string $rootOBJName = null)
    {
        $this->callLog = $callLog;
        $this->status = $status;
        $this->message = $message;
        $this->rootOBJName = $rootOBJName;
    }

    public function toResponse($request)
    {
        $response = [];

        if ($this->message !== null) {
            $response['message'] = $this->message;
        }

        if ($this->callLog !== null) {
            $key = $this->rootOBJName ?? 'call_log';
            $response[$key] = $this->callLog;
        }

        return response()->json($response, $this->status);
    }

}
