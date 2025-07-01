<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/ReviewImage.php
class ReviewImage extends Model
{
    protected $fillable = ['review_id', 'image_path'];

    public function review()
    {
        return $this->belongsTo(ProductReview::class, 'review_id');
    }
}
