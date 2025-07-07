@extends('layouts.seller_home')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/combo.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Tạo Combo Mới</h1>
        <div class="admin-breadcrumb"><a href="{{ route('seller.dashboard') }}" class="admin-breadcrumb-link">Home</a> / <a
                href="{{ route('seller.combo.index') }}" class="admin-breadcrumb-link">Danh sách Combo</a> / Tạo Combo Mới
        </div>
    </div>

    @include('layouts.notification')

    <section class="bg-white rounded-lg shadow-sm p-6 max-w-3xl">
        @if ($errors->any())
            <div class="bg-red-100 text-red-600 text-xs p-4 rounded-md mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('seller.combo.store') }}" method="POST" id="combo-form" class="space-y-6">
            @csrf

            <!-- Tên Combo -->
            <div class="flex flex-col">
                <label for="combo_name" class="text-[13px] font-semibold text-gray-600 mb-2">Tên Combo</label>
                <input type="text" name="combo_name" id="combo_name"
                    class="border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    value="{{ old('combo_name') }}" required>
            </div>

            <!-- Mô tả -->
            <div class="flex flex-col">
                <label for="combo_description" class="text-[13px] font-semibold text-gray-600 mb-2">Mô tả</label>
                <textarea name="combo_description" id="combo_description"
                    class="border border-[#F2F2F6] rounded-md p-2 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    rows="4">{{ old('combo_description') }}</textarea>
            </div>

            <!-- Tổng giá -->
            <div class="flex flex-col">
                <label for="total_price" class="text-[13px] font-semibold text-gray-600 mb-2">Tổng giá (VNĐ)</label>
                <input type="number" name="total_price" id="total_price"
                    class="border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-900 bg-gray-100 cursor-not-allowed"
                    value="{{ old('total_price') }}" readonly>
            </div>

            <!-- Giá trị giảm giá -->
            <div class="flex flex-col">
                <label for="discount_value" class="text-[13px] font-semibold text-gray-600 mb-2">Giá trị giảm giá</label>
                <input type="number" name="discount_value" id="discount_value"
                    class="border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    value="{{ old('discount_value') }}" min="0">
            </div>

            <!-- Loại giảm giá -->
            <div class="flex flex-col">
                <label for="discount_type" class="text-[13px] font-semibold text-gray-600 mb-2">Loại giảm giá</label>
                <select name="discount_type" id="discount_type"
                    class="border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Không giảm giá</option>
                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>%</option>
                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>VNĐ</option>
                </select>
            </div>

            <!-- Chọn sản phẩm -->
            <div class="flex flex-col">
                <label class="text-[13px] font-semibold text-gray-600 mb-2">Chọn sản phẩm (ít nhất 2 sản phẩm)</label>
                <div id="product-list" class="space-y-4">
                    <div class="product-item flex flex-col md:flex-row md:items-end gap-4">
                        <div class="flex-1">
                            <label class="text-[12px] text-gray-500">Sản phẩm</label>
                            <select name="products[0][productID]"
                                class="product-select w-full border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600"
                                required onchange="calculateTotal()">
                                <option value="">Chọn sản phẩm</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-[120px]">
                            <label class="text-[12px] text-gray-500">Số lượng</label>
                            <input type="number" name="products[0][quantity]"
                                class="quantity-input w-full border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600"
                                value="1" min="1" required oninput="calculateTotal()">
                        </div>
                        <button type="button" onclick="removeProduct(this)"
                            class="absolute top-0 right-0 text-red-500 text-sm hover:text-red-700 px-2 py-1">
                            X
                        </button>
                    </div>
                </div>
                <button type="button"
                    class="mt-4 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-xs focus:outline-none"
                    onclick="addProduct()">Thêm sản phẩm</button>
            </div>

            <!-- Nút gửi -->
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm focus:outline-none">
                    Tạo Combo
                </button>
            </div>
        </form>
    </section>

    <script>
        let productIndex = 1;

        const productPrices = {
            @foreach ($products as $product)
                "{{ $product->id }}": {{ $product->price }},
            @endforeach
        };

        function addProduct() {
            const productList = document.getElementById('product-list');
            const newProduct = `
                <div class="product-item flex flex-col md:flex-row md:items-center gap-4 relative group">
                    <div class="flex-1">
                        <label class="text-[12px] text-gray-500">Sản phẩm</label>
                        <select name="products[${productIndex}][productID]"
                            class="product-select w-full border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600"
                            required onchange="calculateTotal()">
                            <option value="">Chọn sản phẩm</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:w-[120px]">
                        <label class="text-[12px] text-gray-500">Số lượng</label>
                        <input type="number" name="products[${productIndex}][quantity]"
                            class="quantity-input w-full border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600"
                            value="1" min="1" required oninput="calculateTotal()">
                    </div>
                    <button type="button" onclick="removeProduct(this)"
                        class="text-red-500 text-sm hover:text-red-700 px-2 py-1 mt-4" title="Xoá">
                        X
                    </button>
                </div>`;
            productList.insertAdjacentHTML('beforeend', newProduct);
            productIndex++;
            calculateTotal();
        }

        function removeProduct(button) {
            const productList = document.getElementById('product-list');
            const productItems = productList.querySelectorAll('.product-item');

            if (productItems.length <= 1) {
                alert('Phải có ít nhất 1 sản phẩm trong combo.');
                return;
            }

            button.closest('.product-item').remove();
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            const productItems = document.querySelectorAll('.product-item');

            productItems.forEach(item => {
                const select = item.querySelector('.product-select');
                const quantityInput = item.querySelector('.quantity-input');
                const productId = select.value;
                const quantity = parseInt(quantityInput.value) || 1;

                if (productId && productPrices[productId]) {
                    total += productPrices[productId] * quantity;
                }
            });

            const discountValue = parseFloat(document.getElementById('discount_value').value) || 0;
            const discountType = document.getElementById('discount_type').value;

            if (discountType === 'percentage' && discountValue > 0) {
                total -= (total * discountValue) / 100;
            } else if (discountType === 'fixed' && discountValue > 0) {
                total -= discountValue;
            }

            total = Math.max(0, total);
            document.getElementById('total_price').value = Math.round(total);
        }

        document.getElementById('discount_value').addEventListener('input', calculateTotal);
        document.getElementById('discount_type').addEventListener('change', calculateTotal);

        document.getElementById('combo-form').addEventListener('submit', function(event) {
            const productItems = document.querySelectorAll('.product-item');
            if (productItems.length < 2) {
                event.preventDefault();
                alert('Combo phải có ít nhất 2 sản phẩm!');
            }
        });

        calculateTotal();
    </script>
@endsection
