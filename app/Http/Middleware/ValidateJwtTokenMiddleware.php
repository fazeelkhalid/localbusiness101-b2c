<?php

namespace App\Http\Middleware;

use App\Http\Services\AuthRequestService;
use App\Http\Services\UserCredService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateJwtTokenMiddleware
{
    protected UserCredService $userCredService;

    public function __construct(UserCredService $userCredService)
    {
        $this->userCredService = $userCredService;
    }
    public function handle(Request $request, Closure $next): Response
    {
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader) {
            return response()->json(['error' => 'Authorization header not found'], 401);
        }

        $jwtToken = str_replace('Bearer ', '', $authorizationHeader);
        $response = AuthRequestService::request('/api/validate-jwt-token', 'POST', [], $jwtToken);

        if (!$response->successful()) {
            return response()->json([ "Authorization" => $response->json()], 401);
        }

        $jsonResponse = $response->json();
//        need to save the data locally for this request
        $this->userCredService->set('email', $jsonResponse['user']['email']);
        $this->userCredService->set('token', $authorizationHeader);

        return $next($request);
    }
}