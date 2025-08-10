<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Status filter
        if ($request->has('status') && $request->status) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', 1);
                    break;
                case 'inactive':
                    $query->where('is_active', 0);
                    break;
                case 'expired':
                    $query->where('end_date', '<', now());
                    break;
                case 'expiring':
                    $query->where('end_date', '>', now())
                          ->where('end_date', '<=', now()->addDays(7));
                    break;
            }
        }
        
        // Type filter
        if ($request->has('type') && $request->type) {
            $query->where('discount_type', $request->type);
        }
        
        $coupons = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Calculate statistics
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::where('is_active', 1)->count();
        $expiredCoupons = Coupon::where('end_date', '<', now())->count();
        $expiringSoon = Coupon::where('end_date', '>', now())
                              ->where('end_date', '<=', now()->addDays(7))
                              ->count();
        
        return view('admin.coupon.index', compact('coupons', 'totalCoupons', 'activeCoupons', 'expiredCoupons', 'expiringSoon'));
    }

    public function create()
    {
        return view('admin.coupon.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:coupon,code',
            ],
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
            'description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000'
            ],
            'discount_value' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'discount_type' => [
                'required',
                Rule::in(['percentage', 'fixed']),
            ],
            'max_discount_amount' => [
                'nullable',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->discount_type === 'percentage' && $value && $value <= 0) {
                        $fail('Số tiền giảm giá tối đa phải lớn hơn 0.');
                    }
                },
            ],
            'min_order_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:999999',
            ],
            'max_uses_per_user' => [
                'nullable',
                'integer',
                'min:1',
                'max:999999',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && $request->quantity && $value > $request->quantity) {
                        $fail('Số lần sử dụng tối đa mỗi người không thể lớn hơn tổng số lượng.');
                    }
                },
            ],
            'max_uses_total' => [
                'nullable',
                'integer',
                'min:1',
                'max:999999',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && $request->quantity && $value > $request->quantity) {
                        $fail('Tổng số lần sử dụng không thể lớn hơn tổng số lượng.');
                    }
                },
            ],
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                'after:today',
            ],
            'rank_limit' => [
                'required',
                Rule::in(['gold', 'silver', 'bronze', 'diamond', 'all']),
            ],
            'is_active' => [
                'boolean',
            ],
            'is_public' => [
                'boolean',
            ],
        ], [
            'code.required' => 'Mã giảm giá là bắt buộc.',
            'code.string' => 'Mã giảm giá phải là chuỗi ký tự.',
            'code.max' => 'Mã giảm giá không được vượt quá 50 ký tự.',
            'code.unique' => 'Mã giảm giá này đã tồn tại.',
            
            'name.required' => 'Tên mã giảm giá là bắt buộc.',
            'name.string' => 'Tên mã giảm giá phải là chuỗi ký tự.',
            'name.min' => 'Tên mã giảm giá phải có ít nhất 2 ký tự.',
            'name.max' => 'Tên mã giảm giá không được vượt quá 100 ký tự.',
            
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá 500 ký tự.',
            
            'image.image' => 'File phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'image.dimensions' => 'Kích thước hình ảnh phải từ 100x100 đến 2000x2000 pixel.',
            
            'discount_value.required' => 'Giá trị giảm giá là bắt buộc.',
            'discount_value.numeric' => 'Giá trị giảm giá phải là số.',
            'discount_value.min' => 'Giá trị giảm giá phải lớn hơn 0.',
            
            'discount_type.required' => 'Loại giảm giá là bắt buộc.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            
            'max_discount_amount.numeric' => 'Số tiền giảm giá tối đa phải là số.',
            'max_discount_amount.min' => 'Số tiền giảm giá tối đa phải lớn hơn 0.',
            
            'min_order_amount.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'min_order_amount.min' => 'Giá trị đơn hàng tối thiểu không được âm.',
            
            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
            'quantity.max' => 'Số lượng không được vượt quá 999999.',
            
            'max_uses_per_user.integer' => 'Số lần sử dụng tối đa mỗi người phải là số nguyên.',
            'max_uses_per_user.min' => 'Số lần sử dụng tối đa mỗi người phải lớn hơn 0.',
            'max_uses_per_user.max' => 'Số lần sử dụng tối đa mỗi người không được vượt quá 999999.',
            
            'max_uses_total.integer' => 'Tổng số lần sử dụng phải là số nguyên.',
            'max_uses_total.min' => 'Tổng số lần sử dụng phải lớn hơn 0.',
            'max_uses_total.max' => 'Tổng số lần sử dụng không được vượt quá 999999.',
            
            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',
            
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'end_date.after_today' => 'Ngày kết thúc phải sau hôm nay.',
            
            'rank_limit.required' => 'Giới hạn hạng là bắt buộc.',
            'rank_limit.in' => 'Giới hạn hạng không hợp lệ.',
            
            'is_active.boolean' => 'Trạng thái kích hoạt không hợp lệ.',
            'is_public.boolean' => 'Trạng thái công khai không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại thông tin nhập vào.');
        }

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('coupon', 'public');
            }

            $coupon = Coupon::create([
                'code' => strtoupper(trim($request->code)),
                'name' => trim($request->name),
                'description' => trim($request->description),
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
                'is_active' => $request->boolean('is_active'),
                'is_public' => $request->boolean('is_public'),
                'created_by' => Auth::id(),
                'used_count' => 0,
            ]);

            return redirect()->route('admin.coupon.index')
                ->with('success', 'Mã giảm giá đã được tạo thành công!');

        } catch (\Exception $e) {
            // Delete uploaded image if creation fails
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo mã giảm giá. Vui lòng thử lại.');
        }
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupon.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('coupon', 'code')->ignore($id),
            ],
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
            'description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000'
            ],
            'discount_value' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'discount_type' => [
                'required',
                Rule::in(['percentage', 'fixed']),
            ],
            'max_discount_amount' => [
                'nullable',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->discount_type === 'percentage' && $value && $value <= 0) {
                        $fail('Số tiền giảm giá tối đa phải lớn hơn 0.');
                    }
                },
            ],
            'min_order_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:999999',
            ],
            'max_uses_per_user' => [
                'nullable',
                'integer',
                'min:1',
                'max:999999',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && $request->quantity && $value > $request->quantity) {
                        $fail('Số lần sử dụng tối đa mỗi người không thể lớn hơn tổng số lượng.');
                    }
                },
            ],
            'max_uses_total' => [
                'nullable',
                'integer',
                'min:1',
                'max:999999',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && $request->quantity && $value > $request->quantity) {
                        $fail('Tổng số lần sử dụng không thể lớn hơn tổng số lượng.');
                    }
                },
            ],
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                'after:today',
            ],
            'rank_limit' => [
                'required',
                Rule::in(['gold', 'silver', 'bronze', 'diamond', 'all']),
            ],
            'is_active' => [
                'boolean',
            ],
            'is_public' => [
                'boolean',
            ],
        ], [
            'code.required' => 'Mã giảm giá là bắt buộc.',
            'code.unique' => 'Mã giảm giá này đã tồn tại.',
            'name.required' => 'Tên mã giảm giá là bắt buộc.',
            'name.min' => 'Tên mã giảm giá phải có ít nhất 2 ký tự.',
            'discount_value.required' => 'Giá trị giảm giá là bắt buộc.',
            'discount_value.min' => 'Giá trị giảm giá phải lớn hơn 0.',
            'discount_type.required' => 'Loại giảm giá là bắt buộc.',
            'discount_type.in' => 'Loại giảm giá không hợp lệ.',
            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.after_or_equal' => 'Ngày bắt đầu phải từ hôm nay trở đi.',
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'end_date.after:today' => 'Ngày kết thúc phải sau hôm nay.',
            'rank_limit.required' => 'Giới hạn hạng là bắt buộc.',
            'rank_limit.in' => 'Giới hạn hạng không hợp lệ.',
            'image.image' => 'File phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'image.dimensions' => 'Kích thước hình ảnh không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại thông tin nhập vào.');
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
                $imagePath = $request->file('image')->store('coupon', 'public');
            }

            $coupon->update([
                'code' => strtoupper(trim($request->code)),
                'name' => trim($request->name),
                'description' => trim($request->description),
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
                'is_active' => $request->boolean('is_active'),
                'is_public' => $request->boolean('is_public'),
            ]);

            return redirect()->route('admin.coupon.index')
                ->with('success', 'Mã giảm giá đã được cập nhật thành công!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật mã giảm giá. Vui lòng thử lại.');
        }
    }

    public function destroy($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);

            // Check if coupon is being used
            if ($coupon->used_count > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa mã giảm giá đã được sử dụng.'
                ], 400);
            }

            // Delete associated image if it exists
            if ($coupon->image && Storage::disk('public')->exists($coupon->image)) {
                Storage::disk('public')->delete($coupon->image);
            }

            $coupon->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Mã giảm giá đã được xóa thành công!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa mã giảm giá.'
            ], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            
            // Check if coupon is expired
            if ($coupon->end_date && $coupon->end_date->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể kích hoạt mã giảm giá đã hết hạn.'
                ], 400);
            }
            
            $coupon->update([
                'is_active' => !$coupon->is_active
            ]);
            
            $status = $coupon->is_active ? 'kích hoạt' : 'tạm dừng';
            
            return response()->json([
                'success' => true,
                'message' => "Mã giảm giá đã được {$status} thành công!",
                'is_active' => $coupon->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thay đổi trạng thái mã giảm giá.'
            ], 500);
        }
    }

    public function show($id)
    {
        $coupon = Coupon::with(['createdBy'])->findOrFail($id);
        return view('admin.coupon.show', compact('coupon'));
    }
}