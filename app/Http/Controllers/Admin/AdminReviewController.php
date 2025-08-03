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
        $query = OrderReview::with(['product.shop', 'user']);

        if ($request->filled('product_name')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->product_name . '%');
            });
        }

        if ($request->filled('user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->latest()->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
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

    public function destroy(OrderReview $review)
    {
        $review->delete();

        return back()->with('success', 'Đã xoá đánh giá.');
    }
}
