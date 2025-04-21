<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Services\AcquirerService;
use App\Http\Services\UserService;

class UserController extends Controller
{
    protected AcquirerService $acquirerService;
    protected UserService $userService;

    public function __construct(AcquirerService $acquirerService, UserService $userService)
    {
        $this->acquirerService = $acquirerService;
        $this->userService = $userService;
    }

    public function createUser(CreateUserRequest $createUserRequest)
    {
//        $this->acquirerService->hasAuthorityOrThrowException("createUser");
        return $this->userService->createUser($createUserRequest);
    }

}
