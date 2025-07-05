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
