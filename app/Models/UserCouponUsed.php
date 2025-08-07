<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;

class UserCouponUsed extends Model
{
    protected $table = 'user_coupon_used';
    protected $fillable = [
        'user_id',
        'coupon_id',
        'order_id',
        'used_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
    
    
}
