<?php

namespace App\Http\Responses\UserBusinessProfile;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;

class GetUserBusinessProfilesResponses implements Responsable
{
    protected $UserBusinessProfileResponses;
    protected $status;
    protected $pagination;

    public function __construct($UserBusinessProfileResponses,$pagination, int $status = 200)
    {
        $this->UserBusinessProfileResponses = $UserBusinessProfileResponses;
        $this->status = $status;
        $this->pagination = $pagination;
    }

    public function toResponse($request)
    {
        return response()->json([
            "business_profiles" => $this->UserBusinessProfileResponses,
            "pagination"=>$this->pagination,
        ], $this->status);
    }
}
