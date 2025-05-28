<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    protected $fillable = [
        'userID',
        'ranking',
        'preferred_payment_method',
        'total_orders',
        'total_spent',
        'total_points',
        'last_order_at'
    ];

    protected $casts = [
        'total_orders' => 'integer',
        'total_spent' => 'decimal:2',
        'total_points' => 'integer',
        'last_order_at' => 'datetime'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID');
    }

    // Scopes
    public function scopeByRanking($query, $ranking)
    {
        return $query->where('ranking', $ranking);
    }

    // Methods
    public function updateOrderStats($orderAmount)
    {
        $this->increment('total_orders');
        $this->increment('total_spent', $orderAmount);
        $this->update(['last_order_at' => now()]);
        
        // Update ranking based on total spent
        $this->updateRanking();
    }

    public function updateRanking()
    {
        $newRanking = match(true) {
            $this->total_spent >= 10000000 => 'diamond',
            $this->total_spent >= 5000000 => 'gold',
            $this->total_spent >= 2000000 => 'silver',
            default => 'bronze'
        };

        if ($newRanking !== $this->ranking) {
            $this->update(['ranking' => $newRanking]);
        }
    }

    public function addPoints($points)
    {
        $this->increment('total_points', $points);
    }

    public function deductPoints($points)
    {
        $this->decrement('total_points', $points);
    }
} 