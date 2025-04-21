<?php
namespace App\Http\Responses\User;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;

class CreateUserResponses implements Responsable
{
    protected string $message;
    protected $userProfileResponses;
    protected $status;

    public function __construct(string $message, $userProfileResponses, int $status = 200)
    {
        $this->message = $message;
        $this->userProfileResponses = $userProfileResponses;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'message' => $this->message,
            'user' => $this->userProfileResponses
        ], $this->status);
    }
}
