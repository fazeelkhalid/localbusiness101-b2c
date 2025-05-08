<?php

namespace App\Http\Controllers;

use App\Enums\ErrorResponseEnum;
use App\Http\Mapper\PhoneNumberMapper;
use App\Http\Responses\PhoneNumber\GetUserPhoneNumberResponses;
use App\Http\Services\AcquirerService;
use App\Http\Services\PhoneNumberService;

class PhoneNumberController extends Controller
{

    protected AcquirerService $acquirerService;
    protected PhoneNumberService $phoneNumberService;

    public function __construct(AcquirerService $acquirerService, PhoneNumberService $phoneNumberService)
    {
        $this->acquirerService = $acquirerService;
        $this->phoneNumberService = $phoneNumberService;
    }

    public function getPhoneNumbers()
    {
        $this->acquirerService->hasAuthorityOrThrowException("getPhoneNumbers");
        return $this->phoneNumberService->getPhoneNumbers();
    }
}
