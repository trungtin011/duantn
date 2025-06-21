<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemsOrder extends Model
{
    protected $table = 'items_order';
    protected $fillable = ['orderID', 'shop_orderID', 'productID', 'variantID', 'product_name', 'brand', 'category', 'sub_category', 'color', 'size', 'variant_name', 'product_image', 'quantity', 'unit_price', 'total_price', 'discount_amount'];

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
    
}
