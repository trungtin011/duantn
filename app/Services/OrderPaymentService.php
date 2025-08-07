<?php

namespace App\Services;

use App\Models\Order;
use App\Services\WalletService;

class OrderPaymentService
{
    public static function markOrderAsPaid($orderId)
    {
        $order = Order::findOrFail($orderId);
        if ($order->payment_status == 'paid') return;

        $order->payment_status = 'paid';
        $order->paid_at = now();
        $order->save();

        WalletService::handlePaidOrder($order);
    }
}

