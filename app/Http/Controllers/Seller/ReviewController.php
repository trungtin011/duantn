<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        $reviews = Review::with('user', 'product.shop', 'product.images')->latest()->get();
        return view('seller.reviews.index' , compact('reviews'));
    }

    public function show($id)
    {
        $review = Review::with('user', 'product.shop', 'product.images')->find($id);
        return view('seller.reviews.show', compact('review'));
    }

    public function destroy($id)
    {
        $review = Review::find($id);
        $review->delete();
        return redirect()->route('seller.reviews.index');
    }
}
