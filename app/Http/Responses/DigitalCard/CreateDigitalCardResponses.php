<?php
namespace App\Http\Responses\DigitalCard;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;

class CreateDigitalCardResponses implements Responsable
{
    protected string $message;
    protected $digitalCardResponses;
    protected $status;

    public function __construct(string $message, $digitalCardResponses, int $status = 200)
    {
        $this->message = $message;
        $this->digitalCardResponses = $digitalCardResponses;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'message' => $this->message,
            'digital_card' => $this->digitalCardResponses
        ], $this->status);
    }
}
