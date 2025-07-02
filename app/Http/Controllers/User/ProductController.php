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
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{

    public function search(Request $request)
    {
        $query = $request->input('query');
        $categoryIds = $request->input('category', []);
        $brandIds = $request->input('brand', []);
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');
        $sort = $request->input('sort', 'relevance');

        // Lấy tên các danh mục cha + con
        $categoryNames = collect();
        if (!empty($categoryIds)) {
            $categories = Category::whereIn('id', $categoryIds)->with('subCategories')->get();
            $categoryNames = $categories->flatMap(function ($cat) {
                return collect([$cat->name])->merge($cat->subCategories->pluck('name'));
            });
        }

        // Tương tự cho thương hiệu (chưa cần lấy sub-brand)
        $brandNames = collect();
        if (!empty($brandIds)) {
            $brandNames = Brand::whereIn('id', $brandIds)->pluck('name');
        }

        // Truy vấn sản phẩm
        $products = Product::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
            })
            ->when($categoryNames->isNotEmpty(), function ($q) use ($categoryNames) {
                $q->whereIn('category', $categoryNames);
            })
            ->when($brandNames->isNotEmpty(), function ($q) use ($brandNames) {
                $q->whereIn('brand', $brandNames);
            })
            ->when($priceMin, fn($q) => $q->where('sale_price', '>=', $priceMin))
            ->when($priceMax, fn($q) => $q->where('sale_price', '<=', $priceMax))
            ->when($sort, function ($q) use ($sort) {
                switch ($sort) {
                    case 'price_asc':
                        $q->orderBy('sale_price', 'asc');
                        break;
                    case 'price_desc':
                        $q->orderBy('sale_price', 'desc');
                        break;
                    case 'sold':
                        $q->orderBy('sold_quantity', 'desc');
                        break;
                    case 'newest':
                        $q->orderBy('created_at', 'desc');
                        break;
                    default:
                        $q->orderBy('id', 'desc');
                        break;
                }
            })
            ->where('status', 'active')
            ->with(['category', 'brand']) // nếu bạn dùng quan hệ thật
            ->paginate(20);

        // Đếm sản phẩm theo danh mục cha + con
        $categories = Category::whereNull('parent_id')->with('subCategories')->get()->map(function ($category) {
            $category->product_count = Product::where('category', $category->name)->count();

            foreach ($category->subCategories as $sub) {
                $sub->product_count = Product::where('category', $sub->name)->count();
                $category->product_count += $sub->product_count;
            }

            return $category;
        });

        // Đếm sản phẩm theo thương hiệu cha + con
        $brands = Brand::whereNull('parent_id')->with('subBrands')->get()->map(function ($brand) {
            $brand->product_count = Product::where('brand', $brand->name)->count();

            foreach ($brand->subBrands as $sub) {
                $sub->product_count = Product::where('brand', $sub->name)->count();
                $brand->product_count += $sub->product_count;
            }

            return $brand;
        });

        // Gắn lại đường dẫn ảnh nếu thiếu
        $products->getCollection()->transform(function ($product) {
            if ($product->image_path && !Str::startsWith($product->image_path, 'product_images/')) {
                $product->image_path = 'product_images/' . $product->image_path;
            }
            return $product;
        });

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.product_list', compact('products'))->render()
            ]);
        }

        return view('user.search.results', compact('products', 'query', 'categories', 'brands'));
    }


    public function show(Request $request, $slug)
    {
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

        // Tính trung bình rating và số lượng đánh giá
        $averageRating = Cache::remember("product_{$product->id}_average_rating", 3600, function () use ($product) {
            return $product->orderReviews()->avg('rating') ?? 0;
        });
        $totalReviews = $product->orderReviews()->count();
        $ratingCounts = [
            '5' => $product->orderReviews()->where('rating', 5)->count(),
            '4' => $product->orderReviews()->where('rating', 4)->count(),
            '3' => $product->orderReviews()->where('rating', 3)->count(),
            '2' => $product->orderReviews()->where('rating', 2)->count(),
            '1' => $product->orderReviews()->where('rating', 1)->count(),
        ];
        $commentCount = $product->orderReviews()->whereNotNull('comment')->count();
        $mediaCount = $product->orderReviews()->whereHas('images')->orWhereHas('videos')->count();

        // Xử lý bộ lọc
        $filter = $request->input('filter');
        $query = $product->orderReviews()->with(['user', 'images', 'videos']);

        // Áp dụng bộ lọc trước khi phân trang
        if ($filter === 'images') {
            $query->where(function ($q) {
                $q->whereHas('images')->orWhereHas('videos');
            });
        } elseif ($filter === 'comments') {
            $query->whereNotNull('comment');
        } elseif (Str::startsWith($filter, 'star-')) {
            $rating = (int) Str::after($filter, 'star-');
            $query->where('rating', $rating);
        }

        // Lấy danh sách đánh giá với phân trang
        $filteredReviews = $query->orderBy('created_at', 'desc')->paginate(10);

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

        Log::info('Variants for product ID: ' . $product->id . ', count: ' . $product->variants->count());
        foreach ($product->variants as $variant) {
            Log::info('Variant ID: ' . $variant->id . ', images count: ' . $variant->images->count() . ', attributeValues count: ' . $variant->attributeValues->count());
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

        // Lấy sản phẩm liên quan theo danh mục
        $recentProducts = Cache::remember("related_products_{$product->id}", 3600, function () use ($product) {
            Log::info('Fetching related products for product ID: ' . $product->id . ', category: ' . $product->category);

            $products = Product::where('category', $product->category)
                ->where('id', '!=', $product->id)
                ->where('status', 'active')
                ->with('images')
                ->take(8)
                ->get();

            Log::info('Found ' . $products->count() . ' related products by category');

            // Nếu không tìm thấy sản phẩm cùng danh mục, lấy sản phẩm ngẫu nhiên
            if ($products->count() < 4) {
                $additionalProducts = Product::where('id', '!=', $product->id)
                    ->where('status', 'active')
                    ->with('images')
                    ->take(8 - $products->count())
                    ->inRandomOrder()
                    ->get();
                Log::info('Added ' . $additionalProducts->count() . ' random products');
                $products = $products->merge($additionalProducts);
            }

            return $products;
        });

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

        if ($request->ajax()) {
            return view('partials.review_list', ['reviews' => $filteredReviews]);
        }

        return view('user.product.product_detail', [
            'product' => $product,
            'filteredReviews' => $filteredReviews,
            'filter' => $filter,
            'recentProducts' => $recentProducts,
            'shop' => $product->shop,
            'logoPath' => $logoPath,
            'hasPurchased' => $hasPurchased,
            'reviews' => $filteredReviews,
            'attributeImages' => $attributeImages,
            'variantData' => $variantData,
            'selectedVariant' => $selectedVariant,
            'hasReviewed' => $hasReviewed,
            'isWishlisted' => $isWishlisted,
            'savedCoupons' => $savedCoupons,
            'averageRating' => number_format($averageRating, 1),
            'totalReviews' => $totalReviews,
            'ratingCounts' => $ratingCounts,
            'commentCount' => $commentCount,
            'mediaCount' => $mediaCount,
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
