<?php

namespace App\Http\Responses\ContactForm;

use Illuminate\Contracts\Support\Responsable;

class CreateContactFormResponse implements Responsable
{
    protected $message;
    protected $contactRequest;
    protected $status;

    public function __construct($message, $contactRequest, int $status = 422)
    {
        $this->message = $message;
        $this->contactRequest = $contactRequest;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'message' => $this->message,
            'contact_request' => $this->contactRequest
        ], $this->status);
    }
}
