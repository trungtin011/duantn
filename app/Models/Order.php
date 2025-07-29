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

    public static function orderStatusUpdate($order_id)
    {
        $statusOrder = [
            'pending'    => 1,
            'processing' => 2,
            'completed'  => 3,
            'cancelled'  => 4,
            'returned'   => 4,
        ];

        $order = Order::find($order_id);

        if (!$order) {
            return false;
        }

        $shopOrders = ShopOrder::where('orderID', $order_id)->get();

        if ($shopOrders->isEmpty()) {
            return false;
        }

        $statusCounts = [
            1 => 0, // pending
            2 => 0, // processing
            3 => 0, // completed
            4 => 0, // cancel
        ];

        foreach ($shopOrders as $shopOrder) {
            $status = $shopOrder->status;
            if ($status === 'pending') {
                $statusCounts[1]++;
            } elseif (in_array($status, ['confirmed', 'ready_to_pick', 'picked', 'shipping', 'delivered', 'refunded'])) {
                $statusCounts[2]++;
            } elseif (in_array($status, ['completed','returned'])) {
                $statusCounts[3]++;
            } elseif (in_array($status, ['cancelled'])) {
                $statusCounts[4]++;
            }
        }

        $total = $shopOrders->count();

        if ($statusCounts[4] === $total) {
            $order->order_status = 'cancelled';
        }
        elseif (($statusCounts[3] + $statusCounts[4]) === $total && $statusCounts[3] > 0) {
            $order->order_status = 'completed';
        }
        elseif ($statusCounts[1] < $total) {
            $order->order_status = 'processing';
        }
        else {
            $order->order_status = 'pending';
        }

        $order->save();
        return true;
    }

}
