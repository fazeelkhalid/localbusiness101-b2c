<?php

namespace App\Http\Responses\Error;

use Illuminate\Contracts\Support\Responsable;

class ErrorResponse implements Responsable
{
    protected $errors;
    protected $status;

    public function __construct( $errors, int $status = 422)
    {
        $this->errors = $errors;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'errors' => $this->errors
        ], $this->status);
    }
}
