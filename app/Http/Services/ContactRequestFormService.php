<?php

namespace App\Http\Services;

use App\Enums\ErrorResponseEnum;
use App\Http\Filters\ContactRequestFilter;
use App\Http\Mapper\ContactFormRequestMapper;
use App\Http\Pagination\Pagination;
use App\Http\Requests\ContactForm\CreeateContactFormRequest;
use App\Http\Requests\ContactForm\GetContactFormListRequest;
use App\Http\Responses\ContactForm\CreateContactFormResponse;
use App\Http\Responses\ContactForm\DeleteContactFormResponse;
use App\Http\Responses\ContactForm\getContactFormResponse;
use App\Http\Services\Client\HttpNotificationService;
use App\Models\BusinessProfile;
use App\Models\ContactRequest;

class ContactRequestFormService
{
    protected AcquirerService $acquirerService;

    public function __construct(AcquirerService $acquirerService)
    {
        $this->acquirerService = $acquirerService;
    }

    public function createContactFormRequest(CreeateContactFormRequest $contactFormRequest)
    {
        $validatedData = $contactFormRequest->validated();
        $businessProfile = $this->acquirerService->get("businessProfile");
        $validatedData['business_profile_id'] = $businessProfile->id;
        ContactRequest::create($validatedData);

        $businessProfile = BusinessProfile::getBusinessProfileFullDetails()->where('id', $businessProfile->id)->first();

        if(!$businessProfile){
            return ErrorResponseEnum::$BPNF404;
        }
        $contactRequest = $businessProfile->contactDetails->first();
        HttpNotificationService::sendContactUsEmail($validatedData, $contactRequest->business_email);
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

        return new getContactFormResponse($contactRequest, 200);
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

        return new getContactFormResponse($contactRequestList, ['current_page' => $contactRequests->currentPage(), 'last_page' => $contactRequests->lastPage(), 'per_page' => $contactRequests->perPage(), 'total' => $contactRequests->total(), 'next_page_url' => $contactRequests->nextPageUrl(), 'prev_page_url' => $contactRequests->previousPageUrl()], 200);
    }

    public function deleteContactFormRequest($contactId)
    {
        $businessProfileId = $this->acquirerService->get("businessProfile")->id;

        $contactRequest = ContactRequest::where('id', $contactId)
            ->where('business_profile_id', $businessProfileId)
            ->first();

        if (!$contactRequest ) {
            return ErrorResponseEnum::$BPNF404;
        }

        $contactRequest->delete();

        return new DeleteContactFormResponse('Contact request deleted successfully', 200);
    }

}
