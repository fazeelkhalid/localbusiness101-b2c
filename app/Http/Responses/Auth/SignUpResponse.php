<?php
namespace App\Http\Responses\Auth;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;

class SignUpResponse implements Responsable
{
    protected string $message;
    protected User $user;
    protected $status;

    public function __construct(string $message, $user, int $status = 200)
    {
        $this->message = $message;
        $this->user = $user;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'message' => $this->message,
            'user' => $this->user
        ], $this->status);
    }
}
