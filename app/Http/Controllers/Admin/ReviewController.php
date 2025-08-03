<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderReview;
use App\Models\Shop;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderReview::with(['product.images', 'user', 'shop']);

        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->orderByDesc('created_at')->paginate(10);
        $shops = Shop::pluck('shop_name', 'id');

        return view('admin.reviews.index', compact('reviews', 'shops'));
    }

    public function edit($id)
    {
        $review = OrderReview::with(['product', 'user', 'shop'])->findOrFail($id);
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {
        $review = OrderReview::findOrFail($id);
        $review->update($request->only(['rating', 'comment']));
        return redirect()->route('admin.reviews.index')->with('success', 'Đã cập nhật đánh giá!');
    }

    public function destroy($id)
    {
        $review = OrderReview::findOrFail($id);
        $review->delete();
        return redirect()->back()->with('success', 'Đã xóa đánh giá!');
    }
}
