<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart'; // Tên bảng chính xác trong database

    protected $fillable = [
       'userID',
        'productID',
        'variantID',
        'quantity',
        'price',
        'total_price',
        'session_id',
        'buying_flag',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class, 'productID');
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variantID');
    }
}

