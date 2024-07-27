<?php

use App\Enums\ErrorResponseEnum;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactRequestController;
use App\Http\Controllers\UserBusinessProfileController;
use App\Http\Middleware\AcquirerApiKeyMiddleware;
use App\Http\Middleware\FetchAcquirerBusinessProfileMiddleware;
use App\Http\Middleware\JsonResponseMiddleware;
use App\Http\Middleware\LogApiRequestsMiddleware;
use App\Http\Middleware\ValidateJwtTokenMiddleware;
use App\Http\Services\UserCredService;
use Illuminate\Support\Facades\Route;
//LogApiRequestsMiddleware::class

Route::middleware([JsonResponseMiddleware::class, AcquirerApiKeyMiddleware::class, FetchAcquirerBusinessProfileMiddleware::class])->group(function () {
    Route::post('/contact-request', [ContactRequestController::class, 'createContactRequest']);
    Route::get('/business_profile/{business_profiles_key}', [UserBusinessProfileController::class, 'getUserBusinessProfileController']);
});

Route::middleware([JsonResponseMiddleware::class])->group(function () {


    Route::post('/business_profile', [UserBusinessProfileController::class, 'createUserBusinessProfileController']);
    Route::put('/business_profile/{business_profiles_key}', [UserBusinessProfileController::class, 'updateUserBusinessProfileController']);
    Route::get('/business_profile/{business_profiles_key}', [UserBusinessProfileController::class, 'getUserBusinessProfileController']);
    Route::get('/business_profiles', [UserBusinessProfileController::class, 'getUserBusinessProfileListController']);
    //    Route::post('/business_profile', [UserBusinessProfileController::class, 'createUserBusinessProfileController']);

});
Route::fallback(function () {
    return ErrorResponseEnum::$RNE404;
});
