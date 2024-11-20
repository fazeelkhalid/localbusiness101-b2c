<?php
namespace App\Http\Responses\Auth;

use Illuminate\Contracts\Support\Responsable;

class LoginResponse implements Responsable
{
    protected string $message;
    protected $login;
    protected $status;

    public function __construct(string $message, $login, int $status = 200)
    {
        $this->message = $message;
        $this->login = $login;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'message' => $this->message,
            'login' => $this->login
        ], $this->status);
    }
}
