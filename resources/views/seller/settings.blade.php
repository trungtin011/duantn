@extends('layouts.seller_home')
@section('title', 'Cài đặt cửa hàng')
@section('content')
    <div class="flex-1 space-y-6 overflow-y-auto">
        <div class="w-full mx-0">
            <div class="admin-page-header mb-5">
                <h1 class="admin-page-title text-2xl">Cài đặt cửa hàng</h1>
                <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Trang chủ</a> / Cập nhật thông
                    tin cửa
                </div>
            </div>

            @include('seller.partials.account_submenu')

            <form action="{{ route('seller.settings') }}" method="POST" enctype="multipart/form-data"
                class="bg-white rounded-lg p-4 shadow-sm">
                @csrf
                @method('POST')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Cột trái: thông tin & địa chỉ & trạng thái -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Thông tin cơ bản -->
                        <section class="space-y-4">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-600 font-medium mb-1">Tên cửa hàng <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="shop_name" required
                                        class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        value="{{ old('shop_name', $shop->shop_name ?? '') }}">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-600 font-medium mb-1">Số điện thoại</label>
                                        <input type="text" name="shop_phone"
                                            class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            value="{{ old('shop_phone', $shop->shop_phone ?? '') }}">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-600 font-medium mb-1">Email cửa hàng</label>
                                        <input type="email" name="shop_email"
                                            class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            value="{{ old('shop_email', $shop->shop_email ?? '') }}">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 font-medium mb-1">Mô tả cửa hàng</label>
                                    <textarea name="shop_description" rows="3"
                                        class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('shop_description', $shop->shop_description ?? '') }}</textarea>
                                </div>
                            </div>
                        </section>

                        <!-- Địa chỉ cửa hàng -->
                        <section class="space-y-4">
                            <h3 class="text-sm font-semibold text-gray-700">Địa chỉ cửa hàng</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs text-gray-600 font-medium mb-1">Địa chỉ (số nhà,
                                        đường...)</label>
                                    <input type="text" name="shop_address"
                                        class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        value="{{ old('shop_address', $address->shop_address ?? '') }}">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 font-medium mb-1">Tỉnh/Thành</label>
                                    <select id="business_province" name="shop_province"
                                        class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="" disabled selected>Chọn tỉnh/thành</option>
                                    </select>
                                    <input type="hidden" id="business_province_name" name="shop_province_name" value="{{ old('shop_province_name', $address->shop_province ?? '') }}">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 font-medium mb-1">Quận/Huyện</label>
                                    <select id="business_district" name="shop_district"
                                        class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="" disabled selected>Chọn quận/huyện</option>
                                    </select>
                                    <input type="hidden" id="business_district_name" name="shop_district_name" value="{{ old('shop_district_name', $address->shop_district ?? '') }}">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 font-medium mb-1">Phường/Xã</label>
                                    <select id="business_ward" name="shop_ward"
                                        class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="" disabled selected>Chọn phường/xã</option>
                                    </select>
                                    <input type="hidden" id="business_ward_name" name="shop_ward_name" value="{{ old('shop_ward_name', $address->shop_ward ?? '') }}">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs text-gray-600 font-medium mb-1">Ghi chú</label>
                                    <textarea name="address_note" rows="2"
                                        class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address_note', $address->note ?? '') }}</textarea>
                                </div>
                            </div>
                        </section>

                        <!-- Trạng thái -->
                        <section class="space-y-3">
                            <div>
                                <label class="block text-xs text-gray-600 font-medium mb-1">Trạng thái</label>
                                <select name="shop_status"
                                    class="text-xs text-gray-700 border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="active" @if (old('shop_status', $shop->shop_status ?? '') == 'active') selected @endif>Đang hoạt động
                                    </option>
                                    <option value="inactive" @if (old('shop_status', $shop->shop_status ?? '') == 'inactive') selected @endif>Ngừng hoạt
                                        động
                                    </option>
                                </select>
                            </div>
                        </section>
                    </div>

                    <!-- Cột phải: Logo/Banner -->
                    <div class="space-y-4">
                        <div class="border rounded-md p-3">
                            <label class="block text-xs text-gray-600 font-medium mb-2">Logo cửa hàng</label>
                            <input id="shop_logo" type="file" name="shop_logo" accept="image/*" class="hidden">
                            @if (!empty($shop->shop_logo))
                                <div id="logo_preview" class="mt-2 border rounded-md p-2 bg-gray-50 flex items-center justify-center">
                                    <img src="{{ asset('storage/' . $shop->shop_logo) }}" alt="Logo"
                                        class="max-h-28 rounded object-contain">
                                </div>
                                <label for="shop_logo" class="mt-2 inline-flex items-center text-xs text-blue-600 rounded cursor-pointer hover:underline hover:text-blue-600">Chỉnh sửa logo</label>
                            @else
                                <label id="logo_placeholder" for="shop_logo" class="mt-1 block border-2 border-dashed border-gray-300 rounded-md p-6 text-center cursor-pointer hover:bg-gray-50">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fas fa-cloud-upload-alt text-2xl mb-2"></i>
                                        <div class="text-sm"><span class="text-blue-600 font-medium">Click để tải lên</span> hoặc kéo thả</div>
                                        <div class="text-xs mt-1">PNG, JPG, JPEG (Tối đa 2MB)</div>
                                    </div>
                                </label>
                            @endif
                        </div>
                        <div class="border rounded-md p-3">
                            <label class="block text-xs text-gray-600 font-medium mb-2">Banner cửa hàng</label>
                            <input id="shop_banner" type="file" name="shop_banner" accept="image/*" class="hidden">
                            @if (!empty($shop->shop_banner))
                                <div id="banner_preview" class="mt-2 border rounded-md p-2 bg-gray-50">
                                    <img src="{{ asset('storage/' . $shop->shop_banner) }}" alt="Banner"
                                        class="rounded object-cover w-full max-h-40">
                                </div>
                                <label for="shop_banner" class="mt-2 inline-flex items-center text-xs text-blue-600 rounded cursor-pointer hover:underline hover:text-blue-600">Chỉnh sửa banner</label>
                            @else
                                <label id="banner_placeholder" for="shop_banner" class="mt-1 block border-2 border-dashed border-gray-300 rounded-md p-6 text-center cursor-pointer hover:bg-gray-50">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fas fa-cloud-upload-alt text-2xl mb-2"></i>
                                        <div class="text-sm"><span class="text-blue-600 font-medium">Click để tải lên</span> hoặc kéo thả</div>
                                        <div class="text-xs mt-1">PNG, JPG, JPEG (Tối đa 4MB)</div>
                                    </div>
                                </label>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Actions -->
                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="inline-flex items-center text-xs bg-[#f42f46] hover:bg-[#d62a3e] text-white px-5 py-2 rounded-md shadow-sm transition-colors">
                        <i class="fas fa-save mr-2"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('business_province');
            const districtSelect = document.getElementById('business_district');
            const wardSelect = document.getElementById('business_ward');
            const provinceNameInput = document.getElementById('business_province_name');
            const districtNameInput = document.getElementById('business_district_name');
            const wardNameInput = document.getElementById('business_ward_name');

            // Lấy giá trị hiện tại từ session hoặc database
            const currentProvince = @json(old('shop_province', $address->shop_province ?? ''));
            const currentDistrict = @json(old('shop_district', $address->shop_district ?? ''));
            const currentWard = @json(old('shop_ward', $address->shop_ward ?? ''));

            function showLoading(select, text = 'Đang tải...') {
                select.innerHTML = `<option value="" disabled selected>${text}</option>`;
                select.disabled = true;
            }

            function doneLoading(select, placeholder) {
                select.disabled = false;
                if (placeholder) {
                    select.innerHTML = `<option value="" disabled selected>${placeholder}</option>`;
                }
            }

            function showError(message) {
                const div = document.createElement('div');
                div.className = 'mt-2 text-sm text-red-600';
                div.textContent = message;
                provinceSelect.parentNode.appendChild(div);
                setTimeout(() => div.remove(), 5000);
            }

            // Load Provinces (VNPost -> fallback)
            function loadProvinces() {
                showLoading(provinceSelect, 'Đang tải tỉnh/thành...');
                fetch('https://api.vnpost.vn/api/v1/province')
                    .then(r => r.json())
                    .then(data => {
                        if (data.success && data.data) {
                            doneLoading(provinceSelect, 'Chọn tỉnh/thành');
                            data.data.forEach(p => {
                                const opt = document.createElement('option');
                                opt.value = p.provinceCode;
                                opt.textContent = p.provinceName;
                                // Preselect nếu có giá trị hiện tại
                                if (opt.value == currentProvince || opt.textContent == currentProvince) {
                                    opt.selected = true;
                                }
                                provinceSelect.appendChild(opt);
                            });
                        } else {
                            throw new Error('VNPost provinces failed');
                        }
                    })
                    .catch(() => {
                        // Fallback
                        fetch('https://provinces.open-api.vn/api/p/')
                            .then(r => r.json())
                            .then(list => {
                                doneLoading(provinceSelect, 'Chọn tỉnh/thành');
                                list.forEach(p => {
                                    const opt = document.createElement('option');
                                    opt.value = p.code;
                                    opt.textContent = p.name;
                                    // Preselect nếu có giá trị hiện tại
                                    if (opt.value == currentProvince || opt.textContent == currentProvince) {
                                        opt.selected = true;
                                    }
                                    provinceSelect.appendChild(opt);
                                });
                            })
                            .catch(() => showError('Không thể tải danh sách tỉnh/thành'));
                    })
                    .finally(() => {
                        if (provinceSelect.value) {
                            provinceNameInput.value = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';
                            provinceSelect.dispatchEvent(new Event('change'));
                        }
                    });
            }

            // Load Districts by Province
            function loadDistricts(provinceCode) {
                showLoading(districtSelect, 'Đang tải quận/huyện...');
                wardSelect.innerHTML = '<option value="" disabled selected>Chọn phường/xã</option>';
                wardSelect.disabled = true;

                fetch(`https://api.vnpost.vn/api/v1/district?provinceCode=${provinceCode}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.success && data.data) {
                            doneLoading(districtSelect, 'Chọn quận/huyện');
                            data.data.forEach(d => {
                                const opt = document.createElement('option');
                                opt.value = d.districtCode;
                                opt.textContent = d.districtName;
                                // Preselect nếu có giá trị hiện tại
                                if (opt.value == currentDistrict || opt.textContent == currentDistrict) {
                                    opt.selected = true;
                                }
                                districtSelect.appendChild(opt);
                            });
                        } else {
                            throw new Error('VNPost districts failed');
                        }
                    })
                    .catch(() => {
                        // Fallback
                        fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                            .then(r => r.json())
                            .then(p => {
                                doneLoading(districtSelect, 'Chọn quận/huyện');
                                p.districts.forEach(d => {
                                    const opt = document.createElement('option');
                                    opt.value = d.code;
                                    opt.textContent = d.name;
                                    // Preselect nếu có giá trị hiện tại
                                    if (opt.value == currentDistrict || opt.textContent == currentDistrict) {
                                        opt.selected = true;
                                    }
                                    districtSelect.appendChild(opt);
                                });
                            })
                            .catch(() => showError('Không thể tải danh sách quận/huyện'));
                    })
                    .finally(() => {
                        if (districtSelect.value) {
                            districtNameInput.value = districtSelect.options[districtSelect.selectedIndex]?.text || '';
                            districtSelect.dispatchEvent(new Event('change'));
                        }
                    });
            }

            // Load Wards by District
            function loadWards(districtCode) {
                showLoading(wardSelect, 'Đang tải phường/xã...');
                fetch(`https://api.vnpost.vn/api/v1/ward?districtCode=${districtCode}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.success && data.data) {
                            doneLoading(wardSelect, 'Chọn phường/xã');
                            data.data.forEach(w => {
                                const opt = document.createElement('option');
                                opt.value = w.wardCode;
                                opt.textContent = w.wardName;
                                // Preselect nếu có giá trị hiện tại
                                if (opt.value == currentWard || opt.textContent == currentWard) {
                                    opt.selected = true;
                                }
                                wardSelect.appendChild(opt);
                            });
                        } else {
                            throw new Error('VNPost wards failed');
                        }
                    })
                    .catch(() => {
                        // Fallback
                        fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                            .then(r => r.json())
                            .then(d => {
                                doneLoading(wardSelect, 'Chọn phường/xã');
                                d.wards.forEach(w => {
                                    const opt = document.createElement('option');
                                    opt.value = w.code;
                                    opt.textContent = w.name;
                                    // Preselect nếu có giá trị hiện tại
                                    if (opt.value == currentWard || opt.textContent == currentWard) {
                                        opt.selected = true;
                                    }
                                    wardSelect.appendChild(opt);
                                });
                            })
                            .catch(() => showError('Không thể tải danh sách phường/xã'));
                    });
            }

            // Events
            provinceSelect.addEventListener('change', function() {
                const code = this.value;
                // lưu tên
                provinceNameInput.value = this.options[this.selectedIndex]?.text || '';
                districtSelect.innerHTML = '<option value="" disabled selected>Chọn quận/huyện</option>';
                wardSelect.innerHTML = '<option value="" disabled selected>Chọn phường/xã</option>';
                districtSelect.disabled = false;
                loadDistricts(code);
            });

            districtSelect.addEventListener('change', function() {
                const code = this.value;
                // lưu tên
                districtNameInput.value = this.options[this.selectedIndex]?.text || '';
                wardSelect.disabled = false;
                loadWards(code);
            });

            wardSelect.addEventListener('change', function() {
                // lưu tên
                wardNameInput.value = this.options[this.selectedIndex]?.text || '';
            });

            // Init
            loadProvinces();

            // Nếu đã có giá trị cũ thì điền tên tương ứng lần đầu tải
            const setInitialNames = () => {
                if (provinceSelect.value) {
                    provinceNameInput.value = provinceSelect.options[provinceSelect.selectedIndex]?.text || provinceNameInput.value;
                }
                if (districtSelect.value) {
                    districtNameInput.value = districtSelect.options[districtSelect.selectedIndex]?.text || districtNameInput.value;
                }
                if (wardSelect.value) {
                    wardNameInput.value = wardSelect.options[wardSelect.selectedIndex]?.text || wardNameInput.value;
                }
            };
            // đợi danh sách load xong rồi set
            setTimeout(setInitialNames, 1000);
        });

        // Click placeholder to open file chooser always works via label[for],
        // but ensure preview when user selects a file
        (function() {
            const logoInput = document.getElementById('shop_logo');
            const bannerInput = document.getElementById('shop_banner');
            const logoPlaceholder = document.getElementById('logo_placeholder');
            const bannerPlaceholder = document.getElementById('banner_placeholder');
            const logoPreview = document.getElementById('logo_preview');
            const bannerPreview = document.getElementById('banner_preview');

            const makePreview = (file, containerId, maxHeight) => {
                const url = URL.createObjectURL(file);
                const container = document.getElementById(containerId);
                if (!container) return;
                container.innerHTML = '';
                const wrap = document.createElement('div');
                wrap.className = 'mt-2 border rounded-md p-2 bg-gray-50 flex items-center justify-center';
                const img = document.createElement('img');
                img.src = url;
                img.className = maxHeight ? `rounded object-contain` : 'rounded object-cover w-full';
                if (maxHeight) img.style.maxHeight = maxHeight;
                wrap.appendChild(img);
                container.appendChild(wrap);
            };

            if (logoInput) {
                logoInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        if (!logoPreview) {
                            const newDiv = document.createElement('div');
                            newDiv.id = 'logo_preview';
                            logoInput.parentElement.appendChild(newDiv);
                        }
                        makePreview(this.files[0], 'logo_preview', '7rem');
                    }
                });
            }
            if (bannerInput) {
                bannerInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        if (!bannerPreview) {
                            const newDiv = document.createElement('div');
                            newDiv.id = 'banner_preview';
                            bannerInput.parentElement.appendChild(newDiv);
                        }
                        makePreview(this.files[0], 'banner_preview');
                    }
                });
            }
        })();
    </script>
@endsection
