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
        'shop_province',
        'shop_district',
        'shop_ward',
        'note',
        'is_default',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shopID');
    }
}