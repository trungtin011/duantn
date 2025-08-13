<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CouponControllerSeller extends Controller
{

    public function index(Request $request)
    {
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để quản lý mã giảm giá.');
        }

        $query = Coupon::where('shop_id', $shop->id);
        
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        // Thêm filter theo trạng thái
        if ($request->has('status') && $request->status) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', 1)->where('status', 'active');
                    break;
                case 'inactive':
                    $query->where('is_active', 0);
                    break;
                case 'expired':
                    $query->where('end_date', '<', now());
                    break;
                case 'out_of_stock':
                    $query->whereRaw('(quantity > 0 AND used_count >= quantity) OR (max_uses_total > 0 AND used_count >= max_uses_total)');
                    break;
            }
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Tính toán thống kê
        $stats = [
            'total' => Coupon::where('shop_id', $shop->id)->count(),
            'active' => Coupon::where('shop_id', $shop->id)->where('is_active', 1)->where('status', 'active')->count(),
            'expired' => Coupon::where('shop_id', $shop->id)->where('end_date', '<', now())->count(),
            'out_of_stock' => Coupon::where('shop_id', $shop->id)
                ->whereRaw('(quantity > 0 AND used_count >= quantity) OR (max_uses_total > 0 AND used_count >= max_uses_total)')
                ->count(),
        ];
        
        return view('seller.coupon.index', compact('coupons', 'stats'));
    }

    public function create()
    {
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để tạo mã giảm giá.');
        }

        return view('seller.coupon.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để tạo mã giảm giá.');
        }

        // Xử lý ngày từ các trường riêng biệt
        $startDate = null;
        $endDate = null;
        
        if ($request->filled(['start_day', 'start_month', 'start_year'])) {
            $startDate = sprintf('%04d-%02d-%02d', 
                $request->start_year, 
                $request->start_month, 
                $request->start_day
            );
        }
        
        if ($request->filled(['end_day', 'end_month', 'end_year'])) {
            $endDate = sprintf('%04d-%02d-%02d', 
                $request->end_year, 
                $request->end_month, 
                $request->end_day
            );
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:coupon,code|min:3|max:100',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'discount_value' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'max_uses_total' => 'nullable|integer|min:1',
            'start_day' => 'required|integer|between:1,31',
            'start_month' => 'required|integer|between:1,12',
            'start_year' => 'required|integer|min:' . date('Y'),
            'end_day' => 'required|integer|between:1,31',
            'end_month' => 'required|integer|between:1,12',
            'end_year' => 'required|integer|min:' . date('Y'),
            'rank_limit' => 'required|in:gold,silver,bronze,diamond,all',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
        ]);

        // Thêm validation cho ngày hợp lệ
        if ($startDate && $endDate) {
            $validator->after(function ($validator) use ($startDate, $endDate) {
                if (!checkdate(
                    (int)date('m', strtotime($startDate)), 
                    (int)date('d', strtotime($startDate)), 
                    (int)date('Y', strtotime($startDate))
                )) {
                    $validator->errors()->add('start_date', 'Ngày bắt đầu không hợp lệ.');
                }
                
                if (!checkdate(
                    (int)date('m', strtotime($endDate)), 
                    (int)date('d', strtotime($endDate)), 
                    (int)date('Y', strtotime($endDate))
                )) {
                    $validator->errors()->add('end_date', 'Ngày kết thúc không hợp lệ.');
                }
                
                if (strtotime($startDate) < strtotime(date('Y-m-d'))) {
                    $validator->errors()->add('start_date', 'Ngày bắt đầu phải từ hôm nay trở đi.');
                }
                
                if (strtotime($endDate) <= strtotime($startDate)) {
                    $validator->errors()->add('end_date', 'Ngày kết thúc phải sau ngày bắt đầu.');
                }
            });
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Xác định created_by_role dựa trên user hiện tại
        $createdByRole = 'admin';
        if (Auth::user() && Auth::user()->seller) {
            $createdByRole = 'shop';
        }

        Log::info('Created by role', [
            'created_by_role' => $createdByRole
        ]);

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('coupons', 'public');
            }

            $couponData = [
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath,
                'discount_value' => $request->discount_value,
                'discount_type' => $request->discount_type,
                'max_discount_amount' => $request->max_discount_amount,
                'min_order_amount' => $request->min_order_amount,
                'quantity' => $request->quantity,
                'max_uses_per_user' => $request->max_uses_per_user,
                'max_uses_total' => $request->max_uses_total,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'created_by_role' => $createdByRole,
                'rank_limit' => $request->rank_limit,
                'is_active' => $request->has('is_active'),
                'is_public' => $request->has('is_public'),
                'created_by' => Auth::id(),
                'shop_id' => $shop->id,
                'status' => 'active',
            ];

            Log::info('Creating coupon', $couponData);

            Coupon::create($couponData);

            return redirect()->route('seller.coupon.index')->with('success', 'Mã giảm giá đã được tạo thành công.');
        } catch (\Exception $e) {
            Log::error('Error creating coupon: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo mã giảm giá.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để quản lý mã giảm giá.');
        }

        $coupon = Coupon::where('shop_id', $shop->id)->findOrFail($id);
        
        // Lấy thống kê sử dụng
        $usageStats = [
            'total_used' => $coupon->used_count,
            'remaining_quantity' => $coupon->getRemainingQuantity(),
            'usage_percentage' => $coupon->quantity > 0 ? round(($coupon->used_count / $coupon->quantity) * 100, 2) : 0,
            'is_out_of_stock' => !$coupon->hasAvailableQuantity(),
            'is_expired' => $coupon->isExpired(),
            'is_active' => $coupon->isActive(),
        ];

        // Lấy danh sách user đã sử dụng
        $usersUsed = $coupon->users()
            ->with('user:id,name,email')
            ->orderBy('used_count', 'desc')
            ->paginate(10);

        return view('seller.coupon.show', compact('coupon', 'usageStats', 'usersUsed'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để quản lý mã giảm giá.');
        }

        $coupon = Coupon::where('shop_id', $shop->id)->findOrFail($id);
        
        return view('seller.coupon.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để quản lý mã giảm giá.');
        }

        $coupon = Coupon::where('shop_id', $shop->id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:coupon,code,' . $id . '|min:3|max:100',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'discount_value' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'max_uses_total' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rank_limit' => 'required|in:gold,silver,bronze,diamond,all',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Handle image upload
            $imagePath = $coupon->image;
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                // Store new image
                $imagePath = $request->file('image')->store('coupons', 'public');
            }

            $coupon->update([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath,
                'discount_value' => $request->discount_value,
                'discount_type' => $request->discount_type,
                'max_discount_amount' => $request->max_discount_amount,
                'min_order_amount' => $request->min_order_amount,
                'quantity' => $request->quantity,
                'max_uses_per_user' => $request->max_uses_per_user,
                'max_uses_total' => $request->max_uses_total,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'rank_limit' => $request->rank_limit,
                'is_active' => $request->has('is_active'),
                'is_public' => $request->has('is_public'),
            ]);

            return redirect()->route('seller.coupon.index')->with('success', 'Mã giảm giá đã được cập nhật thành công.');
        } catch (\Exception $e) {
            Log::error('Error updating coupon: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật mã giảm giá.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để quản lý mã giảm giá.');
        }

        $coupon = Coupon::where('shop_id', $shop->id)->findOrFail($id);

        try {
            // Delete associated image if it exists
            if ($coupon->image && Storage::disk('public')->exists($coupon->image)) {
                Storage::disk('public')->delete($coupon->image);
            }

            $coupon->delete();
            
            return redirect()->route('seller.coupon.index')->with('success', 'Mã giảm giá đã được xóa thành công.');
        } catch (\Exception $e) {
            Log::error('Error deleting coupon: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa mã giảm giá.');
        }
    }

    /**
     * Remove multiple resources from storage.
     */
    public function destroyMultiple(Request $request)
    {
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để quản lý mã giảm giá.');
        }

        $ids = json_decode($request->ids, true);
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Không có mã giảm giá nào được chọn.');
        }

        try {
            $coupons = Coupon::where('shop_id', $shop->id)
                            ->whereIn('id', $ids)
                            ->get();

            $deletedCount = 0;
            foreach ($coupons as $coupon) {
                // Delete associated image if it exists
                if ($coupon->image && Storage::disk('public')->exists($coupon->image)) {
                    Storage::disk('public')->delete($coupon->image);
                }
                $coupon->delete();
                $deletedCount++;
            }
            
            return redirect()->route('seller.coupon.index')
                           ->with('success', "Đã xóa thành công {$deletedCount} mã giảm giá.");
        } catch (\Exception $e) {
            Log::error('Error deleting multiple coupons: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa mã giảm giá.');
        }
    }

    /**
     * Hoàn trả coupon (giảm số lần sử dụng)
     */
    public function refundCoupon(Request $request, $id)
    {
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để quản lý mã giảm giá.');
        }

        $coupon = Coupon::where('shop_id', $shop->id)->findOrFail($id);
        
        try {
            $coupon->refund();
            
            return redirect()->back()->with('success', 'Đã hoàn trả coupon thành công.');
        } catch (\Exception $e) {
            Log::error('Error refunding coupon: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi hoàn trả coupon.');
        }
    }

    /**
     * Cập nhật trạng thái coupon
     */
    public function updateStatus(Request $request, $id)
    {
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để quản lý mã giảm giá.');
        }

        $coupon = Coupon::where('shop_id', $shop->id)->findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:active,inactive,expired,deleted'
        ]);

        try {
            $coupon->update(['status' => $request->status]);
            
            return redirect()->back()->with('success', 'Đã cập nhật trạng thái coupon thành công.');
        } catch (\Exception $e) {
            Log::error('Error updating coupon status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật trạng thái coupon.');
        }
    }

    /**
     * Xem thống kê tổng quan về coupon
     */
    public function statistics()
    {
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để xem thống kê.');
        }

        $coupons = Coupon::where('shop_id', $shop->id)->get();
        
        $stats = [
            'total_coupons' => $coupons->count(),
            'total_uses' => $coupons->sum('used_count'),
            'active_coupons' => $coupons->where('is_active', 1)->where('status', 'active')->count(),
            'expired_coupons' => $coupons->where('end_date', '<', now())->count(),
            'out_of_stock_coupons' => $coupons->filter(function($coupon) {
                return !$coupon->hasAvailableQuantity();
            })->count(),
            'total_discount_given' => $coupons->sum('discount_value'),
            'most_used_coupon' => $coupons->sortByDesc('used_count')->first(),
            'recent_coupons' => $coupons->sortByDesc('created_at')->take(5),
        ];

        return view('seller.coupon.statistics', compact('stats'));
    }

    /**
     * Get seller's shop
     */
    private function getSellerShop()
    {
        $user = Auth::user();
        
        // Check if user is a seller
        if (!$user->seller) {
            return null;
        }

        // Get shop from session or database
        $shopId = session('current_shop_id');
        if ($shopId) {
            $shop = Shop::where('id', $shopId)
                       ->where('ownerID', $user->id)
                       ->first();
            if ($shop) {
                return $shop;
            }
        }

        // If no shop in session or invalid, get first shop
        $shop = Shop::where('ownerID', $user->id)->first();
        if ($shop) {
            session(['current_shop_id' => $shop->id]);
            return $shop;
        }

        return null;
    }
}
