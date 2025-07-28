<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $ranks = ['gold', 'silver', 'bronze', 'diamond', 'all'];
        $typeCoupons = ['shipping', 'order', 'first_order', 'referral', 'other'];
        $shops = [1, 2, null];

        $coupons = [];

        for ($i = 0; $i < 10; $i++) {
            $rank = $ranks[$i % count($ranks)];
            $shop_id = $shops[$i % count($shops)];
            $is_public = $i % 2 === 0 ? 1 : 0;
            $discount_type = $i % 2 === 0 ? 'percentage' : 'fixed';
            $discount_value = $discount_type === 'percentage' ? rand(5, 30) : rand(20000, 100000);
            $type_coupon = $typeCoupons[$i % count($typeCoupons)];
            $coupons[] = [
                'code' => strtoupper(Str::random(8)),
                'name' => "Khuyến mãi #{$i}",
                'description' => "Giảm giá dành cho hạng {$rank}" . ($is_public ? ' (public)' : ' (private)'),
                'discount_value' => $discount_value,
                'discount_type' => $discount_type,
                'max_discount_amount' => $discount_type === 'percentage' ? rand(30000, 100000) : null,
                'min_order_amount' => rand(100000, 500000),
                'quantity' => rand(10, 100),
                'max_uses_per_user' => rand(1, 5),
                'max_uses_total' => rand(20, 200),
                'used_count' => 0,
                'start_date' => $now->toDateString(),
                'end_date' => $now->copy()->addDays(rand(10, 30))->toDateString(),
                'rank_limit' => $rank,
                'is_active' => 1,
                'is_public' => $is_public,
                'created_by' => rand(1, 5),
                'shop_id' => $shop_id,
                'type_coupon' => $type_coupon,
                'status' => 'active',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('coupon')->insert($coupons);
    }
}
