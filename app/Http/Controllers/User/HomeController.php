<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Notification;
use App\Models\NotificationReceiver;
use App\Models\ProductVariant;
use App\Models\PointTransaction;
use App\Models\Banner; // Thêm model Banner nếu có
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Danh mục "Điện tử" và các danh mục con
        $parentCategory = Category::where('name', 'Điện tử')->first();

        // Toàn bộ danh mục con (cho chỗ cần hiển thị đầy đủ)
        $subCategories = $parentCategory ? $parentCategory->subCategories : collect();

        // Danh mục con cần lọc riêng (chỉ hiển thị 5 mục đặc biệt)
        $filteredSubCategories = $parentCategory
            ? $parentCategory->subCategories()
            ->whereIn('name', ['Smart Watch', 'Smart TV', 'Keyboard', 'Mouse', 'Microphone'])
            ->get()
            : collect();

        // Danh mục "Thời trang" → Nam, Nữ → con của Nam/Nữ
        $fashion = Category::where('name', 'Thời trang')->first();
        $fashionSub = $fashion ? $fashion->subCategories()->with('subCategories')->get() : collect();

        // Danh mục "Trang sức" và các danh mục con
        $jewelry = Category::where('name', 'Trang sức')->first();
        $jewelrySub = $jewelry ? $jewelry->subCategories : collect();

        // Danh mục "Nước hoa" và các danh mục con
        $perfume = Category::where('name', 'Nước hoa')->first();
        $perfumeSub = $perfume ? $perfume->subCategories : collect();

        // Hiển thị 8 danh mục đầu tiên (có thể thêm điều kiện where nếu cần)
        $homeCategories = Category::whereNull('parent_id') // danh mục cha
            ->withCount('products') // đếm số sản phẩm
            ->take(8)
            ->get();

        // Lấy danh mục bên phải (sidebar) với các danh mục con và sản phẩm
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
        $bestSellers = Product::with('defaultImage')
            ->orderByDesc('sold_quantity') // Sắp theo số lượng đã bán
            ->take(8) // Lấy 8 sản phẩm
            ->get();

        // Sản phẩm nổi bật
        $featuredProducts = Product::with('defaultImage')
            ->where('is_featured', 1)
            ->where('status', 'active')
            ->orderByDesc('updated_at')
            ->get();


        // Sản phẩm trending (bán nhiều nhất)
        $trendingProducts = Product::with('defaultImage')
            ->orderByDesc('sold_quantity') // Bán nhiều nhất
            ->take(10) // số lượng bạn muốn hiển thị
            ->get();

        // Sản phẩm đánh giá cao nhất
        $topRatedProducts = Product::with(['defaultImage', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->whereHas('reviews') // Chắc chắn có ít nhất 1 review
            ->orderByDesc('reviews_avg_rating')
            ->take(10)
            ->get();

        // Flash sale
        $flashSaleProducts = Product::with(['defaultImage', 'reviews'])
            ->whereNotNull('flash_sale_price')
            ->where('flash_sale_end_at', '>', Carbon::now())
            ->where('status', 'active')
            ->orderBy('flash_sale_end_at', 'asc') // sắp xếp theo thời gian kết thúc gần nhất
            ->take(10)
            ->get();

            
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
            'user',
        ));
    }
}
