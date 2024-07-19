<?php

use App\Enums\ErrorResponseEnum;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AcquirerApiKeyMiddleware;
use App\Http\Middleware\LogApiRequestsMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([LogApiRequestsMiddleware::class, AcquirerApiKeyMiddleware::class])->group(function () {
    Route::post('/signup', [AuthController::class, 'signUp']);
});

Route::post('/hey', function(){
    return "Hey";
});

Route::fallback(function () {
    return ErrorResponseEnum::$RNE404;
});
