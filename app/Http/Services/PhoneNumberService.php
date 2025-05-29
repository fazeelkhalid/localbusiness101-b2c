<?php

namespace App\Http\Services;

use App\Enums\Configuration;
use App\Enums\ConfigurationEnum;
use App\Enums\ErrorResponseEnum;
use App\Exceptions\ErrorException;
use App\Http\Mapper\PhoneNumberMapper;
use App\Http\Requests\PhoneNumber\VerifyPhoneNumberRequest;
use App\Http\Responses\PhoneNumber\GetUserPhoneNumberResponses;
use App\Http\Responses\PhoneNumber\verifyPhoneNumberResponses;
use Carbon\Carbon;
use Ramsey\Uuid\Type\Integer;

class PhoneNumberService
{
    protected AcquirerService $acquirerService;
    protected ConfigurationService $configurationService;

    public function __construct(AcquirerService $acquirerService, ConfigurationService $configurationService)
    {
        $this->acquirerService = $acquirerService;
        $this->configurationService = $configurationService;
    }


    public function getPhoneNumbers()
    {
        $acquirer = $this->acquirerService->get("acquirer");

        $allowedPhoneNumbers = $acquirer->user->allowedPhoneNumbers;

        if ($allowedPhoneNumbers->isEmpty()) {
            return new GetUserPhoneNumberResponses([], "No phone numbers assigned to this user.");
        }

        $allowedPhoneNumbersVM = PhoneNumberMapper::mapUserPhoneNumberDomainToUserPhoneNumberVM($allowedPhoneNumbers);
        return new GetUserPhoneNumberResponses($allowedPhoneNumbersVM, "Assigned Phone Numbers");
    }

    /**
     * @throws ErrorException
     */
    public function verifyPhoneNumbers(VerifyPhoneNumberRequest $verifyPhoneNumberRequest)
    {
        $verifyPhoneNumberRequest = $verifyPhoneNumberRequest->validated();
        $fromNumber = $verifyPhoneNumberRequest["from"];

        $this->validateAndGetUserPhoneNumber($fromNumber);
        $allowCallRecording = $this->configurationService->getConfigurationValueByKey(ConfigurationEnum::$ALLOW_CALL_RECORDING);
        $callDelayLatency = $this->getCallDelayLatency();

        $allowedPhoneNumbersVM = PhoneNumberMapper::mapVerifyPhoneNumberDomainToVM($allowCallRecording, $callDelayLatency);
        return new verifyPhoneNumberResponses($allowedPhoneNumbersVM, "Phone Number verified");
    }


    public function validateAndGetUserPhoneNumber($fromNumber)
    {
        $acquirer = $this->acquirerService->get("acquirer");
        $allowedPhoneNumbers = $acquirer->user->allowedPhoneNumbers;

        if ($allowedPhoneNumbers->isEmpty()) {
            throw new ErrorException("No Number Assign. Please contact support", null,404);
        }

        $matchedPhone = $allowedPhoneNumbers->first(function ($phone) use ($fromNumber) {
            return $phone->phone_number === $fromNumber;
        });

        if (!$matchedPhone) {
            throw new ErrorException("Number is invalid or not assigned to your account. Please select another number", null, 404);
        }

        return $matchedPhone;
    }


    private function getCallDelayLatency()
    {
        $acquirer = $this->acquirerService->get("acquirer");
        $twelveHoursAgo = Carbon::now()->subHours(12);
        $callLogsCount = $acquirer->user->callLogs()
            ->where('created_at', '>=', $twelveHoursAgo)
            ->count();

        $callResponseDelay = (Integer)$this->configurationService->getConfigurationValueByKey(ConfigurationEnum:: $CALL_DELAY_LATENCY);

        if ($callLogsCount > 200) {
            $delayMin = 5;
            $delayMax = $callResponseDelay + 5;
        }
        else{
            $delayMin = 0;
            $delayMax = $callResponseDelay;
        }

        return rand($delayMin, $delayMax);

    }
}
