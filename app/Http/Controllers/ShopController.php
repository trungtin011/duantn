<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\ShopStatus;

class ShopController extends Controller
{
    public function show($id)
    {
        $shop = Shop::with([
            'products.images', 
            'products.orderReviews',
            'combos.products.product.images',
            'categories',
            'user', 
            'owner',
            'address', 
            'followers'
        ])->findOrFail($id);

        // Nếu shop bị cấm thì trả về trang lỗi 404 tùy chỉnh
        if ($shop->shop_status === ShopStatus::BANNED) {
            return response()->view('error.404NotFound', [], 404);
        }

        return view('shop.profile', compact('shop'));
    }

    public function follow(Request $request, Shop $shop)
    {
        $user = Auth::user();

        if (!$shop->followers->contains($user->id)) {
            $shop->followers()->attach($user->id);
        }

        return back()->with('success', 'Đã theo dõi shop.');
    }

    public function unfollow(Shop $shop)
    {
        $user = Auth::user();
        $shop->followers()->detach($user->id);

        return back()->with('success', 'Bạn đã hủy theo dõi shop.');
    }

    public function searchProducts(Request $request, Shop $shop)
    {
        $query = $request->input('query');

        $products = $shop->products()
            ->where('name', 'like', '%' . $query . '%')
            ->with(['images', 'orderReviews'])
            ->get();

        // Load all necessary relationships for the shop
        $shop->load([
            'products.images', 
            'products.orderReviews',
            'combos.products.product.images',
            'categories',
            'user', 
            'owner',
            'address', 
            'followers'
        ]);

        return view('shop.profile', compact('shop', 'products', 'query'));
    }
    
    public function productsByCategory($shopId, $categoryId)
    {
        $shop = Shop::with([
            'products.images', 
            'products.orderReviews',
            'combos.products.product.images',
            'categories',
            'user', 
            'owner',
            'address', 
            'followers'
        ])->findOrFail($shopId);

        // Nếu shop bị cấm thì trả về trang lỗi 404 tùy chỉnh
        if ($shop->shop_status === ShopStatus::BANNED) {
            return response()->view('error.404NotFound', [], 404);
        }

        $category = $shop->categories()->findOrFail($categoryId);

        // Lấy sản phẩm thuộc danh mục này trong shop
        $products = $category->products()
            ->where('shopID', $shop->id)
            ->with(['images', 'orderReviews'])
            ->get();

        return view('shop.profile', compact('shop', 'products'));
    }
}
