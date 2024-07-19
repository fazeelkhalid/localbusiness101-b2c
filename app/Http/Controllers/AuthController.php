<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUp\SignUpRequest;
use App\Http\Responses\Error\ErrorResponse;
use App\Http\Responses\SignUp\SignUpResponse;
use App\Http\Services\AcquirerService;
use App\Http\Services\AuthService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(title="Auth API", version="1.0")
 */

class AuthController extends Controller
{
    protected AuthService $authService;
    protected AcquirerService $acquirerService;
    public function __construct(AuthService $authService, AcquirerService $acquirerService)
    {
        $this->authService = $authService;
        $this->acquirerService = $acquirerService;
    }


    /**
     * @OA\Post(
     *     path="/signup",
     *     operationId="signup",
     *     tags={"Auth"},
     *     summary="User signup",
     *     description="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", example="password123"),
     *             @OA\Property(property="application_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User created successfully"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                 @OA\Property(property="application_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array",
     *                     @OA\Items(type="string", example="The email has already been taken for this application.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function signUp(SignUpRequest $request): SignUpResponse | ErrorResponse
    {
        $this->acquirerService->hasAuthorityOrThrowException("createUser");
        return $this->authService->signUp($request);
    }
}
