<?php

namespace App\Http\Middleware;

use App\Services\AuthRequestService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateJwtTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader) {
            return response()->json(['error' => 'Authorization header not found'], 401);
        }

        $jwtToken = str_replace('Bearer ', '', $authorizationHeader);
        $response = AuthRequestService::request('/api/validate-jwt-token', 'POST', [], $jwtToken);

        if ($response->successful()) {
            return $next($request);
        }
        return response()->json([ "Authorization" => $response->json()], 401);
    }
}
