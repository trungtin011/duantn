<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function edit()
    {
        return view('account.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'fullname' => 'required|string|max:100',
            'phone' => 'nullable|string|max:11',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'current_password' => 'nullable|required_with:new_password|string',
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
        $user->username = $request->username;
        $user->fullname = $request->fullname;
        $user->phone = $request->phone;
        $user->birthday = $request->birthday;
        $user->gender = $request->gender;

        // Nếu có yêu cầu đổi mật khẩu
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
            }

            $user->password = bcrypt($request->new_password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }
    public function dashboard()
    {
        return view('account.dashboard', ['user' => auth()->user()]);
    }

    public function changePasswordForm()
    {
        return view('account.change_password');
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
