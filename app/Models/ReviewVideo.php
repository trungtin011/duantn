<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewVideo extends Model
{
    protected $fillable = ['review_id', 'video_path'];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
