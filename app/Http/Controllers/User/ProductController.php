<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
public function show($productID, $slug)
{
    $product = Product::with(['images', 'variants', 'dimension'])->where('slug', $slug)->firstOrFail();
    return view('user.products.product_detail', compact('product', 'productImages'));
}
}