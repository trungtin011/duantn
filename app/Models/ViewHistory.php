<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewHistory extends Model
{
    protected $table = 'view_history';
    
    protected $fillable = [
        'userID',
        'productID',
        'shopID',
        'view_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'productID');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shopID');
    }
} 