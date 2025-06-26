<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserControllerAdmin extends Controller
{
    /**
     * Hiển thị danh sách người dùng với phân trang và tìm kiếm.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        if ($search = $request->query('search')) {
            $query->where('username', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $users = $query->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Hiển thị chi tiết người dùng.
     */
    public function show($id): View
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Hiển thị form chỉnh sửa người dùng.
     */
    public function edit($id): View
    {
        $user = User::findOrFail($id);
        $roles = ['admin' => 'Quản trị viên', 'customer' => 'Khách hàng'];
        $statuses = ['active' => 'Hoạt động', 'inactive' => 'Không hoạt động', 'banned' => 'Bị khóa'];
        $genders = ['male' => 'Nam', 'female' => 'Nữ'];

        return view('admin.users.edit', compact('user', 'roles', 'statuses', 'genders'));
    }

    /**
     * Cập nhật thông tin người dùng.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $user = User::findOrFail($id);

            $validatedData = $request->validate([
                'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
                'fullname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'phone' => ['required', 'string', 'regex:/^\+?\d{9,15}$/', Rule::unique('users')->ignore($user->id)],
                'role' => ['required', Rule::in(['admin', 'customer'])],
                'status' => ['required', Rule::in(['active', 'inactive'])],
                'gender' => ['required', Rule::in(['male', 'female'])],
                'birthday' => ['nullable', 'date', 'before:today'],
            ]);

            $user->update($validatedData);

            return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công!');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Cập nhật người dùng thất bại. Vui lòng thử lại.');
        }
    }

    /**
     * Xóa người dùng, ngăn xóa nếu là quản trị viên.
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $user = User::findOrFail($id);

            if ($user->role === UserRole::ADMIN) {
                return back()->with('error', 'Không thể xóa tài khoản quản trị viên!');
            }

            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng!');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return back()->with('error', 'Xóa người dùng thất bại. Vui lòng thử lại.');
        }
    }
}
