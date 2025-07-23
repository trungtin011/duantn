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
        'coupon_id',
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

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Đang chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đang giao hàng',
            'delivered' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            'refunded' => 'Trả hàng/Hoàn tiền',
            'confirmed' => 'Đã xác nhận',
            'preparing' => 'Đang chuẩn bị',
            'ready_to_pick' => 'Sẵn sàng giao',
            'picked' => 'Đã lấy hàng',
            'shipping' => 'Đang vận chuyển',
            'shipping_failed' => 'Giao hàng thất bại',
            'returned' => 'Đã trả hàng',
            'completed' => 'Hoàn tất',
            'partially_pending' => 'Chờ xử lý một phần',
            'partially_confirmed' => 'Xác nhận một phần',
            'partially_preparing' => 'Chuẩn bị một phần',
            'partially_ready_to_pick' => 'Sẵn sàng giao một phần',
            'partially_picked' => 'Đã lấy hàng một phần',
            'partially_shipping' => 'Đang vận chuyển một phần',
            'partially_delivered' => 'Hoàn thành một phần',
            'partially_cancelled' => 'Hủy một phần',
            'partially_returned' => 'Trả hàng một phần',
            'partially_completed' => 'Hoàn tất một phần',
        ];

        return $statuses[$this->order_status] ?? 'Không xác định';
    }

    public function getStatusClassesAttribute()
    {
        $classes = [
            'pending' => 'bg-yellow-100 text-yellow-800 ring-yellow-600/20',
            'processing' => 'bg-blue-100 text-blue-800 ring-blue-600/20',
            'shipped' => 'bg-green-100 text-green-800 ring-green-600/20',
            'delivered' => 'bg-teal-100 text-teal-800 ring-teal-600/20',
            'cancelled' => 'bg-gray-100 text-gray-800 ring-gray-600/20',
            'refunded' => 'bg-red-100 text-red-800 ring-red-600/20',
            'confirmed' => 'bg-indigo-100 text-indigo-800 ring-indigo-600/20',
            'preparing' => 'bg-purple-100 text-purple-800 ring-purple-600/20',
            'ready_to_pick' => 'bg-orange-100 text-orange-800 ring-orange-600/20',
            'picked' => 'bg-lime-100 text-lime-800 ring-lime-600/20',
            'shipping' => 'bg-cyan-100 text-cyan-800 ring-cyan-600/20',
            'shipping_failed' => 'bg-red-200 text-red-900 ring-red-700/20',
            'returned' => 'bg-pink-100 text-pink-800 ring-pink-600/20',
            'completed' => 'bg-emerald-100 text-emerald-800 ring-emerald-600/20',
            'partially_pending' => 'bg-yellow-200 text-yellow-900 ring-yellow-700/20',
            'partially_confirmed' => 'bg-indigo-200 text-indigo-900 ring-indigo-700/20',
            'partially_preparing' => 'bg-purple-200 text-purple-900 ring-purple-700/20',
            'partially_ready_to_pick' => 'bg-orange-200 text-orange-900 ring-orange-700/20',
            'partially_picked' => 'bg-lime-200 text-lime-900 ring-lime-700/20',
            'partially_shipping' => 'bg-cyan-200 text-cyan-900 ring-cyan-700/20',
            'partially_delivered' => 'bg-teal-200 text-teal-900 ring-teal-700/20',
            'partially_cancelled' => 'bg-gray-200 text-gray-900 ring-gray-700/20',
            'partially_returned' => 'bg-pink-200 text-pink-900 ring-pink-700/20',
            'partially_completed' => 'bg-emerald-200 text-emerald-900 ring-emerald-700/20',
        ];

        return $classes[$this->order_status] ?? 'bg-gray-100 text-gray-800 ring-gray-600/20';
    }

    public static function orderStatusUpdate($order_id)
    {
        $order = Order::find($order_id);

        if (!$order) {
            return false;
        }

        $shopOrders = ShopOrder::where('orderID', $order_id)->get();
        $countChildOrder = $shopOrders->count();

        if ($countChildOrder == 0) {
            return false;
        }

        $statusHierarchy = [
            'pending',
            'confirmed',
            'preparing',
            'ready_to_pick',
            'picked',
            'shipping',
            'delivered',
            'cancelled',
            'shipping_failed',
            'returned',
            'completed'
        ];

        $childStatuses = $shopOrders->pluck('status')->toArray();

        if (in_array('confirmed', $childStatuses)) {
            $order->order_status = 'confirmed';
            $order->save();
            return true;
        }

        if (in_array('shipping', $childStatuses)) {
            $order->order_status = 'shipping';
            $order->save();
            return true;
        }

        if (in_array('delivered', $childStatuses)) {
            $order->order_status = 'delivered';
            $order->save();
            return true;
        }

        $highestStatus = 'pending';
        foreach ($statusHierarchy as $status) {
            if (in_array($status, $childStatuses)) {
                $highestStatus = $status;
            }
        }

        $countAtHighestStatus = count(array_filter($childStatuses, function ($status) use ($highestStatus) {
            return $status === $highestStatus;
        }));

        if ($countAtHighestStatus == $countChildOrder) {
            $order->order_status = $highestStatus;
        } else {
            $order->order_status = 'partially_' . $highestStatus;
        }

        $order->save();
        return true;
    }
}
