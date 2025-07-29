<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    // protected $fillable = [
    //     'userID',
    //     'productID',
    //     'shopID',
    //     'rating',
    //     'comment',
    // ];

    protected $fillable = ['user_id', 'order_id', 'product_id', 'shop_id', 'rating', 'comment'];
    // Quan hệ với user
    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function likes()
    {
        return $this->hasMany(ReviewLike::class);
    }

    public function likedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    // Hình ảnh & video kèm đánh giá
    public function images()
    {
        return $this->hasMany(ReviewImage::class);
    }

    public function videos()
    {
        return $this->hasMany(ReviewVideo::class);
    }
public function reviews()
    {
        return $this->hasMany(Review::class, 'combo_id'); 
    }
}
