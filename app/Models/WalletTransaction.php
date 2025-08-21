<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model {
    protected $table = 'wallet_transactions';

    protected $fillable = [
        'shop_wallet_id', 'order_id', 'amount', 'direction', 'type', 'description', 'status', 'meta'
    ];

    protected $casts = [
        'meta' => 'array',
        'amount' => 'decimal:2',
    ];

    public function wallet() {
        return $this->belongsTo(ShopWallet::class);
    }

    public function order() {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function getDisplayCodeAttribute() {
        // Prefer explicit meta codes for revenue batches
        if ($this->type === 'revenue' && is_array($this->meta ?? null)) {
            $orderCodes = $this->meta['order_codes'] ?? [];
            if (!empty($orderCodes)) {
                $preview = array_slice($orderCodes, 0, 3);
                return implode(', ', $preview) . (count($orderCodes) > 3 ? '…' : '');
            }
        }

        // Fallback to linked order's code if present
        if ($this->relationLoaded('order') || $this->order_id) {
            $order = $this->order;
            if ($order && !empty($order->order_code)) {
                return $order->order_code;
            }
        }

        // Last resort: transaction_code if exists or the id
        if (property_exists($this, 'transaction_code') && !empty($this->transaction_code)) {
            return $this->transaction_code;
        }
        return (string) $this->id;
    }
}

