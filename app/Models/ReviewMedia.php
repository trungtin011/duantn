<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReviewMedia extends Model
{
    use HasFactory;

    protected $table = 'review_media';

    protected $fillable = [
        'review_id',
        'type',
        'path',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id');
    }
}
