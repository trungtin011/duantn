<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL) && !preg_match('/^0[0-9]{9}$/', $value)) {
                        $fail('Vui lòng nhập email hoặc số điện thoại hợp lệ.');
                    }
                },
            ],
            'password' => 'required|string',
        ], [
            'login.required' => 'Vui lòng nhập email hoặc số điện thoại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $loginType => strtolower($request->login),
            'password' => $request->password
        ];

        $key = 'login-attempt:' . strtolower($request->login) . ':' . $request->ip();

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            if ($user && $user->status && $user->status->value === 'banned') {
                Auth::logout();
                return back()->withErrors([
                    'login' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên để được hỗ trợ.'
                ])->withInput($request->only('login', 'remember'));
            }
            $request->session()->regenerate();
            RateLimiter::clear($key);

            // Kiểm tra role của user để chuyển hướng phù hợp
            $user = Auth::user();
            if ($user->role == \App\Enums\UserRole::ADMIN) {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công! Chào mừng bạn đến với trang quản trị.');
            } elseif ($user->role == \App\Enums\UserRole::SELLER) {
                return redirect()->route('seller.dashboard')->with('success', 'Đăng nhập thành công! Chào mừng bạn đến với trang người bán.');
            } else {
                return redirect()->intended(route('home'))->with('success', 'Đăng nhập thành công! Chào mừng bạn trở lại.');
            }
        }

        RateLimiter::hit($key, 300);
        return back()->withErrors([
            'login' => 'Thông tin đăng nhập không chính xác. Vui lòng kiểm tra lại email/số điện thoại và mật khẩu.',
        ])->withInput($request->only('login', 'remember'));
    }

    public function logout(Request $request)
    {
        // Cập nhật last_activity trong bảng sessions trước khi đăng xuất
        if (Auth::check()) {
            DB::table('sessions')
                ->where('user_id', Auth::id())
                ->update(['last_activity' => time()]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Đăng xuất thành công! Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        /** @var \Laravel\Socialite\Contracts\OAuth2Provider $socialiteProvider */
        $socialiteProvider = Socialite::driver('google');
        $googleUser = $socialiteProvider->stateless()->user();
        $user = User::where('email', $googleUser->getEmail())->first();
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
        Auth::login($user);
        return redirect()->route('home')->with('success', 'Đăng nhập bằng Google thành công! Chào mừng bạn đến với hệ thống.');
    }
}
