<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        return view('user.account.profile', compact('user'));
    }

    public function edit()
    {
        return view('user.account.profile', ['user' => Auth::user()]);
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
}
