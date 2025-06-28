<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Bảng dữ liệu
    protected $table = 'review';

    protected $fillable = [
        'userID',
        'orderID',     // ✅ Đã thay productID thành orderID
        'shopID',
        'rating',
        'comment',
        'video_path',  // nếu bạn có lưu video thì thêm vào fillable
    ];

    // Quan hệ với user
    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    // Quan hệ với order
    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID');
    }

    // Quan hệ với shop (nếu có)
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shopID');
    }

    // Lượt thích
    public function likes()
    {
        return $this->hasMany(ReviewLike::class);
    }

    // Kiểm tra người dùng đã thích chưa
    public function likedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    // Hình ảnh & video kèm đánh giá
    public function media()
    {
        return $this->hasMany(ReviewMedia::class); // dùng chung bảng cho cả ảnh và video
    }
}
