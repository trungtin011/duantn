<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'review'; // giữ nguyên

    protected $fillable = [
        'userID',
        'orderID',      // Đã thay vì productID
        'shopID',
        'rating',
        'comment',
        'video_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shopID');
    }

    public function likes()
    {
        return $this->hasMany(ReviewLike::class);
    }

    public function media()
    {
        return $this->hasMany(ReviewMedia::class);
    }
}
