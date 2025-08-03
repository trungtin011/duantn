<?php

namespace App\Observers;

use App\Models\ShopOrder;
use Illuminate\Support\Facades\Log; 

class ShopOrderObserver
{
    /**
     * Handle the ShopOrder "created" event.
     */
    public function created(ShopOrder $shopOrder): void
    {
        //
    }

    /**
     * Handle the ShopOrder "updated" event.
     */

    public function updated(ShopOrder $shopOrder): void
    {
        Log::info('ShopOrder updated', [
            'shop_order_id' => $shopOrder->id,
            'status' => $shopOrder->status,
            'isDirty' => $shopOrder->isDirty('status')
        ]);
        if ($shopOrder->isDirty('status') && in_array($shopOrder->status, ['confirmed', 'completed'])) {
            Log::info('Triggering updateStatus for status: ' . $shopOrder->status, ['shop_order_id' => $shopOrder->id]);
            $shopOrder->updateStatus($shopOrder->status);
        }
    }

    /**
     * Handle the ShopOrder "deleted" event.
     */
    public function deleted(ShopOrder $shopOrder): void
    {
        //
    }

    /**
     * Handle the ShopOrder "restored" event.
     */
    public function restored(ShopOrder $shopOrder): void
    {
        //
    }

    /**
     * Handle the ShopOrder "force deleted" event.
     */
    public function forceDeleted(ShopOrder $shopOrder): void
    {
        //
    }
}
