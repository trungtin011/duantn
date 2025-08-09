<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderReview;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderReview::with(['product.shop', 'user'])
            ->join('products', 'order_reviews.product_id', '=', 'products.id')
            ->join('users', 'order_reviews.user_id', '=', 'users.id')
            ->join('shops', 'order_reviews.shop_id', '=', 'shops.id')
            ->select('order_reviews.*');

        // Search functionality - Fixed users.username
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('products.name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('users.username', 'like', '%' . $searchTerm . '%') // Fixed: was users.name
                  ->orWhere('order_reviews.comment', 'like', '%' . $searchTerm . '%')
                  ->orWhere('order_reviews.seller_reply', 'like', '%' . $searchTerm . '%');
            });
        }

        // Legacy filters for backward compatibility
        if ($request->filled('product_name')) {
            $query->where('products.name', 'like', '%' . $request->product_name . '%');
        }

        if ($request->filled('user')) {
            $query->where('users.username', 'like', '%' . $request->user . '%');
        }

        // Rating filter
        if ($request->filled('rating')) {
            $query->where('order_reviews.rating', $request->rating);
        }

        // Shop filter
        if ($request->filled('shop_id')) {
            $query->where('order_reviews.shop_id', $request->shop_id);
        }

        // Date filter
        if ($request->filled('filter_date')) {
            $query->whereDate('order_reviews.created_at', $request->filter_date);
        }

        $reviews = $query->orderBy('order_reviews.created_at', 'desc')->paginate(10);
        $shops = Shop::select('id', 'shop_name')->get();

        // If AJAX request, return only table body
        if ($request->ajax()) {
            return view('admin.reviews._table_body', compact('reviews'))->render();
        }

        return view('admin.reviews.index', compact('reviews', 'shops'));
    }

    public function ajax(Request $request)
    {
        // Fresh query without cache issues
        $query = OrderReview::with(['product.shop', 'user'])
            ->join('products', 'order_reviews.product_id', '=', 'products.id')
            ->join('users', 'order_reviews.user_id', '=', 'users.id')
            ->join('shops', 'order_reviews.shop_id', '=', 'shops.id')
            ->select('order_reviews.*');

        // Search functionality - FIXED users.username
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('products.name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('users.username', 'like', '%' . $searchTerm . '%') // FIXED
                  ->orWhere('order_reviews.comment', 'like', '%' . $searchTerm . '%')
                  ->orWhere('order_reviews.seller_reply', 'like', '%' . $searchTerm . '%');
            });
        }

        // Rating filter
        if ($request->filled('rating')) {
            $query->where('order_reviews.rating', $request->rating);
        }

        // Shop filter
        if ($request->filled('shop_id')) {
            $query->where('order_reviews.shop_id', $request->shop_id);
        }

        // Date filter
        if ($request->filled('filter_date')) {
            $query->whereDate('order_reviews.created_at', $request->filter_date);
        }

        $reviews = $query->orderBy('order_reviews.created_at', 'desc')->paginate(10);
        
        return view('admin.reviews._table_body', compact('reviews'))->render();
    }

    public function warnSeller(Shop $shop)
    {
        $user = $shop->owner;

        $user->increment('warning_count');

        if ($user->warning_count >= 3) {
            $user->status = 'banned';
        }

        $user->save();

        return back()->with('success', 'Đã cảnh cáo/cấm seller.');
    }

    public function banCustomer(User $user)
    {
        $user->status = 'banned';
        $user->save();

        return back()->with('success', 'Đã ban khách hàng.');
    }

    public function banSeller(User $user)
    {
        $user->status = 'banned';
        $user->save();

        return back()->with('success', 'Đã ban seller.');
    }

    public function destroy(OrderReview $review)
    {
        $review->delete();

        return back()->with('success', 'Đã xoá đánh giá.');
    }
}
