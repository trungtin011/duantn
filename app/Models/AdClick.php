<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AdClick extends Model
{
    protected $fillable = [
        'shop_id',
        'ads_campaign_id',
        'product_id',
        'click_type',
        'user_ip',
        'user_agent',
        'cost_per_click',
        'is_charged',
        'wallet_transaction_id',
        'user_id', // Thêm user_id để track theo user
        'clicked_at' // Thêm thời gian click
    ];

    protected $casts = [
        'cost_per_click' => 'decimal:2',
        'is_charged' => 'boolean',
        'clicked_at' => 'datetime',
    ];

    // Thêm timestamps nếu chưa có
    public $timestamps = true;

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function adsCampaign()
    {
        return $this->belongsTo(AdsCampaign::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function walletTransaction()
    {
        return $this->belongsTo(WalletTransaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kiểm tra user đã click quảng cáo này chưa
     */
    public static function hasUserClicked($userId, $campaignId, $shopId)
    {
        $cacheKey = "user_ad_click_{$userId}_{$campaignId}_{$shopId}";
        
        // Kiểm tra cache trước
        if (Cache::has($cacheKey)) {
            return true;
        }
        
        // Kiểm tra database
        $hasClicked = static::where('user_id', $userId)
            ->where('ads_campaign_id', $campaignId)
            ->where('shop_id', $shopId)
            ->exists();
        
        // Cache kết quả trong 1 giờ
        if ($hasClicked) {
            Cache::put($cacheKey, true, 3600);
        }
        
        return $hasClicked;
    }

    /**
     * Kiểm tra IP đã click quá nhiều trong thời gian ngắn
     */
    public static function hasIpClicked($ip, $campaignId, $shopId, $timeLimit = 24)
    {
        $cacheKey = "ip_ad_click_{$ip}_{$campaignId}_{$shopId}";
        
        // Kiểm tra cache trước
        if (Cache::has($cacheKey)) {
            return true;
        }
        
        // Kiểm tra database
        $hasClicked = static::where('user_ip', $ip)
            ->where('ads_campaign_id', $campaignId)
            ->where('shop_id', $shopId)
            ->whereRaw('COALESCE(clicked_at, created_at) >= ?', [now()->subHours($timeLimit)])
            ->exists();
        
        // Cache kết quả trong 1 giờ
        if ($hasClicked) {
            Cache::put($cacheKey, true, 3600);
        }
        
        return $hasClicked;
    }

    /**
     * Kiểm tra rate limit cho IP
     */
    public static function checkRateLimit($ip, $limit = 5, $minutes = 1)
    {
        $cacheKey = "ad_click_rate_{$ip}";
        
        if (Cache::has($cacheKey) && Cache::get($cacheKey) >= $limit) {
            return false;
        }
        
        Cache::add($cacheKey, 1, $minutes * 60);
        Cache::increment($cacheKey);
        
        return true;
    }

    /**
     * Tạo click tracking mới
     */
    public static function createClick($data)
    {
        $click = static::create([
            'user_id' => $data['user_id'] ?? null,
            'shop_id' => $data['shop_id'],
            'ads_campaign_id' => $data['campaign_id'],
            'product_id' => $data['product_id'] ?? null,
            'click_type' => $data['click_type'],
            'user_ip' => $data['ip'],
            'user_agent' => $data['user_agent'],
            'clicked_at' => now(),
            'cost_per_click' => 0, // Sẽ được tính sau
            'is_charged' => false
        ]);

        // Cache để tránh click lại
        if ($data['user_id']) {
            $cacheKey = "user_ad_click_{$data['user_id']}_{$data['campaign_id']}_{$data['shop_id']}";
            Cache::put($cacheKey, true, 86400); // 24 giờ
        }

        return $click;
    }
}
