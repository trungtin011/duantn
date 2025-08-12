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
                        $fail('Người dùng phải từ 12 tuổi trở lên để đăng ký tài khoản.');
                    }
                }
            }],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.string' => 'Tên đăng nhập phải là chuỗi ký tự.',
            'username.max' => 'Tên đăng nhập không được quá 50 ký tự.',
            'username.unique' => 'Tên đăng nhập này đã được sử dụng. Vui lòng chọn tên khác.',
            'fullname.required' => 'Vui lòng nhập họ và tên.',
            'fullname.string' => 'Họ và tên phải là chuỗi ký tự.',
            'fullname.max' => 'Họ và tên không được quá 100 ký tự.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max' => 'Số điện thoại không được quá 10 chữ số.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập đúng định dạng 10 chữ số.',
            'phone.unique' => 'Số điện thoại này đã được đăng ký. Vui lòng sử dụng số khác.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.max' => 'Địa chỉ email không được quá 100 ký tự.',
            'email.unique' => 'Email này đã được đăng ký. Vui lòng sử dụng email khác.',
            'gender.required' => 'Vui lòng chọn giới tính.',
            'gender.in' => 'Giá trị giới tính không hợp lệ.',
            'birthday.date' => 'Ngày sinh không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.string' => 'Mật khẩu phải là chuỗi ký tự.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp. Vui lòng nhập lại.',
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

            return redirect()->route('login')->with('success', 'Đăng ký tài khoản thành công! Vui lòng đăng nhập để tiếp tục.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra khi tạo tài khoản. Vui lòng thử lại sau.');
        }
    }
}
