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
        'shopID',
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
        return $this->belongsTo(User::class, 'userID', 'id');
    }

    public function shop()
    {
        return $this->hasOneThrough(Shop::class, OrderItem::class, 'orderID', 'id', 'id', 'shop_orderID');
    }

    public function shop_order()
    {
        return $this->hasMany(ShopOrder::class, 'orderID', 'id');
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ItemsOrder::class, 'orderID', 'id');
        return $this->hasMany(OrderItem::class, 'orderID', 'id');
    }

    public function address(): HasOne
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'id');
    }



    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id', 'id');
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

    public function shopOrders()
    {
        return $this->hasMany(ShopOrder::class, 'orderID', 'id');
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

    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'pending' => 'Đang chờ xử lý',
            'processing' => 'Đang xử lý',
            'awaiting-pickup' => 'Chờ lấy hàng',
            'in-delivery' => 'Đang giao hàng',
            'delivered' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            'refunded' => 'Trả hàng/Hoàn tiền',
        ];

        return $statusLabels[$this->order_status] ?? ucfirst($this->order_status);
    }

    public function getStatusClassesAttribute()
    {
        $statusClasses = [
            'pending' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/10',
            'processing' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/10',
            'awaiting-pickup' => 'bg-blue-50 text-blue-700 ring-blue-600/10',
            'in-delivery' => 'bg-blue-50 text-blue-700 ring-blue-600/10',
            'delivered' => 'bg-green-50 text-green-700 ring-green-600/10',
            'cancelled' => 'bg-red-50 text-red-700 ring-red-600/10 filter-none',
            'refunded' => 'bg-gray-50 text-gray-700 ring-gray-600/10',
        ];

        return $statusClasses[$this->order_status] ?? 'bg-gray-50 text-gray-700 ring-gray-600/10';
    }

    public static function orderStatusUpdate($order_id)
    {
        $order = Order::find($order_id);

        if(!$order){
            return false;
        }

        $shopOrders = ShopOrder::where('orderID', $order_id)->get();
        $countChildOrder = $shopOrders->count();

        if($countChildOrder == 0){
            return false;
        }

        // Định nghĩa các trạng thái theo thứ tự ưu tiên
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

        // Lấy trạng thái của tất cả đơn hàng con
        $childStatuses = $shopOrders->pluck('status')->toArray();
        
        // Kiểm tra nếu có đơn hàng bị hủy
        if(in_array('confirmed', $childStatuses)) {
            $order->order_status = 'confirmed';
            $order->save();
            return true;
        }

        // Kiểm tra nếu có đơn hàng bị lỗi giao hàng
        if(in_array('shipping', $childStatuses)) {
            $order->order_status = 'shipping';
            $order->save();
            return true;
        }

        // Kiểm tra nếu có đơn hàng bị trả lại
        if(in_array('delivered', $childStatuses)) {
            $order->order_status = 'delivered';
            $order->save();
            return true;
        }

        // Tìm trạng thái cao nhất trong các đơn hàng con
        $highestStatus = 'pending';
        foreach($statusHierarchy as $status) {
            if(in_array($status, $childStatuses)) {
                $highestStatus = $status;
            }
        }

        // Đếm số lượng đơn hàng con ở trạng thái cao nhất
        $countAtHighestStatus = count(array_filter($childStatuses, function($status) use ($highestStatus) {
            return $status === $highestStatus;
        }));

        // Nếu tất cả đơn hàng con đều ở cùng trạng thái
        if($countAtHighestStatus == $countChildOrder) {
            $order->order_status = $highestStatus;
        } else {
            $order->order_status = 'partially_' . $highestStatus;
        }

        $order->save();
        return true;
    }
}
