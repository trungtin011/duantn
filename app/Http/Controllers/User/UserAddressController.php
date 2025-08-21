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
            // Không chấp nhận chỉ toàn số (mã), bắt buộc là tên hiển thị
            'province' => 'required|string|max:100|not_regex:/^\\d+$/',
            'district' => 'required|string|max:100|not_regex:/^\\d+$/',
            'ward' => 'required|string|max:100|not_regex:/^\\d+$/',
            'address_type' => 'required|in:home,office,other',
        ], [
            'receiver_phone.regex' => 'Số điện thoại phải có 10-11 chữ số.',
            'receiver_name.max' => 'Tên người nhận không được quá 100 ký tự.',
            'address.max' => 'Địa chỉ không được quá 255 ký tự.',
            'province.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
            'district.required' => 'Vui lòng chọn Quận/Huyện.',
            'ward.required' => 'Vui lòng chọn Phường/Xã.',
            'province.not_regex' => 'Vui lòng chọn Tỉnh/Thành (không phải mã số).',
            'district.not_regex' => 'Vui lòng chọn Quận/Huyện (không phải mã số).',
            'ward.not_regex' => 'Vui lòng chọn Phường/Xã (không phải mã số).',
        ]);

        if ($request->has('is_default')) {
            UserAddress::where('userID', Auth::id())->update(['is_default' => 0]);
        }

        // Chuẩn hóa tên địa phương: ưu tiên tên từ form, fallback chuyển code -> tên bằng API
        $provinceName = $request->input('province_name')
            ?: $this->getNameFromApi('province', $request->province)
            ?: $this->getVNPostProvinceName($request->province)
            ?: $request->province; // cuối cùng dùng code để tránh null
        $districtName = $request->input('district_name')
            ?: $this->getNameFromApi('district', $request->district)
            ?: $this->getVNPostDistrictName($request->province, $request->district)
            ?: $request->district;
        $wardName = $request->input('ward_name')
            ?: $this->getNameFromApi('ward', $request->ward)
            ?: $this->getVNPostWardName($request->district, $request->ward)
            ?: $request->ward;

        // Ưu tiên lưu tên hiển thị nếu được gửi kèm, fallback lưu tên từ API (không bao giờ lưu mã số)
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
            'province' => 'required|string|max:100|not_regex:/^\\d+$/',
            'district' => 'required|string|max:100|not_regex:/^\\d+$/',
            'ward' => 'required|string|max:100|not_regex:/^\\d+$/',
            'address_type' => 'required|in:home,office,other',
        ], [
            'receiver_phone.regex' => 'Số điện thoại phải có 10-11 chữ số.',
            'receiver_name.max' => 'Tên người nhận không được quá 100 ký tự.',
            'address.max' => 'Địa chỉ không được quá 255 ký tự.',
            'province.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
            'district.required' => 'Vui lòng chọn Quận/Huyện.',
            'ward.required' => 'Vui lòng chọn Phường/Xã.',
            'province.not_regex' => 'Vui lòng chọn Tỉnh/Thành (không phải mã số).',
            'district.not_regex' => 'Vui lòng chọn Quận/Huyện (không phải mã số).',
            'ward.not_regex' => 'Vui lòng chọn Phường/Xã (không phải mã số).',
        ]);

        if ($request->has('is_default')) {
            UserAddress::where('userID', Auth::id())->update(['is_default' => 0]);
        }

        // Chuẩn hóa tên địa phương: ưu tiên tên từ form, fallback chuyển code -> tên bằng API
        $provinceName = $request->input('province_name')
            ?: $this->getNameFromApi('province', $request->province)
            ?: $this->getVNPostProvinceName($request->province)
            ?: $request->province;
        $districtName = $request->input('district_name')
            ?: $this->getNameFromApi('district', $request->district)
            ?: $this->getVNPostDistrictName($request->province, $request->district)
            ?: $request->district;
        $wardName = $request->input('ward_name')
            ?: $this->getNameFromApi('ward', $request->ward)
            ?: $this->getVNPostWardName($request->district, $request->ward)
            ?: $request->ward;

        // Ưu tiên lưu tên hiển thị nếu được gửi kèm, fallback lưu tên từ API (không bao giờ lưu mã số)
        $address->update([
            'receiver_name' => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'address' => $request->address,
            'province' => $provinceName,
            'district' => $districtName,
            'ward' => $wardName,
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
