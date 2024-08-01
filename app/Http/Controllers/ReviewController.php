<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Services\BusinessProfileReviewService;

class ReviewController extends Controller
{
    protected BusinessProfileReviewService $businessProfileReviewService;

    public function __construct(BusinessProfileReviewService $businessProfileReviewService)
    {
        $this->businessProfileReviewService = $businessProfileReviewService;
    }

    public function createReview(StoreReviewRequest $request)
    {
        return $this->businessProfileReviewService->createReview($request);
    }

    public function getProfileReviewAndRatingList()
    {
        return $this->businessProfileReviewService->getProfileReviewAndRatingList();
    }

}
