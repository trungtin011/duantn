@extends('layouts.admin')

@section('title', 'Chi Tiết Cửa Hàng')

@section('content')
<div class="container mx-auto py-8 px-4 max-w-7xl">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <h2 class="text-3xl font-bold text-gray-800">Chi tiết cửa hàng: {{ $shop->shop_name }}</h2>
        <a href="{{ route('admin.shops.pending') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-6 rounded-lg transition duration-300">
            Quay lại
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Shop Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Shop Information -->
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Thông tin Shop</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tên Shop</p>
                        <p class="text-gray-800">{{ $shop->shop_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Email</p>
                        <p class="text-gray-800">{{ $shop->shop_email }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Số điện thoại</p>
                        <p class="text-gray-800">{{ $shop->shop_phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Trạng thái</p>
                        <p class="text-gray-800">{{ $shop->shop_status }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-600">Mô tả</p>
                        <p class="text-gray-800">{{ $shop->shop_description }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-600">Ngày tạo</p>
                        <p class="text-gray-800">{{ $shop->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
                <!-- Images -->
                <div class="mt-4 grid grid-cols-2 gap-4">
                    @if($shop->shop_logo)
                        <div>
                            <p class="text-sm font-medium text-gray-600">Logo</p>
                            <img src="{{ asset('storage/' . $shop->shop_logo) }}" alt="Shop Logo" class="w-24 h-24 object-cover rounded-full border border-gray-200">
                        </div>
                    @endif
                    @if($shop->shop_banner)
                        <div>
                            <p class="text-sm font-medium text-gray-600">Banner</p>
                            <img src="{{ asset('storage/' . $shop->shop_banner) }}" alt="Shop Banner" class="w-48 h-24 object-cover rounded-lg border border-gray-200">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Shop Address -->
            @if($shop->shopAddress)
                <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Địa chỉ Shop</h3>
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Địa chỉ chi tiết</p>
                            <p class="text-gray-800">{{ $shop->shopAddress->shop_address }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tỉnh/Thành phố</p>
                            <p class="text-gray-800">{{ $shop->shopAddress->shop_province }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Quận/Huyện</p>
                            <p class="text-gray-800">{{ $shop->shopAddress->shop_district }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Phường/Xã</p>
                            <p class="text-gray-800">{{ $shop->shopAddress->shop_ward }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Additional Info -->
        <div class="space-y-6">
            <!-- Shipping Options -->
            @if($shop->shopShippingOptions->isNotEmpty())
                <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Tùy chọn Vận chuyển</h3>
                    <ul class="space-y-2">
                        @foreach($shop->shopShippingOptions as $option)
                            <li class="text-gray-800">
                                <span class="font-medium">{{ ucfirst($option->shipping_type) }}:</span>
                                COD {{ $option->cod_enabled ? 'Bật' : 'Tắt' }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Business License -->
            @if($shop->businessLicense)
                <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Giấy phép Kinh doanh</h3>
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Loại hình kinh doanh</p>
                            <p class="text-gray-800">{{ $shop->businessLicense->business_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Mã số thuế</p>
                            <p class="text-gray-800">{{ $shop->businessLicense->tax_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Email nhận hóa đơn</p>
                            <p class="text-gray-800">{{ $shop->businessLicense->invoice_email }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Trạng thái</p>
                            <p class="text-gray-800">{{ $shop->businessLicense->status }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Ngày cấp</p>
                            <p class="text-gray-800">{{ $shop->businessLicense->business_license_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Ngày hết hạn</p>
                            <p class="text-gray-800">{{ $shop->businessLicense->expiry_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Owner Identity -->
            @if($shop->owner && $shop->owner->seller && $shop->owner->seller->identityVerification)
                <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Định danh Chủ Shop</h3>
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Họ và Tên</p>
                            <p class="text-gray-800">{{ $shop->owner->seller->identityVerification->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Số CMND/CCCD</p>
                            <p class="text-gray-800">{{ $shop->owner->seller->identityVerification->identity_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Ngày sinh</p>
                            <p class="text-gray-800">{{ $shop->owner->seller->identityVerification->birth_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Quốc tịch</p>
                            <p class="text-gray-800">{{ $shop->owner->seller->identityVerification->nationality }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Quê quán</p>
                            <p class="text-gray-800">{{ $shop->owner->seller->identityVerification->hometown }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Nơi cư trú</p>
                            <p class="text-gray-800">{{ $shop->owner->seller->identityVerification->residence }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Loại giấy tờ</p>
                            <p class="text-gray-800">{{ strtoupper($shop->owner->seller->identityVerification->identity_type) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Ngày cấp</p>
                            <p class="text-gray-800">{{ $shop->owner->seller->identityVerification->identity_card_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Nơi cấp</p>
                            <p class="text-gray-800">{{ $shop->owner->seller->identityVerification->identity_card_place }}</p>
                        </div>
                        @if($shop->owner->seller->identityVerification->identity_card_image)
                            <div>
                                <p class="text-sm font-medium text-gray-600">Ảnh CMND/CCCD</p>
                                <img src="{{ asset('storage/' . $shop->owner->seller->identityVerification->identity_card_image) }}" alt="Identity Card" class="w-48 h-auto object-cover rounded-lg border border-gray-200">
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection