@extends('layouts.admin')

@section('title', 'Chi Tiết Cửa Hàng')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 bg-gray-100 min-h-screen">
    <!-- Header -->
    <div class="bg-white rounded-xl p-6 mb-6 shadow-md">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                    <i class="fas fa-store text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $shop->shop_name }}</h1>
                    <p class="text-gray-600">Chi tiết thông tin cửa hàng</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.shops.index') }}" class="px-4 py-2 bg-gray-600 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-75 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="bg-white rounded-xl p-4 mb-6 shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-gray-600">Trạng thái:</span>
                @if($shop->shop_status && $shop->shop_status->value == 'active')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-2"></i>Đang hoạt động
                    </span>
                @elseif($shop->shop_status && $shop->shop_status->value == 'inactive')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-2"></i>Chờ duyệt
                    </span>
                @elseif($shop->shop_status && $shop->shop_status->value == 'banned')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                        <i class="fas fa-ban mr-2"></i>Đã cấm
                    </span>
                @else
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                        <i class="fas fa-question-circle mr-2"></i>Không xác định ({{ $shop->shop_status ? $shop->shop_status->value : 'null' }})
                    </span>
                @endif
            </div>
            <div class="text-sm text-gray-500">
                Đăng ký: {{ $shop->created_at->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Main Shop Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Shop Information Card -->
            <div class="bg-white rounded-xl p-6 shadow-md">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Thông tin Cửa hàng</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Tên cửa hàng</label>
                            <p class="text-gray-800 font-medium">{{ $shop->shop_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                            <p class="text-gray-800">{{ $shop->shop_email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Số điện thoại</label>
                            <p class="text-gray-800">{{ $shop->shop_phone }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Đánh giá</label>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-star text-yellow-400"></i>
                                <span class="text-gray-800 font-medium">{{ number_format($shop->shop_rating, 1) }}</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Tổng doanh thu</label>
                            <p class="text-gray-800 font-medium">{{ number_format($shop->total_sales, 0, ',', '.') }} VNĐ</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Số sản phẩm</label>
                            <p class="text-gray-800">{{ $shop->total_products }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Mô tả</label>
                    <p class="text-gray-800 bg-gray-50 p-4 rounded-lg">{{ $shop->shop_description }}</p>
                </div>

                <!-- Shop Images -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($shop->shop_logo)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-600 mb-2">Logo cửa hàng</label>
                            <img src="{{ asset('storage/' . $shop->shop_logo) }}" alt="Shop Logo" 
                                 class="w-32 h-32 object-cover rounded-lg border border-gray-200 shadow-sm">
                        </div>
                    @endif
                    @if($shop->shop_banner)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-600 mb-2">Banner cửa hàng</label>
                            <img src="{{ asset('storage/' . $shop->shop_banner) }}" alt="Shop Banner" 
                                 class="w-full h-32 object-cover rounded-lg border border-gray-200 shadow-sm">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Shop Address Card -->
            @if($shop->shopAddress)
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Địa chỉ Cửa hàng</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Tỉnh/Thành phố</label>
                                <p class="text-gray-800">{{ $shop->shopAddress->shop_province }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Quận/Huyện</label>
                                <p class="text-gray-800">{{ $shop->shopAddress->shop_district }}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Phường/Xã</label>
                                <p class="text-gray-800">{{ $shop->shopAddress->shop_ward }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Địa chỉ chi tiết</label>
                                <p class="text-gray-800">{{ $shop->shopAddress->shop_address }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Additional Info -->
        <div class="space-y-6">
            <!-- Owner Information Card -->
            @if($shop->owner)
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-user text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Chủ sở hữu</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Họ và tên</label>
                            <p class="text-gray-800 font-medium">{{ $shop->owner->fullname ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                            <p class="text-gray-800">{{ $shop->owner->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Số điện thoại</label>
                            <p class="text-gray-800">{{ $shop->owner->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Shipping Options Card -->
            @if($shop->shopShippingOptions->isNotEmpty())
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-shipping-fast text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Tùy chọn Vận chuyển</h3>
                    </div>
                    
                    <div class="space-y-3">
                        @foreach($shop->shopShippingOptions as $option)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-truck text-gray-600"></i>
                                    <span class="text-gray-800 font-medium">{{ ucfirst($option->shipping_type) }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-600">COD:</span>
                                    @if($option->cod_enabled)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Bật
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>Tắt
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Business License Card -->
            @if($shop->owner && $shop->owner->seller && $shop->owner->seller->businessLicense)
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                            <i class="fas fa-file-contract text-orange-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Giấy phép Kinh doanh</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Loại hình kinh doanh</label>
                            <p class="text-gray-800">{{ $shop->owner->seller->businessLicense->business_type }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Mã số thuế</label>
                            <p class="text-gray-800 font-mono">{{ $shop->owner->seller->businessLicense->tax_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Email nhận hóa đơn</label>
                            <p class="text-gray-800">{{ $shop->owner->seller->businessLicense->invoice_email }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Ngày cấp</label>
                                <p class="text-gray-800">{{ $shop->owner->seller->businessLicense->business_license_date->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Ngày hết hạn</label>
                                <p class="text-gray-800">{{ $shop->owner->seller->businessLicense->expiry_date->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Trạng thái</label>
                            @if($shop->owner->seller->businessLicense->status == 'active')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Hiệu lực
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i>Hết hạn
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Identity Verification Card -->
            @if($shop->owner && $shop->owner->seller && $shop->owner->seller->identityVerification)
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <i class="fas fa-id-card text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Định danh Chủ Shop</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Họ và Tên</label>
                            <p class="text-gray-800 font-medium">{{ $shop->owner->seller->identityVerification->full_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Số CMND/CCCD</label>
                            <p class="text-gray-800 font-mono">{{ $shop->owner->seller->identityVerification->identity_number }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Ngày sinh</label>
                                <p class="text-gray-800">{{ $shop->owner->seller->identityVerification->birth_date->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Quốc tịch</label>
                                <p class="text-gray-800">{{ $shop->owner->seller->identityVerification->nationality }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Quê quán</label>
                            <p class="text-gray-800">{{ $shop->owner->seller->identityVerification->hometown }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Nơi cư trú</label>
                            <p class="text-gray-800">{{ $shop->owner->seller->identityVerification->residence }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Loại giấy tờ</label>
                                <p class="text-gray-800">{{ strtoupper($shop->owner->seller->identityVerification->identity_type) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Ngày cấp</label>
                                <p class="text-gray-800">{{ $shop->owner->seller->identityVerification->identity_card_date->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Nơi cấp</label>
                            <p class="text-gray-800">{{ $shop->owner->seller->identityVerification->identity_card_place }}</p>
                        </div>
                        
                        @if($shop->owner->seller->identityVerification->identity_card_image)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">Ảnh CMND/CCCD</label>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <img src="{{ asset('storage/' . $shop->owner->seller->identityVerification->identity_card_image) }}" 
                                         alt="Identity Card" 
                                         class="w-full h-auto object-cover rounded-lg border border-gray-200 shadow-sm">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection