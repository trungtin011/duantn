<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class ShopOrder extends Model
{
    protected $table = 'shop_order';

    protected $fillable = [
        'shopID',
        'orderID',
        'code',
        'shipping_shop_fee',
        'discount_shop_amount',
        'shipping_provider',
        'shipping_fee',
        'tracking_code',
        'expected_delivery_date',
        'actual_delivery_date',
        'status',
        'note',
    ];

    protected $casts = [
        'expected_delivery_date' => 'datetime',
        'actual_delivery_date' => 'datetime',
        'status' => 'string',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shopID', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID', 'id');
    }

    public function items()
    {
        return $this->hasMany(ItemsOrder::class, 'shop_orderID', 'id');
    }

    public function history()
    {
        return $this->hasMany(ShopOrderHistory::class, 'shop_order_id', 'id');
    }
    
    public function updateStatus($status)
    {
        // Kiểm tra nếu trạng thái mới là confirmed hoặc completed
        if (in_array($status, ['completed'])) {
            // Kiểm tra xem total_spent đã được cập nhật cho đơn hàng này chưa
            $hasUpdatedTotalSpent = ShopOrderHistory::where('shop_order_id', $this->id)
                ->where('description', 'like', '%Cập nhật total_spent%')
                ->exists();

            // Cập nhật trạng thái nếu cần
            if ($this->status !== $status) {
                $this->status = $status;
                $this->save();
                Log::info('Saving shop_order ID: ' . $this->id . ' with status: ' . $this->status);
            }

            // Chỉ cập nhật total_spent nếu chưa được cập nhật trước đó
            if (!$hasUpdatedTotalSpent) {
                $order = Order::find($this->orderID);
                if ($order) {
                    Log::info('Found order ID: ' . $order->id . ', userID: ' . $order->userID . ', total_price: ' . $order->total_price);
                    if ($order->userID) {
                        $user = User::find($order->userID);
                        if ($user) {
                            Log::info('Found user ID: ' . $user->id . ', current total_spent: ' . ($user->total_spent ?? 0));
                            $user->total_spent = ($user->total_spent ?? 0) + $order->total_price;
                            $user->updateRank();
                            $user->save();
                            Log::info('Updated user ID: ' . $user->id . ' to total_spent: ' . $user->total_spent . ', rank: ' . $user->rank);

                            // Lưu lịch sử để đánh dấu total_spent đã được cập nhật
                            ShopOrderHistory::create([
                                'shop_order_id' => $this->id,
                                'status' => $status,
                                'description' => 'Cập nhật total_spent cho user ID: ' . $user->id,
                            ]);
                        } else {
                            Log::warning('User not found for userID: ' . $order->userID);
                        }
                    } else {
                        Log::warning('userID is NULL for order ID: ' . $order->id);
                    }
                } else {
                    Log::warning('Order not found for orderID: ' . $this->orderID);
                }
            } else {
                Log::info('total_spent already updated for shop_order ID: ' . $this->id);
            }

            // Lưu lịch sử trạng thái
            ShopOrderHistory::create([
                'shop_order_id' => $this->id,
                'status' => $status,
                'description' => $status === 'completed' ? 'Người bán xác nhận đơn hàng' : 'Người bán xác nhận hoàn thành đơn hàng',
            ]);
        } else {
            Log::info('No action taken, status: ' . $this->status . ', new status: ' . $status);
        }
    }
}
