<?php

use App\Enums\ErrorResponseEnum;
use App\Http\Middleware\AcquirerApiKeyMiddleware;
use App\Http\Middleware\ApplicationIpAndPortMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware([AcquirerApiKeyMiddleware::class, ApplicationIpAndPortMiddleware::class])->group(function () {
    Route::get('/signup', [AuthController::class, 'signUp']);
});


Route::fallback(function () {
    return ErrorResponseEnum::$RNE404;
});
