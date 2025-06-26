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
<<<<<<< HEAD
        'note',
=======
>>>>>>> bd658a28a89dcbbe87205b492b7250294d4890ad
        'is_default',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shopID', 'id');
    }
}