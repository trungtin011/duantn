<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Notification;
use App\Models\NotificationReceiver;
use App\Models\ProductVariant;
use App\Models\PointTransaction;
use App\Models\OrderReview;
use App\Models\Post;
use App\Models\Combo;
use App\Models\Shop;
use App\Models\AdsCampaign;
use App\Models\AdsCampaignItem;
use App\Models\Banner;

class HomeController extends Controller
{
    /**
     * Tính toán giá hiển thị cho sản phẩm
     */
    private function calculateDisplayPrices($product): array
    {
        if ($product->is_variant && $product->variants->isNotEmpty()) {
            // Lấy giá thấp nhất từ các biến thể
            $minPrice = $product->variants->min('price') ?? 0;
            $minSalePrice = $product->variants->min('sale_price') ?? 0;

            // Nếu có sale_price và nhỏ hơn price thì dùng sale_price
            if ($minSalePrice > 0 && $minSalePrice < $minPrice) {
                return [
                    'display_price' => $minSalePrice,
                    'display_original_price' => $minPrice
                ];
            } else {
                return [
                    'display_price' => $minPrice,
                    'display_original_price' => $minPrice
                ];
            }
        } else {
            // Sản phẩm đơn
            return [
                'display_price' => $product->sale_price > 0 ? $product->sale_price : $product->price,
                'display_original_price' => $product->price
            ];
        }
    }

    /**
     * Lấy sản phẩm nổi bật - logic đơn giản trong controller
     */
    private function getFeaturedProducts()
    {
        // Logic mặc định: kết hợp sản phẩm bán chạy, mới và đánh giá cao
        return Product::with(['defaultImage', 'categories', 'variants'])
            ->where('status', 'active')
            ->where('stock_total', '>', 0) // Chỉ lấy sản phẩm còn hàng
            ->where(function($query) {
                $query->where('sold_quantity', '>=', 5) // Sản phẩm đã bán ít nhất 5 cái
                      ->orWhere('created_at', '>=', Carbon::now()->subDays(30)) // Hoặc sản phẩm mới trong 30 ngày
                      ->orWhereHas('orderReviews', function($reviewQuery) {
                          $reviewQuery->where('rating', '>=', 4); // Hoặc có đánh giá từ 4 sao trở lên
                      });
            })
            ->orderByDesc('sold_quantity') // Ưu tiên sản phẩm bán chạy
            ->orderByDesc('created_at') // Sau đó ưu tiên sản phẩm mới
            ->take(12) // Lấy nhiều hơn để có thể lọc
            ->get()
            ->map(function ($product) {
                $prices = $this->calculateDisplayPrices($product);
                $product->display_price = $prices['display_price'];
                $product->display_original_price = $prices['display_original_price'];
                return $product;
            })
            ->take(8); // Giới hạn cuối cùng 8 sản phẩm
    }

    /**
     * Xử lý danh mục với kiểm tra danh mục cha/con
     */
    private function processCategoryWithSubCategories($categoryName): array
    {
        $category = Category::where('name', $categoryName)->first();

        if (!$category) {
            return [
                'category' => null,
                'subCategories' => collect(),
                'isParent' => false,
                'parentCategory' => null
            ];
        }

        if ($category->parent_id === null) {
            // Nếu là danh mục cha, lấy các danh mục con
            $subCategories = $category->subCategories()->with(['products' => function ($query) {
                $query->where('status', 'active');
            }])->get();

            return [
                'category' => $category,
                'subCategories' => $subCategories,
                'isParent' => true,
                'parentCategory' => null
            ];
        } else {
            // Nếu là danh mục con, lấy danh mục cha và các danh mục con khác
            $parentCategory = Category::find($category->parent_id);
            $subCategories = $parentCategory ? $parentCategory->subCategories()->with(['products' => function ($query) {
                $query->where('status', 'active');
            }])->get() : collect();

            return [
                'category' => $category,
                'subCategories' => $subCategories,
                'isParent' => false,
                'parentCategory' => $parentCategory
            ];
        }
    }

