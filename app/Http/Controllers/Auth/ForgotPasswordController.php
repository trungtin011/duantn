<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendResetCode;

class ForgotPasswordController extends Controller
{
    // Hiển thị form nhập email
    public function showEmailForm()
    {
        return view('auth.forgot-password');
    }

    // Gửi mã 6 số và link qua email
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $code = random_int(100000, 999999);
        $user = User::where('email', $request->email)->first();

        $user->reset_code = $code;
        $user->reset_code_expires_at = now()->addMinutes(10);
        $user->save();

        $resetLink = route('password.reset.form');

        // Gửi email với mã và nút
        Mail::to($user->email)->send(new SendResetCode($code, $resetLink));

        session(['reset_email' => $user->email]);

        return redirect()->route('password.code.verify.form')->with('success', 'Mã xác nhận đã được gửi tới email.');
    }

    // Hiển thị form nhập mã
    public function showVerifyForm()
    {
        return view('auth.verify-code');
    }

    // Xác thực mã đã nhập
    public function verifyCode(Request $request)
    {
        $request->merge([
    'code' => implode('', $request->input('code_digits', []))
]);

$request->validate([
    'code' => 'required|digits:6',
]);


        $email = session('reset_email');
        $user = User::where('email', $email)
                    ->where('reset_code', $request->code)
                    ->first();

        if (!$user || $user->reset_code_expires_at < now()) {
            return back()->withErrors(['code' => 'Mã không đúng hoặc đã hết hạn.']);
        }

        return redirect()->route('password.reset.form');
    }

    // Hiển thị form đổi mật khẩu
    public function showResetForm()
    {
        return view('auth.reset-password');
    }

    // Cập nhật mật khẩu mới
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $email = session('reset_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.email.form')->with('error', 'Không tìm thấy tài khoản.');
        }

        $user->password = bcrypt($request->password);
        $user->reset_code = null;
        $user->reset_code_expires_at = null;
        $user->save();

        session()->forget('reset_email');

        return redirect()->route('login')->with('success', 'Đổi mật khẩu thành công. Vui lòng đăng nhập.');
    }
}
