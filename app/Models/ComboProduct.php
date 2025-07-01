<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'comboID',
        'productID',
        'variantID',
        'quantity',
    ];

    public function combo()
    {
        return $this->belongsTo(Combo::class, 'comboID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'productID');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variantID');
    }
}