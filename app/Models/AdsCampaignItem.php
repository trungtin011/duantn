<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AdsCampaign;
use App\Models\Product;

class AdsCampaignItem extends Model
{
    protected $fillable = [
        'ads_campaign_id',
        'product_id',
    ];

    public function adsCampaign()
    {
        return $this->belongsTo(AdsCampaign::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
