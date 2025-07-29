<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemsOrder extends Model
{
    protected $table = 'items_order';
    protected $fillable = ['orderID','combo_id', 'shop_orderID', 'productID', 'variantID', 'product_name', 'brand', 'category', 'sub_category', 'color', 'size', 'variant_name', 'product_image', 'quantity', 'unit_price', 'total_price', 'discount_amount','combo_quantity'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID', 'id');
    }

    public function shop_order()
    {
        return $this->belongsTo(ShopOrder::class, 'shop_orderID', 'id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'productID', 'id');
    }

    public function variant(){
        return $this->belongsTo(ProductVariant::class, 'variantID', 'id');
    }

    public function shopOrder(): BelongsTo
    {
        return $this->belongsTo(ShopOrder::class, 'shop_orderID', 'id');
    }

    public function combo(){
        return $this->belongsTo(Combo::class, 'combo_id', 'id');
    }
}
