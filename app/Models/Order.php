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
        'userID', // Cột trong migration là userID
        'order_code',
        'total_price',
        'couponID',
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
        'coupon_discount' => 'decimal:2'
    ];

    // Sửa quan hệ user và shop
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID'); // Chỉ định rõ cột userID
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shopID'); // Chỉ định rõ cột shopID
    }

    public function shop_order()
    {
        return $this->hasMany(ShopOrder::class, 'orderID', 'id');
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(ItemsOrder::class, 'orderID', 'id');
    }

    public function address(): HasOne
    {
        return $this->hasOne(OrderAddress::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id', 'id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('order_status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('order_status', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('order_status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('order_status', 'cancelled');
    }

    public function scopeRefunded($query)
    {
        return $query->where('order_status', 'refunded');
    }

    // Methods
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
        return $this->total_price - $this->coupon_discount;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function orderAddress()
    {
        return $this->hasOne(OrderAddress::class, 'order_id');
    }

    public function orderStatusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id');
    }
}
