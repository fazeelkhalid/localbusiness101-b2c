<?php

use App\Enums\ErrorResponseEnum;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessCategoryController;
use App\Http\Controllers\BusinessProfileAnalyticsController;
use App\Http\Controllers\CallLogController;
use App\Http\Controllers\ClientLogsController;
use App\Http\Controllers\ContactRequestFormController;
use App\Http\Controllers\DigitalCardController;
use App\Http\Controllers\InitController;
use App\Http\Controllers\LaravelCommandController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PhoneNumberController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\UserBusinessProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AcquirerApiKeyMiddleware;
use App\Http\Middleware\FetchAcquirerBusinessProfileMiddleware;
use App\Http\Middleware\JsonResponseMiddleware;
use App\Http\Middleware\LogApiRequestsMiddleware;
use App\Http\Middleware\ValidateJwtTokenMiddleware;
use Illuminate\Support\Facades\Route;
use Modules\Webhook\Controllers\WebhookController;


Route::get('/sitemap.xml', [SitemapController::class, 'index']);


Route::middleware([LogApiRequestsMiddleware::class, JsonResponseMiddleware::class])->group(function () {
    Route::get('start/{start}/end/{end}/sitemap.xml', [SitemapController::class, 'sliceProfiles']);


    Route::post('/webhook', [WebhookController::class, 'dumpWebHook']);

    Route::post('/business-profile/{slug}/analytics', [BusinessProfileAnalyticsController::class, 'generateAnalytics']);
    Route::get('/business-profile/{slug}/analytics', [BusinessProfileAnalyticsController::class, 'sendAnalyticsReport']);

    Route::post('/payment', [PaymentController::class, 'createPayment']);
    Route::get('/payment/{payment_id}', [PaymentController::class, 'getPayment']);
    Route::put('/payment/{payment_id}', [PaymentController::class, 'updatePaymentStatus']);

    Route::post("/login", [AuthController::class, 'login']);

    Route::get('/categories', [BusinessCategoryController::class, 'getBusinessCategoriesList']);
    Route::get('/categories_name_list', [BusinessCategoryController::class, 'getBusinessCategoriesNameList']);
    Route::get('/init', [InitController::class, 'init']);

    Route::get('/business_profile/{business_profiles_key}', [UserBusinessProfileController::class, 'getUserBusinessProfile']);
    Route::get('/business_profile/slug/{business_profiles_slugs}', [UserBusinessProfileController::class, 'getUserBusinessProfileBySlugs']);
    Route::get('/business_profiles', [UserBusinessProfileController::class, 'getUserBusinessProfileList']);

    Route::get('/digital-cards/{slug}', [DigitalCardController::class, 'getDigitalCardBySlug']);

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
            Route::get('/business_profile_stats', [ClientLogsController::class, 'fetchBusinessProfileStats']);
            Route::get('/contact_request/{contact_request_id}', [ContactRequestFormController::class, 'getContactFormRequest']);
            Route::get('/contact_requests', [ContactRequestFormController::class, 'getContactFormRequestList']);
            Route::delete('/contact_requests/{contactId}', [ContactRequestFormController::class, 'deleteContactFormRequest']);

            //BUSINESS PROFILE OPERATION
            Route::post('/business_profile/slug/{business_profiles_slug}', [UserBusinessProfileController::class, 'updateUserBusinessProfile']);
            Route::post('/business_profile', [UserBusinessProfileController::class, 'createUserBusinessProfile']);


            // USERS
            Route::post('/user', [UserController::class, 'createUser']);
            Route::get('/users', [UserController::class, 'getUserList']);

            // DIGITAL CARD
            Route::post('/digital-cards', [DigitalCardController::class, 'createDigitalCard']);

            //PHONE NUMBERS
            Route::get('/phone', [PhoneNumberController::class, 'getPhoneNumbers']);
            Route::post('/verify/number', [PhoneNumberController::class, 'verifyPhoneNumbers']);

            Route::post('/call-log', [CallLogController::class, 'createCallLog']);
            Route::put('/call-log/twilio-sid/{twilio_sid}', [CallLogController::class, 'updateCallLog']);
            Route::get('/call-logs', [CallLogController::class, 'getCallLogList']);
            Route::get('/call-log/{call_sid}/recording', [CallLogController::class, 'getCallLogRecording']);

        });
    });
});

Route::fallback(function () {
    return ErrorResponseEnum::$RNE404;
});
