<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'product_id',
        'shop_id',
        'rating',
        'comment',
    ];
public function index() {
    $orders = Order::with(['items'])->where('userID', auth()->id())->get();

    // Lấy danh sách product_id đã đánh giá của người dùng hiện tại
    $reviewedProductIds = OrderReview::where('user_id', auth()->id())
        ->pluck('product_id')
        ->toArray();

    return view('user.orders.index', compact('orders', 'reviewedProductIds'));
}
    public function images()
    {
        return $this->hasMany(OrderReviewImage::class, 'review_id');
    }

    public function videos()
    {
        return $this->hasMany(OrderReviewVideo::class, 'review_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
