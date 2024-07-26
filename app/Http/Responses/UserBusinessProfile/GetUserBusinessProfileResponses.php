<?php
namespace App\Http\Responses\UserBusinessProfile;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;

class GetUserBusinessProfileResponses implements Responsable
{
    protected $UserBusinessProfileResponses;
    protected $status;

    public function __construct($UserBusinessProfileResponses, int $status = 200)
    {
        $this->UserBusinessProfileResponses = $UserBusinessProfileResponses;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'business_profile' => $this->UserBusinessProfileResponses
        ], $this->status);
    }
}
