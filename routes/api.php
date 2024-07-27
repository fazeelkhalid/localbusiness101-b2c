<?php

use App\Enums\ErrorResponseEnum;
use App\Http\Controllers\ContactRequestFormController;
use App\Http\Controllers\UserBusinessProfileController;
use App\Http\Middleware\AcquirerApiKeyMiddleware;
use App\Http\Middleware\FetchAcquirerBusinessProfileMiddleware;
use App\Http\Middleware\JsonResponseMiddleware;
use App\Http\Middleware\LogApiRequestsMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([LogApiRequestsMiddleware::class, JsonResponseMiddleware::class, AcquirerApiKeyMiddleware::class, FetchAcquirerBusinessProfileMiddleware::class])->group(function () {
    Route::post('/contact_request', [ContactRequestFormController::class, 'createContactFormRequest']);
    Route::get('/contact_request/{contact_request_id}', [ContactRequestFormController::class, 'getContactFormRequest']);
    Route::get('/contact_requests', [ContactRequestFormController::class, 'getContactFormRequestList']);
    Route::delete('/contact_requests/{contactId}', [ContactRequestFormController::class, 'deleteContactFormRequest']);

});

Route::middleware([JsonResponseMiddleware::class])->group(function () {

    Route::post('/business_profile', [UserBusinessProfileController::class, 'createUserBusinessProfile']);
    Route::put('/business_profile/{business_profiles_key}', [UserBusinessProfileController::class, 'updateUserBusinessProfile']);
    Route::get('/business_profile/{business_profiles_key}', [UserBusinessProfileController::class, 'getUserBusinessProfile']);
    Route::get('/business_profiles', [UserBusinessProfileController::class, 'getUserBusinessProfileList']);
});
Route::fallback(function () {
    return ErrorResponseEnum::$RNE404;
});
