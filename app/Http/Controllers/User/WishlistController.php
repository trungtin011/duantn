<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;
use App\Models\Product;

class WishlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Lấy danh sách sản phẩm yêu thích
        $wishlistItems = Wishlist::where('userID', $user->id)
            ->with([
                'product' => function ($query) {
                    $query->with(['images' => function ($q) {
                        $q->where('is_default', 1);
                    }]);
                },
                'shop'
            ])
            ->get();

        // Lấy gợi ý sản phẩm
        $recommendedProducts = Product::inRandomOrder()
            ->take(4)
            ->with(['images' => function ($q) {
                $q->where('is_default', 1);
            }])
            ->get();

        return view('user.account.wishlist.wishlist', compact('user', 'wishlistItems', 'recommendedProducts'));
    }
}
