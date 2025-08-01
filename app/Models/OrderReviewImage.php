<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReviewImage extends Model
{
    protected $table = 'order_review_images';

    protected $fillable = ['review_id', 'image_path'];

    public function review()
    {
        return $this->belongsTo(OrderReview::class, 'review_id');
    }
}
