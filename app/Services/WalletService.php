<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ShopWallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public static function handlePaidOrder(Order $order)
    {
        $items = DB::table('items_order')
            ->join('products', 'items_order.productID', '=', 'products.id')
            ->where('items_order.orderID', $order->id)
            ->select('products.shopID', 'items_order.quantity', 'items_order.unit_price')
            ->get();

        $grouped = $items->groupBy('shopID');

        foreach ($grouped as $shopId => $shopItems) {
            $amount = $shopItems->sum(fn($item) => $item->quantity * $item->unit_price);
            $wallet = ShopWallet::firstOrCreate(['shop_id' => $shopId], ['balance' => 0]);

            $wallet->increment('balance', $amount);

            WalletTransaction::create([
                'shop_wallet_id' => $wallet->id,
                'order_id' => $order->id,
                'amount' => $amount,
                'direction' => 'in',
                'type' => 'order',
                'description' => 'Doanh thu từ đơn hàng #' . $order->order_code,
                'status' => 'completed',
            ]);
        }
    }
}
