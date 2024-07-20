<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUp\SignUpRequest;
use App\Http\Responses\Error\ErrorResponse;
use App\Http\Responses\SignUp\SignUpResponse;
use App\Http\Services\AcquirerService;
use App\Http\Services\AuthService;
use App\Http\Services\UserCredService;
use Illuminate\Http\JsonResponse;

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

    public function signUp(SignUpRequest $request): SignUpResponse | ErrorResponse
    {
        $this->acquirerService->hasAuthorityOrThrowException("createUser");
        return $this->authService->signUp($request);
    }

    public function hey()
    {
        $email = $this->userCredService->get("token");
        print($email);
        die();
    }
}
