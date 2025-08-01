<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $category = $shop->categories()->findOrFail($categoryId);

        // Lấy sản phẩm thuộc danh mục này trong shop
        $products = $category->products()
            ->where('shopID', $shop->id)
            ->with(['images', 'orderReviews'])
            ->get();

        return view('shop.profile', compact('shop', 'products'));
    }
}
