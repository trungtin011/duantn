<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    use HasFactory;
protected $table = 'combo';
    protected $fillable = [
        'shopID',
        'combo_name',
        'combo_description',
        'total_price',
        'discount_value',
        'discount_type',
        'status',
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