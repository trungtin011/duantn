<?php

namespace App\Services;

use App\Models\AdsCampaign;
use App\Models\ShopWallet;
use Illuminate\Support\Facades\Log;

class CampaignBudgetEnforcer
{
    /**
     * Ensure a single campaign's status reflects wallet sufficiency against its bid.
     */
    public static function enforceForCampaign(AdsCampaign $campaign): void
    {
        try {
            // Only consider campaigns that are within their configured time window if provided
            $now = now();
            if (($campaign->start_date && $campaign->start_date > $now) || ($campaign->end_date && $campaign->end_date < $now)) {
                return;
            }

            $wallet = ShopWallet::where('shop_id', $campaign->shop_id)->first();
            $bidAmount = (float) ($campaign->bid_amount ?? 0);
            $balance = (float) ($wallet->balance ?? 0);

            if ($bidAmount <= 0) {
                return;
            }

            // Auto-pause if balance is insufficient
            if ($balance < $bidAmount) {
                if ($campaign->status === 'active') {
                    $campaign->update(['status' => 'pending']);
                    Log::info("Auto-paused campaign {$campaign->id} due to insufficient balance ({$balance} < {$bidAmount})");
                }
                return;
            }

            // Auto-resume if balance is now sufficient
            if ($balance >= $bidAmount && $campaign->status === 'pending') {
                $campaign->update(['status' => 'active']);
                Log::info("Auto-resumed campaign {$campaign->id} as balance is sufficient ({$balance} >= {$bidAmount})");
            }
        } catch (\Throwable $e) {
            Log::warning('Failed enforcing budget for campaign ' . $campaign->id . ': ' . $e->getMessage());
        }
    }

    /**
     * Enforce budget rules for all campaigns of a shop.
     */
    public static function enforceForShop(int $shopId): void
    {
        $campaigns = AdsCampaign::where('shop_id', $shopId)->get();
        foreach ($campaigns as $campaign) {
            self::enforceForCampaign($campaign);
        }
    }

    /**
     * Enforce across all campaigns in the system.
     */
    public static function enforceAll(): void
    {
        AdsCampaign::chunk(200, function ($chunk) {
            foreach ($chunk as $campaign) {
                self::enforceForCampaign($campaign);
            }
        });
    }
}


