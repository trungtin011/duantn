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

        // Log ban đầu
        Log::info('Filter request received:', [
            'query' => $query,
            'selected_category_ids' => $categoryIds,
            'selected_brand_ids' => $brandIds,
            'price_min' => $priceMin,
            'price_max' => $priceMax,
            'sort' => $sort
        ]);

        // Lấy tất cả ID danh mục (cả con cháu)
        $allCategoryIds = collect();
        if (!empty($categoryIds)) {
            $selectedCategories = Category::with('subCategories.subCategories')
                ->whereIn('id', $categoryIds)->get();

            foreach ($selectedCategories as $cat) {
                $allCategoryIds->push($cat->id);
                foreach ($cat->subCategories as $sub) {
                    $allCategoryIds->push($sub->id);
                    foreach ($sub->subCategories as $sub2) {
                        $allCategoryIds->push($sub2->id);
                    }
                }
            }
            $allCategoryIds = $allCategoryIds->unique()->values();
        }
        Log::debug('📂 Final Category IDs used:', $allCategoryIds->toArray());

        // Lấy tất cả ID thương hiệu (cả con)
        $allBrandIds = collect();
        if (!empty($brandIds)) {
            $selectedBrands = Brand::with('subBrands')
                ->whereIn('id', $brandIds)->get();

            foreach ($selectedBrands as $brand) {
                $allBrandIds->push($brand->id);
                foreach ($brand->subBrands as $sub) {
                    $allBrandIds->push($sub->id);
                }
            }
            $allBrandIds = $allBrandIds->unique()->values();
        }
        Log::debug('🏷️ Final Brand IDs used:', $allBrandIds->toArray());

        // Truy vấn sản phẩm
        $productQuery = Product::query()
            ->when($query, fn($q) => $q->where('name', 'like', "%$query%"))
            ->when($allCategoryIds->isNotEmpty(), fn($q) => $q->whereHas('categories', fn($q2) => $q2->whereIn('categories.id', $allCategoryIds)))
            ->when($allBrandIds->isNotEmpty(), fn($q) => $q->whereHas('brands', fn($q2) => $q2->whereIn('brand.id', $allBrandIds)))
            ->when($priceMin, fn($q) => $q->where('sale_price', '>=', $priceMin))
            ->when($priceMax, fn($q) => $q->where('sale_price', '<=', $priceMax))
            ->when($sort, fn($q) => match ($sort) {
                'price_asc' => $q->orderBy('sale_price', 'asc'),
                'price_desc' => $q->orderBy('sale_price', 'desc'),
                'sold' => $q->orderBy('sold_quantity', 'desc'),
                'newest' => $q->orderBy('created_at', 'desc'),
                default => $q->orderBy('id', 'desc'),
            })
            ->where('status', 'active')
            ->with(['categories', 'brands', 'images']);

        $products = $productQuery->paginate(20);
        $productIds = $products->pluck('id');

        Log::info('✅ Total products matched:', ['count' => $products->total()]);

        // Lấy danh mục liên quan đến danh mục được chọn
        $categories = collect();
        if (!empty($categoryIds)) {
            $categories = Cache::remember('selected_categories_' . md5(json_encode($categoryIds)), 600, function () use ($categoryIds, $productIds) {
                return Category::with(['subCategories.subCategories'])
                    ->whereIn('id', $categoryIds)
                    ->select('id', 'name', 'parent_id')
                    ->get()
                    ->map(function ($cat) use ($productIds) {
                        $cat->product_count = $cat->products()->whereIn('products.id', $productIds)->count();
                        foreach ($cat->subCategories as $sub) {
                            $sub->product_count = $sub->products()->whereIn('products.id', $productIds)->count();
                            foreach ($sub->subCategories as $sub2) {
                                $sub2->product_count = $sub2->products()->whereIn('products.id', $productIds)->count();
                                $sub->product_count += $sub2->product_count;
                            }
                            $cat->product_count += $sub->product_count;
                        }
                        return $cat;
                    });
            });
        } else {
            // Nếu không có danh mục được chọn, lấy tất cả danh mục cha
            $categories = Cache::remember('all_categories', 600, function () use ($productIds) {
                return Category::with(['subCategories.subCategories'])
                    ->whereNull('parent_id')
                    ->select('id', 'name', 'parent_id')
                    ->get()
                    ->map(function ($cat) use ($productIds) {
                        $cat->product_count = $cat->products()->whereIn('products.id', $productIds)->count();
                        foreach ($cat->subCategories as $sub) {
                            $sub->product_count = $sub->products()->whereIn('products.id', $productIds)->count();
                            foreach ($sub->subCategories as $sub2) {
                                $sub2->product_count = $sub2->products()->whereIn('products.id', $productIds)->count();
                                $sub->product_count += $sub2->product_count;
                            }
                            $cat->product_count += $sub->product_count;
                        }
                        return $cat;
                    });
            });
        }

        // Lấy thương hiệu liên quan đến thương hiệu được chọn
        $brands = collect();
        if (!empty($brandIds)) {
            $brands = Cache::remember('selected_brands_' . md5(json_encode($brandIds)), 600, function () use ($brandIds, $productIds) {
                return Brand::with(['subBrands'])
                    ->whereIn('id', $brandIds)
                    ->select('id', 'name', 'parent_id')
                    ->get()
                    ->map(function ($brand) use ($productIds) {
                        $brand->product_count = $brand->products()->whereIn('products.id', $productIds)->count();
                        foreach ($brand->subBrands as $sub) {
                            $sub->product_count = $sub->products()->whereIn('products.id', $productIds)->count();
                            $brand->product_count += $sub->product_count;
                        }
                        return $brand;
                    });
            });
        } else {
            // Nếu không có thương hiệu được chọn, lấy tất cả thương hiệu cha
            $brands = Cache::remember('all_brands', 600, function () use ($productIds) {
                return Brand::with(['subBrands'])
                    ->whereNull('parent_id')
                    ->select('id', 'name', 'parent_id')
                    ->get()
                    ->map(function ($brand) use ($productIds) {
                        $brand->product_count = $brand->products()->whereIn('products.id', $productIds)->count();
                        foreach ($brand->subBrands as $sub) {
                            $sub->product_count = $sub->products()->whereIn('products.id', $productIds)->count();
                            $brand->product_count += $sub->product_count;
                        }
                        return $brand;
                    });
            });
        }

        Log::info('✅ Categories and Brands fetched:', ['categories' => $categories->count(), 'brands' => $brands->count()]);

        // Log kết quả cuối cùng
        Log::info('🔍 Search Results:', [
            'query' => $query,
            'total_products' => $products->total(),
            'categories_count' => $categories->count(),
            'brands_count' => $brands->count()
        ]);

        // Xử lý yêu cầu AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.product_list', compact('products'))->render(),
                'filters' => [
                    'category' => $allCategoryIds->toArray(),
                    'brand' => $allBrandIds->toArray(),
                ],
            ]);
        }

        return view('user.search.results', compact('products', 'query', 'categories', 'brands'));
    }

    public function show(Request $request, $slug)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->guest(route('login'))->with('error', 'Bạn cần đăng nhập để xem chi tiết sản phẩm');
        }
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
        $image = null;
        foreach ($product->variants as $variant) {
            $attributeValues = $variant->attributeValues->keyBy('attribute.name');
            $image = null; // ← Khởi tạo mặc định

            foreach ($attributeValues as $attrName => $attrValue) {
                $image = $variant->images->first()->image_path ?? null;
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
                    : asset('images/default_product_image.png'), // ← tránh lỗi
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
}
