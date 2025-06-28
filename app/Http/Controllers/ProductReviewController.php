<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductReview;
use App\Models\Order;
use App\Models\ReviewImage;

class ProductReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:4096',
            'video' => 'nullable|mimetypes:video/mp4,video/quicktime|max:10240',
        ]);

        // Kiểm tra người dùng đã đánh giá đơn hàng này chưa
        if (Review::where('userID', Auth::id())
            ->where('orderID', $request->orderID)
            ->exists()) {
            return back()->with('error', 'Bạn đã đánh giá đơn hàng này.');
        }

        $review = new Review();
        $review->userID = Auth::id();
        $review->orderID = $request->orderID;
        $review->shopID = $request->shopID;
        $review->rating = $request->rating;
        $review->comment = $request->comment;

        // Lưu video nếu có
        if ($request->hasFile('video')) {
            $review->video_path = $request->file('video')->store('reviews/videos', 'public');
        }

        $review->save();

        // Lưu ảnh nếu có
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('reviews', 'public');
                ReviewImage::create([
                    'review_id' => $review->id,
                    'image_path' => $path,
                ]);
            }
        }

        return back()->with('success', 'Đánh giá đã được gửi thành công!');
    }

    public function like($id)
    {
        $review = Review::findOrFail($id);
        $review->likes += 1;
        $review->save();

        return response()->json(['likes' => $review->likes]);
    }
}
