<?php

namespace App\Http\Services;

use App\Enums\ApplicationConfigurationEnum;
use App\Models\ApplicationConfiguration;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;

class TwilioService
{
    protected AcquirerService $acquirerService;

    public function __construct( AcquirerService $acquirerService)
    {
        $this->acquirerService = $acquirerService;
    }

    public function generateTwilioToken()
    {
        $acquirer = $this->acquirerService->get("acquirer");
        $identity = $acquirer->key;

        $twilioTokenExpireTime = (integer)(ApplicationConfiguration::getApplicationConfiguration(ApplicationConfigurationEnum::TWILIO_TOKEN_EXPIRY_TIME) ?? 3600);

        $token = new AccessToken(
            env('TWILIO_ACCOUNT_SID'),
            env('TWILIO_API_KEY'),
            env('TWILIO_API_SECRET'),
            $twilioTokenExpireTime,
            $identity
        );

        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid(env('TWILIO_APP_SID'));
        $voiceGrant->setIncomingAllow(true);

        $token->addGrant($voiceGrant);

        return response()->json([
            'token' => $token->toJWT(),
            'identity' => $identity,
        ]);
    }


}
