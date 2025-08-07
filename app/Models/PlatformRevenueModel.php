<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformRevenueModel extends Model
{
    protected $table = 'platform_revenues';

    protected $fillable = [
        'shop_order_id',
        'order_id',
        'shop_id',
        'shop_name',
        'payment_method',
        'commission_rate',
        'commission_amount',
        'total_amount',
        'net_revenue',
        'status',
        'confirmed_at',
        'note',
    ];

    public function shopOrder()
    {
        return $this->belongsTo(ShopOrder::class, 'shop_order_id', 'id');
    }

    public function order(){
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function shop(){
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    // các phần này nữa

    public static function totalPlatformRevenue()
    {
        return self::sum('commission_amount');
    }

    public static function totalPlatformRevenueByShop($shop_id)
    {
        return self::where('shop_id', $shop_id)->sum('commission_amount');
    }

    public static function totalPlatformRevenueByShopAndDate($shop_id, $date)
    {
        return self::where('shop_id', $shop_id)
            ->whereDate('created_at', $date)
            ->sum('commission_amount');
    }

    public static function shopNetRevenue($shop_id)
    {
        return self::where('shop_id', $shop_id)->sum('net_revenue');
    }

    public static function shopNetRevenueByDate($shop_id, $date)
    {
        return self::where('shop_id', $shop_id)
            ->whereDate('created_at', $date)
            ->sum('net_revenue');
    }

    public static function totalPlatformOrder()
    {
        return self::sum('total_amount');
    }

    public static function totalPlatformOrderByShop($shop_id)
    {
        return self::where('shop_id', $shop_id)->sum('total_amount');
    }
    
}
