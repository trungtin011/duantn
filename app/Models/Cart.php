<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = [
        'userID',
        'productID',    
        'quantity',
        'price',
        'total_price',
        'buying_flag',
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
}
