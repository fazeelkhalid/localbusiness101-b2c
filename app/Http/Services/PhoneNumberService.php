<?php

namespace App\Http\Services;

use App\Enums\ErrorResponseEnum;
use App\Http\Mapper\DigitalCardMapper;
use App\Http\Mapper\PhoneNumberMapper;
use App\Http\Requests\DigitalCard\CreateDigitalCardRequest;
use App\Http\Responses\DigitalCard\CreateDigitalCardResponses;
use App\Http\Responses\DigitalCard\GetDigitalCardResponses;
use App\Http\Responses\PhoneNumber\GetUserPhoneNumberResponses;
use App\Http\Utils\CustomUtils;
use App\Models\DigitalCard;
use App\Models\OfficeHour;
use App\Models\PaymentMethod;
use Exception;
use Illuminate\Support\Facades\DB;
class PhoneNumberService
{
    protected AcquirerService $acquirerService;

    public function __construct(AcquirerService $acquirerService)
    {
        $this->acquirerService = $acquirerService;
    }


    public function getPhoneNumbers()
    {
        $acquirer = $this->acquirerService->get("acquirer");

        $allowedPhoneNumbers = $acquirer->user->allowedPhoneNumbers;

        if($allowedPhoneNumbers->isEmpty()){
            return new GetUserPhoneNumberResponses([], "No phone numbers assigned to this user.");
        }

        $allowedPhoneNumbersVM = PhoneNumberMapper::mapUserPhoneNumberDomainToUserPhoneNumberVM($allowedPhoneNumbers);
        return new GetUserPhoneNumberResponses($allowedPhoneNumbersVM, "Assigned Phone Numbers");
    }
}
