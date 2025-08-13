<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\UserCouponUsed;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CouponService
{
    /**
     * Kiểm tra và áp dụng coupon
     */
    public function applyCoupon($couponCode, $userId, $orderAmount = 0)
    {
        try {
            DB::beginTransaction();

            $coupon = Coupon::where('code', $couponCode)->first();
            
            if (!$coupon) {
                return ['success' => false, 'message' => 'Mã giảm giá không tồn tại.'];
            }

            // Kiểm tra tính khả dụng
            if (!$coupon->isActive()) {
                return ['success' => false, 'message' => 'Mã giảm giá không khả dụng.'];
            }

            // Kiểm tra user có thể sử dụng không
            if (!$coupon->canBeUsedByUser($userId)) {
                return ['success' => false, 'message' => 'Bạn không thể sử dụng mã giảm giá này.'];
            }

            // Kiểm tra giá trị đơn hàng tối thiểu
            if ($coupon->min_order_amount && $orderAmount < $coupon->min_order_amount) {
                return [
                    'success' => false, 
                    'message' => "Đơn hàng phải có giá trị tối thiểu " . number_format($coupon->min_order_amount) . " VNĐ."
                ];
            }

            // Sử dụng coupon
            $coupon->use();

            // Cập nhật hoặc tạo record trong user_coupon_used
            $this->updateUserCouponUsage($userId, $coupon->id);

            DB::commit();

            return [
                'success' => true,
                'coupon' => $coupon,
                'discount_amount' => $coupon->calculateDiscount($orderAmount),
                'message' => 'Áp dụng mã giảm giá thành công.'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error applying coupon: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Có lỗi xảy ra khi áp dụng mã giảm giá.'];
        }
    }

    /**
     * Hoàn trả coupon (khi đơn hàng bị hủy)
     */
    public function refundCoupon($couponId, $userId)
    {
        try {
            DB::beginTransaction();

            $coupon = Coupon::findOrFail($couponId);
            
            // Hoàn trả coupon
            $coupon->refund();

            // Cập nhật số lần sử dụng của user
            $userCoupon = UserCouponUsed::where('user_id', $userId)
                ->where('coupon_id', $couponId)
                ->first();

            if ($userCoupon && $userCoupon->used_count > 0) {
                $userCoupon->decrement('used_count');
            }

            DB::commit();

            return ['success' => true, 'message' => 'Hoàn trả coupon thành công.'];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error refunding coupon: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Có lỗi xảy ra khi hoàn trả coupon.'];
        }
    }

    /**
     * Cập nhật số lần sử dụng coupon của user
     */
    private function updateUserCouponUsage($userId, $couponId)
    {
        $userCoupon = UserCouponUsed::where('user_id', $userId)
            ->where('coupon_id', $couponId)
            ->first();

        if ($userCoupon) {
            $userCoupon->increment('used_count');
        } else {
            UserCouponUsed::create([
                'user_id' => $userId,
                'coupon_id' => $couponId,
                'used_count' => 1,
            ]);
        }
    }

    /**
     * Lấy danh sách coupon khả dụng cho user
     */
    public function getAvailableCouponsForUser($userId, $userRank = 'bronze', $orderAmount = 0)
    {
        $ranks = ['bronze' => 1, 'silver' => 2, 'gold' => 3, 'diamond' => 4];
        $userRankValue = $ranks[$userRank] ?? 1;

        return Coupon::where('is_public', 1)
            ->where('shop_id', null)
            ->where('status', 'active')
            ->where('is_active', 1)
            ->where('end_date', '>', now())
            ->where(function ($query) use ($userRank, $ranks, $userRankValue) {
                $query->where('rank_limit', 'all')
                      ->orWhere(function ($subQuery) use ($ranks, $userRankValue) {
                          foreach ($ranks as $rank => $value) {
                              if ($value <= $userRankValue) {
                                  $subQuery->orWhere('rank_limit', $rank);
                              }
                          }
                      });
            })
            ->get()
            ->filter(function ($coupon) use ($userId, $orderAmount) {
                return $coupon->isActive() && 
                       $coupon->canBeUsedByUser($userId) &&
                       (!$coupon->min_order_amount || $orderAmount >= $coupon->min_order_amount);
            });
    }

    /**
     * Lấy thống kê coupon
     */
    public function getCouponStatistics($shopId = null)
    {
        $query = Coupon::query();
        
        if ($shopId) {
            $query->where('shop_id', $shopId);
        }

        $coupons = $query->get();

        return [
            'total_coupons' => $coupons->count(),
            'total_uses' => $coupons->sum('used_count'),
            'active_coupons' => $coupons->where('is_active', 1)->where('status', 'active')->count(),
            'expired_coupons' => $coupons->where('end_date', '<', now())->count(),
            'out_of_stock_coupons' => $coupons->filter(function($coupon) {
                return !$coupon->hasAvailableQuantity();
            })->count(),
            'total_discount_value' => $coupons->sum('discount_value'),
            'most_used_coupon' => $coupons->sortByDesc('used_count')->first(),
            'recent_coupons' => $coupons->sortByDesc('created_at')->take(5),
        ];
    }
}

