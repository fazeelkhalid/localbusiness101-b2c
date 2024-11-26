<?php
namespace App\Http\Responses\Auth;

use Illuminate\Contracts\Support\Responsable;

class verifyResponse implements Responsable
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
        if($this->login){
            return response()->json([
                'message' => $this->message,
                'verify' => $this->login
            ], $this->status);
        }
        else{
            return response()->json([
                'message' => $this->message,
            ], $this->status);
        }
    }
}
