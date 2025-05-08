<?php
namespace App\Http\Responses\PhoneNumber;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;

class GetUserPhoneNumberResponses implements Responsable
{
    protected $phoneNumbers;
    protected $status;
    protected $message;

    public function __construct($phoneNumbers, $message, int $status = 200)
    {
        $this->phoneNumbers = $phoneNumbers;
        $this->status = $status;
        $this->message = $message;
    }

    public function toResponse($request)
    {
        return response()->json([
            'message' => $this->message,
            'allowed_numbers' => $this->phoneNumbers
        ], $this->status);
    }
}
