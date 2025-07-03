<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\PointTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        return view('user.account.profile', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('user.account.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'nullable|string|max:50|unique:users,username,' . $user->id,
            'fullname' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:11',
            'day' => 'nullable|integer|between:1,31',
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|between:1900,' . date('Y'),
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
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
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng hoặc không được cung cấp.']);
            }

            $user->password = bcrypt($request->new_password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function changePasswordForm()
    {
        return view('user.account.changePassword');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->route('account.password')->with('success', 'Đổi mật khẩu thành công!');
    }

    // Hàm xử lý hiển thị lịch sử điểm thưởng
    public function points()
    {
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

        return view('user.account.points.index', compact('user', 'points', 'totalPoints', 'totalReceived', 'totalUsed'));
    }

    public function requestChangePasswordWithCode(Request $request)
    {
        $user = Auth::user();
        $code = random_int(100000, 999999);

        $user->reset_code = $code;
        $user->reset_code_expires_at = now()->addMinutes(10);
        $user->save();

        $resetLink = route('account.password.verify.form');

        Mail::send([], [], function ($message) use ($user, $code, $resetLink) {
            $message->to($user->email)
                ->subject('Mã xác nhận đổi mật khẩu')
                ->setBody("
                <p>Chào {$user->fullname},</p>
                <p>Mã xác nhận đổi mật khẩu của bạn là: <strong>$code</strong></p>
                <p>Hoặc bạn có thể nhấn vào nút bên dưới để nhập mã và đổi mật khẩu:</p>
                <p><a href='{$resetLink}' style='padding:10px 15px; background:#ef4444; color:white; text-decoration:none; border-radius:5px;'>Xác nhận đổi mật khẩu</a></p>
                <p>Trân trọng,</p>
                <p>Hệ thống</p>
            ", 'text/html');
        });

        return back()->with('success', 'Mã xác nhận đã được gửi tới email của bạn.');
    }

    public function showVerifyCodeForm()
    {
        return view('user.account.verify-password-code');
    }

    public function verifyPasswordCode(Request $request)
    {
        $request->validate(['code' => 'required|numeric']);

        $user = Auth::user();

        if ($user->reset_code !== $request->code || $user->reset_code_expires_at < now()) {
            return back()->withErrors(['code' => 'Mã xác nhận không đúng hoặc đã hết hạn.']);
        }

        return redirect()->route('account.password.reset.form');
    }

    public function showPasswordResetForm()
    {
        return view('user.account.reset-password');
    }

    public function confirmNewPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->reset_code = null;
        $user->reset_code_expires_at = null;
        $user->save();

        return redirect()->route('account.password')->with('success', 'Đổi mật khẩu thành công!');
    }
    public function requestPasswordChangeConfirm(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Sai mật khẩu hiện tại.']);
        }

        $code = random_int(100000, 999999);

        session([
            'pending_password' => bcrypt($request->new_password),
            'password_code' => $code,
        ]);

        Mail::raw("Mã xác nhận đổi mật khẩu là: $code", function ($m) {
            $m->to(Auth::user()->email)->subject('Mã xác nhận đổi mật khẩu');
        });

        return redirect()->route('account.password.code.verify.form')->with('success', 'Mã xác nhận đã gửi về email.');
    }

    public function confirmPasswordChangeCode(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);


        if ($request->code == session('password_code')) {
            $user = Auth::user();
            $user->password = session('pending_password');
            $user->save();

            session()->forget(['password_code', 'pending_password']);

            return redirect()->route('account.password')->with('password_success', 'Đổi mật khẩu thành công!');
        }

        return back()->withErrors(['code' => 'Mã xác nhận không đúng.']);
    }

    public function requestPasswordVerify(Request $request)
    {
        $user = Auth::user();

        $code = random_int(100000, 999999);
        $user->reset_code = $code;
        $user->reset_code_expires_at = now()->addMinutes(10);
        $user->save();

        // Gửi email mã xác nhận
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


        return redirect()->route('account.password.code.verify.form')
            ->with('success', 'Mã xác nhận đã được gửi tới email của bạn.');
    }
}
