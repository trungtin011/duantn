<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopShippingOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'shopID',
        'shipping_type',
        'cod_enabled',
        'is_active',
    ];

    protected $casts = [
        'cod_enabled' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shopID');
    }
} 