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
            'receiver_name' => 'required',
            'receiver_phone' => 'required',
            'address' => 'required',
            'province' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'zip_code' => 'nullable',
            'address_type' => 'required|in:home,office,other',
        ]);

        $zip_code = $request->zip_code ?? str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

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
            'zip_code' => $zip_code,
        ]);
        Log::info($store);
        if ($store) {
            Log::info('Đã thêm địa chỉ thành công.');
            return redirect()->back()->with('success', 'Đã thêm địa chỉ thành công.');
        }

        return redirect()->back()->with('error', 'Đã xảy ra lỗi khi thêm địa chỉ.');
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
            'receiver_name' => 'required',
            'receiver_phone' => 'required',
            'address' => 'required',
            'province' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'zip_code' => 'required',
            'address_type' => 'required|in:home,office,other',
        ]);

        if ($request->has('is_default')) {
            UserAddress::where('userID', Auth::id())->update(['is_default' => 0]);
        }

        $address->update([
            ...$request->all(),
            'is_default' => $request->has('is_default') ? 1 : 0
        ]);

        return redirect()->route('account.addresses')->with('success', 'Đã cập nhật địa chỉ.');
    }

    public function destroy(UserAddress $address)
    {
        $this->authorize('delete', $address);
        $address->delete();

        return back()->with('success', 'Đã xoá địa chỉ.');
    }

    public function setDefault(UserAddress $address)
    {
        $this->authorize('update', $address);

        // Unset all other addresses as default
        UserAddress::where('userID', Auth::id())->update(['is_default' => 0]);

        // Set the selected address as default
        $address->update(['is_default' => 1]);

        return redirect()->route('account.addresses')->with('success', 'Đã đặt địa chỉ làm mặc định.');
    }
}
