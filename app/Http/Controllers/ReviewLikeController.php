<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewLikeController extends Controller
{
    public function toggle(Review $review)
    {
        $user = Auth::user();

        $existingLike = ReviewLike::where('review_id', $review->id)
                                  ->where('user_id', $user->id)
                                  ->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json(['liked' => false, 'likes_count' => $review->likes()->count()]);
        } else {
            ReviewLike::create([
                'user_id' => $user->id,
                'review_id' => $review->id,
            ]);
            return response()->json(['liked' => true, 'likes_count' => $review->likes()->count()]);
        }
    }
}
