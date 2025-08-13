<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $table = 'coupon';
    protected $fillable = [
        'code',
        'name',
        'description',
        'image',
        'discount_value',
        'discount_type',
        'max_discount_amount',
        'min_order_amount',
        'quantity',
        'max_uses_per_user',
        'max_uses_total',
        'used_count',
        'start_date',
        'end_date',
        'rank_limit',
        'created_by_role',
        'is_active',
        'is_public',
        'created_by',
        'shop_id',
        'status',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_public' => 'boolean'
    ];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(CouponUser::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByRank($query, $rank)
    {
        return $query->where('rank_limit', 'all')
            ->orWhere('rank_limit', $rank);
    }

    // Methods
    public function isValid()
    {
        return $this->is_active &&
            $this->status === 'active' &&
            now()->between($this->start_date, $this->end_date) &&
            ($this->max_uses_total === null || $this->used_count < $this->max_uses_total);
    }

    public function hasAvailableQuantity()
    {
        // Nếu quantity = 0, coupon vô hạn
        if ($this->quantity == 0) {
            return true;
        }
        
        // Nếu có giới hạn max_uses_total, ưu tiên kiểm tra này
        if ($this->max_uses_total !== null) {
            return $this->used_count < $this->max_uses_total;
        }
        
        // Kiểm tra theo quantity
        return $this->used_count < $this->quantity;
    }

    public function canBeUsedByUser($userId)
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->max_uses_per_user !== null) {
            $userUsage = $this->users()
                ->where('user_id', $userId)
                ->first();
            
            if ($userUsage && $userUsage->used_count >= $this->max_uses_per_user) {
                return false;
            }
        }

        return true;
    }

    /**
     * Sử dụng coupon (tăng used_count)
     */
    public function use()
    {
        $this->increment('used_count');
        return $this;
    }

    /**
     * Hoàn trả coupon (giảm used_count)
     */
    public function refund()
    {
        if ($this->used_count > 0) {
            $this->decrement('used_count');
        }
        return $this;
    }

    /**
     * Lấy số lượng coupon còn lại
     */
    public function getRemainingQuantity()
    {
        if ($this->quantity == 0) {
            return 'unlimited'; // Vô hạn
        }
        
        if ($this->max_uses_total !== null) {
            return max(0, $this->max_uses_total - $this->used_count);
        }
        
        return max(0, $this->quantity - $this->used_count);
    }

    /**
     * Kiểm tra xem coupon có còn hạn sử dụng không
     */
    public function isExpired()
    {
        return now()->isAfter($this->end_date);
    }

    /**
     * Kiểm tra xem coupon có thể sử dụng chưa
     */
    public function isActive()
    {
        return $this->is_active && 
               $this->status === 'active' && 
               !$this->isExpired() && 
               $this->hasAvailableQuantity();
    }

    public function calculateDiscount($amount)
    {
        $discount = $this->discount_type === 'percentage'
            ? ($amount * $this->discount_value / 100)
            : $this->discount_value;

        if ($this->max_discount_amount !== null) {
            $discount = min($discount, $this->max_discount_amount);
        }

        return $discount;
    }
}
