<?php

namespace App\Http\Controllers;

use App\Http\Mapper\ContactFormRequestMapper;
use App\Http\Requests\ContactForm\CreeateContactFormRequest;
use App\Http\Responses\ContactForm\CreateContactFormResponse;
use App\Http\Services\AcquirerService;
use App\Models\BusinessProfile;
use App\Models\ContactRequest;

class ContactRequestController extends Controller
{

    protected AcquirerService $acquirerService;

    public function __construct(AcquirerService $acquirerService)
    {
        $this->acquirerService = $acquirerService;
    }

    public function createContactRequest(CreeateContactFormRequest $contactFormRequest)
    {
        $validatedData = $contactFormRequest->validated();
        $validatedData['business_profile_id'] = $this->acquirerService->get("businessProfile")->id;

        ContactRequest::create($validatedData);

        $contactRequest = ContactFormRequestMapper::mapContactFormRequestToResponse($validatedData);
        return new CreateContactFormResponse("Request submitted successfully", $contactRequest, 201);
    }
}
