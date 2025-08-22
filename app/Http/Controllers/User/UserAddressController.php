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

    private function getNameFromApi(string $type, string $code = null): ?string
    {
        if (!$code) return null;
        $url = '';
        if ($type === 'province') $url = "https://provinces.open-api.vn/api/p/$code";
        if ($type === 'district') $url = "https://provinces.open-api.vn/api/d/$code";
        if ($type === 'ward') $url = "https://provinces.open-api.vn/api/w/$code";
        try {
            $json = @file_get_contents($url);
            if ($json) {
                $data = json_decode($json, true);
                return $data['name'] ?? null;
            }
        } catch (\Exception $e) {
            // ignore
        }
        return null;
    }

    private function getVNPostProvinceName(?string $provinceCode): ?string
    {
        if (!$provinceCode) return null;
        try {
            $json = @file_get_contents('https://api.vnpost.vn/api/v1/province');
            if ($json) {
                $data = json_decode($json, true);
                foreach (($data['data'] ?? []) as $p) {
                    if (($p['provinceCode'] ?? null) == $provinceCode) {
                        return $p['provinceName'] ?? null;
                    }
                }
            }
        } catch (\Exception $e) {}
        return null;
    }

    private function getVNPostDistrictName(?string $provinceCode, ?string $districtCode): ?string
    {
        if (!$provinceCode || !$districtCode) return null;
        try {
            $json = @file_get_contents("https://api.vnpost.vn/api/v1/district?provinceCode={$provinceCode}");
            if ($json) {
                $data = json_decode($json, true);
                foreach (($data['data'] ?? []) as $d) {
                    if (($d['districtCode'] ?? null) == $districtCode) {
                        return $d['districtName'] ?? null;
                    }
                }
            }
        } catch (\Exception $e) {}
        return null;
    }

    private function getVNPostWardName(?string $districtCode, ?string $wardCode): ?string
    {
        if (!$districtCode || !$wardCode) return null;
        try {
            $json = @file_get_contents("https://api.vnpost.vn/api/v1/ward?districtCode={$districtCode}");
            if ($json) {
                $data = json_decode($json, true);
                foreach (($data['data'] ?? []) as $w) {
                    if (($w['wardCode'] ?? null) == $wardCode) {
                        return $w['wardName'] ?? null;
                    }
                }
            }
        } catch (\Exception $e) {}
        return null;
    }

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
            'receiver_name.required' => 'Vui lòng nhập tên người nhận.',
            'receiver_phone.required' => 'Vui lòng nhập số điện thoại.',
            'receiver_phone.regex' => 'Số điện thoại phải có 10-11 chữ số.',
            'receiver_name.max' => 'Tên người nhận không được quá 100 ký tự.',
            'address.required' => 'Vui lòng nhập địa chỉ chi tiết.',
            'address.max' => 'Địa chỉ không được quá 255 ký tự.',
            'province.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
            'district.required' => 'Vui lòng chọn Quận/Huyện.',
            'ward.required' => 'Vui lòng chọn Phường/Xã.',
            'address_type.required' => 'Vui lòng chọn loại địa chỉ.',
        ]);

        if ($request->has('is_default')) {
            UserAddress::where('userID', Auth::id())->update(['is_default' => 0]);
        }

        // Ưu tiên sử dụng tên từ hidden inputs, fallback về tên từ select options
        $provinceName = $request->input('province_name') ?: $request->province;
        $districtName = $request->input('district_name') ?: $request->district;
        $wardName = $request->input('ward_name') ?: $request->ward;

        // Nếu vẫn là mã số, thử chuyển đổi thành tên bằng API
        if (is_numeric($provinceName)) {
            $provinceName = $this->getVNPostProvinceName($provinceName) ?: $this->getNameFromApi('province', $provinceName) ?: $provinceName;
        }
        if (is_numeric($districtName)) {
            $districtName = $this->getVNPostDistrictName($request->province, $districtName) ?: $this->getNameFromApi('district', $districtName) ?: $districtName;
        }
        if (is_numeric($wardName)) {
            $wardName = $this->getVNPostWardName($request->district, $wardName) ?: $this->getNameFromApi('ward', $wardName) ?: $wardName;
        }

        try {
            $store = UserAddress::create([
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'address' => $request->address,
                'province' => $provinceName,
                'district' => $districtName,
                'ward' => $wardName,
                'address_type' => $request->address_type,
                'userID' => Auth::id(),
                'is_default' => $request->has('is_default') ? 1 : 0,
                'zip_code' => null,
                'note' => $request->input('note'),
            ]);

            Log::info('Đã thêm địa chỉ thành công.', ['address_id' => $store->id]);
            return redirect()->route('account.addresses')->with('success', 'Đã thêm địa chỉ thành công!');

        } catch (\Exception $e) {
            Log::error('Lỗi khi thêm địa chỉ: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi khi thêm địa chỉ. Vui lòng kiểm tra lại thông tin và thử lại.');
        }
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
            'receiver_name.required' => 'Vui lòng nhập tên người nhận.',
            'receiver_phone.required' => 'Vui lòng nhập số điện thoại.',
            'receiver_phone.regex' => 'Số điện thoại phải có 10-11 chữ số.',
            'receiver_name.max' => 'Tên người nhận không được quá 100 ký tự.',
            'address.required' => 'Vui lòng nhập địa chỉ chi tiết.',
            'address.max' => 'Địa chỉ không được quá 255 ký tự.',
            'province.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
            'district.required' => 'Vui lòng chọn Quận/Huyện.',
            'ward.required' => 'Vui lòng chọn Phường/Xã.',
            'address_type.required' => 'Vui lòng chọn loại địa chỉ.',
        ]);

        if ($request->has('is_default')) {
            UserAddress::where('userID', Auth::id())->update(['is_default' => 0]);
        }

        // Ưu tiên sử dụng tên từ hidden inputs, fallback về tên từ select options
        $provinceName = $request->input('province_name') ?: $request->province;
        $districtName = $request->input('district_name') ?: $request->district;
        $wardName = $request->input('ward_name') ?: $request->ward;

        // Nếu vẫn là mã số, thử chuyển đổi thành tên bằng API
        if (is_numeric($provinceName)) {
            $provinceName = $this->getVNPostProvinceName($provinceName) ?: $this->getNameFromApi('province', $provinceName) ?: $provinceName;
        }
        if (is_numeric($districtName)) {
            $districtName = $this->getVNPostDistrictName($request->province, $districtName) ?: $this->getNameFromApi('district', $districtName) ?: $districtName;
        }
        if (is_numeric($wardName)) {
            $wardName = $this->getVNPostWardName($request->district, $wardName) ?: $this->getNameFromApi('ward', $wardName) ?: $wardName;
        }

        try {
            $address->update([
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'address' => $request->address,
                'province' => $provinceName,
                'district' => $districtName,
                'ward' => $wardName,
                'address_type' => $request->address_type,
                'note' => $request->input('note'),
                'is_default' => $request->has('is_default') ? 1 : 0,
                'zip_code' => null,
            ]);

            Log::info('Đã cập nhật địa chỉ thành công.', ['address_id' => $address->id]);
            return redirect()->route('account.addresses')->with('success', 'Đã cập nhật địa chỉ thành công!');

        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật địa chỉ: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật địa chỉ. Vui lòng kiểm tra lại thông tin và thử lại.');
        }
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
