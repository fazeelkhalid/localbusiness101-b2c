<?php

namespace App\Http\Services;

use App\Enums\ErrorResponseEnum;
use App\Exceptions\ErrorException;
use App\Http\Mapper\AuthMapper;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Http\Responses\Auth\LoginResponse;
use App\Http\Responses\Auth\verifyResponse;
use App\Http\Responses\Error\ErrorResponse;
use App\Http\Responses\Auth\SignUpResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class AuthService
{
    protected AcquirerService $acquirerService;

    public function __construct(AcquirerService $acquirerService)
    {
        $this->acquirerService = $acquirerService;
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

    public function login(LoginRequest $loginRequest)
    {
        $cred = $loginRequest->validated();
        $authServiceResponse = AuthRequestService::request('/api/login', 'POST', $cred);
        if (!$authServiceResponse->successful()) {
            $authServiceErrorExceptionResponse= AuthMapper::mapAuthServiceErrorResponse($authServiceResponse);
            throw new ErrorException("Invalid email or password.", $authServiceErrorExceptionResponse, $authServiceResponse->status());
        }
        $userAcquirerKey = User::with('acquirer')->where('email', $cred['email'])->first()->acquirer->key;
        $loginResponse = AuthMapper::mapLoginResponse($authServiceResponse, $userAcquirerKey);
        return new LoginResponse('Login Successfully', $loginResponse, 200);
    }
    public function verifyJwt(Request $request) {

        $authorizationHeader = $request->header('Authorization');
        $jwtToken = str_replace('Bearer ', '', $authorizationHeader);

        $response = AuthRequestService::request('/api/refresh-jwt-token', 'POST', [], $jwtToken);

        if (!$response->successful()) {
            return ErrorResponseEnum::$INVALID_JWT_TOKEN;
        }
        $jsonResponse = $response->json();

        return new verifyResponse('User has been verified', $jsonResponse, 200);
    }

}
