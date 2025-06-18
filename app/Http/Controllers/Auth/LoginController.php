<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB; // Thêm để truy vấn bảng sessions

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
            'login.required' => 'Vui lòng nhập email hoặc số điện thoại',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $loginType => strtolower($request->login),
            'password' => $request->password
        ];

        $key = 'login-attempt:' . strtolower($request->login) . ':' . $request->ip();

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Kiểm tra trạng thái của người dùng
            if ($user->isBanned()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors([
                    'login' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
                ]);
            }

            $request->session()->regenerate();
            RateLimiter::clear($key);
            return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
        }

        RateLimiter::hit($key, 300);
        return back()->withErrors([
            'login' => 'Tài khoản hoặc mật khẩu không đúng.',
        ])->withInput($request->only('login', 'remember'))
            ->withInput($request->only('login'));
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
        return redirect()->route('home')->with('success', 'Đăng xuất thành công!');
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
        return redirect()->route('account.dashboard')->with('success', 'Đăng nhập bằng Google thành công!');
    }

    public function handleFacebookCallback()
    {
        try {
            /** @var \Laravel\Socialite\Contracts\OAuth2Provider $socialiteProvider */
            $socialiteProvider = Socialite::driver('facebook');
            $facebookUser = $socialiteProvider->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Đăng nhập Facebook thất bại. Vui lòng thử lại.');
        }
        $email = $facebookUser->getEmail() ?? 'fb_' . $facebookUser->getId() . '@noemail.facebook';
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
        /** @var \Laravel\Socialite\Contracts\OAuth2Provider $socialiteProvider */
        $socialiteProvider = Socialite::driver('facebook');
        return $socialiteProvider
            ->scopes(['email'])
            ->redirect();
    }
}
