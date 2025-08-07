<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewLike extends Model
{
    protected $fillable = ['user_id', 'order_review_id'];

    public function review()
    {
        return $this->belongsTo(OrderReview::class, 'order_review_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
