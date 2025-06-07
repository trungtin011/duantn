<?php
// filepath: e:\duantn\app\Http\Controllers\Seller\SellerSettingsController.php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;

class SellerSettingsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $shop = Shop::where('ownerID', $user->id)->first();
        return view('seller.settings', compact('shop'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $shop = Shop::where('ownerID', $user->id)->firstOrFail();
        $validated = $request->validate([
            'shop_name' => 'required|string|max:100',
            'shop_phone' => 'nullable|string|max:20',
            'shop_email' => 'nullable|email|max:100',
            'shop_description' => 'nullable|string|max:500',
            'shop_logo' => 'nullable|image|max:2048',
            'shop_banner' => 'nullable|image|max:4096',
            'shop_status' => 'required|in:active,inactive,banned',
        ]);
        if ($request->hasFile('shop_logo')) {
            if ($shop->shop_logo) Storage::delete($shop->shop_logo);
            $validated['shop_logo'] = $request->file('shop_logo')->store('shop_logos', 'public');
        }
        if ($request->hasFile('shop_banner')) {
            if ($shop->shop_banner) Storage::delete($shop->shop_banner);
            $validated['shop_banner'] = $request->file('shop_banner')->store('shop_banners', 'public');
        }
        $shop->update($validated);
        return redirect()->route('seller.settings')->with('success', 'Cập nhật thông tin cửa hàng thành công!');
    }
}
