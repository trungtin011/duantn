<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAddress extends Model
{
    protected $fillable = [
        'order_id',
        'receiver_name',
        'receiver_phone',
        'receiver_email',
        'address',
        'province',
        'district',
        'ward',
        'zip_code',
        'note',
        'address_type'
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Methods
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->ward}, {$this->district}, {$this->province}";
    }
} 