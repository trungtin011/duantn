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

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Danh mục "Điện tử" và các danh mục con
        $parentCategory = Category::where('name', 'Điện tử')->first();

        // Toàn bộ danh mục con
        $subCategories = $parentCategory ? $parentCategory->subCategories()->with('products')->get() : collect();

        // Danh mục con cần lọc riêng (chỉ hiển thị 5 mục đặc biệt)
        $filteredSubCategories = $parentCategory
            ? $parentCategory->subCategories()
            ->whereIn('name', ['Smart Watch', 'Smart TV', 'Keyboard', 'Mouse', 'Microphone'])
            ->with('products')
            ->get()
            : collect();

        // Danh mục "Thời trang" và các danh mục con
        $fashion = Category::where('name', 'Thời trang')->first();
        $fashionSub = $fashion ? $fashion->subCategories()->with('subCategories.products')->get() : collect();

        // Danh mục "Trang sức" và các danh mục con
        $jewelry = Category::where('name', 'Trang sức')->first();
        $jewelrySub = $jewelry ? $jewelry->subCategories()->with('products')->get() : collect();

        // Danh mục "Nước hoa" và các danh mục con
        $perfume = Category::where('name', 'Nước hoa')->first();
        $perfumeSub = $perfume ? $perfume->subCategories()->with('products')->get() : collect();

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
            ->with('defaultImage')
            ->orderByDesc('created_at')
            ->take(1)
            ->get();

        // Lấy 8 sản phẩm bán chạy nhất
        $bestSellers = Product::with(['defaultImage', 'categories', 'variants'])
            ->orderByDesc('sold_quantity')
            ->where('status', 'active')
            ->take(8)
            ->get();

        // Sản phẩm nổi bật
        $featuredProducts = Product::with(['defaultImage', 'categories', 'variants'])
            ->where('is_featured', 1)
            ->where('status', 'active')
            ->orderByDesc('updated_at')
            ->get();

        // Sản phẩm được xem nhiều nhất
        $trendingProducts = Product::with(['defaultImage', 'categories', 'variants'])
            ->whereHas('viewHistory')
            ->withCount('viewHistory')
            ->orderByDesc('view_history_count')
            ->where('status', 'active')
            ->take(10)
            ->get();

        // Sản phẩm đánh giá cao nhất
        $topRatedProducts = Product::with(['defaultImage', 'orderReviews', 'categories', 'variants'])
            ->withAvg('orderReviews', 'rating')
            ->whereHas('orderReviews')
            ->orderByDesc('order_reviews_avg_rating')
            ->where('status', 'active')
            ->take(10)
            ->get();

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
            ->get();

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
        $advertisedProductsByShop = AdsCampaignItem::with(['product.defaultImage', 'product.shop', 'adsCampaign.shop'])
            ->whereHas('adsCampaign', function ($query) {
                $query->where('status', 'active')
                      ->where('start_date', '<=', now())
                      ->where('end_date', '>=', now());
            })
            ->whereHas('product', function ($query) {
                $query->where('status', 'active');
            })
            ->inRandomOrder()
            ->take(12)
            ->get()
            ->groupBy('product.shop.id')
            ->map(function ($items, $shopId) {
                $firstItem = $items->first();
                return [
                    'shop' => $firstItem->product->shop,
                    'products' => $items->map(function ($item) {
                        $item->product->ads_campaign_name = $item->adsCampaign->name;
                        return $item->product;
                    })->take(6), // Giới hạn 6 sản phẩm mỗi shop
                    'campaign_name' => $firstItem->adsCampaign->name,
                    'all_campaigns' => $items->map(function ($item) {
                        return [
                            'campaign' => $item->adsCampaign,
                            'product' => $item->product
                        ];
                    })
                ];
            })
            ->take(1); // Chỉ lấy 1 shop duy nhất

        // Lấy và tính toán xếp hạng shop
        $rankingShops = Shop::where('shop_status', 'active')
            ->where(function ($query) {
                $query->where('total_products', '>', 0);
            })
            ->withCount('followers') // Thêm eager loading để đếm followers từ bảng shop_followers
            ->withCount('orderReviews') // Thêm eager loading để đếm reviews từ bảng order_reviews
            ->withAvg('orderReviews', 'rating') // Thêm eager loading để tính trung bình rating từ bảng order_reviews
            ->withSum('products', 'sold_quantity') // Thêm tổng số lượng sản phẩm đã bán
            ->orderBy('order_reviews_avg_rating', 'desc')
            ->orderBy('total_sales', 'desc')
            ->take(10)
            ->get()
            ->map(function ($shop) {
                // Sử dụng rating thực tế từ bảng order_reviews
                $actualRating = $shop->order_reviews_avg_rating ?? 0;
                $actualReviewsCount = $shop->order_reviews_count ?? 0;

                $ratingScore = $actualRating / 5;
                $salesScore = min($shop->total_sales / 1000000000, 1);
                $productsScore = min($shop->total_products / 100, 1);
                // Sử dụng số lượng followers thực tế từ bảng shop_followers
                $actualFollowers = $shop->followers_count;
                $followersScore = min($actualFollowers / 1000, 1);

                $totalScore =
                    ($ratingScore * 0.4) +
                    ($salesScore * 0.3) +
                    ($productsScore * 0.2) +
                    ($followersScore * 0.1);

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

        return view('user.home', compact(
            'rankingShops',
            'parentCategory',
            'subCategories',
            'fashionSub',
            'filteredSubCategories',
            'homeCategories',
            'sidebarCategories',
            'jewelrySub',
            'perfumeSub',
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
            'advertisedProductsByShop'
        ));
    }
}
