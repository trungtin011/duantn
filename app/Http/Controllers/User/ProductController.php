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
    public function show($slug)
    {
        $product = Product::with(['images', 'reviews', 'variants'])->where('slug', $slug)->firstOrFail();
        return view('user.product.product_detail', compact('product'));
    }
}
