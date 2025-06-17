<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request, $slug)
    {
        $ratingFilter = $request->input('rating');

        $product = Product::with([
            'images',
            'reviews.user',
            'variants',
            'reviews.likes',
            'shop'
        ])->where('slug', $slug)->firstOrFail();

        $filteredReviews = $ratingFilter
            ? $product->reviews->where('rating', (int) $ratingFilter)
            : $product->reviews;

        $viewed = session()->get('viewed_products', []);
        $viewed = array_unique(array_merge([$product->id], $viewed));
        session()->put('viewed_products', array_slice($viewed, 0, 10));

        $recentProducts = Product::whereIn('id', $viewed)->where('id', '!=', $product->id)->with('images')->get();

        return view('user.product.product_detail', [
            'product' => $product,
            'filteredReviews' => $filteredReviews,
            'ratingFilter' => $ratingFilter,
            'recentProducts' => $recentProducts,
            'shop' => $product->shop
        ]);
    }
}
