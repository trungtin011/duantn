<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class UserAddressController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses;
        $message = $addresses->isEmpty() ? 'Bạn chưa có địa chỉ nào. Hãy thêm địa chỉ mới.' : null;
        return view('user.account.addresses.index', compact('addresses', 'user', 'message'));
    }

    public function create()
    {
        $user = Auth::user();
        return view('user.account.addresses.create', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required|string|max:100',
            'receiver_phone' => 'required|string|regex:/^[0-9]{10,11}$/',
            'address' => 'required|string|max:255',
            'province' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'ward' => 'required|string|max:100',
            'address_type' => 'required|in:home,office,other',
        ], [
            'receiver_phone.regex' => 'Số điện thoại phải có 10-11 chữ số.',
            'receiver_name.max' => 'Tên người nhận không được quá 100 ký tự.',
            'address.max' => 'Địa chỉ không được quá 255 ký tự.',
        ]);

        if ($request->has('is_default')) {
            UserAddress::where('userID', Auth::id())->update(['is_default' => 0]);
        }

        $store = UserAddress::create([
            'receiver_name' => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'address' => $request->address,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'address_type' => $request->address_type,
            'userID' => Auth::id(),
            'is_default' => $request->has('is_default') ? 1 : 0,
            'zip_code' => null, // Bỏ trường zip_code
        ]);
        Log::info($store);
        if ($store) {
            Log::info('Đã thêm địa chỉ thành công.');
            return redirect()->route('account.addresses')->with('success', 'Đã thêm địa chỉ thành công!');
        }

        return redirect()->back()->with('error', 'Đã xảy ra lỗi khi thêm địa chỉ. Vui lòng kiểm tra lại thông tin và thử lại.');
    }

    public function edit(UserAddress $address)
    {
        $user = Auth::user();
        $this->authorize('view', $address);
        return view('user.account.addresses.edit', compact('address', 'user'));
    }

    public function update(Request $request, UserAddress $address)
    {
        $this->authorize('update', $address);

        $request->validate([
            'receiver_name' => 'required|string|max:100',
            'receiver_phone' => 'required|string|regex:/^[0-9]{10,11}$/',
            'address' => 'required|string|max:255',
            'province' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'ward' => 'required|string|max:100',
            'address_type' => 'required|in:home,office,other',
        ], [
            'receiver_phone.regex' => 'Số điện thoại phải có 10-11 chữ số.',
            'receiver_name.max' => 'Tên người nhận không được quá 100 ký tự.',
            'address.max' => 'Địa chỉ không được quá 255 ký tự.',
        ]);

        if ($request->has('is_default')) {
            UserAddress::where('userID', Auth::id())->update(['is_default' => 0]);
        }

        $address->update([
            'receiver_name' => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'address' => $request->address,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'address_type' => $request->address_type,
            'note' => $request->note,
            'is_default' => $request->has('is_default') ? 1 : 0,
            'zip_code' => null, // Bỏ trường zip_code
        ]);

        return redirect()->route('account.addresses')->with('success', 'Đã cập nhật địa chỉ thành công!');
    }

    public function destroy(UserAddress $address)
    {
        $this->authorize('delete', $address);
        $address->delete();

        return redirect()->route('account.addresses')->with('success', 'Đã xoá địa chỉ thành công!');
    }

    public function setDefault(UserAddress $address)
    {
        $this->authorize('update', $address);

        // Unset all other addresses as default
        UserAddress::where('userID', Auth::id())->update(['is_default' => 0]);

        // Set the selected address as default
        $address->update(['is_default' => 1]);

        return redirect()->route('account.addresses')->with('success', 'Đã đặt địa chỉ làm mặc định thành công!');
    }
}
