<?php

use Illuminate\Support\Facades\Route;

use App\Enums\ErrorResponseEnum;

use App\Http\Middleware\AcquirerApiKeyMiddleware;
use App\Http\Middleware\ApplicationIpAndPortMiddleware;
use App\Http\Middleware\LogApiRequestsMiddleware;

use App\Http\Controllers\AuthController;

Route::middleware([LogApiRequestsMiddleware::class, AcquirerApiKeyMiddleware::class, ApplicationIpAndPortMiddleware::class])->group(function () {
    Route::post('/signup', [AuthController::class, 'signUp']);
});


Route::fallback(function () {
    return ErrorResponseEnum::$RNE404;
});
