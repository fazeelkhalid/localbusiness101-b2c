<?php

namespace App\Http\Services;

use App\Http\Mapper\BusinessProfileReviewMapper;
use App\Http\Pagination\Pagination;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Responses\Review\CreateReviewResponse;
use App\Http\Responses\Review\getReviewsListResponse;
use App\Models\Rating;

class BusinessProfileReviewService
{
    protected AcquirerService $acquirerService;

    public function __construct(AcquirerService $acquirerService)
    {
        $this->acquirerService = $acquirerService;
    }

    public function createReview(StoreReviewRequest $request)
    {

        $businessProfile = $this->acquirerService->get("businessProfile");

        $rating = new Rating([
            'business_profile_id' => $businessProfile->id,
            'email' => $request->email,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);
        $rating->save();

        return new CreateReviewResponse("Review submitted successfully", 201);
    }

    public function getProfileReviewAndRatingList()
    {
        list($averageRating, $businessProfilesReviews, $mappedBusinessProfilesReviews) = $this->getProfileReviews();
        return new getReviewsListResponse($mappedBusinessProfilesReviews, $averageRating, $businessProfilesReviews, 200);
    }

    public function getProfileReviews()
    {
        $businessProfile = $this->acquirerService->get("businessProfile");

        $query = Rating::where('business_profile_id', $businessProfile->id);
        $averageRating = $query->get()->avg('rating');

        $businessProfilesReviews = Pagination::setDefault($query);

        $mappedBusinessProfilesReviews = $businessProfilesReviews->map(function ($businessProfileReview) {
            return BusinessProfileReviewMapper::MapReviewDBTOReview($businessProfileReview);
        });
        return array($averageRating, $businessProfilesReviews, $mappedBusinessProfilesReviews);
    }


}
