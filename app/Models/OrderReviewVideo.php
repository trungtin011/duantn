<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReviewVideo extends Model
{
    use HasFactory;

    protected $fillable = ['review_id', 'video_path'];

    public function review()
    {
        return $this->belongsTo(OrderReview::class, 'review_id');
    }
}
