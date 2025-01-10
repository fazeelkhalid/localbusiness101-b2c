<?php

use App\Enums\ErrorResponseEnum;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessCategoryController;
use App\Http\Controllers\BusinessProfileAnalyticsController;
use App\Http\Controllers\ClientLogsController;
use App\Http\Controllers\ContactRequestFormController;
use App\Http\Controllers\InitController;
use App\Http\Controllers\LaravelCommandController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\UserBusinessProfileController;
use App\Http\Middleware\AcquirerApiKeyMiddleware;
use App\Http\Middleware\FetchAcquirerBusinessProfileMiddleware;
use App\Http\Middleware\JsonResponseMiddleware;
use App\Http\Middleware\LogApiRequestsMiddleware;
use App\Http\Middleware\ValidateJwtTokenMiddleware;
use Illuminate\Support\Facades\Route;


Route::get('/sitemap.xml', [SitemapController::class, 'index']);

Route::middleware([LogApiRequestsMiddleware::class, JsonResponseMiddleware::class])->group(function () {

    Route::post('/business-profile/{slug}/analytics', [BusinessProfileAnalyticsController::class, 'generateAnalytics']);
    Route::get('/business-profile/{slug}/analytics', [BusinessProfileAnalyticsController::class, 'sendAnalyticsReport']);

    Route::post('/payment', [PaymentController::class, 'createPayment']);
    Route::get('/payment/{payment_id}', [PaymentController::class, 'getPayment']);
    Route::put('/payment/{payment_id}', [PaymentController::class, 'updatePaymentStatus']);

    Route::post("/image-host", [LaravelCommandController::class, 'imageHost']);

    Route::post("/login", [AuthController::class, 'login']);

    Route::get('/categories', [BusinessCategoryController::class, 'getBusinessCategoriesList']);
    Route::get('/categories_name_list', [BusinessCategoryController::class, 'getBusinessCategoriesNameList']);
    Route::get('/init', [InitController::class, 'init']);

    Route::put('/business_profile/{business_profiles_key}', [UserBusinessProfileController::class, 'updateUserBusinessProfile']);
    Route::get('/business_profile/{business_profiles_key}', [UserBusinessProfileController::class, 'getUserBusinessProfile']);
    Route::get('/business_profile/slug/{business_profiles_slugs}', [UserBusinessProfileController::class, 'getUserBusinessProfileBySlugs']);
    Route::get('/business_profiles', [UserBusinessProfileController::class, 'getUserBusinessProfileList']);

    Route::middleware([AcquirerApiKeyMiddleware::class, FetchAcquirerBusinessProfileMiddleware::class])->group(function () {
        Route::get('/migrate', [LaravelCommandController::class, 'migrate']);
        Route::get('/storage-link', [LaravelCommandController::class, 'createStorageLink']);
        Route::get('/migrate-rollback', [LaravelCommandController::class, 'rollback']);

        Route::post('/review', [ReviewController::class, 'createReview']);
        Route::get('/reviews', [ReviewController::class, 'getProfileReviewAndRatingList']);


        Route::get('/dump-logs', [ClientLogsController::class, 'clientLogs']);
        Route::post('/contact_request', [ContactRequestFormController::class, 'createContactFormRequest']);


        Route::middleware([ValidateJwtTokenMiddleware::class])->group(function () {
            Route::get('/verify', [AuthController::class, 'verifyJwt']);
            Route::post('/category', [BusinessCategoryController::class, 'createCategory']);
            Route::post('/business_profile', [UserBusinessProfileController::class, 'createUserBusinessProfile']);
            Route::get('/business_profile_stats', [ClientLogsController::class, 'fetchBusinessProfileStats']);
            Route::get('/contact_request/{contact_request_id}', [ContactRequestFormController::class, 'getContactFormRequest']);
            Route::get('/contact_requests', [ContactRequestFormController::class, 'getContactFormRequestList']);
            Route::delete('/contact_requests/{contactId}', [ContactRequestFormController::class, 'deleteContactFormRequest']);
        });
    });
});

Route::fallback(function () {
    return ErrorResponseEnum::$RNE404;
});
