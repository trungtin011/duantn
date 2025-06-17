<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\ShopAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ShopController extends Controller
{
    public function show($id)
    {
        $shop = Shop::with('products', 'user', 'address', 'products.reviews')->findOrFail($id);
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
        $user = auth()->user();

        if (!$shop->followers->contains($user->id)) {
            $shop->followers()->attach($user->id); // gắn người dùng vào
        }

        return back();
    }
    public function unfollow(Shop $shop)
    {
        $user = Auth::user();
        $user->followedShops()->detach($shop->id);

        return back()->with('success', 'Bạn đã huỷ theo dõi shop.');
    }
}
