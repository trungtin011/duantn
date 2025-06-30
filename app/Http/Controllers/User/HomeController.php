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
use App\Models\ProductVariant;
use App\Models\PointTransaction;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $products = Product::with(['images', 'reviews', 'variants'])
            ->orderByDesc('is_new')
            ->orderByDesc('is_best_seller')
            ->orderByDesc('is_hot')
            ->orderByDesc('created_at')
            ->paginate(20);
        $categories = Category::where('status', 'active')->get();
        $cartItems = $user ? Cart::where('userID', $user->id)->get() : [];
        $notifications = $user ? Notification::where('receiver_user_id', $user->id)->where('status', 'unread')->get() : [];

        $now = Carbon::now();

        // Sản phẩm đang Flash Sale
        $flashSaleProducts = Product::whereNotNull('flash_sale_price')
            ->where('flash_sale_end_at', '>', $now)
            ->orderBy('flash_sale_end_at')
            ->take(5) // lấy 5 sản phẩm sắp hết hạn
            ->get();

        $products = Product::with(['images', 'reviews', 'variants'])
            ->orderByDesc('is_new')
            ->orderByDesc('is_best_seller')
            ->orderByDesc('is_hot')
            ->orderByDesc('created_at')
            ->paginate(20);

        $totalPoints = 0;
        if ($user) {
            $totalPoints = PointTransaction::where('userID', $user->id)->sum('points');
        }

        return view('user.home', compact('products', 'categories', 'cartItems', 'notifications', 'flashSaleProducts', 'totalPoints'));
    }
}
