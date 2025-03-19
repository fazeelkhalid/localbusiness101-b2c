<?php
namespace App\Http\Responses\DigitalCard;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;

class GetDigitalCardResponses implements Responsable
{
    protected $digitalCardResponse;
    protected $status;

    public function __construct($UserBusinessProfileResponses, int $status = 200)
    {
        $this->digitalCardResponse = $UserBusinessProfileResponses;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'digital_card' => $this->digitalCardResponse
        ], $this->status);
    }
}
