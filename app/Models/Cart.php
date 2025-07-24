<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = [
       'userID',
        'productID',
        'variantID',
        'quantity',
        'price',
        'total_price',
        'session_id',
        'buying_flag',
        'combo_id',

    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productID', 'id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shopID', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variantID', 'id');
    }
     public function combo()
    {
        return $this->belongsTo(Combo::class, 'combo_id');
    }
}

