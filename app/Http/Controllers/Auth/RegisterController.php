<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'fullname' => 'required|string|max:100',
            'phone' => ['required', 'string', 'max:10', 'regex:/^[0-9]{10}$/', 'unique:users,phone'],
            'email' => 'required|email|max:100|unique:users,email',
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'birthday' => ['nullable', 'date', function ($attribute, $value, $fail) {
                if ($value) {
                    $age = Carbon::parse($value)->age;
                    if ($age < 12) {
                        $fail('Người dùng phải từ 12 tuổi trở lên.');
                    }
                }
            }],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'username.required' => 'Tên đăng nhập là bắt buộc.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'fullname.required' => 'Họ và tên là bắt buộc.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.max' => 'Số điện thoại không được quá 10 số.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Phải là 10 chữ số.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'email.required' => 'Email là bắt buộc.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'gender.required' => 'Giới tính là bắt buộc.',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'username' => $request->username,
                'fullname' => $request->fullname,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'gender' => $request->gender,
                'birthday' => $request->birthday,
                'status' => 'active',
                'role' => 'customer',
                'is_verified' => false,
            ]);

            Customer::create([
                'userID' => $user->id,
                'ranking' => 'bronze',
                'preferred_payment_method' => null,
                'total_orders' => 0,
                'total_spent' => 0,
                'total_points' => 0,
                'last_order_at' => null,
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Đăng ký thành công! Mời bạn đăng nhập.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra khi tạo tài khoản. Vui lòng thử lại.');
        }
    }
}
