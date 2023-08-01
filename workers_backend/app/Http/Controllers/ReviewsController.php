<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeReviewRequest;
use App\Models\Review;

class ReviewsController extends Controller
{
    public function addReview(storeReviewRequest $request)
    {
        $data = $request->all();
        $data['client_id'] = auth()->guard('clients')->id();
        $review = Review::create($data);
        return response()->json([
            'message' => "Thank you for your review",
            'review' => $review
        ]);
    }
    public function getPostReviews($id)
    {
        $reviews = Review::wherePostId($id)->with('client:id,name')->get();
        $average = $reviews->sum('rate') / $reviews->count();

        return response()->json([
            'average rate' => round($average, 1),
            'reviews' => $reviews
        ]);
    }
}
