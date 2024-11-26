<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Http\Responses\Error\ErrorResponse;
use App\Http\Responses\Auth\SignUpResponse;
use App\Http\Services\AcquirerService;
use App\Http\Services\AuthService;
use App\Http\Services\UserCredService;
use http\Env\Request;

class AuthController extends Controller
{
    protected AuthService $authService;
    protected AcquirerService $acquirerService;
    protected UserCredService $userCredService;

    public function __construct(AuthService $authService, AcquirerService $acquirerService, UserCredService $userCredService)
    {
        $this->authService = $authService;
        $this->acquirerService = $acquirerService;
        $this->userCredService = $userCredService;
    }

    public function signUp(SignUpRequest $request): SignUpResponse|ErrorResponse
    {
        $this->acquirerService->hasAuthorityOrThrowException("createUser");
        return $this->authService->signUp($request);
    }

    public function login(LoginRequest $loginRequest)
    {
        return $this->authService->login($loginRequest);
    }
    public function verifyJwt()
    {
        return $this->authService->verifyJwt();
    }


}
