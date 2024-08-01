<?php

namespace App\Http\Responses\Review;

use Illuminate\Contracts\Support\Responsable;

class getReviewsListResponse implements Responsable
{
    protected $reviews;
    protected $avgRating;
    protected $pagination;
    protected $status;

    public function __construct($reviews, $avgRating, $pagination, int $status = 422)
    {
        $this->avgRating = $avgRating;
        $this->reviews = $reviews;
        $this->status = $status;
        $this->pagination = $pagination;
    }

    public function toResponse($request)
    {
        return response()->json([
            "avgRating" => $this->avgRating,
            "reviews" => $this->reviews,
            "pagination" => [
                'current_page' => $this->pagination->currentPage(),
                'last_page' => $this->pagination->lastPage(),
                'per_page' => $this->pagination->perPage(),
                'total' => $this->pagination->total(),
                'next_page_url' => $this->pagination->nextPageUrl(),
                'prev_page_url' => $this->pagination->previousPageUrl()
            ]
        ], $this->status);
    }
}
