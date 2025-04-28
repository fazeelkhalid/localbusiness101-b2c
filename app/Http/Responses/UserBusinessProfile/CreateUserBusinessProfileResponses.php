<?php

namespace App\Http\Responses\UserBusinessProfile;

use Illuminate\Contracts\Support\Responsable;

class CreateUserBusinessProfileResponses implements Responsable
{
    protected string $message;
    protected $UserBusinessProfileResponses;
    protected $status;

    public function __construct(string $message, $UserBusinessProfileResponses, int $status = 200)
    {
        $this->message = $message;
        $this->UserBusinessProfileResponses = $UserBusinessProfileResponses;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'message' => $this->message,
            'business_profile' => $this->UserBusinessProfileResponses
        ], $this->status);
    }
}
