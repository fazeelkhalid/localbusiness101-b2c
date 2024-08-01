<?php

namespace App\Http\Responses\Review;

use Illuminate\Contracts\Support\Responsable;

class CreateReviewResponse implements Responsable
{
    protected $message;
    protected $status;

    public function __construct($message, int $status = 422)
    {
        $this->message = $message;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'message' => $this->message,
        ], $this->status);
    }
}
