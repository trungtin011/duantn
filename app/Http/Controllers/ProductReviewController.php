<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductReview;
use App\Models\Product;
use App\Models\ReviewImage;

class ProductReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:4096',
            'video' => 'nullable|mimetypes:video/mp4,video/quicktime,video/x-msvideo|max:10240',
        ]);

        // Chỉ đánh giá 1 lần
        if (ProductReview::where('user_id', Auth::id())->where('product_id', $product->id)->exists()) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này.');
        }

        $review = new ProductReview();
        $review->user_id = Auth::id();
        $review->product_id = $product->id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
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
        // Lưu video nếu có
        if ($request->hasFile('video')) {
            $review->video_path = $request->file('video')->store('reviews/videos', 'public');
        }

        $review->save();

        return back()->with('success', 'Đánh giá đã được gửi thành công!');
    }
    public function like($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->likes += 1;
        $review->save();

        return response()->json(['likes' => $review->likes]);
    }
}