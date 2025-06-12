<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserAddressController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $addresses = Auth::user()->addresses;
        $user = Auth::user();
        if ($addresses->isEmpty()) {
            return view('user.account.addresses.index', ['addresses' => $addresses])
                ->with('message', 'Bạn chưa có địa chỉ nào. Hãy thêm địa chỉ mới.');
        }
        return view('user.account.addresses.index', compact('addresses', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        return view('user.account.addresses.create' , compact('user'));
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
            'zip_code' => 'required',
            'address_type' => 'required|in:home,office,other',
        ]);

        if ($request->has('is_default')) {
            UserAddress::where('userID', Auth::id())->update(['is_default' => 0]);
        }

        UserAddress::create([
            ...$request->all(),
            'userID' => Auth::id(),
            'is_default' => $request->has('is_default') ? 1 : 0
        ]);

        return redirect()->route('account.addresses')->with('success', 'Đã thêm địa chỉ thành công.');
    }

    public function edit(UserAddress $address)
    {
        $this->authorize('view', $address);
        return view('user.account.addresses.edit', compact('address'));
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
}
