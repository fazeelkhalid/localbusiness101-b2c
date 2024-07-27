<?php

namespace App\Http\Responses\ContactForm;

use Illuminate\Contracts\Support\Responsable;

class DeleteContactFormResponse implements Responsable
{
    protected $message;
    protected $status;

    public function __construct($message, int $status = 422)
    {
        $this->status = $status;
        $this->message = $message;
    }

    public function toResponse($request)
    {
        return response()->json([
            'message' => $this->message,
        ], $this->status);
    }
}
