<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
Use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($productID)
    {
        $product = Product::find($productID);
        $productImages = ProductImage::where('productID', $productID)->get();
        $reviews = Review::where('productID', $productID)
                        ->with('user')
                        ->latest()
                        ->get();
        return view('user.product.product_detail', compact('product', 'productImages', 'reviews'));
    }
}