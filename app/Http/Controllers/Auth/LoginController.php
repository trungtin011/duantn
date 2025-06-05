<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Vui lòng nhập email hoặc số điện thoại',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $loginType => $request->login,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors([
            'login' => 'Tài khoản hoặc mật khẩu không đúng.',
        ])->withInput($request->only('login'));
    }

    // ========== ĐĂNG NHẬP / ĐĂNG KÝ BẰNG GOOGLE ==========

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Tìm user theo email
        $user = User::where('email', $googleUser->getEmail())->first();

        // Nếu chưa có tài khoản → TẠO MỚI
        if (!$user) {
            $user = User::create([
                'username' => explode('@', $googleUser->getEmail())[0],
                'fullname' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'avatar' => $googleUser->getAvatar(),
                'phone' => null,
                'is_verified' => true,
                'status' => \App\Enums\UserStatus::ACTIVE,
                'role' => \App\Enums\UserRole::CUSTOMER,
            ]);
        }

        // Đăng nhập
        Auth::login($user);

        return redirect()->route('account.dashboard')->with('success', 'Đăng nhập bằng Google thành công!');
    }

public function handleFacebookCallback()
{
    try {
        $facebookUser = Socialite::driver('facebook')->stateless()->user();
    } catch (\Exception $e) {
        return redirect()->route('login')->with('error', 'Đăng nhập Facebook thất bại. Vui lòng thử lại.');
    }

    // ⚠ Kiểm tra email
    $email = $facebookUser->getEmail() ?? 'fb_' . $facebookUser->getId() . '@noemail.facebook';

    // Tạo user nếu chưa tồn tại
    $user = User::firstOrCreate(
        ['email' => $email],
        [
            'username' => Str::slug($facebookUser->getName()) . '-' . Str::random(4),
            'fullname' => $facebookUser->getName(),
            'email' => $email,
            'password' => bcrypt(Str::random(16)),
            'avatar' => $facebookUser->getAvatar(),
            'phone' => null,
            'is_verified' => true,
            'status' => \App\Enums\UserStatus::ACTIVE,
            'role' => \App\Enums\UserRole::CUSTOMER,
        ]
    );

    Auth::login($user);

    return redirect()->route('account.dashboard')->with('success', 'Đăng nhập bằng Facebook thành công!');
}
public function redirectToFacebook()
{
    return Socialite::driver('facebook')
        ->scopes(['email'])
        ->redirect();
}

}
