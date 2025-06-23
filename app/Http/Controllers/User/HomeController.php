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

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $products = Product::with(['images', 'reviews'])->get();
        $variants = ProductVariant::all();
        $categories = Category::where('status', 'active')->get();
        $cartItems = $user ? Cart::where('userID', $user->id)->get() : [];
        $notifications = $user ? Notification::where('receiver_user_id', $user->id)->where('status', 'unread')->get() : [];

        return view('user.home', compact('products', 'categories', 'cartItems', 'notifications', 'variants'));
    }
}
