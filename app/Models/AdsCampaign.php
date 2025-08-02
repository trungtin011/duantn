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
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function adsCampaignItems()
    {
        return $this->hasMany(AdsCampaignItem::class);
    }
}
