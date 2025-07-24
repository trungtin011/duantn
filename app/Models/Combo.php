<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    protected $table = 'combo';
    protected $fillable = [
        'shopID',
        'combo_name',
        'combo_description',
        'image',
        'total_price',
        'discount_value',
        'discount_type',
        'quantity',
        'status',
        'created_at',
        'updated_at',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shopID');
    }

    public function products()
    {
        return $this->hasMany(ComboProduct::class, 'comboID');
    }
}