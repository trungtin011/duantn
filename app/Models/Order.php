<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'userID',
        'order_code',
        'total_price',
        'shipping_fee',
        'used_points',
        'coupon_discount',
        'payment_method',
        'payment_status',
        'order_status',
        'order_note',
        'cancel_reason',
        'paid_at',
        'cancelled_at',
        'delivered_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'delivered_at' => 'datetime',
        'total_price' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'shipping_fee' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ItemsOrder::class, 'orderID', 'id');
    }

    public function address(): HasOne
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'id');
    }

    public function shopOrders(): HasMany
    {
        return $this->hasMany(ShopOrder::class, 'orderID', 'id');
    }

    public function shop_order()
    {
        return $this->hasMany(ShopOrder::class, 'orderID', 'id');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id', 'id');
    }

    // Thêm quan hệ shop (dựa trên shopOrders)
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shopID', 'id');
    }

    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function getSubtotalAttribute()
    {
        return $this->items->sum('total_price');
    }

    public function getFinalPriceAttribute()
    {
        return $this->total_price - ($this->coupon_discount ?? 0);
    }

    public function orderAddress()
    {
        return $this->hasOne(OrderAddress::class, 'order_id');
    }

    public function orderStatusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id');
    }

    public static function isOrderSpam($userId, $limit = 3, $minutes = 5)
    {
        $recentOrdersCount = self::where('userID', $userId)
            ->where('created_at', '>=', now()->subMinutes($minutes))
            ->count();
        return $recentOrdersCount >= $limit;
    }
}
