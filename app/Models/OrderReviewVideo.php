<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReviewVideo extends Model
{
    protected $table = 'order_review_videos';

    protected $fillable = ['review_id', 'video_path'];

    public function review()
    {
        return $this->belongsTo(OrderReview::class, 'review_id');
    }
}
