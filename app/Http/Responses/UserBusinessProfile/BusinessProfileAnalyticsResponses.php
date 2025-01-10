<?php
namespace App\Http\Responses\UserBusinessProfile;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;

class BusinessProfileAnalyticsResponses implements Responsable
{
    protected $message;
    protected $UserBusinessProfileResponses;
    protected $status;

    public function __construct($message, $UserBusinessProfileResponses, int $status = 200)
    {
        $this->UserBusinessProfileResponses = $UserBusinessProfileResponses;
        $this->status = $status;
        $this->message = $message;
    }

    public function toResponse($request)
    {
        return response()->json([
            'message' => $this->message,
            'analytics' => $this->UserBusinessProfileResponses
        ], $this->status);
    }
}
