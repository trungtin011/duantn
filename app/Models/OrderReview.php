<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReview extends Model
{
    protected $table = 'order_reviews';

    protected $fillable = ['user_id', 'product_id', 'shop_order_id', 'shop_id', 'rating', 'comment'];


    public function shopOrder()
    {
        return $this->belongsTo(ShopOrder::class, 'shopID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function images()
    {
        return $this->hasMany(OrderReviewImage::class, 'review_id');
    }

    public function videos()
    {
        return $this->hasMany(OrderReviewVideo::class, 'review_id');
    }

    public function likes()
    {
        return $this->hasMany(ReviewLike::class, 'order_review_id');
    }
}
