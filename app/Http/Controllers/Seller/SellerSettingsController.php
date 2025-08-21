<?php
// filepath: e:\duantn\app\Http\Controllers\Seller\SellerSettingsController.php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopAddress;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Arr;

class SellerSettingsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $shop = Shop::where('ownerID', $user->id)->first();
        $address = ShopAddress::where('shopID', $shop->id)
            ->where('is_default', true)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first();
        return view('seller.settings', compact('shop', 'address'));
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
            // Address fields
            'shop_address' => 'nullable|string|max:255',
            'shop_province' => 'nullable|string|max:100',
            'shop_district' => 'nullable|string|max:100',
            'shop_ward' => 'nullable|string|max:100',
            'shop_province_name' => 'nullable|string|max:100',
            'shop_district_name' => 'nullable|string|max:100',
            'shop_ward_name' => 'nullable|string|max:100',
            'address_note' => 'nullable|string|max:500',
        ]);
        if ($request->hasFile('shop_logo')) {
            if ($shop->shop_logo) Storage::delete($shop->shop_logo);
            $validated['shop_logo'] = $request->file('shop_logo')->store('shop_logos', 'public');
        }
        if ($request->hasFile('shop_banner')) {
            if ($shop->shop_banner) Storage::delete($shop->shop_banner);
            $validated['shop_banner'] = $request->file('shop_banner')->store('shop_banners', 'public');
        }
        // Update shop basic fields only
        $shopData = Arr::only($validated, [
            'shop_name','shop_phone','shop_email','shop_description','shop_status','shop_logo','shop_banner'
        ]);
        $shop->update($shopData);

        // Upsert default shop address if any address field provided
        // Prefer human-readable names if provided from client hidden inputs
        $addressPayload = [
            'shop_address' => $validated['shop_address'] ?? null,
            'shop_province' => $validated['shop_province_name'] ?? ($validated['shop_province'] ?? null),
            'shop_district' => $validated['shop_district_name'] ?? ($validated['shop_district'] ?? null),
            'shop_ward' => $validated['shop_ward_name'] ?? ($validated['shop_ward'] ?? null),
            'note' => $validated['address_note'] ?? null,
        ];

        $hasAddressInput = collect($addressPayload)->filter(function($v){ return !is_null($v) && $v !== ''; })->isNotEmpty();
        if ($hasAddressInput) {
            $addr = ShopAddress::firstOrNew(['shopID' => $shop->id, 'is_default' => true]);
            $addr->fill(array_merge($addressPayload, ['shopID' => $shop->id, 'is_default' => true]));
            $addr->save();
        }
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
            $emailData = [
                'name' => $user->fullname ?? $user->username,
                'code' => $code,
                'email' => $user->email
            ];

            Mail::send('emails.seller-password-reset-code', $emailData, function ($message) use ($user) {
                $message->to($user->email, $user->fullname ?? $user->username)
                        ->subject('Mã xác nhận đổi mật khẩu - ZynoxMall');
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

    // ===== Seller Profile (basic user info) =====
    public function profile()
    {
        return view('seller.profile');
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'fullname' => 'nullable|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . Auth::id(),
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'birthday' => 'nullable|date',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::delete(str_replace('storage/', '', $user->avatar));
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = 'storage/' . $validated['avatar'];
        }

        // Update simple fields
        $user->fill($validated);
        $user->save();

        return redirect()->route('seller.profile')->with('success', 'Cập nhật thông tin cá nhân thành công!');
    }
}
