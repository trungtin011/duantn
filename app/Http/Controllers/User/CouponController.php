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
        $status = $request->get('status', 'available');

        $publicCoupons = Coupon::with(['shop'])
            ->where('is_public', true)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where(function ($q) {
                $q->where('max_uses_total', null)
                  ->orWhereRaw('used_count < max_uses_total');
            })
            ->get();

        $savedCoupons = CouponUser::with(['coupon.shop'])
            ->where('user_id', $user->id)
            ->when($status !== 'all', function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->get();

        $allCoupons = collect();
        
        foreach ($publicCoupons as $coupon) {
            $allCoupons->push([
                'id' => $coupon->id,
                'coupon' => $coupon,
                'is_public' => true,
                'user_status' => null,
                'saved_at' => null,
                'used_at' => null,
                'order_id' => null,
                'discount_amount' => null
            ]);
        }
        
        // Thêm các coupon đã lưu
        foreach ($savedCoupons as $savedCoupon) {
            $allCoupons->push([
                'id' => $savedCoupon->id,
                'coupon' => $savedCoupon->coupon,
                'is_public' => false,
                'user_status' => $savedCoupon->status,
                'saved_at' => $savedCoupon->created_at,
                'used_at' => $savedCoupon->used_at,
                'order_id' => $savedCoupon->order_id,
                'discount_amount' => $savedCoupon->discount_amount
            ]);
        }

        // Lọc theo status nếu cần
        if ($status !== 'all') {
            $allCoupons = $allCoupons->filter(function ($item) use ($status) {
                if ($item['is_public']) {
                    // Với coupon public, chỉ hiển thị nếu status là 'available'
                    return $status === 'available';
                } else {
                    // Với coupon đã lưu, kiểm tra user_status
                    return $item['user_status'] === $status;
                }
            });
        }

        // Sắp xếp và phân trang
        $allCoupons = $allCoupons->sortByDesc('id')->values();
        $perPage = 12;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedCoupons = $allCoupons->slice($offset, $perPage);
        
        $saved = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedCoupons,
            $allCoupons->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($request->ajax()) {
            $html = view('user.account.coupons.partials.list', [
                'saved' => $saved,
                'status' => $status,
            ])->render();

            return response()->json(['html' => $html]);
        }

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

        if ($coupon->status == 'inactive' ) {
            return response()->json(['error' => 'Mã giảm giá đã bị vô hiệu hóa.']);
        }else if($coupon->status == 'expired'){
            return response()->json(['error' => 'Mã giảm giá đã hết hạn.']);
        }else if($coupon->status == 'deleted'){
            return response()->json(['error' => 'Mã giảm giá đã bị xóa.']);
        }
        

        if ($coupon->start_date > now() || $coupon->end_date < now()) {
            return response()->json(['error' => 'Mã giảm giá đã hết hạn.']);
        }

        if ($coupon->rank_limit && $coupon->rank_limit !== 'all') {
            $customer = Customer::where('userID', $user->id)->first();
                if (!$customer || !$customer->hasRankAtLeast($coupon->rank_limit)) {
                    return response()->json(['error' => 'Bạn không đủ điều kiện sử dụng mã giảm giá này, ít nhất cần đạt cấp độ ' . $coupon->rank_limit], 422);
                }
        }

        if ($coupon->max_uses_total > 0 && $coupon->used_count >= $coupon->max_uses_total) {
            return response()->json(['error' => 'Mã giảm giá đã đạt số lần sử dụng tối đa.']);
        }

        if ($coupon->min_order_amount > 0 && $subtotal < $coupon->min_order_amount) {
            return response()->json(['error' => 'Đơn hàng chưa đạt giá trị tối thiểu để áp dụng mã giảm giá.']);
        }

        $couponUser = CouponUser::where('coupon_id', $coupon->id)
            ->where('user_id', $user->id)
            ->first();

        $userUsedCount = $couponUser ? ($couponUser->used_count ?? 0) : 0;
        if ($coupon->max_uses_per_user > 0 && $userUsedCount >= $coupon->max_uses_per_user) {
            return response()->json(['error' => 'Bạn đã sử dụng mã giảm giá này tối đa số lần cho phép.']);
        }

        $discountAmount = 0;
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
