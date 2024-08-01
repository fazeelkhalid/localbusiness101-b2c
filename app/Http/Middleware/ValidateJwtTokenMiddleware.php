<?php

namespace App\Http\Middleware;

use App\Enums\ErrorResponseEnum;
use App\Exceptions\ErrorException;
use App\Http\Services\AcquirerService;
use App\Http\Services\AuthRequestService;
use App\Http\Services\UserCredService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateJwtTokenMiddleware
{
    protected UserCredService $userCredService;
    protected AcquirerService $acquirerService;


    public function __construct(UserCredService $userCredService, AcquirerService $acquirerService)
    {
        $this->userCredService = $userCredService;
        $this->acquirerService = $acquirerService;
    }

    public function handle(Request $request, Closure $next)
    {
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader) {
            return response()->json(['error' => 'Authorization header not found'], 401);
        }

        $jwtToken = str_replace('Bearer ', '', $authorizationHeader);
        $response = AuthRequestService::request('/api/validate-jwt-token', 'POST', [], $jwtToken);

        if (!$response->successful()) {
            return response()->json(["Authorization" => $response->json()], 401);
        }

        $jsonResponse = $response->json();
        $acquirer = $this->acquirerService->get("acquirer");

        if($acquirer->user->email !== $jsonResponse['user']['email']){
            return ErrorResponseEnum::$UAA401;
        }

        $this->userCredService->set('email', $jsonResponse['user']['email']);
        $this->userCredService->set('token', $authorizationHeader);

        return $next($request);
    }
}
