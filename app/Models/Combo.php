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

    protected static function booted()
    {
        static::addGlobalScope('shopNotBanned', function ($builder) {
            // Chỉ áp dụng cho frontend, không áp dụng cho admin
            if (!app()->runningInConsole() && !request()->is('admin/*')) {
                $builder->whereHas('shop', function ($q) {
                    $q->where('shop_status', '!=', 'banned');
                });
            }
        });
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shopID');
    }

    public function products()
    {
        return $this->hasMany(ComboProduct::class, 'comboID');
    }
}