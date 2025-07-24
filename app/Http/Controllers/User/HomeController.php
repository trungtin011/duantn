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
        $bestSellers = Product::with(['defaultImage', 'categories'])
            ->orderByDesc('sold_quantity')
            ->where('status', 'active')
            ->take(8)
            ->get();

        // Sản phẩm nổi bật
        $featuredProducts = Product::with(['defaultImage', 'categories'])
            ->where('is_featured', 1)
            ->where('status', 'active')
            ->orderByDesc('updated_at')
            ->get();

        // Sản phẩm trending (bán nhiều nhất)
        $trendingProducts = Product::with(['defaultImage', 'categories'])
            ->orderByDesc('sold_quantity')
            ->where('status', 'active')
            ->take(10)
            ->get();

        // Sản phẩm đánh giá cao nhất
        $topRatedProducts = Product::with(['defaultImage', 'reviews', 'categories'])
            ->withAvg('reviews', 'rating')
            ->whereHas('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->where('status', 'active')
            ->take(10)
            ->get();

        // Flash sale
        $flashSaleProducts = Product::with(['defaultImage', 'reviews', 'categories'])
            ->whereNotNull('flash_sale_price')
            ->where('flash_sale_end_at', '>', Carbon::now())
            ->where('status', 'active')
            ->orderBy('flash_sale_end_at', 'asc')
            ->take(10)
            ->get();

        // Sản phẩm mới (tạo trong vòng 7 ngày gần nhất)
        $newProducts = Product::with(['defaultImage', 'categories', 'reviews'])
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

        return view('user.home', compact(
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
            'user'
        ));
    }
}
