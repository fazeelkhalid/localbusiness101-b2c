<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Services\AcquirerService;
use App\Http\Services\BusinessProfileReviewService;

class ReviewController extends Controller
{
    protected AcquirerService $acquirerService;
    protected BusinessProfileReviewService $businessProfileReviewService;

    public function __construct(AcquirerService $acquirerService, BusinessProfileReviewService $businessProfileReviewService)
    {
        $this->acquirerService = $acquirerService;
        $this->businessProfileReviewService = $businessProfileReviewService;
    }

    public function createReview(StoreReviewRequest $request)
    {
        $this->acquirerService->hasAuthorityOrThrowException("createReview");
        return $this->businessProfileReviewService->createReview($request);
    }

    public function getProfileReviewAndRatingList()
    {
        $this->acquirerService->hasAuthorityOrThrowException("getProfileReviewAndRatingList");
        return $this->businessProfileReviewService->getProfileReviewAndRatingList();
    }

}
