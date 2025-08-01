<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\User;

class OrderObserver
{
   public function updated(Order $order)
    {
        // Chỉ xử lý khi trạng thái chuyển thành 'completed'
        if ($order->isDirty('status') && $order->status === 'completed') {
            $order->markAsCompleted();
        }
    }
}