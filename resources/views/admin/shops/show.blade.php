@extends('layouts.admin')

@section('title', 'Chi Tiết Cửa Hàng')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 min-h-screen">
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
    @php
        $status = $shop->shop_status instanceof \App\Enums\ShopStatus ? $shop->shop_status->value : $shop->shop_status;
    @endphp
    <div class="bg-white rounded-xl p-4 mb-6 shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-gray-600">Trạng thái:</span>
                @if($status == 'active')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-2"></i>Đang hoạt động
                    </span>
                @elseif($status == 'inactive')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-2"></i>Chờ duyệt
                    </span>
                @elseif($status == 'banned')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                        <i class="fas fa-ban mr-2"></i>Đã cấm
                    </span>
                @elseif($status == 'suspended')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-orange-100 text-orange-800">
                        <i class="fas fa-pause mr-2"></i>Tạm ngưng
                    </span>
                @else
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                        <i class="fas fa-question-circle mr-2"></i>Không xác định ({{ $status ?? 'null' }})
                    </span>
                @endif
            </div>
            <div class="text-sm text-gray-500">
                Đăng ký: {{ $shop->created_at->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    @if($shop->shop_status && $shop->shop_status->value == 'inactive')
        <div class="bg-white rounded-xl p-6 mb-6 shadow-md">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-tasks text-blue-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Hành động duyệt</h3>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Approve as Active -->
                <form action="{{ route('admin.shops.approve', $shop) }}" method="POST" class="flex-1" id="approveForm">
                    @csrf
                    <input type="hidden" name="approval_type" value="active" id="approval_type">
                    
                    @if ($errors->has('approval_type'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <strong>Lỗi:</strong> {{ $errors->first('approval_type') }}
                        </div>
                    @endif
                    
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-75 transition-colors duration-200 flex items-center justify-center gap-2"
                            onclick="return confirm('Bạn có chắc muốn duyệt cửa hàng này để hoạt động?')">
                        <i class="fas fa-check"></i>
                        Duyệt để hoạt động
                    </button>
                </form>
                
                <!-- Reject -->
                <button type="button" 
                        data-shop-id="{{ $shop->id }}" 
                        data-shop-name="{{ $shop->shop_name }}" 
                        class="flex-1 px-6 py-3 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-75 transition-colors duration-200 flex items-center justify-center gap-2 reject-shop-btn">
                    <i class="fas fa-times"></i>
                    Từ chối
                </button>
            </div>
        </div>
    @endif

    <!-- Action for Suspended Shops -->
    @if($shop->shop_status && $shop->shop_status->value == 'suspended')
        <div class="bg-white rounded-xl p-6 mb-6 shadow-md">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                    <i class="fas fa-play text-orange-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Kích hoạt cửa hàng</h3>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <form action="{{ route('admin.shops.activate', $shop) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-75 transition-colors duration-200 flex items-center justify-center gap-2"
                            onclick="return confirm('Bạn có chắc muốn kích hoạt cửa hàng này?')">
                        <i class="fas fa-play"></i>
                        Kích hoạt cửa hàng
                    </button>
                </form>
            </div>
        </div>
    @endif

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
                            <p class="text-gray-800">{{ $shop->shop_phone ?: 'Chưa cập nhật' }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <!-- Đã xóa Đánh giá, Tổng doanh thu, Số sản phẩm -->
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
                                <p class="text-gray-800">{{ $shop->shopAddress->shop_ward ?: 'Chưa cập nhật' }}</p>
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
            {{-- Đã xóa hoàn toàn phần hiển thị tùy chọn vận chuyển và mọi liên quan đến shopShippingOptions --}}

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
                            @php 
                                $businessType = $shop->owner->seller->businessLicense->business_type;
                                $businessTypeLabels = [
                                    'individual' => 'Cá nhân / Kinh doanh cá nhân',
                                    'household' => 'Hộ kinh doanh',
                                    'company' => 'Công ty / Doanh nghiệp'
                                ];
                            @endphp
                            <p class="text-gray-800 font-medium">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                    {{ $businessTypeLabels[$businessType] ?? ucfirst($businessType) }}
                                </span>
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Số giấy phép</label>
                                <p class="text-gray-800 font-mono">{{ $shop->owner->seller->businessLicense->business_license_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Mã số thuế</label>
                                <p class="text-gray-800 font-mono">{{ $shop->owner->seller->businessLicense->tax_number }}</p>
                            </div>
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
                        
                        <!-- Hiển thị ảnh giấy phép nếu có -->
                        @if($shop->owner->seller->businessLicense->license_file_path && $businessType !== 'individual')
                            @php
                                $licenseFiles = json_decode($shop->owner->seller->businessLicense->license_file_path, true);
                            @endphp
                            @if($licenseFiles && isset($licenseFiles['front']) && isset($licenseFiles['back']))
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-600 mb-2">Ảnh giấy phép kinh doanh</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500 mb-1">Mặt trước</label>
                                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                                <img src="{{ asset('storage/' . $licenseFiles['front']) }}" 
                                                     alt="Mặt trước giấy phép" 
                                                     class="w-full h-48 object-cover cursor-pointer hover:opacity-90 transition-opacity"
                                                     data-image="{{ asset('storage/' . $licenseFiles['front']) }}"
                                                     data-title="Mặt trước giấy phép"
                                                     onclick="openImageModal(this.dataset.image, this.dataset.title)">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500 mb-1">Mặt sau</label>
                                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                                <img src="{{ asset('storage/' . $licenseFiles['back']) }}" 
                                                     alt="Mặt sau giấy phép" 
                                                     class="w-full h-48 object-cover cursor-pointer hover:opacity-90 transition-opacity"
                                                     data-image="{{ asset('storage/' . $licenseFiles['back']) }}"
                                                     data-title="Mặt sau giấy phép"
                                                     onclick="openImageModal(this.dataset.image, this.dataset.title)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Trạng thái</label>
                                @php $licenseStatus = $shop->owner->seller->businessLicense->status; @endphp
                                @if($licenseStatus == 'active')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Hiệu lực
                                    </span>
                                @elseif($licenseStatus == 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Chờ duyệt
                                    </span>
                                @elseif($licenseStatus == 'expired')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>Hết hạn
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <i class="fas fa-question mr-1"></i>{{ ucfirst($licenseStatus) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Identity Verification Card -->
            @php
                $identityVerification = \App\Models\IdentityVerification::where('shop_id', $shop->id)->first();
            @endphp
            @if($identityVerification)
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
                            <p class="text-gray-800 font-medium">{{ $identityVerification->full_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Số CMND/CCCD</label>
                            <p class="text-gray-800 font-mono">{{ $identityVerification->identity_number }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Ngày sinh</label>
                                <p class="text-gray-800">{{ $identityVerification->birth_date->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Quốc tịch</label>
                                <p class="text-gray-800">{{ $identityVerification->nationality }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Quê quán</label>
                            <p class="text-gray-800">{{ $identityVerification->hometown }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Nơi cư trú</label>
                            <p class="text-gray-800">{{ $identityVerification->residence }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Loại giấy tờ</label>
                                <p class="text-gray-800">{{ strtoupper($identityVerification->identity_type) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Ngày cấp</label>
                                <p class="text-gray-800">{{ $identityVerification->identity_card_date->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Nơi cấp</label>
                            <p class="text-gray-800">{{ $identityVerification->identity_card_place }}</p>
                        </div>
                        
                        @if($identityVerification->identity_card_image)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">Ảnh CMND/CCCD</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if($identityVerification->identity_card_image)
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <label class="block text-sm font-medium text-gray-600 mb-2">Mặt trước</label>
                                            <img src="{{ asset('storage/' . $identityVerification->identity_card_image) }}" 
                                                 alt="Identity Card Front" 
                                                 class="w-full h-auto object-cover rounded-lg border border-gray-200 shadow-sm">
                                        </div>
                                    @endif
                                    @if($identityVerification->identity_card_holding_image)
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <label class="block text-sm font-medium text-gray-600 mb-2">Mặt sau</label>
                                            <img src="{{ asset('storage/' . $identityVerification->identity_card_holding_image) }}" 
                                                 alt="Identity Card Back" 
                                                 class="w-full h-auto object-cover rounded-lg border border-gray-200 shadow-sm">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">Ảnh CMND/CCCD</label>
                                <div class="bg-gray-50 p-4 rounded-lg text-gray-500 italic">Chưa cập nhật ảnh</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <div class="mt-2 text-center">
                <h3 class="text-lg font-medium text-gray-900">Từ chối cửa hàng</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Bạn sắp từ chối cửa hàng: <span id="shopNameReject" class="font-semibold text-gray-700"></span>
                    </p>
                </div>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mt-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Lý do từ chối:</label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                              placeholder="Nhập lý do từ chối cửa hàng..." required></textarea>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-75">
                        <i class="fas fa-times mr-2"></i>Gửi từ chối
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('rejectModal').classList.add('hidden')" 
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-75">
                        Hủy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 max-w-4xl">
        <div class="bg-white rounded-lg shadow-xl">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900" id="imageModalTitle">Xem ảnh</h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-4">
                <img id="imageModalImage" src="" alt="Image" class="w-full h-auto max-h-96 object-contain mx-auto">
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.querySelectorAll('.reject-shop-btn').forEach(button => {
        button.addEventListener('click', function() {
            const shopId = this.dataset.shopId;
            const shopName = this.dataset.shopName;
            showRejectModal(shopId, shopName);
        });
    });

    function showRejectModal(shopId, shopName) {
        const modal = document.getElementById('rejectModal');
        const shopNameSpan = document.getElementById('shopNameReject');
        const rejectForm = document.getElementById('rejectForm');
        
        shopNameSpan.textContent = shopName;
        rejectForm.action = '/admin/shops/' + shopId + '/reject';
        modal.classList.remove('hidden');
    }

    function openImageModal(imageUrl, title) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('imageModalImage');
        const modalTitle = document.getElementById('imageModalTitle');

        modalImage.src = imageUrl;
        modalTitle.textContent = title;
        modal.classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }
</script>
@endsection