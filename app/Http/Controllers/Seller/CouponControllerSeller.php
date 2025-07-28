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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get seller's shop
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để quản lý mã giảm giá.');
        }

        $query = Coupon::where('shop_id', $shop->id);
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }
        
        $coupons = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('seller.coupon.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
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

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('coupons', 'public');
            }

            Coupon::create([
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
                'rank_limit' => $request->rank_limit,
                'is_active' => $request->has('is_active'),
                'is_public' => $request->has('is_public'),
                'created_by' => Auth::id(),
                'shop_id' => $shop->id,
                'status' => 'active',
            ]);

            return redirect()->route('seller.coupon.index')->with('success', 'Mã giảm giá đã được tạo thành công.');
        } catch (\Exception $e) {
            Log::error('Error creating coupon: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo mã giảm giá.')->withInput();
        }
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
