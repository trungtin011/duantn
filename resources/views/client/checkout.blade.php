@extends('layouts.app')

@section('title', 'Thanh toán')
@section('content')
    <div class="container mx-auto px-[20px] md:px-0 md:py-0 md:mb-[200px]">
        <!-- Breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 my-10 px-[10px] sm:px-0 md:my-10 text-sm md:text-base">
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

        <div class="bg-white p-8 rounded-[4px]">
            <!-- Tiêu đề -->
            <div class="mb-6">
                <h1 class="text-[36px] font-bold text-gray-800">Chi tiết hóa đơn</h1>
            </div>
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('message') }}
                </div>
            @endif
            <!-- Main Container -->
            <div class="flex flex-col lg:flex-row gap-[100px]">

                <!-- Thông tin người nhận -->
                <div class="w-full lg:w-1/2">

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

                    <!-- Form thanh toán -->
                    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form"
                        class="space-y-4 mb-6 w-full">
                        @csrf
                        <!-- Chọn địa chỉ -->
                        <div class="mb-10">
                            <h2 class="text-lg font-semibold mb-2">Chọn địa chỉ</h2>
                            @if (isset($user_addresses) && $user_addresses->count() > 0)
                                <select name="address" id="address"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach ($user_addresses as $address)
                                        <option value="{{ $address->id }}">{{ $address->receiver_name }} -
                                            {{ $address->address }} - {{ $address->province }} - {{ $address->district }} -
                                            {{ $address->ward }} - {{ $address->zip_code }}</option>
                                    @endforeach
                                </select>
                            @else
                                <p>Không có địa chỉ nào</p>
                            @endif
                            <!-- Nút Thêm địa chỉ -->
                            <button type="button" id="showAddressForm"
                                class="mt-4 w-full text-[14px] sm:w-auto px-4 py-2 hover:bg-[#F5F5F5]"
                                style="border: 1px dashed #000; border-radius: 4px;">
                                <i class="fa fa-plus"></i> Thêm địa chỉ
                            </button>
                        </div>
                        <!-- Lời nhắn cho người bán -->
                        <div class="mb-10">
                            <input type="hidden" name="shop_notes" id="shop_notes" value="">
                        </div>
                        <!-- Phương thức thanh toán -->
                        <div class="space-y-4 mb-6">
                            <label class="flex items-center space-x-3">
                                <input type="radio" name="payment" value="MOMO" class="h-4 w-4" />
                                <span class="text-sm">Thanh toán bằng MOMO</span>
                                <div class="flex space-x-2">
                                    <img src="{{ 'https://pay2s.vn/blog/wp-content/uploads/2024/11/momo_icon_circle_pinkbg_RGB-1024x1024.png' }}"
                                        alt="Momo Icon" class="w-[39px]" />
                                </div>
                            </label><label class="flex items-center space-x-3">
                                <input type="radio" name="payment" value="VNPAY" class="h-4 w-4" />
                                <span class="text-sm">Thanh toán bằng vnpay</span>
                                <div class="flex space-x-2">
                                    <img src="{{ 'https://vinadesign.vn/uploads/images/2023/05/vnpay-logo-vinadesign-25-12-57-55.jpg' }}"
                                        alt="Momo Icon" class="w-[39px]" />
                                </div>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="radio" name="payment" value="COD" class="h-4 w-4" />
                                <span class="text-sm">Thanh toán bằng tiền mặt</span>
                            </label>
                        </div>
                    </form>

                    <!-- Form thêm địa chỉ mới -->
                </div>

                <!-- Danh sách sản phẩm và thanh toán -->
                <div class="w-full lg:w-1/2">
                    <!-- Danh sách sản phẩm -->
                    <div class="space-y-4 mb-6">
                        <!-- Nhóm sản phẩm theo shop -->
                        @php
                            // Nhóm items theo shopID
                            $itemsByShop = collect($items)->groupBy('product.shopID')->sortKeys();
                        @endphp

                        @foreach ($itemsByShop as $shopId => $shopItems)
                            <!-- Tiêu đề shop -->
                            <div class="border-b pb-2 mb-4">
                                <h3 class="text-lg font-semibold">
                                    Shop: {{ \App\Models\Shop::find($shopId)->name ?? 'Shop ' . $shopId }}
                                </h3>
                            </div>

                            <!-- Danh sách sản phẩm trong shop -->
                            <div class="space-y-4">
                                @foreach ($shopItems as $item)
                                    <div class="flex items-center space-x-4">
                                        <img src="{{ $item['product']->image ?? 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRGh5WFH8TOIfRKxUrIgJZoDCs1yvQ4hIcppw&s' }}"
                                            alt="Product Image" class="w-16 h-16 object-cover rounded-md" />
                                        <div class="flex-1">
                                            <span class="block text-sm font-medium">{{ $item['product']->name }}</span>
                                            <span class="text-sm text-gray-500">x{{ $item['quantity'] }}</span>
                                            <span class="text-sm text-gray-500">Loại:
                                                {{ $item['product']->variants->first()->variant_name ?? 'N/A' }}</span>
                                        </div>
                                        <span class="text-sm font-semibold">{{ number_format($item['product']->price) }}
                                            VND</span>
                                    </div>
                                @endforeach
                                <textarea name="note_for_shop[{{ $shopId }}][{{ $item['product']->id }}]"
                                    id="note-{{ $shopId }}-{{ $item['product']->id }}"
                                    class="w-full h-[40px] border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Lời nhắn cho người bán" data-shop-id="{{ $shopId }}">
                                </textarea>
                            </div>
                        @endforeach
                    </div>

                    <!-- Tóm tắt đơn hàng -->
                    <div class="border-t pt-4 mb-6">
                        <h2 class="text-lg font-semibold mb-2">Tóm tắt đơn hàng</h2>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Tổng cộng:</span>
                                <span
                                    class="font-semibold">{{ number_format($subtotal = array_sum(array_column($items, 'total_price'))) }}
                                    VND</span>
                            </div>
                            <div class="border-t my-2"></div>
                            <div class="flex justify-between text-sm">
                                <span>Phí vận chuyển:</span>
                                <span class="font-semibold" id="shipping-fee">Đang tính...</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Thời gian giao hàng dự kiến:</span>
                                <span class="font-semibold" id="expected-delivery-time"></span>
                            </div>
                            <div class="border-t my-2"></div>
                            <div class="flex justify-between text-lg font-bold">
                                <span>Tổng đơn:</span>
                                <span id="total-amount">{{ number_format($subtotal) }} VND</span>
                            </div>
                        </div>
                    </div>

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
                            <button type="submit" class="bg-[#F1416C] text-white px-4 py-3 hover:bg-pink-600"
                                form="checkout-form">
                                Đặt hàng
                            </button>
                        </div>
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
                const isHidden = window.getComputedStyle(createAddressForm).display === 'none';

                createAddressForm.style.display = isHidden ? 'block' : 'none';

                if (isHidden) {
                    showAddressFormBtn.innerHTML = '<i class="fa fa-times"></i> Đóng';
                } else {
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addressSelect = document.querySelector('select[name="address"]');
            if (addressSelect) {
                calculateShippingFee(addressSelect.value);

                addressSelect.addEventListener('change', function() {
                    calculateShippingFee(this.value);
                });
            }

            function calculateShippingFee(addressId) {
                if (!addressId) return;

                document.getElementById('shipping-fee').textContent = '0 VND';
                fetch('/calculate-shipping-fee', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            address_id: addressId,
                            _token: '{{ csrf_token() }}'
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.shipping_fee && typeof data.shipping_fee === 'number') {
                            const shippingFee = data.shipping_fee;
                            const subtotal = {{ $subtotal ?? 0 }};
                            const total = subtotal + shippingFee;

                            document.getElementById('shipping-fee').textContent =
                                new Intl.NumberFormat('vi-VN').format(shippingFee) + ' VND';
                            document.getElementById('expected-delivery-time').textContent = data
                                .expected_delivery_time;
                            document.getElementById('total-amount').textContent =
                                new Intl.NumberFormat('vi-VN').format(total) + ' VND';
                        } else {
                            document.getElementById('shipping-fee').textContent = data.error ||
                                'Không thể tính phí vận chuyển';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('shipping-fee').textContent = 'Không thể tính phí vận chuyển';
                    });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            event.preventDefault();
            const checkoutForm = document.getElementById('checkout-form');
            const shopNotesInput = document.getElementById('shop_notes');

            function collectShopNotes() {
                const notes = {};
                document.querySelectorAll('textarea[name^="note_for_shop"]').forEach(textarea => {
                    const shopId = textarea.getAttribute('data-shop-id');
                    const note = textarea.value.trim();

                    if (note) {
                        if (!notes[shopId]) {
                            notes[shopId] = {};
                        }
                        notes[shopId] = note;
                    }
                });
                return notes;
            }

            // Cập nhật shop_notes khi submit form
            checkoutForm.addEventListener('submit', function(event) {
                const notes = collectShopNotes();
                shopNotesInput.value = JSON.stringify(notes);
            });
        });
    </script>
@endpush
