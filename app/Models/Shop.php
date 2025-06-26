<?php

namespace App\Models;

use App\Enums\ShopStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShopAddress;
use App\Models\ShopShippingOption;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'ownerID',
        'shop_name',
        'shop_phone',
        'shop_email',
        'shop_description',
        'shop_rating',
        'shop_logo',
        'shop_banner',
        'shop_status',
    ];

    protected $casts = [
        'shop_rating' => 'decimal:1',
        'shop_status' => ShopStatus::class,
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'ownerID');
    }

    public function shopAddress()
    {
        return $this->hasOne(ShopAddress::class, 'shopID')->where('is_default', 1);
    }

    public function shopShippingOptions()
    {
        return $this->hasMany(ShopShippingOption::class, 'shopID');
    }

    public function addresses()
    {
        return $this->belongsTo(ShopAddress::class, 'shop_address_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'shop_followers', 'shopID', 'followerID')
            ->withTimestamps();
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'shopID');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'shopID');
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class, 'shop_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'shop_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'shop_orderID', 'id');
    }
}
