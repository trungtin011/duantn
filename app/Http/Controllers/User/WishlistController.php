<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

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

    public function store(Request $request)
    {
        $checkWishlist = $this->checkWishlist($request->product_id);
        if ($checkWishlist) {
            return redirect()->back()->with('error', 'Sản phẩm đã có trong danh sách yêu thích');
        }
        $wishlist = new Wishlist();
        $wishlist->userID= Auth::user()->id;
        $wishlist->productID = $request->product_id;
        $wishlist->shopID = $request->shop_id;
        $wishlist->save();

        return redirect()->back()->with('success', 'Product added to wishlist successfully');
    }

    public function checkWishlist($product_id)
    {
        $wishlist = Wishlist::where('userID', Auth::user()->id)->where('productID', $product_id)->first();
        if ($wishlist) {
            return true;
        } else {
            return false;
        }
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::find($id);
        $wishlist->delete();
        return redirect()->back()->with('success', 'Product removed from wishlist successfully');
    }

    
}
