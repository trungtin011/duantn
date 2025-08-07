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
use App\Models\ProductCategory;
use App\Models\ProductVariant as Variant;
use App\Models\AdsCampaign;
use App\Models\AdsCampaignItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

        // Log số lượng danh mục/thương hiệu trong database
        Log::info('Tổng số danh mục trong database:', ['số lượng' => Category::count()]);
        Log::info('Tổng số thương hiệu trong database:', ['số lượng' => Brand::count()]);

        // Log yêu cầu
        Log::info('Yêu cầu bộ lọc nhận được:', [
            'từ khóa tìm kiếm' => $query,
            'danh mục đã chọn' => $categoryIds,
            'thương hiệu đã chọn' => $brandIds,
            'giá tối thiểu' => $priceMin,
            'giá tối đa' => $priceMax,
            'sắp xếp' => $sort
        ]);

        // Xóa cache khi không có bộ lọc
        if (empty($categoryIds) && empty($brandIds) && !$priceMin && !$priceMax) {
            Cache::forget('all_categories');
            Cache::forget('all_brands');
        }

        // Lấy tất cả danh mục
        $categories = Cache::remember('all_categories', 600, function () {
            return Category::with(['subCategories.subCategories'])
                ->whereNull('parent_id') // Lấy tất cả danh mục cha
                ->select('id', 'name', 'parent_id')
                ->get()
                ->map(function ($cat) {
                    $cat->product_count = $cat->products()->where('status', 'active')->count();
                    foreach ($cat->subCategories as $sub) {
                        $sub->product_count = $sub->products()->where('status', 'active')->count();
                        foreach ($sub->subCategories as $sub2) {
                            $sub2->product_count = $sub2->products()->where('status', 'active')->count();
                            $sub->product_count += $sub2->product_count;
                        }
                        $cat->product_count += $sub->product_count;
                    }
                    return $cat;
                });
        });

        // Lấy tất cả thương hiệu
        $brands = Cache::remember('all_brands', 600, function () {
            return Brand::with(['subBrands'])
                ->whereNull('parent_id') // Lấy tất cả thương hiệu cha
                ->select('id', 'name', 'parent_id')
                ->get()
                ->map(function ($brand) {
                    $brand->product_count = $brand->products()->where('status', 'active')->count();
                    foreach ($brand->subBrands as $sub) {
                        $sub->product_count = $sub->products()->where('status', 'active')->count();
                        $brand->product_count += $sub->product_count;
                    }
                    return $brand;
                });
        });

        // Truy vấn sản phẩm
        $productQuery = Product::query()
            ->when($query, fn($q) => $q->where('name', 'like', "%$query%"))
            ->when($categoryIds, fn($q) => $q->whereHas('categories', fn($q2) => $q2->whereIn('categories.id', $categoryIds)))
            ->when($brandIds, fn($q) => $q->whereHas('brands', fn($q2) => $q2->whereIn('brands.id', $brandIds)))
            ->when($priceMin, fn($q) => $q->where('sale_price', '>=', $priceMin))
            ->when($priceMax, fn($q) => $q->where('sale_price', '<=', $priceMax));

        // Lấy các sản phẩm quảng cáo
        $advertisedProducts = collect();
        if ($query) {
            $advertisedCampaigns = AdsCampaign::where('status', 'active')
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->whereHas('adsCampaignItems.product', function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%");
                })
                ->with(['adsCampaignItems.product.images', 'adsCampaignItems.product.shop'])
                ->get();

            foreach ($advertisedCampaigns as $campaign) {
                foreach ($campaign->adsCampaignItems as $item) {
                    // Chỉ thêm sản phẩm nếu tên của nó chứa từ khóa tìm kiếm
                    if ($item->product && Str::contains(strtolower($item->product->name), strtolower($query))) {
                        // Gán tên chiến dịch vào thuộc tính mới của sản phẩm
                        $item->product->ads_campaign_name = $campaign->name;
                        $advertisedProducts->push($item->product);
                    }
                }
            }
            // Lọc các sản phẩm quảng cáo trùng lặp và chỉ lấy tối đa 4 sản phẩm
            $advertisedProducts = $advertisedProducts->unique('id')->take(4);
        }

        // Áp dụng sắp xếp cho các sản phẩm không quảng cáo
        $productQuery->when($sort, fn($q) => match ($sort) {
            'price_asc' => $q->orderBy('sale_price', 'asc'),
            'price_desc' => $q->orderBy('sale_price', 'desc'),
            'sold' => $q->orderBy('sold_quantity', 'desc'),
            'newest' => $q->orderBy('created_at', 'desc'),
            default => $q->orderBy('id', 'desc'),
        })
        ->where('status', 'active')
        ->with(['categories', 'brands', 'images']);

        // Lấy ID của các sản phẩm quảng cáo để loại trừ khỏi kết quả tìm kiếm thông thường
        $advertisedProductIds = $advertisedProducts->pluck('id')->toArray();

        // Loại trừ các sản phẩm quảng cáo khỏi truy vấn chính
        if (!empty($advertisedProductIds)) {
            $productQuery->whereNotIn('id', $advertisedProductIds);
        }

        $products = $productQuery->paginate(20);

        Log::info('✅ Tổng số sản phẩm khớp:', ['số lượng' => $products->total()]);
        Log::info('✅ Danh mục và thương hiệu đã lấy:', ['danh mục' => $categories->count(), 'thương hiệu' => $brands->count()]);

        // Trong phương thức search, phần AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.product_list', compact('products', 'advertisedProducts'))->render(),
                'categories_html' => view('partials.category_filters', compact('categories'))->render(),
                'brands_html' => view('partials.brand_filters', compact('brands'))->render(),
                // 'advertised_html' => view('partials.advertised_products', compact('advertisedProducts'))->render(), // Không render riêng nữa
            ]);
        }

        return view('user.search.results', compact('products', 'query', 'categories', 'brands', 'advertisedProducts')); // Truyền sản phẩm quảng cáo
    }

    public function show(Request $request, $slug)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->guest(route('login'))->with('error', 'Bạn cần đăng nhập để xem chi tiết sản phẩm');
        }

        $product = Product::with([
            'images',
            'variants.attributeValues.attribute',
            'variants.images',
            'shop.coupons',
            'orderReviews.user',
            'orderReviews.images',
            'orderReviews.videos',
            'orderReviews.likes',
            'categories',
            'brands'
        ])->where('slug', $slug)->firstOrFail();
        
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
        $mostLikedCount = $product->orderReviews()->withCount('likes')->having('likes_count', '>', 0)->count();

        // Xử lý bộ lọc
        $filter = $request->input('filter');
        $query = $product->orderReviews()->with(['user', 'images', 'videos', 'likes']);

        if ($filter === 'images') {
            $query->where(function ($q) {
                $q->whereHas('images')->orWhereHas('videos');
            });
        } 
        elseif ($filter === 'comments') {
            $query->whereNotNull('comment');
        } 
        elseif (Str::startsWith($filter, 'star-')) {
            $rating = (int) Str::after($filter, 'star-');
            $query->where('rating', $rating);
        }

        // Sắp xếp theo filter
        if ($filter === 'most-liked') {
            $query->withCount('likes')->orderByDesc('likes_count');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $filteredReviews = $query->paginate(10);

        // Gán thêm thông tin like cho từng review
        $authId = Auth::id();
        foreach ($filteredReviews as $review) {
            // Đảm bảo quan hệ likes được load
            if (!$review->relationLoaded('likes')) {
                $review->load('likes');
            }
            
            $review->likes_count = $review->likes ? $review->likes->count() : 0;
            $review->liked_by_auth = $authId && $review->likes ? $review->likes->where('user_id', $authId)->isNotEmpty() : false;
            
            Log::info('Main Review like info:', [
                'review_id' => $review->id,
                'likes_count' => $review->likes_count,
                'liked_by_auth' => $review->liked_by_auth,
                'auth_id' => $authId,
                'likes_loaded' => $review->relationLoaded('likes'),
                'likes_count_actual' => $review->likes ? $review->likes->count() : 'null',
                'likes_collection' => $review->likes ? $review->likes->toArray() : 'null'
            ]);
        }

        $attributeImages = [];
        $variantData = [];
        foreach ($product->variants as $variant) {
            $attributeValues = $variant->attributeValues->keyBy('attribute.name');
            $image = $variant->images->first()->image_path ?? null;

            foreach ($attributeValues as $attrName => $attrValue) {
                $attributeImages[$attrName][$attrValue->value] = $image
                    ? Storage::url($image)
                    : asset('images/default_product_image.png');
            }

            $variantData[$variant->id] = [
                'price' => $variant->getCurrentPriceAttribute(),
                'original_price' => $variant->price,
                'stock' => $variant->stock, // Số lượng từ biến thể
                'image' => $image
                    ? Storage::url($image)
                    : asset('images/default_product_image.png'),
                'discount_percentage' => $variant->getDiscountPercentageAttribute(),
            ];
        }

        // Nếu không có biến thể, sử dụng thông tin từ sản phẩm đơn
        if ($product->variants->isEmpty()) {
            $variantData['default'] = [
                'price' => $product->getCurrentPriceAttribute(),
                'original_price' => $product->price,
                'stock' => $product->stock_total, // Số lượng từ stock_total
                'image' => $product->images->first()
                    ? Storage::url($product->images->first()->image_path)
                    : asset('images/default_product_image.png'),
                'discount_percentage' => $product->getDiscountPercentageAttribute(),
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

        // Lựa chọn biến thể mặc định
        if ($request->has('variant_id')) {
            $selectedVariant = $product->variants->find($request->variant_id);
        } elseif ($product->variants->isNotEmpty()) {
            $selectedVariant = $product->variants->first(); // Chọn biến thể đầu tiên làm mặc định
        }

        // Số lượng mặc định dựa trên biến thể hoặc sản phẩm đơn
        $defaultStock = $selectedVariant ? $selectedVariant->stock : ($product->variants->isEmpty() ? $product->stock_total : 0);

        $viewed = session()->get('viewed_products', []);
        $viewed = array_unique(array_merge([$product->id], $viewed));
        session()->put('viewed_products', array_slice($viewed, 0, 10));

        $recentProducts = Cache::remember("related_products_{$product->id}", 3600, function () use ($product) {
            Log::info('Fetching related products for product ID: ' . $product->id);

            $categoryIds = $product->categories->pluck('id');

            $products = Product::where('status', 'active')
                ->where('id', '!=', $product->id)
                ->whereHas('categories', function ($query) use ($categoryIds) {
                    $query->whereIn('category_id', $categoryIds);
                })
                ->with('images')
                ->take(8)
                ->get();

            Log::info('Found ' . $products->count() . ' related products by categories');

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

        $isWishlisted = Auth::check() ? Wishlist::where('userID', Auth::id())->where('productID', $product->id)->exists() : false;

        $savedCoupons = [];
        if (Auth::check() && $product->shop) {
            $savedCoupons = CouponUser::where('user_id', Auth::id())
                ->whereIn('coupon_id', $product->shop->coupons->pluck('id'))
                ->pluck('coupon_id')
                ->toArray();
        }

        $hasPurchased = Auth::check() ? Order::where('userID', Auth::id())
            ->where('order_status', 'delivered')
            ->whereHas('items', function ($query) use ($product) {
                $query->where('productID', $product->id);
            })
            ->exists() : false;

        if ($request->ajax()) {
            // Gán thêm thông tin like cho từng review
            $authId = Auth::id();
            foreach ($filteredReviews as $review) {
                // Đảm bảo quan hệ likes được load
                if (!$review->relationLoaded('likes')) {
                    $review->load('likes');
                }
                
                $review->likes_count = $review->likes ? $review->likes->count() : 0;
                $review->liked_by_auth = $authId && $review->likes ? $review->likes->where('user_id', $authId)->isNotEmpty() : false;
                
                Log::info('AJAX Review like info:', [
                    'review_id' => $review->id,
                    'likes_count' => $review->likes_count,
                    'liked_by_auth' => $review->liked_by_auth,
                    'auth_id' => $authId,
                    'likes_loaded' => $review->relationLoaded('likes'),
                    'likes_count_actual' => $review->likes ? $review->likes->count() : 'null',
                    'likes_collection' => $review->likes ? $review->likes->toArray() : 'null'
                ]);
            }
            return view('partials.review_list', ['reviews' => $filteredReviews]);
        }

        // Gán thêm thông tin like cho từng review
        $authId = Auth::id();
        foreach ($filteredReviews as $review) {
            // Đảm bảo quan hệ likes được load
            if (!$review->relationLoaded('likes')) {
                $review->load('likes');
            }
            
            $review->likes_count = $review->likes ? $review->likes->count() : 0;
            $review->liked_by_auth = $authId && $review->likes ? $review->likes->where('user_id', $authId)->isNotEmpty() : false;
            
            Log::info('Main Review like info:', [
                'review_id' => $review->id,
                'likes_count' => $review->likes_count,
                'liked_by_auth' => $review->liked_by_auth,
                'auth_id' => $authId,
                'likes_loaded' => $review->relationLoaded('likes'),
                'likes_count_actual' => $review->likes ? $review->likes->count() : 'null'
            ]);
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
            'mostLikedCount' => $mostLikedCount,
            'defaultStock' => $defaultStock, // Thêm số lượng mặc định
        ]);
    }

    protected function getProductReviewStats($product)
    {
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

        return [$averageRating, $totalReviews, $ratingCounts, $commentCount, $mediaCount];
    }

    protected function getFilteredReviews($product, $filter)
    {
        $query = $product->orderReviews()->with(['user', 'images', 'videos', 'likes']);

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

        // Sắp xếp theo filter
        if ($filter === 'most-liked') {
            $query->withCount('likes')->orderByDesc('likes_count');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $reviews = $query->paginate(10);
        
        // Đảm bảo quan hệ likes được load cho từng review
        $authId = Auth::id();
        foreach ($reviews as $review) {
            if (!$review->relationLoaded('likes')) {
                $review->load('likes');
            }
            $review->likes_count = $review->likes ? $review->likes->count() : 0;
            $review->liked_by_auth = $authId && $review->likes ? $review->likes->where('user_id', $authId)->isNotEmpty() : false;
            
            Log::info('Filtered Review like info:', [
                'review_id' => $review->id,
                'likes_count' => $review->likes_count,
                'liked_by_auth' => $review->liked_by_auth,
                'auth_id' => $authId,
                'likes_loaded' => $review->relationLoaded('likes'),
                'likes_count_actual' => $review->likes ? $review->likes->count() : 'null'
            ]);
        }
        
        return $reviews;
    }

    protected function getVariantImagesAndData($product)
    {
        $attributeImages = [];
        $variantData = [];
        foreach ($product->variants as $variant) {
            $attributeValues = $variant->attributeValues->keyBy('attribute.name');
            $image = $variant->images->first()->image_path ?? null;

            foreach ($attributeValues as $attrName => $attrValue) {
                $attributeImages[$attrName][$attrValue->value] = $image
                    ? Storage::url($image)
                    : asset('images/default_product_image.png');
            }

            $variantData[$variant->id] = [
                'price' => $variant->getCurrentPriceAttribute(),
                'original_price' => $variant->price,
                'stock' => $variant->stock,
                'image' => $image
                    ? Storage::url($image)
                    : asset('images/default_product_image.png'),
                'discount_percentage' => $variant->getDiscountPercentageAttribute(),
            ];
        }

        if ($product->variants->isEmpty()) {
            $variantData['default'] = [
                'price' => $product->getCurrentPriceAttribute(),
                'original_price' => $product->price,
                'stock' => $product->stock_total,
                'image' => $product->images->first()
                    ? Storage::url($product->images->first()->image_path)
                    : asset('images/default_product_image.png'),
                'discount_percentage' => $product->getDiscountPercentageAttribute(),
            ];
        }

        Log::info('Variants for product ID: ' . $product->id . ', count: ' . $product->variants->count());
        foreach ($product->variants as $variant) {
            Log::info('Variant ID: ' . $variant->id . ', images count: ' . $variant->images->count() . ', attributeValues count: ' . $variant->attributeValues->count());
        }

        return [$attributeImages, $variantData];
    }

    protected function getSelectedVariant($product, $request)
    {
        if ($request->has('variant_id')) {
            return $product->variants->find($request->variant_id);
        } elseif ($product->variants->isNotEmpty()) {
            return $product->variants->first();
        }
        return null;
    }

    protected function updateViewedProductsSession($productId)
    {
        $viewed = session()->get('viewed_products', []);
        $viewed = array_unique(array_merge([$productId], $viewed));
        session()->put('viewed_products', array_slice($viewed, 0, 10));

        // Lấy sản phẩm liên quan theo danh mục (Phần này sẽ được xử lý ở nơi khác nếu cần)
        // Hiện tại loại bỏ để tránh lỗi biến $product không được định nghĩa
        /*
        $recentProducts = Cache::remember("related_products_{\$product->id}", 3600, function () use ($product) {
            Log::info('Fetching related products for product ID: ' . $product->id);

            // Lấy danh sách category_id từ bảng product_categories
            $categoryIds = $product->categories->pluck('id');

            $products = Product::where('status', 'active')
                ->where('id', '!=', $product->id)
                ->whereHas('categories', function ($query) use ($categoryIds) {
                    $query->whereIn('category_id', $categoryIds);
                })
                ->with('images')
                ->take(8)
                ->get();

            Log::info('Found ' . $products->count() . ' related products by categories');

            // Nếu không đủ sản phẩm liên quan, lấy thêm sản phẩm ngẫu nhiên
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
        */
    }

    protected function checkIsWishlisted($product)
    {
        if (Auth::check()) {
            return Wishlist::where('userID', Auth::id())
                ->where('productID', $product->id)
                ->exists();
        }
        return false;
    }

    protected function getSavedCoupons($product)
    {
        if (Auth::check() && $product->shop) {
            return CouponUser::where('user_id', Auth::id())
                ->whereIn('coupon_id', $product->shop->coupons->pluck('id'))
                ->pluck('coupon_id')
                ->toArray();
        }
        return [];
    }

    protected function checkHasPurchased($product)
    {
        if (Auth::check()) {
            return Order::where('userID', Auth::id())
                ->where('order_status', 'delivered')
                ->whereHas('items', function ($query) use ($product) {
                    $query->where('productID', $product->id);
                })
                ->exists();
        }
        return false;
    }

    protected function checkHasReviewed($product)
    {
        if (Auth::check()) {
            return OrderReview::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->exists();
        }
        return false;
    }

    public function quickView($slug)
    {
        try {
            $product = Product::with([
                'images' => function ($query) {
                    $query->select('id', 'productID', 'image_path', 'is_default');
                },
                'variants' => function ($query) {
                    $query->select('id', 'productID', 'price', 'sale_price', 'stock')
                        ->with([
                            'images' => function ($q) {
                                $q->select('id', 'variantID', 'image_path');
                            },
                            'attributeValues' => function ($q) {
                                $q->select('attribute_values.id', 'attribute_values.attribute_id', 'attribute_values.value')
                                    ->with(['attribute' => function ($qa) {
                                        $qa->select('id', 'name');
                                    }]);
                            }
                        ]);
                }
            ])->where('slug', $slug)->firstOrFail();

            // Gán dữ liệu hiển thị theo biến thể
            $attributeImages = [];
            $variantData = [];
            foreach ($product->variants as $variant) {
                $attributeValues = $variant->attributeValues?->keyBy(fn($val) => $val->attribute->name) ?? collect();
                $image = $variant->images->first()->image_path ?? null;

                // Gán ảnh theo giá trị thuộc tính
                foreach ($attributeValues as $attrName => $attrValue) {
                    $attributeImages[$attrName][$attrValue->value] = $image
                        ? Storage::url($image)
                        : asset('storage/product_images/default.jpg');
                }

                // ✅ Thêm attributes vào từng biến thể
                $attributesArray = $variant->attributeValues->mapWithKeys(function ($attr) {
                    return [$attr->attribute->name => $attr->value];
                })->toArray();

                $variantData[$variant->id] = [
                    'price' => $variant->sale_price ?? $variant->price,
                    'original_price' => $variant->price,
                    'stock' => $variant->stock,
                    'image' => $variant->images->first()
                        ? Storage::url($variant->images->first()->image_path)
                        : asset('storage/product_images/default.jpg'),
                    'discount_percentage' => $variant->sale_price
                        ? round((($variant->price - $variant->sale_price) / $variant->price) * 100)
                        : 0,
                    'attributes' => $variant->attributeValues->mapWithKeys(function ($attr) {
                        return [$attr->attribute->name => $attr->value];
                    })->toArray(),
                ];
            }

            // Nếu không có biến thể → mặc định
            if ($product->variants->isEmpty()) {
                $variantData['default'] = [
                    'price' => $product->sale_price ?? $product->price,
                    'original_price' => $product->price,
                    'stock' => $product->stock_total,
                    'image' => $product->images->first()
                        ? Storage::url($product->images->first()->image_path)
                        : asset('storage/product_images/default.jpg'),
                    'discount_percentage' => $product->sale_price
                        ? round((($product->price - $product->sale_price) / $product->price) * 100)
                        : 0,
                    'attributes' => [] // Không có thuộc tính
                ];
            }

            // Chuẩn bị danh sách thuộc tính cho view
            $attributes = [];
            foreach ($product->variants as $variant) {
                foreach ($variant->attributeValues as $attributeValue) {
                    $attributeName = $attributeValue->attribute->name;
                    $value = $attributeValue->value;
                    if (!isset($attributes[$attributeName])) {
                        $attributes[$attributeName] = collect();
                    }
                    if (!$attributes[$attributeName]->contains($value)) {
                        $attributes[$attributeName]->push($value);
                    }
                }
            }

            Log::info('QuickView Product:', [
                'id' => $product->id,
                'variants_count' => $product->variants->count(),
                'variantData' => $variantData,
                'attributeImages' => $attributeImages
            ]);

            $html = view('partials.quick_view', compact('product', 'attributeImages', 'variantData', 'attributes'))->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'variantData' => $variantData
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('QuickView Error: Product not found for slug ' . $slug);
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại!'
            ], 404);
        } catch (\Exception $e) {
            Log::error('QuickView Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra khi tải chi tiết sản phẩm!'
            ], 500);
        }
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
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn cần đăng nhập để thêm sản phẩm vào danh sách yêu thích!'
                ], 401);
            }

            $product = Product::find($productId);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không tồn tại!'
                ], 404);
            }

            $wishlist = Wishlist::where('userID', $user->id)
                ->where('productID', $productId)
                ->first();

            if ($wishlist) {
                $wishlist->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa khỏi danh sách yêu thích!',
                    'isWishlisted' => false
                ]);
            }

            Wishlist::create([
                'userID' => $user->id,
                'productID' => $productId,
                'shopID' => $product->shopID
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào danh sách yêu thích!',
                'isWishlisted' => true
            ]);
        } catch (\Exception $e) {
            Log::error('ToggleWishlist Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra!'
            ], 500);
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

    public function instantBuy(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Bạn cần đăng nhập để mua sản phẩm'], 401);
        }

        // Validate request
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:variants,id',
            'quantity' => 'required|integer|min:1',
            'shop_id' => 'required|exists:shops,id'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if variant exists and belongs to product
        $variant = null;
        if ($request->variant_id) {
            $variant = Variant::where('id', $request->variant_id)
                ->where('product_id', $product->id)
                ->firstOrFail();
        }

        // Check stock availability
        $availableStock = $variant ? $variant->stock : $product->stock_total;
        if ($request->quantity > $availableStock) {
            return response()->json([
                'message' => 'Số lượng vượt quá tồn kho!',
                'available_stock' => $availableStock
            ], 400);
        }

        // Store checkout data in session for direct checkout
        $checkoutData = [
            'items' => [
                [
                    'product_id' => $product->id,
                    'variant_id' => $request->variant_id,
                    'quantity' => $request->quantity,
                    'shop_id' => $request->shop_id,
                    'product' => $product,
                    'variant' => $variant
                ]
            ],
            'is_instant_buy' => true
        ];

        session(['checkout_data' => $checkoutData]);

        return response()->json([
            'message' => 'Chuyển hướng đến trang thanh toán',
            'redirect_url' => route('checkout.index')
        ], 200);
    }

    public function likeReview(Request $request, $reviewId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để thích đánh giá!'], 401);
            }

            $review = \App\Models\OrderReview::findOrFail($reviewId);
            $like = \App\Models\ReviewLike::where('user_id', $user->id)->where('order_review_id', $reviewId)->first();

            if ($like) {
                $like->delete();
                $liked = false;
                $message = 'Đã bỏ thích đánh giá!';
            } else {
                \App\Models\ReviewLike::create([
                    'user_id' => $user->id,
                    'order_review_id' => $reviewId,
                ]);
                $liked = true;
                $message = 'Đã thích đánh giá!';
            }

            // Reload review để lấy số lượng like mới nhất
            $review->refresh();
            $likeCount = $review->likes()->count();

            Log::info('Like review response:', [
                'review_id' => $reviewId,
                'user_id' => $user->id,
                'liked' => $liked,
                'like_count' => $likeCount,
                'message' => $message
            ]);

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'like_count' => $likeCount,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Error in likeReview:', [
                'error' => $e->getMessage(),
                'review_id' => $reviewId,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thích đánh giá!'
            ], 500);
        }
    }
}
