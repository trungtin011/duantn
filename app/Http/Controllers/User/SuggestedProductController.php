<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class SuggestedProductController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Lấy danh mục sản phẩm mà user đã mua
        $purchasedCategories = Order::where('user_id', $user->id)
            ->where('status', 'completed')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.category_id')
            ->distinct()
            ->pluck('category_id');

        // Lấy sản phẩm từ các danh mục tương tự
        $suggestedProducts = Product::whereIn('category_id', $purchasedCategories)
            ->where('status', 'active')
            ->whereNotIn('id', function($query) use ($user) {
                $query->select('product_id')
                    ->from('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('orders.user_id', $user->id);
            })
            ->with(['images', 'shop'])
            ->take(8)
            ->get();

        // Nếu không đủ sản phẩm gợi ý, lấy thêm sản phẩm bán chạy
        if ($suggestedProducts->count() < 8) {
            $remainingCount = 8 - $suggestedProducts->count();
            $popularProducts = Product::where('status', 'active')
                ->whereNotIn('id', $suggestedProducts->pluck('id'))
                ->with(['images', 'shop'])
                ->orderBy('sold_count', 'desc')
                ->take($remainingCount)
                ->get();

            $suggestedProducts = $suggestedProducts->concat($popularProducts);
        }

        return view('client.suggested_products', compact('suggestedProducts'));
    }
} 