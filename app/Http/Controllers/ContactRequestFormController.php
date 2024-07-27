<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactForm\CreeateContactFormRequest;
use App\Http\Requests\ContactForm\GetContactFormListRequest;
use App\Http\Services\ContactRequestFormService;

class ContactRequestFormController extends Controller
{

    protected ContactRequestFormService $contactRequestFormService;

    public function __construct(ContactRequestFormService $contactRequestFormService)
    {
        $this->contactRequestFormService = $contactRequestFormService;
    }

    public function createContactFormRequest(CreeateContactFormRequest $contactFormRequest)
    {
        return $this->contactRequestFormService->createContactFormRequest($contactFormRequest);
    }

    public function getContactFormRequest($contact_request_id)
    {
        return $this->contactRequestFormService->getContactFormRequest($contact_request_id);
    }

    public function getContactFormRequestList(GetContactFormListRequest $getContactFormListRequest)
    {
        return $this->contactRequestFormService->getContactFormRequestList($getContactFormListRequest);
    }

    public function deleteContactFormRequest($contactId)
    {
        return $this->contactRequestFormService->deleteContactFormRequest($contactId);
    }

}
