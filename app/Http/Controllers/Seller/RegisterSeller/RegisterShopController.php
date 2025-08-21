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
        if (Seller::where('userID', Auth::id())->exists()) {
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
            'shop_name.max' => 'Tên shop không được vượt quá 100 ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã được sử dụng.',
            'email.max' => 'Email không được vượt quá 100 ký tự.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.unique' => 'Số điện thoại đã được sử dụng.',
            'phone.max' => 'Số điện thoại không được vượt quá 11 ký tự.',
            'address.required' => 'Địa chỉ lấy hàng là bắt buộc.',
            'address.max' => 'Địa chỉ lấy hàng không được vượt quá 255 ký tự.',
            // Đã bỏ thông báo lỗi các trường địa phương
            'shop_description.required' => 'Mô tả shop là bắt buộc.',
            'shop_description.max' => 'Mô tả shop không được vượt quá 65535 ký tự.',
            'shop_logo.required' => 'Logo shop là bắt buộc.',
            'shop_logo.file' => 'Tệp logo không hợp lệ.',
            'shop_logo.image' => 'Logo phải là định dạng hình ảnh.',
            'shop_logo.max' => 'Logo shop không được vượt quá 2MB.',
            'shop_logo.mimes' => 'Logo chỉ chấp nhận các định dạng: jpg, jpeg, png.',
            'shop_banner.required' => 'Banner shop là bắt buộc.',
            'shop_banner.max' => 'Banner shop không được vượt quá 4MB.',
            'shop_banner.file' => 'Tệp banner không hợp lệ.',
            'shop_banner.image' => 'Banner phải là định dạng hình ảnh.',
            'shop_banner.mimes' => 'Banner chỉ chấp nhận các định dạng: jpg, jpeg, png.',
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

        return redirect()->route('seller.register.step3')->with('success', 'Thông tin shop đã được lưu tạm.');
    }

    /**
     * Hiển thị Trang 2: Dịch vụ vận chuyển (Đã bỏ)
     */
    public function showStep2()
    {
        // Redirect to step 3 since step 2 is removed
        return redirect()->route('seller.register.step3');
    }

    /**
     * Xử lý dữ liệu Trang 2 (Đã bỏ)
     */
    public function step2(Request $request)
    {
        // Redirect to step 3 since step 2 is removed
        return redirect()->route('seller.register.step3');
    }

    /**
     * Hiển thị Trang 2: Thông tin kinh doanh (Trước đây là Trang 3)
     */
    public function showStep3()
    {
        if ($redirect = $this->checkAlreadySeller()) return $redirect;
        // Kiểm tra dữ liệu session từ Trang 1
        if (!session('register_shop.shop_name')) {
            return redirect()->route('seller.register.step1')->withErrors(['error' => 'Vui lòng hoàn thành bước 1 trước.']);
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
            'business_address_detail.max' => 'Địa chỉ kinh doanh không được vượt quá 255 ký tự.',
            'invoice_email.required' => 'Email nhận hóa đơn là bắt buộc.',
            'invoice_email.email' => 'Email nhận hóa đơn không hợp lệ.',
            'invoice_email.max' => 'Email nhận hóa đơn không được vượt quá 100 ký tự.',
            'tax_code.required' => 'Mã số thuế là bắt buộc.',
            'tax_code.unique' => 'Mã số thuế đã được sử dụng.',
            'tax_code.max' => 'Mã số thuế không được vượt quá 20 ký tự.',
        ]);

        // Gộp địa chỉ kinh doanh
        // Ưu tiên lấy tên từ input hidden đã render sẵn, fallback gọi API
        $business_province_name = $request->input('business_province_name') ?: ($this->getNameFromApi('province', $request->business_province) ?? '');
        $business_district_name = $request->input('business_district_name') ?: ($this->getNameFromApi('district', $request->business_district) ?? '');
        $business_ward_name = $request->input('business_ward_name') ?: ($this->getNameFromApi('ward', $request->business_ward) ?? '');

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
            'gender' => 'required|in:male,female,other',
            'hometown' => 'required|string|max:255',
            'residence' => 'required|string|max:255',
            'identity_card_date' => 'required|date',
            'identity_card_place' => 'required|string|max:255',
            'cccd_image' => 'required|string',
            'back_cccd_image' => 'required|string',
            'confirm' => 'required',
        ], [
            'id_number.required' => 'Số CCCD/CMND là bắt buộc.',
            'id_number.max' => 'Số CCCD/CMND không được vượt quá 20 ký tự.',
            'full_name.required' => 'Họ và tên là bắt buộc.',
            'full_name.max' => 'Họ và tên không được vượt quá 100 ký tự.',
            'birthday.required' => 'Ngày sinh là bắt buộc.',
            'birthday.date' => 'Ngày sinh không hợp lệ.',
            'nationality.required' => 'Quốc tịch là bắt buộc.',
            'nationality.max' => 'Quốc tịch không được vượt quá 100 ký tự.',
            'cccd_image.required' => 'Ảnh mặt trước CCCD/CMND là bắt buộc.',
            'back_cccd_image.required' => 'Ảnh mặt sau CCCD/CMND là bắt buộc.',
            'confirm.required' => 'Bạn phải xác nhận thông tin.',
            'gender.required' => 'Giới tính là bắt buộc.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'hometown.required' => 'Quê quán là bắt buộc.',
            'hometown.max' => 'Quê quán không được vượt quá 255 ký tự.',
            'residence.required' => 'Nơi thường trú là bắt buộc.',
            'residence.max' => 'Nơi thường trú không được vượt quá 255 ký tự.',
            'identity_card_date.required' => 'Ngày cấp là bắt buộc.',
            'identity_card_date.date' => 'Ngày cấp không hợp lệ.',
            'identity_card_place.required' => 'Nơi cấp là bắt buộc.',
            'identity_card_place.max' => 'Nơi cấp không được vượt quá 255 ký tự.',
        ]);

        // Kiểm tra số CCCD đã tồn tại cho user khác chưa
        $exists = \App\Models\IdentityVerification::where('identity_number', $request->id_number)
            ->where('userID', '!=', Auth::id())
            ->exists();

        if ($exists) {
            return back()->withErrors(['id_number' => 'Đã có người sử dụng CCCD này.'])->withInput();
        }

        // Đảm bảo các trường ảnh không bị null
        $frontImage = $request->cccd_image ?: 'uploads/default_cccd_front.svg';
        $backImage = $request->back_cccd_image ?: 'uploads/default_cccd_back.svg';

        // Lưu trực tiếp vào bảng identity_verifications
        $identityData = [
            'userID' => Auth::id(),
            'full_name' => $request->full_name,
            'identity_number' => $request->id_number,
            'birth_date' => $request->birthday,
            'nationality' => $request->nationality,
            'gender' => $request->gender,
            'hometown' => $request->hometown,
            'residence' => $request->residence,
            'identity_type' => $request->id_type ?? 'cccd',
            'identity_card_date' => $request->identity_card_date,
            'identity_card_place' => $request->identity_card_place,
            'identity_card_image' => $frontImage,
            'identity_card_holding_image' => $backImage,
            'status' => 'pending',
        ];

        // Lưu vào bảng identity_verifications
        try {
            \App\Models\IdentityVerification::create($identityData);
        } catch (\Illuminate\Database\QueryException $ex) {
            // Trường hợp lỗi khác ngoài duplicate, vẫn throw ra
            throw $ex;
        }

        // Lưu vào session để sử dụng ở bước tiếp theo
        $sessionData = [
            'identity_card' => $request->id_number,
            'identity_card_type' => $request->id_type ?? 'cccd',
            'identity_card_image' => $frontImage,
            'identity_card_holding_image' => $backImage,
            'full_name' => $request->full_name,
            'birth_date' => $request->birthday,
            'nationality' => $request->nationality,
            'gender' => $request->gender,
            'hometown' => $request->hometown,
            'residence' => $request->residence,
            'identity_card_date' => $request->identity_card_date,
            'identity_card_place' => $request->identity_card_place,
            'privacy_policy_agreed' => $request->has('confirm') ? 1 : 0,
        ];
        session(['register_shop' => array_merge(session('register_shop', []), $sessionData)]);

        return redirect()->route('seller.register.step5')->with('success', 'Thông tin định danh đã được lưu.');
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
        // Bỏ kiểm tra shipping_options vì đã xóa bước vận chuyển
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

            // 3. Lưu vào bảng shop_shipping_options (Đã bỏ bước vận chuyển nên tạo mặc định)
            // Đã xóa logic tạo shop_shipping_options vì shop chỉ có 1 đơn vị vận chuyển

            // 4. Lưu vào bảng business_licenses
            $businessLicense = BusinessLicense::create([
                'shop_id' => $shop->id, // Thêm dòng này
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
            try {
                Seller::create([
                    'userID' => Auth::id(),
                    'shop_id' => $shop->id, // Thêm dòng này
                    'status' => 'suspended',
                    'identity_card' => $data['identity_card'],
                    'identity_card_type' => in_array($data['identity_card_type'], ['cccd', 'cmnd']) ? $data['identity_card_type'] : 'cccd',
                    'identity_card_date' => $data['identity_card_date'] ?? now(),
                    'identity_card_place' => $data['identity_card_place'] ?? 'Unknown',
                    'identity_card_image' => $data['identity_card_image'] ?: 'uploads/default_cccd_front.svg',
                    'identity_card_holding_image' => $data['identity_card_holding_image'] ?: 'uploads/default_cccd_back.svg',
                    'privacy_policy_agreed' => $data['privacy_policy_agreed'] ?? 0,
                    'bank_account' => null,
                    'bank_name' => '',
                    'bank_account_name' => '',
                    'business_license_id' => $businessLicense->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Illuminate\Database\QueryException $ex) {
                if ($ex->getCode() == 23000 && str_contains($ex->getMessage(), 'identity_card')) {
                    DB::rollBack();
                    // Không xóa session, giữ lại dữ liệu
                    return redirect()->route('seller.register.step4')->withErrors(['identity_card' => 'Đã có người sử dụng CCCD này.'])->withInput();
                }
                throw $ex;
            }

            // 6. Cập nhật bảng identity_verifications với shop_id
            $identityVerification = \App\Models\IdentityVerification::where('userID', Auth::id())
                ->where('identity_number', $data['identity_card'])
                ->where('status', 'pending')
                ->latest()
                ->first();
            
            if ($identityVerification) {
                $identityVerification->update([
                    'shop_id' => $shop->id,
                ]);
            }

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
