<?php

namespace App\Http\Controllers\Seller\RegisterSeller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Shop;
use App\Models\ShopAddress;
use App\Models\ShopShippingOption;
use App\Models\BusinessLicense;
use App\Models\Seller;
use App\Models\User;
use App\Models\Notification;

class RegisterShopController extends Controller
{
    /**
     * Kiểm tra nếu đã là seller thì không cho vào đăng ký lại
     */
    private function checkAlreadySeller()
    {
        if (\App\Models\Seller::where('userID', Auth::id())->exists()) {
            return redirect()->route('seller.dashboard')->withErrors(['error' => 'Bạn đã đăng ký trở thành người bán. Không thể đăng ký lại.']);
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->checkAlreadySeller()) return $redirect;
        return view('seller.register.index');
    }

    /**
     * Hiển thị Trang 1: Thông tin shop
     */
    public function showStep1()
    {
        if ($redirect = $this->checkAlreadySeller()) return $redirect;
        return view('seller.register.register');
    }

    /**
     * Xử lý dữ liệu Trang 1
     */
    public function step1(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|unique:shops,shop_name|max:100',
            'email' => 'required|email|unique:shops,shop_email|max:100',
            'phone' => 'required|unique:shops,shop_phone|max:11',
            'address' => 'required|max:255',
            // Đã bỏ validate các trường địa phương
            'shop_description' => 'required|max:65535',
            'shop_logo' => 'required|file|image|mimes:jpg,jpeg,png|max:2048',
            'shop_banner' => 'required|file|image|mimes:jpg,jpeg,png|max:4096',
        ], [
            'shop_name.required' => 'Tên shop là bắt buộc.',
            'shop_name.unique' => 'Tên shop đã tồn tại.',
            'email.required' => 'Email là bắt buộc.',
            'email.unique' => 'Email đã được sử dụng.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.unique' => 'Số điện thoại đã được sử dụng.',
            'address.required' => 'Địa chỉ lấy hàng là bắt buộc.',
            // Đã bỏ thông báo lỗi các trường địa phương
            'shop_description.required' => 'Mô tả shop là bắt buộc.',
            'shop_logo.required' => 'Logo shop là bắt buộc.',
            'shop_logo.max' => 'Logo shop không được vượt quá 2MB.',
            'shop_banner.required' => 'Banner shop là bắt buộc.',
            'shop_banner.max' => 'Banner shop không được vượt quá 4MB.',
        ]);

        // Lưu file logo và banner
        $logoPath = $request->file('shop_logo')->store('shop_logos', 'public');
        $bannerPath = $request->file('shop_banner')->store('shop_banners', 'public');

        // Lưu dữ liệu vào session
        session(['register_shop' => array_merge(session('register_shop', []), [
            'shop_name' => $request->shop_name,
            'shop_email' => $request->email,
            'shop_phone' => $request->phone,
            'shop_address' => $request->address,
            // Đã bỏ lưu các trường địa phương
            'shop_description' => $request->shop_description,
            'shop_logo' => $logoPath,
            'shop_banner' => $bannerPath,
        ])]);

        return redirect()->route('seller.register.step2')->with('success', 'Thông tin shop đã được lưu tạm.');
    }

    /**
     * Hiển thị Trang 2: Dịch vụ vận chuyển
     */
    public function showStep2()
    {
        if ($redirect = $this->checkAlreadySeller()) return $redirect;
        // Kiểm tra dữ liệu session từ Trang 1
        if (!session('register_shop.shop_name')) {
            return redirect()->route('seller.register.step1')->withErrors(['error' => 'Vui lòng hoàn thành bước 1 trước.']);
        }
        return view('seller.register.register1');
    }

    /**
     * Xử lý dữ liệu Trang 2
     */
    public function step2(Request $request)
    {
        $request->validate([
            'shipping_options.express.cod_enabled' => 'nullable|boolean',
            'shipping_options.fast.cod_enabled' => 'nullable|boolean',
            'shipping_options.economy.cod_enabled' => 'nullable|boolean',
            'shipping_options.self_pickup.cod_enabled' => 'nullable|boolean',
            'shipping_options.bulky.cod_enabled' => 'nullable|boolean',
        ]);

        // Lưu dữ liệu vận chuyển vào session
        session(['register_shop' => array_merge(session('register_shop', []), [
            'shipping_options' => [
                'express' => ['cod_enabled' => $request->input('shipping_options.express.cod_enabled', 0)],
                'fast' => ['cod_enabled' => $request->input('shipping_options.fast.cod_enabled', 0)],
                'economy' => ['cod_enabled' => $request->input('shipping_options.economy.cod_enabled', 0)],
                'self_pickup' => ['cod_enabled' => $request->input('shipping_options.self_pickup.cod_enabled', 0)],
                'bulky' => ['cod_enabled' => $request->input('shipping_options.bulky.cod_enabled', 0)],
            ],
        ])]);

        return redirect()->route('seller.register.step3')->with('success', 'Cấu hình vận chuyển đã được lưu tạm.');
    }

    /**
     * Hiển thị Trang 3: Thông tin kinh doanh
     */
    public function showStep3()
    {
        if ($redirect = $this->checkAlreadySeller()) return $redirect;
        // Kiểm tra dữ liệu session từ Trang 2
        if (!session('register_shop.shipping_options')) {
            return redirect()->route('seller.register.step2')->withErrors(['error' => 'Vui lòng hoàn thành bước 2 trước.']);
        }
        return view('seller.register.register2');
    }

    /**
     * Xử lý dữ liệu Trang 3
     */
    public function step3(Request $request)
    {
        $request->validate([
            'business_type' => 'required|in:personal,household,company', // sửa lại cho đúng value radio
            'business_province' => 'required',
            'business_district' => 'required',
            'business_ward' => 'required',
            'business_address_detail' => 'required|max:255',
            'invoice_email' => 'required|email|max:100',
            'tax_code' => 'required|max:20|unique:business_licenses,tax_number',
        ], [
            'business_type.required' => 'Loại hình kinh doanh là bắt buộc.',
            'business_province.required' => 'Tỉnh/thành phố là bắt buộc.',
            'business_district.required' => 'Quận/huyện là bắt buộc.',
            'business_ward.required' => 'Phường/xã là bắt buộc.',
            'business_address_detail.required' => 'Địa chỉ kinh doanh là bắt buộc.',
            'invoice_email.required' => 'Email nhận hóa đơn là bắt buộc.',
            'tax_code.required' => 'Mã số thuế là bắt buộc.',
            'tax_code.unique' => 'Mã số thuế đã được sử dụng.',
        ]);

        // Gộp địa chỉ kinh doanh
        $business_province_name = $this->getNameFromApi('province', $request->business_province) ?? '';
        $business_district_name = $this->getNameFromApi('district', $request->business_district) ?? '';
        $business_ward_name = $this->getNameFromApi('ward', $request->business_ward) ?? '';

        $business_address = $request->business_address_detail . ', ';
        $business_address .= $business_ward_name . ', ';
        $business_address .= $business_district_name . ', ';
        $business_address .= $business_province_name;

        // Lưu dữ liệu kinh doanh vào session
        session(['register_shop' => array_merge(session('register_shop', []), [
            'business_type' => $request->business_type,
            'business_province' => $request->business_province,
            'business_district' => $request->business_district,
            'business_ward' => $request->business_ward,
            'business_province_name' => $business_province_name,
            'business_district_name' => $business_district_name,
            'business_ward_name' => $business_ward_name,
            'business_address_detail' => $request->business_address_detail,
            'business_address' => $business_address,
            'invoice_email' => $request->invoice_email,
            'tax_code' => $request->tax_code,
        ])]);



        return redirect()->route('seller.register.step4')->with('success', 'Thông tin kinh doanh đã được lưu tạm.');
    }

    /**
     * Hiển thị Trang 4: Thông tin định danh
     */
    public function showStep4()
    {
        if ($redirect = $this->checkAlreadySeller()) return $redirect;
        // Kiểm tra dữ liệu session từ Trang 3
        if (!session('register_shop.business_type')) {
            return redirect()->route('seller.register.step3')->withErrors(['error' => 'Vui lòng hoàn thành bước 3 trước.']);
        }
        return view('seller.register.register3');
    }

    /**
     * Xử lý dữ liệu Trang 4: Định danh + OCR
     */
    public function step4(Request $request)
    {
        $request->validate([
            'id_number' => 'required|string|max:20',
            'full_name' => 'required|string|max:100',
            'birthday' => 'required|date',
            'nationality' => 'required|string|max:100',
            'hometown' => 'required|string|max:255',
            'residence' => 'required|string|max:255',
            'confirm' => 'required',
        ], [
            'confirm.required' => 'Bạn phải xác nhận thông tin.',
        ]);

        $exists = \App\Models\IdentityVerification::where('identity_number', $request->id_number)
            ->where('full_name', $request->full_name)
            ->where('birth_date', $request->birthday)
            ->where('nationality', $request->nationality)
            ->where('hometown', $request->hometown)
            ->where('residence', $request->residence)
            ->exists();

        if (!$exists) {
            return back()->withErrors(['id_number' => 'Thông tin định danh không đúng hoặc chưa được xác thực.'])->withInput();
        }


        $identity = [
            'identity_card' => $request->id_number,
            'identity_card_type' => $request->id_type,
            'identity_card_image' => $request->file ? $request->file('file')->store('identity_cards', 'public') : null,
            'full_name' => $request->full_name,
            'birth_date' => $request->birthday,
            'nationality' => $request->nationality,
            'hometown' => $request->hometown,
            'residence' => $request->residence,
            'privacy_policy_agreed' => $request->has('confirm') ? 1 : 0,
        ];
        session(['register_shop' => array_merge(session('register_shop', []), $identity)]);

        return redirect()->route('seller.register.step5')->with('success', 'Thông tin định danh đã được lưu tạm.');
    }

    /**
     * Hiển thị Trang 5: Thành công và lưu dữ liệu vào database
     */
    public function showStep5(Request $request)
    {
        if ($redirect = $this->checkAlreadySeller()) return $redirect;
        $data = session('register_shop');
        // Kiểm tra dữ liệu session từng bước và redirect về đúng bước thiếu
        if (!$data) {
            return redirect()->route('seller.register.step1')->withErrors(['error' => 'Vui lòng nhập thông tin shop.']);
        }
        if (!isset($data['shop_name'])) {
            return redirect()->route('seller.register.step1')->withErrors(['error' => 'Vui lòng nhập thông tin shop.']);
        }
        if (!isset($data['shipping_options'])) {
            return redirect()->route('seller.register.step2')->withErrors(['error' => 'Vui lòng cấu hình vận chuyển.']);
        }
        if (!isset($data['business_type'])) {
            return redirect()->route('seller.register.step3')->withErrors(['error' => 'Vui lòng nhập thông tin kinh doanh.']);
        }
        if (!isset($data['identity_card'])) {
            return redirect()->route('seller.register.step4')->withErrors(['error' => 'Vui lòng nhập thông tin định danh.']);
        }
        if (!isset($data['shop_address'])) {
            return redirect()->route('seller.register.step1')->withErrors(['error' => 'Vui lòng nhập địa chỉ shop.']);
        }
        if (!isset($data['tax_code'])) {
            return redirect()->route('seller.register.step3')->withErrors(['error' => 'Vui lòng nhập mã số thuế.']);
        }

        DB::beginTransaction();
        try {
            // 1. Lưu vào bảng shops
            $shop = Shop::create([
                'ownerID' => Auth::id(),
                'shop_name' => $data['shop_name'],
                'shop_phone' => $data['shop_phone'],
                'shop_email' => $data['shop_email'],
                'shop_description' => $data['shop_description'],
                'shop_logo' => $data['shop_logo'],
                'shop_banner' => $data['shop_banner'],
                'shop_status' => 'inactive', // Sửa từ 'pending' thành 'inactive' cho đúng enum
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Lưu vào bảng shop_addresses
            ShopAddress::create([
                'shopID' => $shop->id,
                'shop_address' => $data['shop_address'],
                'shop_province' => $data['business_province_name'] ?? $data['business_province'],
                'shop_district' => $data['business_district_name'] ?? $data['business_district'],
                'shop_ward' => $data['business_ward_name'] ?? $data['business_ward'],
                'note' => null,
                'is_default' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3. Lưu vào bảng shop_shipping_options
            $shippingOptions = [
                'express' => $data['shipping_options']['express']['cod_enabled'] ?? 0,
                'fast' => $data['shipping_options']['fast']['cod_enabled'] ?? 0,
                'economy' => $data['shipping_options']['economy']['cod_enabled'] ?? 0,
                'self_pickup' => $data['shipping_options']['self_pickup']['cod_enabled'] ?? 0,
                'bulky' => $data['shipping_options']['bulky']['cod_enabled'] ?? 0,
            ];
            foreach ($shippingOptions as $type => $codEnabled) {
                ShopShippingOption::create([
                    'shopID' => $shop->id,
                    'shipping_type' => $type,
                    'cod_enabled' => $codEnabled,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 4. Lưu vào bảng business_licenses
            $businessLicense = BusinessLicense::create([
                'business_license_number' => 'LIC' . time(),
                'tax_number' => $data['tax_code'],
                'business_ID' => 'BUS' . time(),
                'business_name' => $data['shop_name'],
                'business_type' => $data['business_type'],
                'invoice_email' => $data['invoice_email'],
                'business_license_date' => now(),
                'expiry_date' => now()->addYears(5),
                'status' => 'pending',
                'license_file_path' => '', // Sửa null thành chuỗi rỗng để tránh lỗi SQL
                'is_active' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 5. Lưu vào bảng sellers
            Seller::create([
                'userID' => Auth::id(),
                'status' => 'suspended',
                'identity_card' => $data['identity_card'],
                'identity_card_type' => in_array($data['identity_card_type'], ['cccd', 'cmnd']) ? $data['identity_card_type'] : 'cccd',
                'identity_card_date' => now(),
                'identity_card_place' => 'Unknown',
                'identity_card_image' => $data['identity_card_image'],
                'identity_card_holding_image' => $data['identity_card_holding_image'] ?? null,
                'privacy_policy_agreed' => $data['privacy_policy_agreed'] ?? 0,
                'bank_account' => null,
                'bank_name' => '',
                'bank_account_name' => '',
                'business_license_id' => $businessLicense->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 7. Gửi thông báo
            DB::table('notifications')->insert([
                'sender_id' => null,
                'shop_id' => null,
                'title' => 'Đăng ký shop thành công',
                'content' => 'Shop của bạn đã được tạo. Hệ thống sẽ xác thực thông tin trong 3-4 ngày làm việc. Vui lòng chờ kết quả xác thực!',
                'type' => 'shop_registration',
                'reference_id' => Auth::id(), // dùng để theo dõi ai là người nhận nếu cần
                'receiver_type' => 'user',
                'priority' => 'normal',
                'status' => 'pending', // đúng enum
                'is_read' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            session()->forget('register_shop');
            return view('seller.register.register4');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('register_shop');
            return redirect()->route('seller.register.step1')->withErrors(['error' => 'Lỗi khi lưu dữ liệu: ' . $e->getMessage()]);
        }
    }

    /**
     * Hoàn tất và chuyển hướng
     */
    public function finish(Request $request)
    {
        return redirect()->route('home')->with('success', 'Đăng ký shop thành công. Bạn có thể bắt đầu thêm sản phẩm.');
    }

    // Helper lấy tên từ API
    private function getNameFromApi($type, $code)
    {
        if (!$code) return '';
        $url = '';
        if ($type === 'province') $url = "https://provinces.open-api.vn/api/p/$code";
        if ($type === 'district') $url = "https://provinces.open-api.vn/api/d/$code";
        if ($type === 'ward') {
            $url = "https://provinces.open-api.vn/api/w/$code";
        }
        try {
            $json = @file_get_contents($url);
            if ($json) {
                $data = json_decode($json, true);
                return $data['name'] ?? '';
            }
        } catch (\Exception $e) {
        }
        return '';
    }
}
