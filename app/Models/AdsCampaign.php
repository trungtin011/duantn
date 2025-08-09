<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\AdsCampaignItem;

class AdsCampaign extends Model
{
    protected $fillable = [
        'shop_id',
        'name',
        'start_date',
        'end_date',
        'status',
        'bid_amount',
        'big_amount',
        'impressions',
        'clicks',
        'total_spent',
    ];

    protected $casts = [
        'bid_amount' => 'decimal:2',
        'big_amount' => 'decimal:2',
        'total_spent' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function adsCampaignItems()
    {
        return $this->hasMany(AdsCampaignItem::class);
    }

    public function adClicks()
    {
        return $this->hasMany(AdClick::class, 'ads_campaign_id');
    }
}
