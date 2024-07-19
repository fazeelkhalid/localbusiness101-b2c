<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ErrorException extends Exception
{
    protected $message;
    protected $code;

    public function __construct($message, $code = 403)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function render()
    {
        return new JsonResponse([
            'error' => $this->message,
        ], $this->code);
    }
}
