<?php
// filepath: e:\duantn\app\Http\Controllers\Seller\SellerSettingsController.php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SellerSettingsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $shop = Shop::where('ownerID', $user->id)->first();
        return view('seller.settings', compact('shop'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $shop = Shop::where('ownerID', $user->id)->firstOrFail();
        $validated = $request->validate([
            'shop_name' => 'required|string|max:100',
            'shop_phone' => 'nullable|string|max:20',
            'shop_email' => 'nullable|email|max:100',
            'shop_description' => 'nullable|string|max:500',
            'shop_logo' => 'nullable|image|max:2048',
            'shop_banner' => 'nullable|image|max:4096',
            'shop_status' => 'required|in:active,inactive,banned',
        ]);
        if ($request->hasFile('shop_logo')) {
            if ($shop->shop_logo) Storage::delete($shop->shop_logo);
            $validated['shop_logo'] = $request->file('shop_logo')->store('shop_logos', 'public');
        }
        if ($request->hasFile('shop_banner')) {
            if ($shop->shop_banner) Storage::delete($shop->shop_banner);
            $validated['shop_banner'] = $request->file('shop_banner')->store('shop_banners', 'public');
        }
        $shop->update($validated);
        return redirect()->route('seller.settings')->with('success', 'Cập nhật thông tin cửa hàng thành công!');
    }

    // ========== Password Change Flow for Seller (copied and adapted from UserController) ==========
    public function requestChangePasswordWithCode(Request $request)
    {
        Log::info('SellerSettingsController@requestChangePasswordWithCode called', ['user_id' => Auth::id()]);
        /** @var User $user */
        $user = Auth::user();
        $code = random_int(100000, 999999);

        $user->reset_code = $code;
        $user->reset_code_expires_at = now()->addMinutes(10);
        $user->save();

        $resetLink = route('seller.password.verify.form');

        try {
            Mail::send([], [], function ($message) use ($user, $code, $resetLink) {
                $message->to($user->email)
                    ->subject('Mã xác nhận đổi mật khẩu')
                    ->html("
                        <p>Chào {$user->fullname},</p>
                        <p>Mã xác nhận đổi mật khẩu của bạn là: <strong>$code</strong></p>
                        <p>Hoặc bạn có thể nhấn vào nút bên dưới để nhập mã và đổi mật khẩu:</p>
                        <p><a href='{$resetLink}' style='padding:10px 15px; background:#ef4444; color:white; text-decoration:none; border-radius:5px;'>Xác nhận đổi mật khẩu</a></p>
                        <p>Trân trọng,</p>
                        <p>Hệ thống</p>
                    ");
            });

            Log::info('Seller password reset code sent successfully', ['user_id' => $user->id, 'email' => $user->email]);
        } catch (\Exception $e) {
            Log::error('Seller failed to send password reset email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['email' => 'Không thể gửi email. Vui lòng thử lại sau.']);
        }

        return redirect()->route('seller.password.verify.form')
            ->with('success', 'Mã xác nhận đã được gửi tới email của bạn. Vui lòng kiểm tra email và nhập mã.');
    }

    public function showVerifyCodeForm()
    {
        Log::info('SellerSettingsController@showVerifyCodeForm called', ['user_id' => Auth::id()]);
        $user = Auth::user();
        return view('seller.verify-password-code', compact('user'));
    }

    public function verifyPasswordCode(Request $request)
    {
        Log::info('SellerSettingsController@verifyPasswordCode called', ['user_id' => Auth::id()]);
        $request->validate(['code' => 'required|numeric']);

        $user = Auth::user();

        Log::info('Seller verify code start', [
            'user_id' => $user->id,
            'email' => $user->email,
            'received_code' => $request->code,
            'stored_code' => $user->reset_code,
            'expires_at' => $user->reset_code_expires_at,
            'is_expired' => $user->reset_code_expires_at < now()
        ]);

        $receivedCode = (string) $request->code;
        $storedCode = (string) $user->reset_code;

        if ($storedCode !== $receivedCode || $user->reset_code_expires_at < now()) {
            Log::warning('Seller verify code failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'received_code' => $receivedCode,
                'stored_code' => $storedCode,
                'codes_match' => $storedCode === $receivedCode,
                'is_expired' => $user->reset_code_expires_at < now(),
            ]);

            return back()->withErrors(['code' => 'Mã xác nhận không đúng hoặc đã hết hạn.']);
        }

        session(['password_verified' => true]);
        Log::info('Seller verify code success', ['user_id' => $user->id]);

        return redirect()->route('seller.password.reset.form');
    }

    public function showPasswordResetForm()
    {
        Log::info('SellerSettingsController@showPasswordResetForm called', ['user_id' => Auth::id()]);
        $user = Auth::user();
        return view('seller.reset-password', compact('user'));
    }

    public function confirmNewPassword(Request $request)
    {
        Log::info('SellerSettingsController@confirmNewPassword called', ['user_id' => Auth::id()]);

        if (!session('password_verified')) {
            Log::warning('Seller tried to reset password without verification', ['user_id' => Auth::id()]);
            return redirect()->route('seller.password.verify.form')
                ->withErrors(['code' => 'Vui lòng xác thực mã trước khi đặt mật khẩu mới.']);
        }

        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        /** @var User $user */
        $user = Auth::user();

        try {
            $user->password = bcrypt($request->password);
            $user->reset_code = null;
            $user->reset_code_expires_at = null;
            $user->save();

            session()->forget('password_verified');

            Log::info('Seller password changed successfully', ['user_id' => $user->id]);
            return redirect()->route('seller.password')->with('password_success', 'Đổi mật khẩu thành công!');
        } catch (\Exception $e) {
            Log::error('Seller password change failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors(['password' => 'Có lỗi xảy ra khi đổi mật khẩu. Vui lòng thử lại.']);
        }
    }
}
