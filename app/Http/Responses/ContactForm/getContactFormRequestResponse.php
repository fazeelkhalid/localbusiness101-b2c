<?php

namespace App\Http\Responses\ContactForm;

use Illuminate\Contracts\Support\Responsable;

class getContactFormRequestResponse implements Responsable
{
    protected $Pagination;
    protected $contactRequest;
    protected $status;

    public function __construct($contactRequest, $Pagination, int $status = 422)
    {
        $this->Pagination = $Pagination;
        $this->contactRequest = $contactRequest;
        $this->status = $status;
    }

    public function toResponse($request)
    {
        return response()->json([
            'contact_request' => $this->contactRequest,
            'pagination' => $this->Pagination,
        ], $this->status);
    }
}
