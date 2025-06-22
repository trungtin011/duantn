@extends('layouts.app')

@section('title', 'Thanh toán')
@section('content')
    <div class="container mx-auto px-[20px] md:px-0 md:py-8 md:mb-[200px]">
        <!-- Breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 my-10 px-[10px] sm:px-0 md:my-20 text-sm md:text-base">
            <span>Tài khoản</span>
            <span class="text-gray-300">/</span>
            <span>Tài khoản của tôi</span>
            <span class="text-gray-300">/</span>
            <span>Sản phẩm</span>
            <span class="text-gray-300">/</span>
            <span>Xem giỏ hàng</span>
            <span class="text-gray-300">/</span>
            <span class="text-black">Thanh toán</span>
        </div>

        <!-- Tiêu đề -->
        <div class="mb-6">
            <h1 class="text-[36px] font-bold text-gray-800">Chi tiết hóa đơn</h1>
        </div>

        <!-- Main Container -->
        <div class="flex flex-col lg:flex-row gap-[100px]">
            <!-- Thông tin người nhận -->
            <div class="w-full lg:w-1/2">
                <!-- Chọn địa chỉ -->
                <div class="mb-10">
                    <h2 class="text-lg font-semibold mb-2">Chọn địa chỉ</h2>
                    <select name="address"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">51 Nguyễn Sinh Sắc, P.Tân Hoà, Thành phố Buôn Ma Thuột, ...</option>
                        <option value="2">26 Nguyễn Chánh, P.Tân Phong, Quận 7, Hồ Chí Minh</option>
                        <option value="3">123 Nguyễn Văn Cừ, P.4, Quận 5, Hồ Chí Minh</option>
                    </select>
                    <!-- Nút Thêm địa chỉ -->
                    <button type="button" id="showAddressForm"
                        class="mt-4 w-full text-[14px] sm:w-auto px-4 py-2 hover:bg-[#F5F5F5]"
                        style="border: 1px dashed #000; border-radius: 4px;">
                        <i class="fa fa-plus"></i> Thêm địa chỉ
                    </button>
                </div>

                <!-- Form thêm địa chỉ mới -->
                <div class="create-address-form mt-6 hidden mb-10">
                    <form action="/create-address-form" method="POST" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Tên Người Nhận <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                    class="mt-1 w-full bg-[#F5F5F5] border-none rounded-md p-2 focus:outline-none focus:none" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Số điện thoại <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                    class="mt-1 w-full bg-[#F5F5F5] border-none rounded-md p-2 focus:outline-none focus:none" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Loại Địa chỉ <span class="text-red-500">*</span>
                            </label>
                            <select name="address-type"
                                class="w-full border bg-[#F5F5F5] rounded-md p-2 focus:outline-none focus:none">
                                <option value="home">Nhà</option>
                                <option value="office">Văn phòng</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Thành Phố, Tỉnh <span class="text-red-500">*</span>
                                </label>
                                <select name="city" id="city"
                                    class="w-full border bg-[#F5F5F5] rounded-md p-2 focus:outline-none focus:none">
                                    <option value="">Chọn Tỉnh/Thành phố</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Quận, Huyện <span class="text-red-500">*</span>
                                </label>
                                <select name="district" id="district"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:none"
                                    disabled>
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Phường, Xã <span class="text-red-500">*</span>
                                </label>
                                <select name="ward" id="ward"
                                    class="w-full border bg-[#F5F5F5] rounded-md p-2 focus:outline-none focus:none"
                                    disabled>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Địa chỉ cụ thể <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="street"
                                    class="mt-1 w-full bg-[#F5F5F5] border-none rounded-md p-2 focus:outline-none focus:none" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Mã bưu điện
                            </label>
                            <input type="text"
                                class="mt-1 w-full bg-[#F5F5F5] border-none rounded-md p-2 focus:outline-none focus:none" />
                        </div>

                        <label class="flex items-center space-x-2">
                            <input type="checkbox"
                                class="h-4 w-4 text-blue-500 focus:ring-blue-500 border-gray-300 rounded" />
                            <span class="text-sm text-gray-700">Đặt làm địa chỉ mặc định</span>
                        </label>

                        <button type="button"
                            class="mt-4 w-full sm:w-auto bg-black text-white px-4 py-2 hover:bg-transparent hover:text-black hover:border hover:border-[#000]">
                            Thêm địa chỉ
                        </button>
                    </form>
                </div>

                <!-- Form thông tin người nhận -->
                <form action="/submit-address" method="POST" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                class="mt-1 w-full bg-[#F5F5F5] border-none rounded-md p-2 focus:outline-none focus:none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Tên công ty
                            </label>
                            <input type="text"
                                class="mt-1 w-full bg-[#F5F5F5] border-none rounded-md p-2 focus:outline-none focus:none" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Địa chỉ <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            class="mt-1 w-full bg-[#F5F5F5] border-none rounded-md p-2 focus:outline-none focus:none" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Căn hộ, tòa nhà, v.v. (tùy chọn)
                        </label>
                        <input type="text"
                            class="mt-1 w-full bg-[#F5F5F5] border-none rounded-md p-2 focus:outline-none focus:none" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Thành phố/Thị trấn/Phố <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                class="mt-1 w-full bg-[#F5F5F5] border-none rounded-md p-2 focus:outline-none focus:none" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                class="mt-1 w-full bg-[#F5F5F5] border-none rounded-md p-2 focus:outline-none focus:none" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Địa chỉ Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                            class="mt-1 w-full bg-[#F5F5F5] border-none rounded-md p-2 focus:outline-none focus:none" />
                    </div>

                    <label class="flex items-center space-x-2">
                        <input type="checkbox"
                            class="h-4 w-4 text-blue-500 focus:ring-blue-500 border-gray-300 rounded" />
                        <span class="text-sm text-gray-700">Lưu thông tin này để thanh toán nhanh hơn trong lần sau</span>
                    </label>
                </form>
            </div>

            <!-- Danh sách sản phẩm và thanh toán -->
            <div class="w-full lg:w-1/2">
                <!-- Danh sách sản phẩm -->
                <div class="space-y-4 mb-6">
                    <div class="flex items-center space-x-4">
                        <img src="https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-05-27/eyX7CyV5Jm.png"
                            alt="Product Image" class="w-16 h-16 object-cover rounded-md" />
                        <div class="flex-1">
                            <span class="block text-sm font-medium">LCD Monitor</span>
                            <span class="text-sm text-gray-500">x1</span>
                        </div>
                        <span class="text-sm font-semibold">16.250.000 VND</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <img src="https://codia-f2c.s3.us-west-1.amazonaws.com/image/2025-05-27/eyX7CyV5Jm.png"
                            alt="Product Image" class="w-16 h-16 object-cover rounded-md" />
                        <div class="flex-1">
                            <span class="block text-sm font-medium">H1 Gamepad</span>
                            <span class="text-sm text-gray-500">x1</span>
                        </div>
                        <span class="text-sm font-semibold">27.500.000 VND</span>
                    </div>
                </div>

                <!-- Tóm tắt đơn hàng -->
                <div class="border-t pt-4 mb-6">
                    <h2 class="text-lg font-semibold mb-2">Tóm tắt đơn hàng</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>Tổng cộng:</span>
                            <span class="font-semibold">43.750.000 VND</span>
                        </div>
                        <div class="border-t my-2"></div>
                        <div class="flex justify-between text-sm">
                            <span>Phí vận chuyển:</span>
                            <span class="font-semibold">Miễn phí</span>
                        </div>
                        <div class="border-t my-2"></div>
                        <div class="flex justify-between text-lg font-bold">
                            <span>Tổng đơn:</span>
                            <span>43.750.000 VND</span>
                        </div>
                    </div>
                </div>

                <!-- Phương thức thanh toán -->
                <form class="space-y-4 mb-6">
                    <label class="flex items-center space-x-3">
                        <input type="radio" name="payment" value="bank"
                            class="h-4 w-4" />
                        <span class="text-sm">Thanh toán bằng ngân hàng</span>
                        <div class="flex space-x-2">
                            <img src="{{ asset('images/image 31.png') }}" alt="Mastercard Icon" class="w-[39px]" />
                        </div>
                        <div class="flex space-x-2">
                            <img src="{{ asset('images/image 30.png') }}" alt="Visa Icon" class="w-[38px]" />
                        </div>
                    </label>
                    <label class="flex items-center space-x-3">
                        <input type="radio" name="payment" value="cash"
                            class="h-4 w-4" />
                        <span class="text-sm">Thanh toán bằng tiền mặt</span>
                    </label>
                </form>

                <div class="flex flex-col lg:flex-row gap-[20px] justify-between">
                    <div class="flex">
                        <!-- Mã giảm giá -->
                        <form action="" class="flex">
                            <input type="text" placeholder="Mã giảm giá"
                                class="flex-1 border border-[#000] p-2 focus:outline-none focus:none" />
                            <button
                                class="w-[100px] bg-black text-white border border-[#000] px-4 py-2 hover:bg-transparent hover:text-black hover:border hover:border-[#000]">
                                Áp dụng
                            </button>
                        </form>
                    </div>
                    <div class="flex justify-start">
                        <!-- Nút đặt hàng -->
                        <button type="submit" class="bg-[#F1416C] text-white px-4 py-3 hover:bg-pink-600">
                            Đặt hàng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showAddressFormBtn = document.getElementById('showAddressForm');
            const createAddressForm = document.querySelector('.create-address-form');

            showAddressFormBtn.addEventListener('click', function() {
                // Kiểm tra trạng thái hiển thị thực tế của form
                const isHidden = window.getComputedStyle(createAddressForm).display === 'none';

                // Thay đổi trạng thái hiển thị của form
                createAddressForm.style.display = isHidden ? 'block' : 'none';

                // Thay đổi nội dung và biểu tượng của nút
                if (isHidden) {
                    // Form hiển thị -> đổi nút thành "Đóng"
                    showAddressFormBtn.innerHTML = '<i class="fa fa-times"></i> Đóng';
                } else {
                    // Form ẩn -> đổi nút thành "Thêm địa chỉ"
                    showAddressFormBtn.innerHTML = '<i class="fa fa-plus"></i> Thêm địa chỉ';
                }
            });

            const citySelect = document.getElementById('city');
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');

            const API_URL = 'https://provinces.open-api.vn/api/';

            async function fetchCities() {
                try {
                    const response = await fetch(API_URL + '?depth=1');
                    const cities = await response.json();

                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.code;
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error fetching cities:', error);
                }
            }

            async function fetchDistricts(cityCode) {
                try {
                    const response = await fetch(API_URL + `p/${cityCode}?depth=2`);
                    const city = await response.json();

                    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';

                    city.districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.code;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });

                    districtSelect.disabled = false;
                } catch (error) {
                    console.error('Error fetching districts:', error);
                }
            }

            async function fetchWards(districtCode) {
                try {
                    const response = await fetch(API_URL + `d/${districtCode}?depth=2`);
                    const district = await response.json();

                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';

                    district.wards.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.code;
                        option.textContent = ward.name;
                        wardSelect.appendChild(option);
                    });

                    wardSelect.disabled = false;
                } catch (error) {
                    console.error('Error fetching wards:', error);
                }
            }

            citySelect.addEventListener('change', function() {
                if (this.value) {
                    fetchDistricts(this.value);
                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    wardSelect.disabled = true;
                } else {
                    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                    districtSelect.disabled = true;
                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    wardSelect.disabled = true;
                }
            });

            districtSelect.addEventListener('change', function() {
                if (this.value) {
                    fetchWards(this.value);
                } else {
                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    wardSelect.disabled = true;
                }
            });

            fetchCities();
        });
    </script>
@endpush
