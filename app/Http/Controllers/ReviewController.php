<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\ReviewVideo;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:10240',
            'shopID' => 'required|exists:shops,id',
        ]);

        $order = Order::with('items')->where('id', $orderId)
            ->where('userID', Auth::id())
            ->where('order_status', 'delivered')
            ->firstOrFail();

        foreach ($order->items as $item) {
            $review = Review::create([
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'shop_id' => $request->shopID,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            // Ảnh
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $img->store('reviews', 'public');
                    $review->images()->create(['image_path' => $path]);
                }
            }

            // Video
            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('reviews', 'public');
                $review->videos()->create(['video_path' => $videoPath]);
            }
        }

        return redirect()->back()->with('success', 'Đánh giá đã được gửi!');
    }
}
