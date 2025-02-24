<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactForm\CreeateContactFormRequest;
use App\Http\Requests\ContactForm\GetContactFormListRequest;
use App\Http\Services\AcquirerService;
use App\Http\Services\ContactRequestFormService;

class ContactRequestFormController extends Controller
{

    protected AcquirerService $acquirerService;
    protected ContactRequestFormService $contactRequestFormService;

    public function __construct(AcquirerService $acquirerService, ContactRequestFormService $contactRequestFormService)
    {
        $this->acquirerService = $acquirerService;
        $this->contactRequestFormService = $contactRequestFormService;
    }

    public function createContactFormRequest(CreeateContactFormRequest $contactFormRequest)
    {
//        $this->acquirerService->hasAuthorityOrThrowException("createContactFormRequest");
        return $this->contactRequestFormService->createContactFormRequest($contactFormRequest);
    }

    public function getContactFormRequest($contact_request_id)
    {
        $this->acquirerService->hasAuthorityOrThrowException("getContactFormRequest");
        return $this->contactRequestFormService->getContactFormRequest($contact_request_id);
    }

    public function getContactFormRequestList(GetContactFormListRequest $getContactFormListRequest)
    {
        $this->acquirerService->hasAuthorityOrThrowException("getContactFormRequestList");
        return $this->contactRequestFormService->getContactFormRequestList($getContactFormListRequest);
    }

    public function deleteContactFormRequest($contactId)
    {
        $this->acquirerService->hasAuthorityOrThrowException("deleteContactFormRequest");
        return $this->contactRequestFormService->deleteContactFormRequest($contactId);
    }

}
