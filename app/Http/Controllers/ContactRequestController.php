<?php

namespace App\Http\Controllers;

use App\Enums\ErrorResponseEnum;
use App\Http\Filters\ContactRequestFilter;
use App\Http\Mapper\ContactFormRequestMapper;
use App\Http\Pagination\Pagination;
use App\Http\Requests\ContactForm\CreeateContactFormRequest;
use App\Http\Requests\ContactForm\GetContactFormListRequest;
use App\Http\Responses\ContactForm\CreateContactFormResponse;
use App\Http\Responses\ContactForm\getContactFormRequestResponse;
use App\Http\Services\AcquirerService;
use App\Models\ContactRequest;

class ContactRequestController extends Controller
{

    protected AcquirerService $acquirerService;

    public function __construct(AcquirerService $acquirerService)
    {
        $this->acquirerService = $acquirerService;
    }

    public function createContactFormRequest(CreeateContactFormRequest $contactFormRequest)
    {
        $validatedData = $contactFormRequest->validated();
        $validatedData['business_profile_id'] = $this->acquirerService->get("businessProfile")->id;

        ContactRequest::create($validatedData);

        $contactRequest = ContactFormRequestMapper::mapContactFormRequestToResponse($validatedData);
        return new CreateContactFormResponse("Request submitted successfully", $contactRequest, 201);
    }

    public function getContactFormRequest($contact_request_id)
    {
        $businessProfile = $this->acquirerService->get("businessProfile");
        $contactRequest = ContactRequest::where('id', $contact_request_id)->first();

        if (!$contactRequest || $businessProfile->id != $contactRequest->business_profile_id) {
            return ErrorResponseEnum::$BPNF404;
        }

        $contactRequest = ContactFormRequestMapper::mapContactFormRequestToResponse($contactRequest->toArray());

        return new getContactFormRequestResponse($contactRequest, 200);
    }

    public function getContactFormRequestList(GetContactFormListRequest $getContactFormListRequest)
    {
        $businessProfile_id = $this->acquirerService->get("businessProfile")->id;

        $query = ContactRequest::query();
        ContactRequestFilter::applyFilters($query, $businessProfile_id, $getContactFormListRequest);

        $contactRequests = Pagination::set($getContactFormListRequest, $query);

        $contactRequestList = $contactRequests->map(function ($contactRequest) {
            return ContactFormRequestMapper::mapContactFormRequestToResponse($contactRequest->toArray());
        });

        return new getContactFormRequestResponse($contactRequestList, ['current_page' => $contactRequests->currentPage(), 'last_page' => $contactRequests->lastPage(), 'per_page' => $contactRequests->perPage(), 'total' => $contactRequests->total(), 'next_page_url' => $contactRequests->nextPageUrl(), 'prev_page_url' => $contactRequests->previousPageUrl()], 200);


    }

}
