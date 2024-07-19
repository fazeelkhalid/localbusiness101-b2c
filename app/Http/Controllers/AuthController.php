<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUp\SignUpRequest;
use App\Http\Responses\Error\ErrorResponse;
use App\Http\Responses\SignUp\SignUpResponse;
use App\Http\Services\AcquirerService;
use App\Http\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected AuthService $authService;
    protected AcquirerService $acquirerService;
    public function __construct(AuthService $authService, AcquirerService $acquirerService)
    {
        $this->authService = $authService;
        $this->acquirerService = $acquirerService;
    }

    public function signUp(SignUpRequest $request): SignUpResponse | ErrorResponse
    {
        $this->acquirerService->hasAuthorityOrThrowException("createUser");
        return $this->authService->signUp($request);
    }
}
