<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\CouponUser;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status', 'available'); // available|used|expired|all

        $query = CouponUser::with(['coupon.shop'])
            ->where('user_id', $user->id)
            ->when($status !== 'all', function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->orderByDesc('id');

        $saved = $query->paginate(12);

        return view('user.account.coupons.index', [
            'saved' => $saved,
            'status' => $status,
            'user' => $user,
        ]);
    }
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
        if ($coupon->status == 'inactive' ) {
            return response()->json(['error' => 'Mã giảm giá đã bị vô hiệu hóa.']);
        }else if($coupon->status == 'expired'){
            return response()->json(['error' => 'Mã giảm giá đã hết hạn.']);
        }else if($coupon->status == 'deleted'){
            return response()->json(['error' => 'Mã giảm giá đã bị xóa.']);
        }
        

        // Kiểm tra thời gian hiệu lực
        if ($coupon->start_date > now() || $coupon->end_date < now()) {
            return response()->json(['error' => 'Mã giảm giá đã hết hạn.']);
        }

        // Kiểm tra hạn mức rank
        if ($coupon->rank_limit && $coupon->rank_limit !== 'all') {
            $customer = Customer::where('userID', $user->id)->first();
                if (!$customer || !$customer->hasRankAtLeast($coupon->rank_limit)) {
                    return response()->json(['error' => 'Bạn không đủ điều kiện sử dụng mã giảm giá này, ít nhất cần đạt cấp độ ' . $coupon->rank_limit], 422);
                }
        }

        // Kiểm tra số lần sử dụng tối đa toàn hệ thốn
        if ($coupon->max_uses_total > 0 && $coupon->used_count >= $coupon->max_uses_total) {
            return response()->json(['error' => 'Mã giảm giá đã đạt số lần sử dụng tối đa.']);
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
        // if ($coupon->min_order_amount > 0 && $subtotal < $coupon->min_order_amount) {
        //     return response()->json(['error' => 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng mã giảm giá.']);
        // }

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
