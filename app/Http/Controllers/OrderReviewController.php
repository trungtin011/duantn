<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderReview;
use App\Models\OrderReviewImage;
use App\Models\OrderReviewVideo;
use Illuminate\Support\Facades\Auth;
class OrderReviewController extends Controller
{
  public function store(Request $request)
{
    $request->validate([
        'orderID' => 'required|exists:orders,id',
        'shopID' => 'required|exists:shops,id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'video' => 'nullable|mimetypes:video/mp4,video/avi,video/mpeg|max:51200',
    ]);

    $order = Order::where('id', $request->orderID)
        ->where('userID', Auth::id())
        ->where('order_status', 'delivered')
        ->firstOrFail();

    foreach ($order->items as $item) {
        $review = OrderReview::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'product_id' => $item->productID,
            'shop_id' => $request->shopID,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('review_images', 'public');
                $review->images()->create(['image_path' => $path]);
            }
        }

        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('review_videos', 'public');
            $review->videos()->create(['video_path' => $videoPath]);
        }
    }

    return redirect()->back()->with('success', 'Đánh giá đã được gửi thành công!');
}

}
