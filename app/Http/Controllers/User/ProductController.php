<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Review;
use App\Models\Report;
use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\Shop;
use App\Models\Order;
use App\Models\OrderReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function show(Request $request, $slug)
    {
        $ratingFilter = $request->input('rating');
        $product = Product::with([
            'images',
            'reviews.user',
            'variants.attributeValues.attribute',
            'variants.images',
            'reviews.likes',
            'shop.coupons',
            'orderReviews.user',
            'orderReviews.images',
            'orderReviews.videos'
        ])->where('slug', $slug)->firstOrFail();
        $reviews = $product->orderReviews->sortByDesc('created_at');

        // Gán hình ảnh, giá, và số lượng của biến thể
        $attributeImages = [];
        $variantData = [];
        foreach ($product->variants as $variant) {
            $attributeValues = $variant->attributeValues->keyBy('attribute.name');
            foreach ($attributeValues as $attrName => $attrValue) {
                $image = $variant->images->first()->image_path ?? null;
                $attributeImages[$attrName][$attrValue->value] = $image ?: asset('images/default_product_image.png');
            }
            $variantData[$variant->id] = [
                'price' => $variant->getCurrentPriceAttribute(),
                'original_price' => $variant->price,
                'stock' => $variant->stock,
                'image' => $image ?: asset('images/default_product_image.png'),
                'discount_percentage' => $variant->getDiscountPercentageAttribute(),
            ];
        }

        $hasReviewed = false;
        $selectedVariant = null;
        if (Auth::check()) {
            $hasReviewed = OrderReview::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->exists();
        }
        if ($request->has('variant_id')) {
            $selectedVariant = $product->variants->find($request->variant_id);
        } elseif ($product->variants->isNotEmpty()) {
            $selectedVariant = $product->variants->first();
        }

        $viewed = session()->get('viewed_products', []);
        $viewed = array_unique(array_merge([$product->id], $viewed));
        session()->put('viewed_products', array_slice($viewed, 0, 10));

        $recentProducts = Product::whereIn('id', $viewed)->where('id', '!=', $product->id)->with('images')->get();
        $logoPath = $product->shop ? Storage::url($product->shop->logo) : asset('images/default_shop_logo.png');

        // Kiểm tra trạng thái yêu thích
        $isWishlisted = false;
        if (Auth::check()) {
            $isWishlisted = Wishlist::where('userID', Auth::id())
                ->where('productID', $product->id)
                ->exists();
        }

        // Kiểm tra trạng thái đã lưu của các voucher
        $savedCoupons = [];
        if (Auth::check() && $product->shop) {
            $savedCoupons = CouponUser::where('user_id', Auth::id())
                ->whereIn('coupon_id', $product->shop->coupons->pluck('id'))
                ->pluck('coupon_id')
                ->toArray();
        }

        // Kiểm tra xem người dùng đã mua sản phẩm và đơn hàng đã giao thành công
        $hasPurchased = false;
        if (Auth::check()) {
            $hasPurchased = Order::where('userID', Auth::id())
                ->where('order_status', 'delivered')
                ->whereHas('items', function ($query) use ($product) {
                    $query->where('productID', $product->id);
                })
                ->exists();
        }

        // Kiểm tra xem người dùng đã đánh giá sản phẩm chưa
        $hasReviewed = false;
        if (Auth::check()) {
            $hasReviewed = OrderReview::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->exists();
        }

        $filter = $request->input('filter');
        $filteredReviews = $reviews;
        if ($filter === 'images') {
            $filteredReviews = $reviews->filter(fn($r) => $r->images && $r->images->count() > 0);
        } elseif (Str::startsWith($filter, 'star-')) {
            $rating = (int) Str::after($filter, 'star-');
            $filteredReviews = $reviews->where('rating', $rating);
        }

        $filteredReviews = $filteredReviews->sortByDesc('created_at');

        if ($request->ajax()) {
            return view('partials.review_list', ['reviews' => $filteredReviews]);
        }

        return view('user.product.product_detail', [
            'product' => $product,
            'filteredReviews' => $filteredReviews,
            'ratingFilter' => $ratingFilter,
            'recentProducts' => $recentProducts,
            'shop' => $product->shop,
            'logoPath' => $logoPath,
            'hasPurchased' => $hasPurchased,
            'reviews' => $reviews,
            'attributeImages' => $attributeImages,
            'variantData' => $variantData,
            'selectedVariant' => $selectedVariant,
            'hasReviewed' => $hasReviewed,
            'isWishlisted' => $isWishlisted,
            'savedCoupons' => $savedCoupons,
        ]);
    }

    public function reportProduct(Request $request, Product $product)
    {
        $request->validate([
            'report_type' => 'required|in:product_violation,fake_product,copyright,other',
            'report_content' => 'required|string|max:1000',
            'evidence.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov|max:20480', // Max 20MB per file
            'is_anonymous' => 'boolean',
        ]);

        $evidencePaths = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $path = $file->store('reports/evidence', 'public');
                $evidencePaths[] = Storage::url($path);
            }
        }

        $priority = 'medium';
        if (in_array($request->report_type, ['fake_product', 'copyright'])) {
            $priority = 'high';
        }

        try {
            Log::info('Attempting to create report.', [
                'reporter_id' => Auth::id(),
                'product_id' => $product->id,
                'user_id' => Auth::user()->id,
                'shop_id' => $product->shopID,
                'report_type' => $request->report_type,
                'report_content' => $request->report_content,
                'evidence' => !empty($evidencePaths) ? $evidencePaths : null,
                'priority' => $priority,
                'status' => 'pending',
                'is_anonymous' => $request->boolean('is_anonymous', false),
            ]);

            Report::create([
                'reporter_id' => Auth::id(),
                'product_id' => $product->id,
                'user_id' => Auth::user()->id,
                'shop_id' => $product->shopID,
                'report_type' => $request->report_type,
                'report_content' => $request->report_content,
                'evidence' => !empty($evidencePaths) ? json_encode($evidencePaths) : null,
                'priority' => $priority,
                'status' => 'pending',
                'is_anonymous' => $request->boolean('is_anonymous', false),
            ]);

            Log::info('Report created successfully for product: ' . $product->id);
        } catch (\Exception $e) {
            Log::error('Error creating report for product: ' . $product->id, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi gửi báo cáo. Vui lòng thử lại.');
        }

        return redirect()->back()->with('success', 'Báo cáo của bạn đã được gửi thành công. Chúng tôi sẽ xem xét sớm nhất có thể.');
    }

    public function toggleWishlist(Request $request, $productId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Bạn cần đăng nhập để thêm sản phẩm vào danh sách yêu thích'], 401);
        }

        $product = Product::findOrFail($productId);
        $wishlist = Wishlist::where('userID', $user->id)->where('productID', $productId)->first();

        if ($wishlist) {
            // Nếu sản phẩm đã có trong wishlist, xóa nó
            $wishlist->delete();
            return response()->json(['message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích', 'isWishlisted' => false]);
        } else {
            // Nếu sản phẩm chưa có trong wishlist, thêm mới
            Wishlist::create([
                'userID' => $user->id,
                'productID' => $productId,
                'shopID' => $product->shopID,
                'note' => $request->input('note', null),
            ]);
            return response()->json(['message' => 'Đã thêm sản phẩm vào danh sách yêu thích', 'isWishlisted' => true]);
        }
    }


    public function saveCoupon(Request $request, $couponId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Bạn cần đăng nhập để lưu voucher'], 401);
        }

        $coupon = Coupon::findOrFail($couponId);
        if ($coupon->status !== 'active') {
            return response()->json(['message' => 'Voucher không khả dụng'], 400);
        }

        $existingCoupon = CouponUser::where('user_id', $user->id)->where('coupon_id', $couponId)->first();

        if ($existingCoupon) {
            return response()->json(['message' => 'Voucher đã được lưu', 'isSaved' => true]);
        }

        CouponUser::create([
            'user_id' => $user->id,
            'coupon_id' => $couponId,
        ]);

        return response()->json(['message' => 'Đã lưu voucher thành công', 'isSaved' => true]);
    }

    public function saveAllCoupons(Request $request, $shopId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Bạn cần đăng nhập để lưu voucher'], 401);
        }

        $shop = Shop::findOrFail($shopId);
        $activeCoupons = $shop->coupons()->where('status', 'active')->pluck('id')->toArray();
        $savedCoupons = CouponUser::where('user_id', $user->id)
            ->whereIn('coupon_id', $activeCoupons)
            ->pluck('coupon_id')
            ->toArray();

        $newCoupons = array_diff($activeCoupons, $savedCoupons);

        foreach ($newCoupons as $couponId) {
            CouponUser::create([
                'user_id' => $user->id,
                'coupon_id' => $couponId,
            ]);
        }

        return response()->json(['message' => 'Đã lưu tất cả voucher thành công', 'savedCoupons' => $activeCoupons]);
    }


    public function storeReview(Request $request, $productId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Bạn cần đăng nhập để gửi đánh giá'], 401);
        }

        $product = Product::findOrFail($productId);

        if (!$product->shopID) {
            return response()->json(['message' => 'Sản phẩm không thuộc cửa hàng nào, không thể gửi đánh giá'], 400);
        }

        // Kiểm tra xem người dùng đã mua sản phẩm và đơn hàng đã giao thành công
        $order = Order::where('userID', $user->id)
            ->where('order_status', 'delivered')
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('productID', $productId);
            })
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Bạn chỉ có thể đánh giá sau khi đơn hàng được giao thành công'], 403);
        }

        // Kiểm tra xem người dùng đã đánh giá sản phẩm cho đơn hàng này chưa
        $hasReviewed = OrderReview::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('order_id', $order->id)
            ->exists();

        if ($hasReviewed) {
            return response()->json(['message' => 'Bạn đã đánh giá sản phẩm này rồi'], 403);
        }

        // Xác thực dữ liệu đầu vào
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi|max:10240',
        ]);

        // Lưu đánh giá với order_id và shop_id
        $review = OrderReview::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'order_id' => $order->id,
            'shop_id' => $product->shopID,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Xử lý hình ảnh
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('order_review_images', 'public');
                $review->images()->create(['image_path' => $path]);
            }
        }

        // Xử lý video
        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('order_review_videos', 'public');
            $review->videos()->create(['video_path' => $path]);
        }

        Log::info('Saving review with data:', [
            'user_id' => $user->id,
            'product_id' => $productId,
            'order_id' => $order->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'Đánh giá của bạn đã được gửi thành công'], 200);
    }
}