    public function index()
    {
        $user = Auth::user();

        // Danh mục "Điện tử" và các danh mục con
        $parentCategory = Category::where('name', 'Điện tử')->first();

        // Toàn bộ danh mục con
        $subCategories = $parentCategory ? $parentCategory->subCategories()->with(['products' => function ($query) {
            $query->where('status', 'active');
        }])->get() : collect();

        // Danh mục con cần lọc riêng (chỉ hiển thị 5 mục đặc biệt)
        $filteredSubCategories = $parentCategory
            ? $parentCategory->subCategories()
            ->whereIn('name', ['Smart Watch', 'Smart TV', 'Keyboard', 'Mouse', 'Microphone'])
            ->with(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->get()
            : collect();

        // Danh mục "Thời trang" và các danh mục con
        $fashion = Category::where('name', 'Thời trang')->first();
        $fashionSub = $fashion ? $fashion->subCategories()->with(['subCategories.products' => function ($query) {
            $query->where('status', 'active');
        }])->get() : collect();

        // Xử lý danh mục "Trang sức" với kiểm tra danh mục cha/con
        $jewelryData = $this->processCategoryWithSubCategories('Trang sức');
        $jewelry = $jewelryData['category'];
        $jewelrySub = $jewelryData['subCategories'];
        $jewelryIsParent = $jewelryData['isParent'];
        $jewelryParent = $jewelryData['parentCategory'];

        // Hiển thị 8 danh mục cha
        $homeCategories = Category::whereNull('parent_id')
            ->withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->take(8)
            ->get();

        // Lấy danh mục cho sidebar với các danh mục con và sản phẩm
        $sidebarCategories = Category::with([
            'subCategories.products' => function ($query) {
                $query->where('status', 'active')->where('stock_total', '>', 0);
            }
        ])->whereNull('parent_id')->get();

        // Lấy 5 sản phẩm đã mua của khách hàng
        $purchasedProducts = Product::whereHas('orders')
            ->where('status', 'active')
            ->with('defaultImage')
            ->orderByDesc('created_at')
            ->take(1)
            ->get();

        // Lấy 6 sản phẩm bán chạy nhất (chỉ những sản phẩm đã được bán)
        $bestSellers = Product::with(['defaultImage', 'categories', 'variants'])
            ->where('status', 'active')
            ->where('sold_quantity', '>', 0) // Chỉ lấy sản phẩm đã được bán ít nhất 1 cái
            ->orderByDesc('sold_quantity') // Sắp xếp theo số lượng bán giảm dần
            ->take(6) // Giới hạn 6 sản phẩm
            ->get()
            ->map(function ($product) {
                $prices = $this->calculateDisplayPrices($product);
                $product->display_price = $prices['display_price'];
                $product->display_original_price = $prices['display_original_price'];
                return $product;
            });

        // Sản phẩm nổi bật - sử dụng method riêng
        $featuredProducts = $this->getFeaturedProducts();

        // Sản phẩm được xem nhiều nhất
        $trendingProducts = app(\App\Services\ProductViewService::class)->getMostViewedProducts(10, 'all');

        // Sản phẩm đánh giá cao nhất
        $topRatedProducts = Product::with(['defaultImage', 'orderReviews', 'categories', 'variants'])
            ->withAvg('orderReviews', 'rating')
            ->whereHas('orderReviews')
            ->orderByDesc('order_reviews_avg_rating')
            ->where('status', 'active')
            ->take(10)
            ->get()
            ->map(function ($product) {
                $prices = $this->calculateDisplayPrices($product);
                $product->display_price = $prices['display_price'];
                $product->display_original_price = $prices['display_original_price'];
                return $product;
            });

        // Flash sale
        $flashSaleProducts = Product::with(['defaultImage', 'orderReviews', 'categories', 'variants'])
            ->whereNotNull('flash_sale_price')
            ->where('flash_sale_end_at', '>', Carbon::now())
            ->where('status', 'active')
            ->orderBy('flash_sale_end_at', 'asc')
            ->take(10)
            ->get();

        // Process flash sale products to get the lowest variant price if applicable
        $flashSaleProducts->each(function ($product) {
            if ($product->variants->isNotEmpty()) {
                $minSalePriceVariant = $product->variants
                    ->whereNotNull('sale_price') // Check for sale_price on variant
                    ->where('stock', '>', 0)
                    ->sortBy('sale_price') // Sort by sale_price
                    ->first();

                if ($minSalePriceVariant) {
                    $product->display_flash_sale_price = $minSalePriceVariant->sale_price;
                    $product->display_original_price_for_flash_sale = $minSalePriceVariant->price;
                } else {
                    // Fallback to product's flash sale price if no variant has a sale price or is out of stock
                    $product->display_flash_sale_price = $product->flash_sale_price;
                    $product->display_original_price_for_flash_sale = $product->price;
                }
            } else {
                // For simple products (no variants)
                $product->display_flash_sale_price = $product->flash_sale_price;
                $product->display_original_price_for_flash_sale = $product->price;
            }
        });

        // Sản phẩm mới (tạo trong vòng 7 ngày gần nhất)
        $newProducts = Product::with(['defaultImage', 'categories', 'orderReviews', 'variants'])
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->where('status', 'active')
            ->orderByDesc('created_at')
            ->take(12)
            ->get()
            ->map(function ($product) {
                $prices = $this->calculateDisplayPrices($product);
                $product->display_price = $prices['display_price'];
                $product->display_original_price = $prices['display_original_price'];
                return $product;
            });

        // Lấy 5 đánh giá gần đây từ khách hàng
        $testimonials = OrderReview::with(['user', 'product'])
            ->whereNotNull('comment')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Lấy 4 bài viết mới nhất
        $blogs = Post::orderByDesc('created_at')->take(4)->get();

        // Lấy 8 combo sản phẩm mới nhất, có trạng thái 'active', và eager load shop và các sản phẩm trong combo
        $comboProducts = Combo::with(['shop', 'products.product.defaultImage'])
            ->where('status', 'active')
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        // Lấy sản phẩm quảng cáo từ ads_campaigns
        try {
            $advertisedProducts = AdsCampaignItem::with(['product.defaultImage', 'product.shop', 'adsCampaign.shop'])
                ->whereHas('adsCampaign', function ($query) {
                    $query->where('status', 'active')
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now());
                })
                ->whereHas('product', function ($query) {
                    $query->where('status', 'active');
                })
                ->inRandomOrder()
                ->take(6)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error loading advertised products: ' . $e->getMessage());
            $advertisedProducts = collect(); // Fallback to empty collection
        }

        // Lấy và tính toán xếp hạng shop
        $rankingShops = Shop::where('shop_status', 'active')
            ->withCount('followers') // Thêm eager loading để đếm followers từ bảng shop_followers
            ->withCount('orderReviews') // Thêm eager loading để đếm reviews từ bảng order_reviews
            ->withAvg('orderReviews', 'rating') // Thêm eager loading để tính trung bình rating từ bảng order_reviews
            ->withCount('products') // Đếm số lượng products thực tế
            ->withSum('products', 'sold_quantity') // Thêm tổng số lượng sản phẩm đã bán
            ->orderBy('products_sum_sold_quantity', 'desc')  // Ưu tiên số lượng sản phẩm ĐÃ BÁN
            ->orderBy('order_reviews_avg_rating', 'desc')   // Sau đó mới đến rating
            ->orderBy('total_sales', 'desc')                // Cuối cùng là doanh số
            ->take(10)
            ->get()
            ->filter(function ($shop) {
                return $shop->products_count > 0 && ($shop->products_sum_sold_quantity ?? 0) > 0; // Chỉ lấy shops có products và đã bán được sản phẩm
            })
            ->map(function ($shop) {
                // Sử dụng rating thực tế từ bảng order_reviews
                $actualRating = $shop->order_reviews_avg_rating ?? 0;
                $actualReviewsCount = $shop->order_reviews_count ?? 0;

                $ratingScore = $actualRating / 5;
                $salesScore = min($shop->total_sales / 1000000000, 1);
                $productsSoldScore = min(($shop->products_sum_sold_quantity ?? 0) / 100, 1); // Sử dụng số lượng sản phẩm ĐÃ BÁN
                $productsCountScore = min($shop->products_count / 100, 1); // Số lượng products có sẵn
                // Sử dụng số lượng followers thực tế từ bảng shop_followers
                $actualFollowers = $shop->followers_count;
                $followersScore = min($actualFollowers / 1000, 1);
                
                // Đảm bảo không bị lỗi chia cho 0
                if ($shop->total_sales == 0) $salesScore = 0;
                if (($shop->products_sum_sold_quantity ?? 0) == 0) $productsSoldScore = 0;
                if ($shop->products_count == 0) $productsCountScore = 0;
                if ($actualFollowers == 0) $followersScore = 0;

                $totalScore =
                    ($ratingScore * 0.25) +           // Rating: 25% (giảm từ 40%)
                    ($productsSoldScore * 0.35) +     // Sản phẩm ĐÃ BÁN: 35% (quan trọng nhất)
                    ($salesScore * 0.25) +            // Doanh số: 25% (giảm từ 30%)
                    ($productsCountScore * 0.10) +    // Số products: 10% (giảm từ 20%)
                    ($followersScore * 0.05);         // Followers: 5% (giảm từ 10%)

                if ($totalScore >= 0.8) {
                    $shop->tier = 'diamond';
                    $shop->tier_icon = 'diamond';
                } elseif ($totalScore >= 0.6) {
                    $shop->tier = 'gold';
                    $shop->tier_icon = 'medal';
                } elseif ($totalScore >= 0.4) {
                    $shop->tier = 'silver';
                    $shop->tier_icon = 'ribbon';
                } else {
                    $shop->tier = 'bronze';
                    $shop->tier_icon = 'shield';
                }

                $shop->total_score = round($totalScore * 100, 1);
                $shop->formatted_sales = number_format($shop->total_sales / 1000000, 1) . 'M';
                // Gán số lượng followers thực tế
                $shop->total_followers = $actualFollowers;
                // Gán rating thực tế từ order_reviews
                $shop->shop_rating = round($actualRating, 1);
                $shop->total_reviews = $actualReviewsCount;
                // Gán tổng số lượng sản phẩm đã bán
                $shop->total_products_sold = $shop->products_sum_sold_quantity ?? 0;
                return $shop;
            });

        // Lấy banners hiện tại
        try {
            $banners = Banner::current()
                ->orderBy('sort_order', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error loading banners: ' . $e->getMessage());
            $banners = collect(); // Fallback to empty collection
        }

        // Ensure all variables are defined
        $advertisedProducts = $advertisedProducts ?? collect();
        $banners = $banners ?? collect();

        return view('user.home', compact(
            'rankingShops',
            'parentCategory',
            'subCategories',
            'fashionSub',
            'filteredSubCategories',
            'homeCategories',
            'sidebarCategories',
            'jewelry',
            'jewelrySub',
            'jewelryIsParent',
            'jewelryParent',
            'purchasedProducts',
            'bestSellers',
            'trendingProducts',
            'topRatedProducts',
            'flashSaleProducts',
            'featuredProducts',
            'newProducts',
            'testimonials',
            'blogs',
            'user',
            'comboProducts',
            'advertisedProducts',
            'banners'
        ));
    }
}
