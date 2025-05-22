<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'shopID',
        'shop_address',
        'shop_city',
        'shop_state',
        'shop_zip_code',
        'shop_country',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shopID');
    }
} 