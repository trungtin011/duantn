<?php

namespace App\Models;

use App\Enums\ShopStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShopAddress;
use App\Models\ShopShippingOption;
use App\Models\Combo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ShopWallet;

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
        'total_sales',
        'total_rating',
        'total_products',
        'total_followers',
    ];

    protected $casts = [
        'shop_rating' => 'decimal:1',
        'shop_status' => ShopStatus::class,
    ];

    protected static function booted()
    {
        static::addGlobalScope('userVisible', function ($builder) {
            // Chỉ áp dụng cho frontend, không áp dụng cho admin
            if (!app()->runningInConsole() && !request()->is('admin/*')) {
                $builder->where('shop_status', '!=', 'banned');
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'ownerID');
    }

    public function shopAddress()
    {
        return $this->hasOne(ShopAddress::class, 'shopID')->where('is_default', 1);
    }

    public function address()
    {
        return $this->hasOne(ShopAddress::class, 'shopID', 'id');
    }

    public function addresses()
    {
        return $this->hasMany(ShopAddress::class, 'shopID', 'id');
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
        return $this->hasMany(Product::class, 'shopID', 'id');
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
        return $this->hasMany(ItemsOrder::class, 'shop_orderID', 'id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'ownerID', 'id');
    }

    public function orderReviews()
    {
        return $this->hasMany(OrderReview::class, 'shop_id');
    }

    public function followedShops()
    {
        return $this->belongsToMany(User::class, 'shop_followers', 'shopID', 'followerID')
            ->withTimestamps();
    }

    public function shopCategories()
    {
        return $this->hasMany(\App\Models\ShopCategory::class);
    }
    public function categories()
    {
        return $this->hasMany(\App\Models\ShopCategory::class);
    }
    
    /**
     * Get the shop name attribute
     */
    public function getNameAttribute()
    {
        return $this->shop_name;
    }
    
    public function combos()
    {
        return $this->hasMany(Combo::class, 'shopID', 'id');
    }
    public function wallet()
    {
        return $this->hasOne(ShopWallet::class, 'shop_id');
    }

    // protected static function booted()
    // {
    //     static::created(function ($shop) {
    //         ShopWallet::firstOrCreate(['shop_id' => $shop->id], ['balance' => 0]);
    //     });
    // }
}
