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
        $shop = Shop::with(['products', 'user', 'address', 'products.orderReviews'])->findOrFail($id);
        $addresses = ShopAddress::all();

        foreach ($addresses as $address) {
            Shop::where('id', $address->shopID)->update([
                'shop_address_id' => $address->id,
            ]);
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
        $user->followedShops()->detach($shop->id);

        return back()->with('success', 'Bạn đã hủy theo dõi shop.');
    }
}
