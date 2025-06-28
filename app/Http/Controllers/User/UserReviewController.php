<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ReviewMedia;
use Illuminate\Support\Facades\Auth;

class UserReviewController extends Controller
{
    public function create(Request $request)
{
    $productId = $request->productID;
    $shopId = $request->shopID;

    return view('user.reviews.create', compact('productId', 'shopId'));
}

    public function store(Request $request)
    {
        $request->validate([
            'productID' => 'required|exists:products,id',
            'shopID' => 'required|exists:shops,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'videos.*' => 'nullable|mimetypes:video/mp4,video/quicktime|max:10240',
        ]);

        // Tạo đánh giá mới
        $review = Review::create([
            'userID' => Auth::id(),
            'productID' => $request->productID,
            'shopID' => $request->shopID,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Lưu ảnh nếu có
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('review_images', 'public');
                ReviewMedia::create([
                    'review_id' => $review->id,
                    'type' => 'image',
                    'path' => $path,
                ]);
            }
        }

        // Lưu video nếu có
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $path = $video->store('review_videos', 'public');
                ReviewMedia::create([
                    'review_id' => $review->id,
                    'type' => 'video',
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('user.orders')->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}
