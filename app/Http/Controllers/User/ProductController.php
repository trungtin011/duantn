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
use App\Services\ProductViewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $productViewService;

    public function __construct(ProductViewService $productViewService)
    {
        $this->productViewService = $productViewService;
    }

    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            $categoryIds = $request->input('category', []);
            $brandIds = $request->input('brand', []);
            $priceMin = $request->input('price_min');
            $priceMax = $request->input('price_max');
            $sort = $request->input('sort', 'relevance');

            // Validate inputs
            $this->validateSearchInputs($request);

            // Log request for debugging
            Log::info('Search request received:', [
                'query' => $query,
                'categoryIds' => $categoryIds,
                'brandIds' => $brandIds,
                'priceMin' => $priceMin,
                'priceMax' => $priceMax,
                'sort' => $sort
            ]);

            // Clear cache when no filters
            if (empty($categoryIds) && empty($brandIds) && !$priceMin && !$priceMax) {
                Cache::forget('all_categories');
                Cache::forget('all_brands');
            }

            // Build base product query
            $baseProductQuery = $this->buildBaseProductQuery($query, $categoryIds, $brandIds, $priceMin, $priceMax);
            
            // Debug: Log the SQL query for price filter
            if ($priceMin || $priceMax) {
                Log::info('Price filter debug:', [
                    'priceMin' => $priceMin,
                    'priceMax' => $priceMax,
                    'sql' => $baseProductQuery->toSql(),
                    'bindings' => $baseProductQuery->getBindings()
                ]);
            }

            // Get relevant categories and brands
            [$relevantCategories, $relevantBrands, $sampleProducts] = $this->getRelevantCategoriesAndBrands($baseProductQuery);

            // Process categories and brands
            $categories = $this->processCategories($relevantCategories, $sampleProducts);
            $brands = $this->processBrands($relevantBrands, $sampleProducts);

            // Get advertised products
            $advertisedProductsByShop = $this->getAdvertisedProducts($query);

            // Build final product query
            $productQuery = clone $baseProductQuery;
            $productQuery = $this->applySorting($productQuery, $sort);
            $productQuery = $this->excludeAdvertisedProducts($productQuery, $advertisedProductsByShop);

            // Get paginated results
            $products = $productQuery->paginate(50);

            // Log results
            Log::info('Search results:', [
                'total_products' => $products->total(),
                'current_page_products' => $products->count(),
                'categories_count' => $categories->count(),
                'brands_count' => $brands->count(),
                'advertised_products_count' => $advertisedProductsByShop->count()
            ]);

            // Handle AJAX request
            if ($request->ajax()) {
                return $this->handleAjaxResponse($products, $categories, $brands, $advertisedProductsByShop, $categoryIds, $brandIds);
            }

            return view('user.search.results', compact('products', 'query', 'categories', 'brands', 'advertisedProductsByShop'));

        } catch (\Exception $e) {
            Log::error('Search error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Có lỗi xảy ra khi tìm kiếm. Vui lòng thử lại.',
                    'details' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return back()->with('error', 'Có lỗi xảy ra khi tìm kiếm. Vui lòng thử lại.');
        }
    }

    private function addProductToShopAds($advertisedProductsByShop, $item, $campaign)
    {
        $shopId = $item->product->shop->id;
        
        if (!$advertisedProductsByShop->has($shopId)) {
            $shop = $item->product->shop;
            
            // Tính toán thông tin shop từ các bảng riêng biệt
            $actualRating = $shop->order_reviews_avg_rating ?? 0;
            $actualFollowers = $shop->followers_count ?? 0;
            
            // Gán thông tin thực tế vào shop object
            $shop->shop_rating = round($actualRating, 1);
            $shop->total_followers = $actualFollowers;
            
            $advertisedProductsByShop->put($shopId, [
                'shop' => $shop,
                'products' => collect(),
                'campaign_name' => $campaign->name,
                'all_campaigns' => collect(),
                'max_bid_amount' => (float) ($campaign->bid_amount ?? 0),
                'bid_amount' => (float) ($campaign->bid_amount ?? 0),
                'top_campaign_id' => $campaign->id,
            ]);
        }
        
        // Gán tên chiến dịch vào thuộc tính mới của sản phẩm
        $item->product->ads_campaign_name = $campaign->name;
        $advertisedProductsByShop->get($shopId)['products']->push($item->product);
        
        // Lưu thông tin chiến dịch
        $advertisedProductsByShop->get($shopId)['all_campaigns']->push([
            'campaign' => $campaign,
            'product' => $item->product
        ]);

        // Cập nhật bid cao nhất và chiến dịch top nếu có chiến dịch bid cao hơn
        $entry = $advertisedProductsByShop->get($shopId);
        $currentMax = (float) ($entry['max_bid_amount'] ?? 0);
        $campaignBid = (float) ($campaign->bid_amount ?? 0);
        if ($campaignBid > $currentMax) {
            $entry['max_bid_amount'] = $campaignBid;
            $entry['bid_amount'] = $campaignBid;
            $entry['campaign_name'] = $campaign->name;
            $entry['top_campaign_id'] = $campaign->id;
            $advertisedProductsByShop->put($shopId, $entry);
        }
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
        ])->where('slug', $slug)->where('status', 'active')->firstOrFail();
        
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

        // Ghi lại lượt xem sản phẩm
        $this->productViewService->recordView($product, $request);

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

    public function showShopAds(Request $request, $shopId)
    {
        $query = $request->get('query');
        
        // Lấy tất cả quảng cáo của shop có liên quan đến từ khóa tìm kiếm
        $shopAds = AdsCampaignItem::with(['product.defaultImage', 'product.shop', 'adsCampaign.shop'])
            ->whereHas('adsCampaign', function ($query) {
                $query->where('status', 'active')
                      ->where('start_date', '<=', now())
                      ->where('end_date', '>=', now());
            })
            ->whereHas('product', function ($query) use ($shopId) {
                $query->where('shopID', $shopId)
                      ->where('status', 'active');
            })
            ->when($query, function ($q) use ($query) {
                $q->whereHas('product', function ($productQuery) use ($query) {
                    $productQuery->where('name', 'like', "%$query%");
                });
            })
            ->get()
            ->groupBy('adsCampaign.id')
            ->map(function ($items, $campaignId) {
                $firstItem = $items->first();
                return [
                    'campaign' => $firstItem->adsCampaign,
                    'products' => $items->map(function ($item) {
                        $item->product->ads_campaign_name = $item->adsCampaign->name;
                        return $item->product;
                    })
                ];
            });

        $shop = \App\Models\Shop::findOrFail($shopId);
        
        return view('user.shop.ads', compact('shopAds', 'shop', 'query'));
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

    public function quickView(Request $request)
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
            ])->where('slug', $request->slug)->where('status', 'active')->firstOrFail();

            // Ghi lại lượt xem sản phẩm
            $this->productViewService->recordView($product, $request);

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
            Log::error('QuickView Error: Product not found for slug ' . $request->slug);
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

        $product = Product::where('status', 'active')->findOrFail($request->product_id);

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

    /**
     * Validate search inputs
     */
    private function validateSearchInputs(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:255',
            'category' => 'nullable|array',
            'category.*' => 'integer|exists:categories,id',
            'brand' => 'nullable|array',
            'brand.*' => 'integer|exists:brands,id',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0|gte:price_min',
            'sort' => 'nullable|string|in:relevance,newest,sold,price_asc,price_desc'
        ]);
    }

    /**
     * Build base product query
     */
    private function buildBaseProductQuery($query, $categoryIds, $brandIds, $priceMin, $priceMax)
    {
        return Product::query()
            ->when($query, function($q) use ($query) {
                $q->where(function($q2) use ($query) {
                    $q2->where('name', 'like', "%{$query}%")
                       ->orWhere('description', 'like', "%{$query}%");
                });
            })
            ->when($categoryIds, function($q) use ($categoryIds) {
                $q->whereHas('categories', function($q2) use ($categoryIds) {
                    $q2->whereIn('categories.id', $categoryIds);
                });
            })
            ->when($brandIds, function($q) use ($brandIds) {
                $q->whereHas('brands', function($q2) use ($brandIds) {
                    $q2->whereIn('brands.id', $brandIds);
                });
            })
            ->when($priceMin || $priceMax, function($q) use ($priceMin, $priceMax) {
                $q->where(function($q2) use ($priceMin, $priceMax) {
                    // Xử lý price range cho cả simple products và variant products
                    if ($priceMin && $priceMax) {
                        // Có cả min và max
                        $q2->where(function($q3) use ($priceMin, $priceMax) {
                            // Simple products: sale_price hoặc price trong khoảng
                            $q3->where(function($q4) use ($priceMin, $priceMax) {
                                $q4->where('sale_price', '>=', $priceMin)
                                   ->where('sale_price', '<=', $priceMax);
                            })->orWhere(function($q4) use ($priceMin, $priceMax) {
                                $q4->where('price', '>=', $priceMin)
                                   ->where('price', '<=', $priceMax);
                            });
                        })->orWhereHas('variants', function($q3) use ($priceMin, $priceMax) {
                            // Variant products: có variant trong khoảng giá
                            $q3->where(function($q4) use ($priceMin, $priceMax) {
                                $q4->where('sale_price', '>=', $priceMin)
                                   ->where('sale_price', '<=', $priceMax);
                            })->orWhere(function($q4) use ($priceMin, $priceMax) {
                                $q4->where('price', '>=', $priceMin)
                                   ->where('price', '<=', $priceMax);
                            });
                        });
                    } elseif ($priceMin) {
                        // Chỉ có min
                        $q2->where(function($q3) use ($priceMin) {
                            $q3->where('sale_price', '>=', $priceMin)
                               ->orWhere('price', '>=', $priceMin)
                               ->orWhereHas('variants', function($q4) use ($priceMin) {
                                   $q4->where('sale_price', '>=', $priceMin)
                                      ->orWhere('price', '>=', $priceMin);
                               });
                        });
                    } elseif ($priceMax) {
                        // Chỉ có max
                        $q2->where(function($q3) use ($priceMax) {
                            $q3->where('sale_price', '<=', $priceMax)
                               ->orWhere('price', '<=', $priceMax)
                               ->orWhereHas('variants', function($q4) use ($priceMax) {
                                   $q4->where('sale_price', '<=', $priceMax)
                                      ->orWhere('price', '<=', $priceMax);
                               });
                        });
                    }
                });
            })
            ->where('status', 'active');
    }

    /**
     * Get relevant categories and brands from search results
     */
    private function getRelevantCategoriesAndBrands($baseProductQuery)
    {
        $sampleProducts = (clone $baseProductQuery)
            ->with(['categories', 'brands'])
            ->limit(1000)
            ->get();

        $relevantCategories = collect();
        $relevantBrands = collect();

        foreach ($sampleProducts as $product) {
            if ($product->categories && $product->categories->isNotEmpty()) {
                foreach ($product->categories as $category) {
                    if ($category) {
                        $relevantCategories->put($category->id, $category);
                    }
                }
            }
            
            if ($product->brands && $product->brands->isNotEmpty()) {
                foreach ($product->brands as $brand) {
                    if ($brand) {
                        $relevantBrands->put($brand->id, $brand);
                    }
                }
            }
        }

        return [$relevantCategories, $relevantBrands, $sampleProducts];
    }

    /**
     * Process categories with product counts
     */
    private function processCategories($relevantCategories, $sampleProducts)
    {
        $categories = collect();
        
        foreach ($relevantCategories as $category) {
            // Tìm danh mục cha hoặc chính nó
            $parentCategory = null;
            if ($category->parent_id) {
                $parentCategory = Category::with(['subCategories.subCategories'])
                    ->where('id', $category->parent_id)
                    ->first();
            } else {
                $parentCategory = Category::with(['subCategories.subCategories'])
                    ->where('id', $category->id)
                    ->first();
            }
            
            if ($parentCategory && !$categories->has($parentCategory->id)) {
                $categories->put($parentCategory->id, $parentCategory);
            }
        }

        return $categories->map(function ($cat) use ($sampleProducts) {
            $cat->product_count = $this->calculateCategoryProductCount($cat, $sampleProducts);
            
            if ($cat->subCategories && $cat->subCategories->isNotEmpty()) {
                foreach ($cat->subCategories as $sub) {
                    $sub->product_count = $this->calculateCategoryProductCount($sub, $sampleProducts);
                    
                    if ($sub->subCategories && $sub->subCategories->isNotEmpty()) {
                        foreach ($sub->subCategories as $sub2) {
                            $sub2->product_count = $this->calculateCategoryProductCount($sub2, $sampleProducts);
                            $sub->product_count += $sub2->product_count;
                        }
                    }
                    $cat->product_count += $sub->product_count;
                }
            }
            return $cat;
        })->sortByDesc('product_count')->values();
    }

    /**
     * Process brands with product counts
     */
    private function processBrands($relevantBrands, $sampleProducts)
    {
        $brands = collect();
        
        foreach ($relevantBrands as $brand) {
            // Tìm thương hiệu cha hoặc chính nó
            $parentBrand = null;
            if ($brand->parent_id) {
                $parentBrand = Brand::with(['subBrands'])
                    ->where('id', $brand->parent_id)
                    ->first();
            } else {
                $parentBrand = Brand::with(['subBrands'])
                    ->where('id', $brand->id)
                    ->first();
            }
            
            if ($parentBrand && !$brands->has($parentBrand->id)) {
                $brands->put($parentBrand->id, $parentBrand);
            }
        }

        return $brands->map(function ($brand) use ($sampleProducts) {
            $brand->product_count = $this->calculateBrandProductCount($brand, $sampleProducts);
            
            if ($brand->subBrands && $brand->subBrands->isNotEmpty()) {
                foreach ($brand->subBrands as $sub) {
                    $sub->product_count = $this->calculateBrandProductCount($sub, $sampleProducts);
                    $brand->product_count += $sub->product_count;
                }
            }
            return $brand;
        })->sortByDesc('product_count')->values();
    }

    /**
     * Calculate product count for category
     */
    private function calculateCategoryProductCount($category, $sampleProducts)
    {
        if (!$category || !$sampleProducts) {
            return 0;
        }

        return $sampleProducts->filter(function ($product) use ($category) {
            if (!$product->categories) {
                return false;
            }
            
            return $product->categories->contains('id', $category->id) ||
                   $product->categories->filter(function ($productCat) use ($category) {
                       return $productCat && $productCat->parent_id === $category->id;
                   })->isNotEmpty();
        })->count();
    }

    /**
     * Calculate product count for brand
     */
    private function calculateBrandProductCount($brand, $sampleProducts)
    {
        if (!$brand || !$sampleProducts) {
            return 0;
        }

        return $sampleProducts->filter(function ($product) use ($brand) {
            if (!$product->brands) {
                return false;
            }
            
            return $product->brands->contains('id', $brand->id) ||
                   $product->brands->filter(function ($productBrand) use ($brand) {
                       return $productBrand && $productBrand->parent_id === $brand->id;
                   })->isNotEmpty();
        })->count();
    }

    /**
     * Get advertised products
     */
    private function getAdvertisedProducts($query)
    {
        $advertisedCampaigns = AdsCampaign::where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->when($query, function ($q) use ($query) {
                $q->whereHas('adsCampaignItems.product', function ($productQuery) use ($query) {
                    $productQuery->where('name', 'like', "%{$query}%");
                });
            })
            ->with([
                'adsCampaignItems.product.images', 
                'adsCampaignItems.product.variants',
                'adsCampaignItems.product.shop' => function($query) {
                    $query->withCount('followers')
                          ->withCount('orderReviews')
                          ->withAvg('orderReviews', 'rating');
                }
            ])
            ->get();

        $advertisedProductsByShop = collect();

        foreach ($advertisedCampaigns as $campaign) {
            foreach ($campaign->adsCampaignItems as $item) {
                if ($query) {
                    if ($item->product && Str::contains(strtolower($item->product->name), strtolower($query))) {
                        $this->addProductToShopAds($advertisedProductsByShop, $item, $campaign);
                    }
                } else {
                    if ($item->product) {
                        $this->addProductToShopAds($advertisedProductsByShop, $item, $campaign);
                    }
                }
            }
        }

        return $advertisedProductsByShop->sortByDesc(function ($entry) {
            return $entry['max_bid_amount'] ?? 0;
        })->values();
    }

    /**
     * Apply sorting to product query
     */
    private function applySorting($query, $sort)
    {
        return $query->when($sort, function($q) use ($sort) {
            return match ($sort) {
                'price_asc' => $q->orderBy('sale_price', 'asc'),
                'price_desc' => $q->orderBy('sale_price', 'desc'),
                'sold' => $q->orderBy('sold_quantity', 'desc'),
                'newest' => $q->orderBy('created_at', 'desc'),
                default => $q->orderBy('id', 'desc'),
            };
        })->where('status', 'active')
          ->with(['categories', 'brands', 'images', 'variants']);
    }

    /**
     * Exclude advertised products from main query
     */
    private function excludeAdvertisedProducts($query, $advertisedProductsByShop)
    {
        $advertisedProductIds = collect();
        
        if ($advertisedProductsByShop->isNotEmpty()) {
            foreach ($advertisedProductsByShop as $shopAds) {
                $advertisedProductIds = $advertisedProductIds->merge($shopAds['products']->pluck('id'));
            }
        }

        if (!empty($advertisedProductIds->toArray())) {
            $query->whereNotIn('id', $advertisedProductIds->toArray());
        }

        return $query;
    }

    /**
     * Handle AJAX response
     */
    private function handleAjaxResponse($products, $categories, $brands, $advertisedProductsByShop, $categoryIds, $brandIds)
    {
        try {
            $productListHtml = view('partials.product_list', compact('products', 'advertisedProductsByShop'))->render();
            
            // Render category filters with proper data
            $categoryFiltersHtml = '';
            if ($categories && $categories->isNotEmpty()) {
                $categoryFiltersHtml = view('partials.category_filters', compact('categories'))->render();
            }
            
            // Render brand filters with proper data
            $brandFiltersHtml = '';
            if ($brands && $brands->isNotEmpty()) {
                $brandFiltersHtml = view('partials.brand_filters', compact('brands'))->render();
            }
            
            return response()->json([
                'productList' => $productListHtml,
                'categoryFilters' => $categoryFiltersHtml,
                'brandFilters' => $brandFiltersHtml,
                'totalProducts' => $products->total(),
                'currentPage' => $products->currentPage(),
                'lastPage' => $products->lastPage()
            ]);
        } catch (\Exception $e) {
            Log::error('Error rendering AJAX response:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Có lỗi xảy ra khi tải kết quả.',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
