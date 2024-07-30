<?php

namespace App\Http\Services;

use App\Enums\ErrorResponseEnum;
use App\Http\Requests\Auth\SignUpRequest;
use App\Http\Responses\Error\ErrorResponse;
use App\Http\Responses\Auth\SignUpResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ConfigurationService
{

    protected AcquirerService $acquirerService;

    public function __construct(AcquirerService $acquirerService)
    {
        $this->acquirerService = $acquirerService;
    }

    /**
     * @return AcquirerService
     */
    public function getConfigurationValue($configurationCode)
    {
        $acquirer = $this->acquirerService->get("acquirer");
        $configuration = $acquirer->configurations->where('config_code', 'ip_and_port_restrictions')->first();
        if($configuration){
            return $configuration->value;
        }
        else{
            return null;
        }
    }

    public function signUp(SignupRequest $request): SignUpResponse | ErrorResponse
    {

        $acquirer = $this->acquirerService->get('acquirer');


        $data = $request->validated();
        $data['application_id'] = $acquirer->application->id;

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'], [
                'rounds' => 12,
                'key' => $acquirer->application->hash_key,
            ]),
            'application_id' => $acquirer->application->id,
        ]);

        return new SignUpResponse('User created successfully', $user, 201);

    }
}
