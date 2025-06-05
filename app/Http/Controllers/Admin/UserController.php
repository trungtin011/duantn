<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Danh sách tất cả người dùng
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Hiển thị chi tiết 1 người dùng
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // Trang chỉnh sửa thông tin người dùng
    public function edit(User $user)
    {
        
        return view('admin.users.edit', compact('user'));
    }

    // Cập nhật thông tin người dùng
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:users,username,' . $user->id,
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|regex:/^\+?\d{9,15}$/|unique:users,phone,' . $user->id,
            'role' => 'required|in:admin,customer',
            'status' => 'required|in:active,inactive',
            'gender' => 'required|in:male,female',
            'birthdate' => 'nullable|date|before:today',
        ]);

        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công!');
    }


    // Xóa người dùng
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng!');
    }
}
