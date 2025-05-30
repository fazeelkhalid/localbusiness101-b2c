<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationConfigurationEnum;
use App\Http\Services\AcquirerService;
use App\Http\Services\AuthService;
use App\Http\Services\TwilioService;
use App\Models\ApplicationConfiguration;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;

class TwilioController extends Controller
{
    protected TwilioService $twilioService;
    protected AcquirerService $acquirerService;

    public function __construct( AcquirerService $acquirerService, TwilioService $twilioService)
    {
        $this->acquirerService = $acquirerService;
        $this->twilioService = $twilioService;
    }

    public function generateTwilioToken()
    {
        $this->acquirerService->hasAuthorityOrThrowException("generateTwilioToken");
        return $this->twilioService->generateTwilioToken();
    }

}
