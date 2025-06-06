<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::where('user_id', auth()->user()->id)->get();
        return view('user.wishlist.index', compact('wishlist'));
    }


    public function store(Request $request)
    {
        $wishlist = new Wishlist();
        $wishlist->user_id = auth()->user()->id;
        $wishlist->product_id = $request->product_id;
        $wishlist->save();
        return redirect()->route('user.wishlist.index')->with('success', 'Product added to wishlist successfully');
    }

    // Remove product from wishlist
    public function destroy($id)
    {
        // ...code to remove product from wishlist...
    }

    // Other wishlist management methods...
}
