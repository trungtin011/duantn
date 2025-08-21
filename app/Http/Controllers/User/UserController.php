<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\PointTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function index()
    {
        Log::info('UserController@index called', ['user_id' => Auth::id()]);
        $user = Auth::user();
        return view('user.account.profile', compact('user'));
    }

    public function edit()
    {
        Log::info('UserController@edit called', ['user_id' => Auth::id()]);
        $user = Auth::user();
        return view('user.account.profile', compact('user'));
    }

    public function update(Request $request)
    {
        Log::info('UserController@update called', ['user_id' => Auth::id()]);
        $user = Auth::user();

        $request->validate([
            'username' => 'nullable|string|max:50|min:3|unique:users,username,' . $user->id,
            'fullname' => 'nullable|string|max:100|min:2',
            'email' => 'nullable|email|max:100|min:5|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:11|min:10|unique:users,phone,' . $user->id,
            'day' => 'nullable|integer|between:1,31',
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|between:1900,' . date('Y'),
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ], [
            'username.string' => 'Tên đăng nhập phải là chuỗi ký tự.',
            'username.max' => 'Tên đăng nhập không được vượt quá 50 ký tự.',
            'username.min' => 'Tên đăng nhập phải có ít nhất 3 ký tự.',
            'username.unique' => 'Tên đăng nhập này đã được sử dụng.',
            
            'fullname.string' => 'Họ tên phải là chuỗi ký tự.',
            'fullname.max' => 'Họ tên không được vượt quá 100 ký tự.',
            'fullname.min' => 'Họ tên phải có ít nhất 2 ký tự.',
            
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 100 ký tự.',
            'email.min' => 'Email phải có ít nhất 5 ký tự.',
            'email.unique' => 'Email này đã được sử dụng.',
            
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 11 số.',
            'phone.min' => 'Số điện thoại phải có ít nhất 10 số.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            
            'day.integer' => 'Ngày phải là số nguyên.',
            'day.between' => 'Ngày phải từ 1 đến 31.',
            
            'month.integer' => 'Tháng phải là số nguyên.',
            'month.between' => 'Tháng phải từ 1 đến 12.',
            
            'year.integer' => 'Năm phải là số nguyên.',
            'year.between' => 'Năm phải từ 1900 đến ' . date('Y') . '.',
            
            'gender.in' => 'Giới tính không hợp lệ.',
            
            'avatar.image' => 'File phải là hình ảnh.',
            'avatar.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'avatar.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            
            'current_password.string' => 'Mật khẩu hiện tại phải là chuỗi ký tự.',
            
            'new_password.string' => 'Mật khẩu mới phải là chuỗi ký tự.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        // Kiểm tra và xử lý ảnh đại diện
        if ($request->hasFile('avatar')) {
            // Xoá ảnh cũ nếu có
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Lưu ảnh mới
            $path = $request->file('avatar')->store('avatars', 'public'); // lưu vào storage/app/public/avatars
            $user->avatar = $path; // lưu đường dẫn vào database
        }

        // Cập nhật thông tin
        $user->username = $request->input('username', $user->username); // Sử dụng giá trị cũ nếu không có thay đổi
        $user->fullname = $request->input('fullname', $user->fullname);
        $user->email = $request->input('email', $user->email);
        $user->phone = $request->input('phone', $user->phone);

        // Kết hợp ngày, tháng, năm thành birthday nếu có
        if ($request->filled(['day', 'month', 'year'])) {
            $birthday = sprintf('%04d-%02d-%02d', $request->year, $request->month, $request->day);
            $user->birthday = \Carbon\Carbon::createFromFormat('Y-m-d', $birthday)->toDateString();
        }

        $user->gender = $request->input('gender', $user->gender);

        // Nếu có yêu cầu đổi mật khẩu
        if ($request->filled('new_password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                Log::warning('UserController@update: Wrong current password', ['user_id' => $user->id]);
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng hoặc không được cung cấp.']);
            }

            $user->password = bcrypt($request->new_password);
        }

        $user->save();

        Log::info('UserController@update: User info updated', ['user_id' => $user->id]);
        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function changePasswordForm()
    {
        Log::info('UserController@changePasswordForm called', ['user_id' => Auth::id()]);
        $user = Auth::user();
        return view('user.account.changePassword', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        Log::info('UserController@updatePassword called', ['user_id' => Auth::id()]);
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            Log::warning('UserController@updatePassword: Wrong current password', ['user_id' => $user->id]);
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        Log::info('UserController@updatePassword: Password updated', ['user_id' => $user->id]);
        return redirect()->route('account.password')->with('success', 'Đổi mật khẩu thành công!');
    }

    // Hàm xử lý hiển thị lịch sử điểm thưởng
    public function points()
    {
        Log::info('UserController@points called', ['user_id' => Auth::id()]);
        $user = Auth::user();
        $tab = request()->query('tab', 'all'); // Lấy tab từ query string, mặc định là 'all'

        $query = PointTransaction::where('userID', $user->id);

        // Lọc theo tab
        switch ($tab) {
            case 'received':
                $query->where('points', '>', 0);
                break;
            case 'used':
                $query->where('points', '<', 0);
                break;
            default:
                // Không lọc cho tab 'all'
                break;
        }

        $points = $query->orderBy('created_at', 'desc')->paginate(10);

        // Tính tổng điểm
        $totalPoints = PointTransaction::where('userID', $user->id)->sum('points');
        $totalReceived = PointTransaction::where('userID', $user->id)->where('points', '>', 0)->sum('points');
        $totalUsed = abs(PointTransaction::where('userID', $user->id)->where('points', '<', 0)->sum('points'));

        Log::info('UserController@points: Points history loaded', [
            'user_id' => $user->id,
            'tab' => $tab,
            'totalPoints' => $totalPoints,
            'totalReceived' => $totalReceived,
            'totalUsed' => $totalUsed
        ]);

        return view('user.account.points.index', compact('user', 'points', 'totalPoints', 'totalReceived', 'totalUsed'));
    }

    public function requestChangePasswordWithCode(Request $request)
    {
        Log::info('UserController@requestChangePasswordWithCode called', ['user_id' => Auth::id()]);
        $user = Auth::user();
        $code = random_int(100000, 999999);

        $user->reset_code = $code;
        $user->reset_code_expires_at = now()->addMinutes(10);
        $user->save();

        $resetLink = route('account.password.verify.form');

        try {
            $emailData = [
                'name' => $user->fullname ?? $user->username,
                'code' => $code,
                'email' => $user->email
            ];

            Mail::send('emails.user-password-reset-code', $emailData, function ($message) use ($user) {
                $message->to($user->email, $user->fullname ?? $user->username)
                        ->subject('Mã xác nhận đổi mật khẩu - ZynoxMall');
            });

            Log::info('Password reset code sent successfully', ['user_id' => $user->id, 'email' => $user->email]);
        } catch (\Exception $e) {
            Log::error('Failed to send password reset email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['email' => 'Không thể gửi email. Vui lòng thử lại sau.']);
        }

        return redirect()->route('account.password.code.verify.form')
            ->with('success', 'Mã xác nhận đã được gửi tới email của bạn. Vui lòng kiểm tra email và nhập mã.');
    }

    public function showVerifyCodeForm()
    {
        Log::info('UserController@showVerifyCodeForm called', ['user_id' => Auth::id()]);
        $user = Auth::user();
        return view('user.account.verify-password-code', compact('user'));
    }

    public function verifyPasswordCode(Request $request)
    {
        Log::info('UserController@verifyPasswordCode called', ['user_id' => Auth::id()]);
        $request->validate(['code' => 'required|numeric'], [
            'code.required' => 'Vui lòng nhập mã xác nhận.',
            'code.numeric' => 'Mã xác nhận phải là số.',
        ]);

        $user = Auth::user();

        Log::info('Bắt đầu xác thực mã đổi mật khẩu', [
            'user_id' => $user->id,
            'email' => $user->email,
            'received_code' => $request->code,
            'stored_code' => $user->reset_code,
            'code_type_received' => gettype($request->code),
            'code_type_stored' => gettype($user->reset_code),
            'expires_at' => $user->reset_code_expires_at,
            'is_expired' => $user->reset_code_expires_at < now()
        ]);

        // Convert both to string for comparison
        $receivedCode = (string) $request->code;
        $storedCode = (string) $user->reset_code;

        if ($storedCode !== $receivedCode || $user->reset_code_expires_at < now()) {
            Log::warning('Xác thực mã thất bại', [
                'user_id' => $user->id,
                'email' => $user->email,
                'received_code' => $receivedCode,
                'stored_code' => $storedCode,
                'codes_match' => $storedCode === $receivedCode,
                'is_expired' => $user->reset_code_expires_at < now(),
                'reason' => $storedCode !== $receivedCode ? 'Mã không khớp' : 'Mã đã hết hạn'
            ]);

            return back()->withErrors(['code' => 'Mã xác nhận không đúng hoặc đã hết hạn.']);
        }

        // Lưu thông tin xác thực thành công vào session
        session(['password_verified' => true]);

        Log::info('Xác thực mã thành công - chuyển đến trang đặt mật khẩu mới', [
            'user_id' => $user->id,
            'email' => $user->email,
            'session_set' => session('password_verified')
        ]);

        return redirect()->route('account.password.reset.form');
    }

    public function showPasswordResetForm()
    {
        Log::info('UserController@showPasswordResetForm called', ['user_id' => Auth::id()]);
        $user = Auth::user();
        return view('user.account.reset-password', compact('user'));
    }

    public function confirmNewPassword(Request $request)
    {
        Log::info('UserController@confirmNewPassword called', ['user_id' => Auth::id()]);

        // Log tất cả dữ liệu request
        Log::info('Reset password form data received', [
            'user_id' => Auth::id(),
            'all_data' => $request->all(),
            'has_password' => $request->has('password'),
            'has_password_confirmation' => $request->has('password_confirmation'),
            'password_length' => $request->input('password') ? strlen($request->input('password')) : 0,
            'password_confirmation_length' => $request->input('password_confirmation') ? strlen($request->input('password_confirmation')) : 0,
            'passwords_match' => $request->input('password') === $request->input('password_confirmation')
        ]);

        // Kiểm tra xem đã xác thực mã chưa
        if (!session('password_verified')) {
            Log::warning('Người dùng cố gắng đặt mật khẩu mới mà chưa xác thực mã', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'session_password_verified' => session('password_verified')
            ]);

            return redirect()->route('account.password.code.verify.form')
                ->withErrors(['code' => 'Vui lòng xác thực mã trước khi đặt mật khẩu mới.']);
        }

        $request->validate([
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $user = Auth::user();

        Log::info('Bắt đầu quá trình đổi mật khẩu', [
            'user_id' => $user->id,
            'email' => $user->email,
            'password_length' => strlen($request->password)
        ]);

        try {
            // Lưu mật khẩu cũ để log
            $oldPasswordHash = $user->password;

            // Cập nhật mật khẩu mới
            $user->password = bcrypt($request->password);
            $user->reset_code = null;
            $user->reset_code_expires_at = null;
            $user->save();

            Log::info('Đổi mật khẩu thành công', [
                'user_id' => $user->id,
                'email' => $user->email,
                'old_password_hash' => substr($oldPasswordHash, 0, 20) . '...',
                'new_password_hash' => substr($user->password, 0, 20) . '...',
                'reset_code_cleared' => $user->reset_code === null,
                'reset_expires_cleared' => $user->reset_code_expires_at === null
            ]);

            // Xóa session xác thực
            session()->forget('password_verified');

            Log::info('Đã xóa session xác thực', [
                'user_id' => $user->id,
                'session_cleared' => !session('password_verified')
            ]);

            return redirect()->route('account.password')->with('password_success', 'Đổi mật khẩu thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi đổi mật khẩu', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['password' => 'Có lỗi xảy ra khi đổi mật khẩu. Vui lòng thử lại.']);
        }
    }

    public function requestPasswordChangeConfirm(Request $request)
    {
        Log::info('UserController@requestPasswordChangeConfirm called', ['user_id' => Auth::id()]);

        // Log tất cả dữ liệu request
        Log::info('Form data received', [
            'user_id' => Auth::id(),
            'all_data' => $request->all(),
            'has_current_password' => $request->has('current_password'),
            'has_new_password' => $request->has('new_password'),
            'has_password' => $request->has('password'),
            'has_password_confirmation' => $request->has('password_confirmation'),
            'current_password_length' => $request->input('current_password') ? strlen($request->input('current_password')) : 0,
            'new_password_length' => $request->input('new_password') ? strlen($request->input('new_password')) : 0,
            'password_length' => $request->input('password') ? strlen($request->input('password')) : 0,
            'password_confirmation_length' => $request->input('password_confirmation') ? strlen($request->input('password_confirmation')) : 0
        ]);

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            Log::warning('UserController@requestPasswordChangeConfirm: Wrong current password', ['user_id' => Auth::id()]);
            return back()->withErrors(['current_password' => 'Sai mật khẩu hiện tại.']);
        }

        $code = random_int(100000, 999999);

        session([
            'pending_password' => bcrypt($request->new_password),
            'password_code' => $code,
        ]);

        Log::info('Session data set for password change', [
            'user_id' => Auth::id(),
            'code' => $code,
            'session_has_pending_password' => session('pending_password') ? true : false,
            'session_has_password_code' => session('password_code') ? true : false
        ]);

        try {
            Mail::raw("Mã xác nhận đổi mật khẩu là: $code", function ($m) {
                $m->to(Auth::user()->email)->subject('Mã xác nhận đổi mật khẩu');
            });

            Log::info('Password change code sent successfully', ['user_id' => Auth::id(), 'email' => Auth::user()->email]);
        } catch (\Exception $e) {
            Log::error('Failed to send password change email', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['email' => 'Không thể gửi email. Vui lòng thử lại sau.']);
        }

        return redirect()->route('account.password.code.verify.form')->with('success', 'Mã xác nhận đã gửi về email.');
    }

    public function confirmPasswordChangeCode(Request $request)
    {
        Log::info('UserController@confirmPasswordChangeCode called', ['user_id' => Auth::id()]);
        $request->validate([
            'code' => 'required|digits:6',
        ], [
            'code.required' => 'Vui lòng nhập mã xác nhận.',
            'code.digits' => 'Mã xác nhận phải có đúng 6 chữ số.',
        ]);

        if ($request->code == session('password_code')) {
            $user = Auth::user();
            $user->password = session('pending_password');
            $user->save();

            session()->forget(['password_code', 'pending_password']);

            Log::info('UserController@confirmPasswordChangeCode: Password changed successfully', ['user_id' => $user->id]);
            return redirect()->route('account.password')->with('password_success', 'Đổi mật khẩu thành công!');
        }

        Log::warning('UserController@confirmPasswordChangeCode: Wrong code', ['user_id' => Auth::id()]);
        return back()->withErrors(['code' => 'Mã xác nhận không đúng.']);
    }

    public function requestPasswordVerify(Request $request)
    {
        Log::info('UserController@requestPasswordVerify called', ['user_id' => Auth::id()]);
        $user = Auth::user();

        $code = random_int(100000, 999999);
        $user->reset_code = $code;
        $user->reset_code_expires_at = now()->addMinutes(10);
        $user->save();

        // Gửi email mã xác nhận
        try {
            Mail::send([], [], function ($message) use ($user, $code) {
                $message->to($user->email)
                    ->subject('Mã xác nhận đổi mật khẩu')
                    ->html("
                <p>Xin chào <strong>{$user->fullname}</strong>,</p>
                <p>Mã xác nhận đổi mật khẩu của bạn là: <strong style='color: red; font-size: 18px;'>$code</strong></p>
                <p>Mã có hiệu lực trong 10 phút.</p>
                <p>Trân trọng,<br>Hệ thống</p>
            ");
            });

            Log::info('Password verification code sent successfully', ['user_id' => $user->id, 'email' => $user->email]);
        } catch (\Exception $e) {
            Log::error('Failed to send password verification email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['email' => 'Không thể gửi email. Vui lòng thử lại sau.']);
        }

        return redirect()->route('account.password.code.verify.form')
            ->with('success', 'Mã xác nhận đã được gửi tới email của bạn.');
    }

    // Test method để kiểm tra email
    public function testEmail()
    {
        Log::info('UserController@testEmail called', ['user_id' => Auth::id()]);
        $user = Auth::user();

        try {
            Mail::raw("Test email from Laravel application", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Test Email');
            });

            Log::info('Test email sent successfully', ['user_id' => $user->id, 'email' => $user->email]);

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully. Check logs for details.',
                'email' => $user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Test email failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage(),
                'email' => $user->email
            ], 500);
        }
    }

    // Debug method để kiểm tra reset code
    public function debugResetCode()
    {
        Log::info('UserController@debugResetCode called', ['user_id' => Auth::id()]);
        $user = Auth::user();

        return response()->json([
            'user_id' => $user->id,
            'reset_code' => $user->reset_code,
            'reset_code_type' => gettype($user->reset_code),
            'reset_code_expires_at' => $user->reset_code_expires_at,
            'is_expired' => $user->reset_code_expires_at < now(),
            'current_time' => now()
        ]);
    }
}
