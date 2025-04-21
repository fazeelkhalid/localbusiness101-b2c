<?php

namespace App\Http\Responses\User;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;

class GetUserResponses implements Responsable
{
    protected $users;
    protected $status;

    public function __construct($users, int $status = 200)
    {
        $this->users = $users;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            "users" => $this->users,
        ], $this->status);
    }
}
