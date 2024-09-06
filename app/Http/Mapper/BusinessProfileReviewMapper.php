<?php
namespace App\Http\Mapper;


class BusinessProfileReviewMapper
{

    public static function MapReviewDBTOReview($review)
    {
        return [
            'id' => $review->id,
            'name' => $review->name ?? "Anonymous User",
            'email' => $review->email,
            'review' => $review->review ?? "",
            'rating' => $review->rating,
        ];
    }

}
