<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderReview;
use App\Models\Review;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'orderID' => 'required|exists:orders,id',
            'shopID' => 'required|exists:shops,id',
            'productID' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi|max:20240',
        ]);

        $userId = Auth::id();
        $order = Order::where('userID', $userId)
            ->where('id', $request->orderID)
            ->where('order_status', 'delivered')
            ->firstOrFail();

        // Kiểm tra xem sản phẩm đã được đánh giá chưa
        $existingReview = OrderReview::where('user_id', $userId)
            ->where('product_id', $request->productID)
            ->where('order_id', $request->orderID)
            ->exists();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Sản phẩm này đã được đánh giá.');
        }

        // Tạo đánh giá
        $review = OrderReview::create([
            'user_id' => $userId,
            'order_id' => $request->orderID,
            'shop_id' => $request->shopID,
            'product_id' => $request->productID,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Xử lý hình ảnh
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('review_images', 'public');
                $review->images()->create(['image_path' => $path]);
            }
        }

        // Xử lý video
        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('review_videos', 'public');
            $review->videos()->create(['video_path' => $path]);
        }

        // Cập nhật rating của sản phẩm
        $product = Product::find($request->productID);
        $product->update([
            'rating' => $product->orderReviews()->avg('rating'),
            'total_reviews' => $product->orderReviews()->count(),
        ]);

        // Cập nhật rating của shop
        $shop = Shop::find($request->shopID);
        $shop->update([
            'shop_rating' => $shop->orderReviews()->avg('rating'),
            'total_ratings' => $shop->orderReviews()->count(),
        ]);

        return redirect()->route('user.order.history')->with('success', 'Đánh giá đã được gửi thành công.');
    }

    public function index(Request $request)
    {
        $seller = Auth::user();

        // Lấy shop của seller
        $shop = $seller->shop;

        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop nào!');
        }

        // Lấy id các sản phẩm thuộc shop
        $productIds = $shop->products()->pluck('id');

        // Truy vấn đánh giá sản phẩm
        $query = OrderReview::with(['product', 'user', 'order'])
            ->whereIn('product_id', $productIds);

        // Lọc theo tên sản phẩm
        if ($request->filled('product')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->product . '%');
            });
        }

        // Lọc theo phân loại hàng (giả sử bạn có cột `variation`)
        if ($request->filled('variation')) {
            $query->where('variation', 'like', '%' . $request->variation . '%');
        }

        // Lọc theo tên người mua
        if ($request->filled('buyer')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->buyer . '%');
            });
        }

        // Lọc theo ngày đánh giá
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Lọc theo sao
        if ($request->filled('star')) {
            $query->where('rating', $request->star);
        }

        // Lọc theo trạng thái phản hồi
        if ($request->replied === 'yes') {
            $query->whereNotNull('seller_reply');
        } elseif ($request->replied === 'no') {
            $query->whereNull('seller_reply');
        }

        // Sắp xếp & phân trang
        $reviews = $query->orderBy('created_at', 'desc')->paginate(10);

        // Tính điểm trung bình đánh giá
        $averageRating = OrderReview::whereIn('product_id', $productIds)->avg('rating');

        return view('seller.reviews.index', [
            'reviews' => $reviews,
            'averageRating' => $averageRating,
        ]);
    }
    public function show($id)
    {
        $review = Review::with('user', 'product.shop', 'product.images')->find($id);
        return view('seller.reviews.show', compact('review'));
    }

    public function destroy($id)
    {
        $review = Review::find($id);

        if ($review) {
            $review->delete();
            return redirect()->route('seller.reviews.index')->with('success', 'Đánh giá đã được xóa thành công!');
        }

        return redirect()->route('seller.reviews.index')->with('error', 'Không tìm thấy đánh giá!');
    }
    public function reply(Request $request, $id)
    {
        $request->validate([
            'seller_reply' => 'required|string|max:1000',
        ]);

        $review = OrderReview::with('product.shop')->findOrFail($id);

        if ($review->product->shop->ownerID !== Auth::id()) {
            abort(403, 'Bạn không có quyền phản hồi đánh giá này.');
        }

        if ($review->seller_reply) {
            return redirect()->back()->with('error', 'Bạn đã phản hồi đánh giá này rồi.');
        }

        $review->seller_reply = $request->seller_reply;
        $review->save();

        return redirect()->back()->with('success', 'Phản hồi của bạn đã được gửi.');
    }
}
