<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductView extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'ip_address',
        'user_agent',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    /**
     * Mối quan hệ với Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Mối quan hệ với User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope để lấy lượt xem theo sản phẩm
     */
    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope để lấy lượt xem theo user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope để lấy lượt xem trong khoảng thời gian
     */
    public function scopeInTimeRange($query, $startDate, $endDate = null)
    {
        if ($endDate) {
            return $query->whereBetween('viewed_at', [$startDate, $endDate]);
        }
        return $query->where('viewed_at', '>=', $startDate);
    }

    /**
     * Scope để lấy lượt xem hôm nay
     */
    public function scopeToday($query)
    {
        return $query->whereDate('viewed_at', today());
    }

    /**
     * Scope để lấy lượt xem trong tuần này
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('viewed_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope để lấy lượt xem trong tháng này
     */
    public function scopeThisMonth($query)
    {
        return $query->whereBetween('viewed_at', [now()->startOfMonth(), now()->endOfMonth()]);
    }
}
