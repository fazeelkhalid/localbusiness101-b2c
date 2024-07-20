<?php

use App\Enums\ErrorResponseEnum;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AcquirerApiKeyMiddleware;
use App\Http\Middleware\JsonResponseMiddleware;
use App\Http\Middleware\LogApiRequestsMiddleware;
use App\Http\Middleware\ValidateJwtTokenMiddleware;
use App\Http\Services\UserCredService;
use Illuminate\Support\Facades\Route;

Route::middleware([LogApiRequestsMiddleware::class, AcquirerApiKeyMiddleware::class])->group(function () {
    Route::post('/signup', [AuthController::class, 'signUp']);
});

Route::middleware([JsonResponseMiddleware::class, ValidateJwtTokenMiddleware::class])->group(function () {
    Route::post('/hey', [AuthController::class, 'hey']);
});

Route::fallback(function () {
    return ErrorResponseEnum::$RNE404;
});
