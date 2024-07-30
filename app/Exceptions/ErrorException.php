<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ErrorException extends Exception
{
    protected $message;
    protected $responseOBJ;
    protected $code;

    public function __construct($message,$responseOBJ=null, $code = 403)
    {
        $this->message = $message;
        $this->code = $code;
        $this->responseOBJ = $responseOBJ;
    }

    public function render()
    {
        if($this->responseOBJ){
            return new JsonResponse([
                'error' => $this->message,
                'details' => $this->responseOBJ,
            ], $this->code);
        }
        return new JsonResponse([
            'error' => $this->message,
        ], $this->code);


    }

    public function toResponse($request)
    {
        return response()->json([
            'message' => $this->message,
            'login' => $this->login
        ], $this->status);
    }
}
