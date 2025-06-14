<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
        }
        
        $coupons = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.coupon.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupon.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:coupon,code|min:3|max:100',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'discount_value' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'max_uses_total' => 'nullable|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'rank_limit' => 'required|in:gold,silver,bronze,diamond,all',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Coupon::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
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
            'is_active' => $request->is_active ?? 0,
            'is_public' => $request->is_public ?? 0,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.coupon.index')->with('success', 'Coupon created successfully.');
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
            'code' => 'required|unique:coupon,code,' . $id . '|min:3|max:100',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'discount_value' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'max_uses_total' => 'nullable|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'rank_limit' => 'required|in:gold,silver,bronze,diamond,all',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $coupon->update([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
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
            'is_active' => $request->is_active ?? 0,
            'is_public' => $request->is_public ?? 0,
        ]);

        return redirect()->route('admin.coupon.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        
        return redirect()->route('admin.coupon.index')->with('success', 'Coupon deleted successfully.');
    }
}