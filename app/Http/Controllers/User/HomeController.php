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

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $products = Product::with(['images', 'reviews'])
            ->orderByDesc('is_new')
            ->orderByDesc('is_best_seller')
            ->orderByDesc('is_hot')
            ->orderByDesc('created_at')
            ->paginate(20);
        $categories = Category::where('status', 'active')->get();
        $cartItems = $user ? Cart::where('userID', $user->id)->get() : [];
        return view('user.home', compact('products', 'categories', 'cartItems'));
    }
}
