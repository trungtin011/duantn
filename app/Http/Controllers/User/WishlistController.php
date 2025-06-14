<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Lấy danh sách sản phẩm yêu thích của người dùng
        $wishlistItems = Wishlist::where('userID', $user->id)
            ->with([
                'product' => function ($query) {
                    $query->with(['images' => function ($q) {
                        $q->where('is_default', 1); // Lấy ảnh mặc định
                    }]);
                },
                'shop'
            ])
            ->get();

        return view('user.account.wishlist.wishlist', compact('user', 'wishlistItems'));
    }
}
