<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
        'image_path',
        'video_path',
        'likes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes()
    {
        return $this->hasMany(ReviewLike::class, 'review_id');
    }
    // app/Models/ProductReview.php
    public function images()
    {
        return $this->hasMany(ReviewImage::class, 'review_id');
    }
}
