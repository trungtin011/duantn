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
use Carbon\Carbon;

class CouponControllerSeller extends Controller
{
    public function index(Request $request)
    {
        // Giữ nguyên như mã gốc
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để quản lý mã giảm giá.');
        }

        $query = Coupon::where('shop_id', $shop->id);

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        if ($request->has('type') && $request->type !== '') {
            $query->where('discount_type', $request->type);
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            return view('seller.coupon.index', compact('coupons'));
        }

        return view('seller.coupon.index', compact('coupons'));
    }

    public function create()
    {
        // Giữ nguyên như mã gốc
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để tạo mã giảm giá.');
        }

        return view('seller.coupon.create');
    }

    public function store(Request $request)
    {
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để tạo mã giảm giá.');
        }

        // Quy tắc validate
        $rules = [
            'code' => 'required|string|max:50|unique:coupons,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'discount_value' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'max_uses_total' => 'nullable|integer|min:1',
            'rank_limit' => 'required|in:all,gold,silver,bronze,diamond',
            'start_day' => 'required|integer|between:1,31',
            'start_month' => 'required|integer|between:1,12',
            'start_year' => 'required|integer|min:' . now()->year,
            'end_day' => 'required|integer|between:1,31',
            'end_month' => 'required|integer|between:1,12',
            'end_year' => 'required|integer|min:' . now()->year,
            'is_active' => 'boolean',
            'is_public' => 'boolean',
        ];

        // Thông báo lỗi tùy chỉnh
        $messages = [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.string' => 'Mã giảm giá phải là chuỗi ký tự.',
            'code.max' => 'Mã giảm giá không được vượt quá 50 ký tự.',
            'code.unique' => 'Mã giảm giá đã tồn tại trong hệ thống.',
            'name.required' => 'Vui lòng nhập tên mã giảm giá.',
            'name.string' => 'Tên mã giảm giá phải là chuỗi ký tự.',
            'name.max' => 'Tên mã giảm giá không được vượt quá 255 ký tự.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá 500 ký tự.',
            'image.image' => 'File phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm giá.',
            'discount_value.numeric' => 'Giá trị giảm giá phải là số.',
            'discount_value.min' => 'Giá trị giảm giá phải lớn hơn 0.',
            'discount_type.required' => 'Vui lòng chọn loại giảm giá.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            'max_discount_amount.numeric' => 'Số tiền giảm tối đa phải là số.',
            'max_discount_amount.min' => 'Số tiền giảm tối đa phải lớn hơn 0.',
            'min_order_amount.numeric' => 'Đơn hàng tối thiểu phải là số.',
            'min_order_amount.min' => 'Đơn hàng tối thiểu phải lớn hơn 0.',
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
            'max_uses_per_user.integer' => 'Số lần dùng mỗi người phải là số nguyên.',
            'max_uses_per_user.min' => 'Số lần dùng mỗi người phải lớn hơn 0.',
            'max_uses_total.integer' => 'Tổng số lần sử dụng phải là số nguyên.',
            'max_uses_total.min' => 'Tổng số lần sử dụng phải lớn hơn 0.',
            'rank_limit.required' => 'Vui lòng chọn hạn chế theo hạng.',
            'rank_limit.in' => 'Hạn chế theo hạng không hợp lệ.',
            'start_day.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_day.integer' => 'Ngày bắt đầu phải là số nguyên.',
            'start_day.between' => 'Ngày bắt đầu phải từ 1 đến 31.',
            'start_month.required' => 'Vui lòng chọn tháng bắt đầu.',
            'start_month.integer' => 'Tháng bắt đầu phải là số nguyên.',
            'start_month.between' => 'Tháng bắt đầu phải từ 1 đến 12.',
            'start_year.required' => 'Vui lòng chọn năm bắt đầu.',
            'start_year.integer' => 'Năm bắt đầu phải là số nguyên.',
            'start_year.min' => 'Năm bắt đầu phải từ ' . now()->year . ' trở đi.',
            'end_day.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_day.integer' => 'Ngày kết thúc phải là số nguyên.',
            'end_day.between' => 'Ngày kết thúc phải từ 1 đến 31.',
            'end_month.required' => 'Vui lòng chọn tháng kết thúc.',
            'end_month.integer' => 'Tháng kết thúc phải là số nguyên.',
            'end_month.between' => 'Tháng kết thúc phải từ 1 đến 12.',
            'end_year.required' => 'Vui lòng chọn năm kết thúc.',
            'end_year.integer' => 'Năm kết thúc phải là số nguyên.',
            'end_year.min' => 'Năm kết thúc phải từ ' . now()->year . ' trở đi.',
        ];

        // Validate dữ liệu
        $validator = Validator::make($request->all(), $rules, $messages);

        // Validate ngày tháng
        if ($request->filled(['start_day', 'start_month', 'start_year', 'end_day', 'end_month', 'end_year'])) {
            try {
                $startDate = Carbon::create(
                    $request->start_year,
                    $request->start_month,
                    $request->start_day
                );
                $endDate = Carbon::create(
                    $request->end_year,
                    $request->end_month,
                    $request->end_day
                );

                if ($startDate->lt(Carbon::today())) {
                    $validator->errors()->add('start_day', 'Ngày bắt đầu phải từ hôm nay trở đi.');
                }

                if ($endDate->lte($startDate)) {
                    $validator->errors()->add('end_day', 'Ngày kết thúc phải sau ngày bắt đầu ít nhất 1 ngày.');
                }

                Log::info('Validated dates', [
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                ]);
            } catch (\Exception $e) {
                $validator->errors()->add('start_day', 'Ngày bắt đầu không hợp lệ.');
                $validator->errors()->add('end_day', 'Ngày kết thúc không hợp lệ.');
            }
        } else {
            $validator->errors()->add('start_day', 'Vui lòng nhập đầy đủ ngày, tháng, năm bắt đầu.');
            $validator->errors()->add('end_day', 'Vui lòng nhập đầy đủ ngày, tháng, năm kết thúc.');
        }

        // Nếu validate thất bại, trả về lỗi
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Xác định created_by_role
        $createdByRole = Auth::user() && Auth::user()->seller ? 'shop' : 'admin';

        try {
            // Xử lý upload ảnh
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('coupons', 'public');
            }

            $couponData = [
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath, // Giữ null nếu không upload
                'discount_value' => $request->discount_value,
                'discount_type' => $request->discount_type,
                'max_discount_amount' => $request->max_discount_amount ?? null, // Cho phép null
                'min_order_amount' => $request->min_order_amount ?? null, // Cho phép null
                'quantity' => $request->quantity,
                'max_uses_per_user' => $request->max_uses_per_user ?? null, // Cho phép null
                'max_uses_total' => $request->max_uses_total ?? null, // Cho phép null
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
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
            Log::error('Error creating coupon: ' . $e->getMessage(), [
                'shop_id' => $shop->id,
                'coupon_data' => $couponData
            ]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo mã giảm giá.')->withInput();
        }
    }

    public function edit($id)
    {
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để quản lý mã giảm giá.');
        }

        $coupon = Coupon::where('shop_id', $shop->id)->findOrFail($id);

        // Thêm các giá trị ngày, tháng, năm
        $coupon->start_day = $coupon->start_date->day;
        $coupon->start_month = $coupon->start_date->month;
        $coupon->start_year = $coupon->start_date->year;
        $coupon->end_day = $coupon->end_date->day;
        $coupon->end_month = $coupon->end_date->month;
        $coupon->end_year = $coupon->end_date->year;

        return view('seller.coupon.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để quản lý mã giảm giá.');
        }

        $coupon = Coupon::where('shop_id', $shop->id)->findOrFail($id);

        // Quy tắc validate
        $rules = [
            'code' => 'required|string|max:50|unique:coupons,code,' . $id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'discount_value' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'max_uses_total' => 'nullable|integer|min:1',
            'rank_limit' => 'required|in:all,gold,silver,bronze,diamond',
            'start_day' => 'required|integer|between:1,31',
            'start_month' => 'required|integer|between:1,12',
            'start_year' => 'required|integer|min:' . now()->year,
            'end_day' => 'required|integer|between:1,31',
            'end_month' => 'required|integer|between:1,12',
            'end_year' => 'required|integer|min:' . now()->year,
            'is_active' => 'boolean',
            'is_public' => 'boolean',
        ];

        // Thông báo lỗi tùy chỉnh (giữ nguyên như ở store)
        $messages = [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.string' => 'Mã giảm giá phải là chuỗi ký tự.',
            'code.max' => 'Mã giảm giá không được vượt quá 50 ký tự.',
            'code.unique' => 'Mã giảm giá đã tồn tại trong hệ thống.',
            'name.required' => 'Vui lòng nhập tên mã giảm giá.',
            'name.string' => 'Tên mã giảm giá phải là chuỗi ký tự.',
            'name.max' => 'Tên mã giảm giá không được vượt quá 255 ký tự.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá 500 ký tự.',
            'image.image' => 'File phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm giá.',
            'discount_value.numeric' => 'Giá trị giảm giá phải là số.',
            'discount_value.min' => 'Giá trị giảm giá phải lớn hơn 0.',
            'discount_type.required' => 'Vui lòng chọn loại giảm giá.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            'max_discount_amount.numeric' => 'Số tiền giảm tối đa phải là số.',
            'max_discount_amount.min' => 'Số tiền giảm tối đa phải lớn hơn 0.',
            'min_order_amount.numeric' => 'Đơn hàng tối thiểu phải là số.',
            'min_order_amount.min' => 'Đơn hàng tối thiểu phải lớn hơn 0.',
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
            'max_uses_per_user.integer' => 'Số lần dùng mỗi người phải là số nguyên.',
            'max_uses_per_user.min' => 'Số lần dùng mỗi người phải lớn hơn 0.',
            'max_uses_total.integer' => 'Tổng số lần sử dụng phải là số nguyên.',
            'max_uses_total.min' => 'Tổng số lần sử dụng phải lớn hơn 0.',
            'rank_limit.required' => 'Vui lòng chọn hạn chế theo hạng.',
            'rank_limit.in' => 'Hạn chế theo hạng không hợp lệ.',
            'start_day.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_day.integer' => 'Ngày bắt đầu phải là số nguyên.',
            'start_day.between' => 'Ngày bắt đầu phải từ 1 đến 31.',
            'start_month.required' => 'Vui lòng chọn tháng bắt đầu.',
            'start_month.integer' => 'Tháng bắt đầu phải là số nguyên.',
            'start_month.between' => 'Tháng bắt đầu phải từ 1 đến 12.',
            'start_year.required' => 'Vui lòng chọn năm bắt đầu.',
            'start_year.integer' => 'Năm bắt đầu phải là số nguyên.',
            'start_year.min' => 'Năm bắt đầu phải từ ' . now()->year . ' trở đi.',
            'end_day.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_day.integer' => 'Ngày kết thúc phải là số nguyên.',
            'end_day.between' => 'Ngày kết thúc phải từ 1 đến 31.',
            'end_month.required' => 'Vui lòng chọn tháng kết thúc.',
            'end_month.integer' => 'Tháng kết thúc phải là số nguyên.',
            'end_month.between' => 'Tháng kết thúc phải từ 1 đến 12.',
            'end_year.required' => 'Vui lòng chọn năm kết thúc.',
            'end_year.integer' => 'Năm kết thúc phải là số nguyên.',
            'end_year.min' => 'Năm kết thúc phải từ ' . now()->year . ' trở đi.',
        ];

        // Validate dữ liệu
        $validator = Validator::make($request->all(), $rules, $messages);

        // Validate ngày tháng
        if ($request->filled(['start_day', 'start_month', 'start_year', 'end_day', 'end_month', 'end_year'])) {
            try {
                $startDate = Carbon::create(
                    $request->start_year,
                    $request->start_month,
                    $request->start_day
                );
                $endDate = Carbon::create(
                    $request->end_year,
                    $request->end_month,
                    $request->end_day
                );

                if ($startDate->lt(Carbon::today())) {
                    $validator->errors()->add('start_day', 'Ngày bắt đầu phải từ hôm nay trở đi.');
                }

                if ($endDate->lte($startDate)) {
                    $validator->errors()->add('end_day', 'Ngày kết thúc phải sau ngày bắt đầu ít nhất 1 ngày.');
                }

                Log::info('Validated dates', [
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                ]);
            } catch (\Exception $e) {
                $validator->errors()->add('start_day', 'Ngày bắt đầu không hợp lệ.');
                $validator->errors()->add('end_day', 'Ngày kết thúc không hợp lệ.');
            }
        } else {
            $validator->errors()->add('start_day', 'Vui lòng nhập đầy đủ ngày, tháng, năm bắt đầu.');
            $validator->errors()->add('end_day', 'Vui lòng nhập đầy đủ ngày, tháng, năm kết thúc.');
        }

        // Nếu validate thất bại, trả về lỗi
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Xử lý upload ảnh
            $imagePath = $coupon->image;
            if ($request->hasFile('image')) {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('coupons', 'public');
            } elseif ($request->has('remove_image')) { // Thêm logic để xóa ảnh nếu người dùng chọn
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = null;
            }

            $coupon->update([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath, // Giữ null nếu không upload và không xóa
                'discount_value' => $request->discount_value,
                'discount_type' => $request->discount_type,
                'max_discount_amount' => $request->max_discount_amount ?? null,
                'min_order_amount' => $request->min_order_amount ?? null,
                'quantity' => $request->quantity,
                'max_uses_per_user' => $request->max_uses_per_user ?? null,
                'max_uses_total' => $request->max_uses_total ?? null,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'rank_limit' => $request->rank_limit,
                'is_active' => $request->has('is_active'),
                'is_public' => $request->has('is_public'),
            ]);

            return redirect()->route('seller.coupon.index')->with('success', 'Mã giảm giá đã được cập nhật thành công.');
        } catch (\Exception $e) {
            Log::error('Error updating coupon: ' . $e->getMessage(), [
                'shop_id' => $shop->id,
                'coupon_id' => $id
            ]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật mã giảm giá.')->withInput();
        }
    }

    public function destroy($id)
    {
        // Giữ nguyên như mã gốc
        $shop = $this->getSellerShop();
        if (!$shop) {
            return redirect()->back()->with('error', 'Bạn chưa có shop để quản lý mã giảm giá.');
        }

        $coupon = Coupon::where('shop_id', $shop->id)->findOrFail($id);

        try {
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

    public function destroyMultiple(Request $request)
    {
        // Giữ nguyên như mã gốc
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

    private function getSellerShop()
    {
        // Giữ nguyên như mã gốc
        $user = Auth::user();

        if (!$user->seller) {
            return null;
        }

        $shopId = session('current_shop_id');
        if ($shopId) {
            $shop = Shop::where('id', $shopId)
                ->where('ownerID', $user->id)
                ->first();
            if ($shop) {
                return $shop;
            }
        }

        $shop = Shop::where('ownerID', $user->id)->first();
        if ($shop) {
            session(['current_shop_id' => $shop->id]);
            return $shop;
        }

        return null;
    }
}
