<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
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

        // Sản phẩm mới cho "New Products" section
        $newProducts = Product::with('defaultImage')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->orderByDesc('created_at')
            ->get();

        // Sản phẩm trending (bán nhiều nhất)
        $trendingProducts = Product::with('defaultImage')
            ->orderByDesc('sold_quantity') // Bán nhiều nhất
            ->take(10) // số lượng bạn muốn hiển thị
            ->get();

        // Sản phẩm đánh giá cao nhất
        $topRatedProducts = Product::with(['defaultImage', 'reviews'])
            ->withAvg('reviews', 'rating') // Tính điểm trung bình đánh giá
            ->orderByDesc('reviews_avg_rating') // Sắp xếp theo đánh giá
            ->take(10)
            ->get();


        // Deal trong ngày (ví dụ: có giảm giá nhiều nhất)
        $dealOfTheDay = Product::with('defaultImage')
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'price')
            ->orderByRaw('(price - sale_price) DESC') // Giảm giá nhiều nhất
            ->take(2)
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
            'dealOfTheDay',
            'newProducts',
            'user',
        ));
    }
}
