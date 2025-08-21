<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Enums\UserGender;
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
        $user = User::findOrFail($id);

        $rules = [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'regex:/^\+?\d{9,15}$/', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'customer', 'seller'])],
            'status' => ['required', Rule::in(['active', 'inactive', 'banned'])],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'birthday' => ['nullable', 'date', 'before:today'],
        ];

        $messages = [
            'username.required' => 'Tên đăng nhập không được để trống.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',
            'fullname.required' => 'Họ và tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'role.required' => 'Vui lòng chọn quyền.',
            'role.in' => 'Quyền không hợp lệ.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'gender.required' => 'Vui lòng chọn giới tính.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'birthday.date' => 'Ngày sinh không hợp lệ.',
            'birthday.before' => 'Ngày sinh phải nhỏ hơn ngày hiện tại.',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Kiểm tra đổi quyền seller
        $newRole = $request->input('role');
        if ($user->role->value == 'seller' && $newRole != 'seller') {
            // Kiểm tra seller đã có shop chưa
            if ($user->shop) {
                return back()
                    ->withInput()
                    ->withErrors(['role' => 'Người bán đã có cửa hàng, không thể đổi quyền!']);
            }
        }

        try {
            $user->update($validator->validated());
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

    /**
     * Danh sách người dùng cho ajax.
     */
    public function ajaxList(Request $request)
    {
        $query = User::query();

        if ($search = $request->query('search')) {
            $query->where('username', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        }
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        if ($role = $request->query('role')) {
            $query->where('role', $role);
        }

        $users = $query->paginate(10);

        return view('admin.users._table_body', compact('users'))->render();
    }

    /**
     * Ban người dùng, ngăn không cho ban admin và seller đã có shop.
     */
    public function ban($id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Không cho ban admin
        if ($user->role->value == 'admin') {
            return back()->with('error', 'Không thể ban tài khoản quản trị viên!');
        }

        // Nếu là seller, kiểm tra có shop chưa
        if ($user->role->value == 'seller' && $user->shop) {
            return back()->with('error', 'Không thể ban người bán đã có cửa hàng!');
        }

        // Chỉ ban khách hàng và seller chưa có shop
        $user->status = 'banned';
        $user->save();

        return back()->with('success', 'Đã ban người dùng thành công!');
    }

    public function unban($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        if ($user->status->value == 'banned') {
            $user->status = 'active';
            $user->save();
            return back()->with('success', 'Người dùng đã được mở khóa!');
        }
        return back()->with('error', 'Người dùng không ở trạng thái bị khóa!');
    }
}
