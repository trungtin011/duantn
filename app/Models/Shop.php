<?php

namespace App\Models;

use App\Enums\ShopStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShopAddress;
use App\Models\ShopShippingOption;

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
        return $this->hasMany(ShopAddress::class, 'shopID', 'id');
    }

    public function followers()
    {
        return $this->hasMany(ShopFollower::class, 'shopID');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'shopID');
    }
} 