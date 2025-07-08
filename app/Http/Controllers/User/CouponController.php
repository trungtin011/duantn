<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\CouponUser;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function applyAppDiscount(Request $request)
    {
        $user = Auth::user();
        $discountCode = $request->input('discount_code');
        $subtotal = $request->input('subtotal', 0);

        $coupon = Coupon::where('code', $discountCode)->first();

        if (!$coupon) {
            return response()->json(['error' => 'Mã giảm giá không hợp lệ.' . $discountCode]);
        }

        // Kiểm tra trạng thái hoạt động
        if (!$coupon->is_active) {
            return response()->json(['error' => 'Mã giảm giá đã bị vô hiệu hóa.']);
        }

        // Kiểm tra thời gian hiệu lực
        if ($coupon->start_date > now() || $coupon->end_date < now()) {
            return response()->json(['error' => 'Mã giảm giá đã hết hạn.']);
        }

        // Kiểm tra hạn mức rank
        if ($user->rank != $coupon->rank_limit && $coupon->rank_limit != 'all') {
            return response()->json(['error' => 'Mã giảm giá không hợp lệ cho hạng thành viên của bạn.']);
        }

        // Kiểm tra số lần sử dụng tối đa toàn hệ thống
        if ($coupon->max_uses_total > 0 && $coupon->used_count >= $coupon->max_uses_total) {
            return response()->json(['error' => 'Mã giảm giá đã đạt số lần sử dụng tối đa.']);
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($coupon->min_order_amount > 0 && $subtotal < $coupon->min_order_amount) {
            return response()->json(['error' => 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng mã giảm giá.']);
        }

        // Kiểm tra số lần sử dụng tối đa cho mỗi user
        $couponUser = CouponUser::where('coupon_id', $coupon->id)
            ->where('user_id', $user->id)
            ->first();

        $userUsedCount = $couponUser ? ($couponUser->used_count ?? 0) : 0;
        if ($coupon->max_uses_per_user > 0 && $userUsedCount >= $coupon->max_uses_per_user) {
            return response()->json(['error' => 'Bạn đã sử dụng mã giảm giá này tối đa số lần cho phép.']);
        }

        $discountAmount = 0;
        // Tính toán số tiền giảm giá
        if ($coupon->discount_type === 'fixed') {
            $discountAmount = $coupon->discount_value;
        } else {
            $discountAmount = $subtotal * $coupon->discount_value / 100;
        }

        if ($coupon->max_discount_amount > 0 && $discountAmount > $coupon->max_discount_amount) {
            $discountAmount = $coupon->max_discount_amount;
        }

        return response()->json([
            'success' => 'Mã giảm giá hợp lệ.',
            'discount_amount' => $discountAmount
        ]);
    }
}
