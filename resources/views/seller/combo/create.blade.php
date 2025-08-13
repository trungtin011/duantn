
@extends('layouts.seller_home')
@section('title', 'Thêm Combo')
@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Tạo Combo Mới</h1>
        <div class="admin-breadcrumb">
            <a href="{{ route('seller.dashboard') }}" class="admin-breadcrumb-link">Home</a> /
            <a href="{{ route('seller.combo.index') }}" class="admin-breadcrumb-link">Danh sách Combo</a> /
            Tạo Combo Mới
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

        <form action="{{ route('seller.combo.store') }}" method="POST" id="combo-form" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <!-- Tên Combo -->
            <div class="flex flex-col">
                <label for="name" class="text-[13px] font-semibold text-gray-600 mb-2">Tên Combo</label>
                <input type="text" name="name" id="name"
                    class="border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    value="{{ old('name') }}" required>
                @error('name')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Mô tả -->
            <div class="flex flex-col">
                <label for="combo_description" class="text-[13px] font-semibold text-gray-600 mb-2">Mô tả</label>
                <textarea name="combo_description" id="combo_description"
                    class="border border-[#F2F2F6] rounded-md p-2 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    rows="4">{{ old('combo_description') }}</textarea>
                @error('combo_description')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Hình ảnh -->
            <div class="flex flex-col">
                <label for="image" class="text-[13px] font-semibold text-gray-600 mb-2">Hình ảnh Combo</label>
                <input type="file" name="image" id="image"
                    class="border border-[#F2F2F6] rounded-md p-2 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    accept="image/jpeg,image/png,image/jpg,image/gif">
                @error('image')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Số lượng tồn kho -->
            <div class="flex flex-col">
                <label for="quantity" class="text-[13px] font-semibold text-gray-600 mb-2">Số lượng tồn kho</label>
                <input type="number" name="quantity" id="quantity"
                    class="border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    value="{{ old('quantity', 0) }}" min="0" required>
                @error('quantity')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tổng giá -->
            <div class="flex flex-col">
                <label for="total_price" class="text-[13px] font-semibold text-gray-600 mb-2">Tổng giá (VNĐ)</label>
                <input type="number" name="total_price" id="total_price"
                    class="border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-900 bg-gray-100 cursor-not-allowed"
                    value="{{ old('total_price', 0) }}" readonly>
                @error('total_price')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Giá trị giảm giá -->
            <div class="flex flex-col">
                <label for="discount_value" class="text-[13px] font-semibold text-gray-600 mb-2">Giá trị giảm giá</label>
                <input type="number" name="discount_value" id="discount_value"
                    class="border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    value="{{ old('discount_value', 0) }}" min="0" oninput="calculateTotal()">
                @error('discount_value')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Loại giảm giá -->
            <div class="flex flex-col">
                <label for="discount_type" class="text-[13px] font-semibold text-gray-600 mb-2">Loại giảm giá</label>
                <select name="discount_type" id="discount_type"
                    class="border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    onchange="calculateTotal()">
                    <option value="">Không giảm giá</option>
                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>%</option>
                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>VNĐ</option>
                </select>
                @error('discount_type')
                    <span class="text-red-600 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Chọn sản phẩm -->
            <div class="flex flex-col">
                <label class="text-[13px] font-semibold text-gray-600 mb-2">Chọn sản phẩm (ít nhất 2 sản phẩm)</label>
                <div id="product-list" class="space-y-4">
                    <div class="product-item flex flex-col md:flex-row md:items-end gap-4 relative">
                        <div class="flex-1">
                            <label class="text-[12px] text-gray-500">Sản phẩm</label>
                            <select name="products[0][productID]"
                                class="product-select w-full border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600"
                                required onchange="updateVariants(this, 0); calculateTotal()">
                                <option value="">Chọn sản phẩm</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        data-price="{{ $product->sale_price ?? $product->price }}"
                                        data-variants="{{ json_encode($product->variants) }}"
                                        {{ old('products.0.productID') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="text-[12px] text-gray-500">Biến thể</label>
                            <select name="products[0][variantID]"
                                class="variant-select w-full border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600"
                                onchange="calculateTotal()">
                                <option value="">Không chọn biến thể</option>
                                @if (old('products.0.productID') && $selectedProduct = $products->firstWhere('id', old('products.0.productID')))
                                    @foreach ($selectedProduct->variants as $variant)
                                        <option value="{{ $variant->id }}"
                                            data-price="{{ $variant->sale_price ?? $variant->price }}"
                                            {{ old('products.0.variantID') == $variant->id ? 'selected' : '' }}>
                                            {{ $variant->name ?? 'Biến thể ' . $variant->id }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="w-full md:w-[120px]">
                            <label class="text-[12px] text-gray-500">Số lượng</label>
                            <input type="number" name="products[0][quantity]"
                                class="quantity-input w-full border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600"
                                value="{{ old('products.0.quantity', 1) }}" min="1" required oninput="calculateTotal()">
                        </div>
                        <button type="button" onclick="removeProduct(this)"
                            class="absolute top-0 right-0 text-red-500 text-sm hover:text-red-700 px-2 py-1" title="Xoá">
                            X
                        </button>
                    </div>
                    <div class="product-item flex flex-col md:flex-row md:items-end gap-4 relative">
                        <div class="flex-1">
                            <label class="text-[12px] text-gray-500">Sản phẩm</label>
                            <select name="products[1][productID]"
                                class="product-select w-full border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600"
                                required onchange="updateVariants(this, 1); calculateTotal()">
                                <option value="">Chọn sản phẩm</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        data-price="{{ $product->sale_price ?? $product->price }}"
                                        data-variants="{{ json_encode($product->variants) }}"
                                        {{ old('products.1.productID') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="text-[12px] text-gray-500">Biến thể</label>
                            <select name="products[1][variantID]"
                                class="variant-select w-full border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600"
                                onchange="calculateTotal()">
                                <option value="">Không chọn biến thể</option>
                                @if (old('products.1.productID') && $selectedProduct = $products->firstWhere('id', old('products.1.productID')))
                                    @foreach ($selectedProduct->variants as $variant)
                                        <option value="{{ $variant->id }}"
                                            data-price="{{ $variant->sale_price ?? $variant->price }}"
                                            {{ old('products.1.variantID') == $variant->id ? 'selected' : '' }}>
                                            {{ $variant->name ?? 'Biến thể ' . $variant->id }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="w-full md:w-[120px]">
                            <label class="text-[12px] text-gray-500">Số lượng</label>
                            <input type="number" name="products[1][quantity]"
                                class="quantity-input w-full border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600"
                                value="{{ old('products.1.quantity', 1) }}" min="1" required oninput="calculateTotal()">
                        </div>
                        <button type="button" onclick="removeProduct(this)"
                            class="absolute top-0 right-0 text-red-500 text-sm hover:text-red-700 px-2 py-1" title="Xoá">
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

    @push('scripts')
        <script>
            let productIndex = 2;

            const productData = @json($productDataForJs);

            const productOptionsHtml = `
                <option value="">Chọn sản phẩm</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}"
                        data-price="{{ $product->sale_price ?? $product->price }}"
                        data-variants='{{ json_encode($product->variants) }}'>
                        {{ $product->name }}
                    </option>
                @endforeach
            `;

            function addProduct() {
                const productList = document.getElementById('product-list');
                const newProduct = `
                    <div class="product-item flex flex-col md:flex-row md:items-end gap-4 relative">
                        <div class="flex-1">
                            <label class="text-[12px] text-gray-500">Sản phẩm</label>
                            <select name="products[${productIndex}][productID]"
                                class="product-select w-full border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600"
                                required onchange="updateVariants(this, ${productIndex}); calculateTotal()">
                                ${productOptionsHtml}
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="text-[12px] text-gray-500">Biến thể</label>
                            <select name="products[${productIndex}][variantID]"
                                class="variant-select w-full border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600"
                                onchange="calculateTotal()">
                                <option value="">Không chọn biến thể</option>
                            </select>
                        </div>
                        <div class="w-full md:w-[120px]">
                            <label class="text-[12px] text-gray-500">Số lượng</label>
                            <input type="number" name="products[${productIndex}][quantity]"
                                class="quantity-input w-full border border-[#F2F2F6] rounded-md p-2 h-9 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600"
                                value="1" min="1" required oninput="calculateTotal()">
                        </div>
                        <button type="button" onclick="removeProduct(this)"
                            class="absolute top-0 right-0 text-red-500 text-sm hover:text-red-700 px-2 py-1" title="Xoá">
                            X
                        </button>
                    </div>`;
                productList.insertAdjacentHTML('beforeend', newProduct);
                productIndex++;
            }

            function removeProduct(button) {
                const productItems = document.querySelectorAll('.product-item');
                if (productItems.length > 2) {
                    button.parentElement.remove();
                    calculateTotal();
                } else {
                    alert('Combo phải có ít nhất 2 sản phẩm.');
                }
            }

            function updateVariants(select, index) {
                const productID = select.value;
                const variantSelect = select.parentElement.parentElement.querySelector('.variant-select');
                variantSelect.innerHTML = '<option value="">Không chọn biến thể</option>';

                if (productID && productData[productID]) {
                    const variants = productData[productID].variants;
                    variants.forEach(variant => {
                        const option = document.createElement('option');
                        option.value = variant.id;
                        option.textContent = variant.name || `Biến thể ${variant.id}`;
                        option.dataset.price = parseFloat(variant.price);
                        variantSelect.appendChild(option);
                    });
                }
            }

            function calculateTotal() {
                let total = 0;
                const productItems = document.querySelectorAll('.product-item');
                productItems.forEach(item => {
                    const productSelect = item.querySelector('.product-select');
                    const variantSelect = item.querySelector('.variant-select');
                    const quantityInput = item.querySelector('.quantity-input');
                    let price = parseFloat(productSelect.options[productSelect.selectedIndex]?.dataset.price) || 0;
                    if (variantSelect.value) {
                        price = parseFloat(variantSelect.options[variantSelect.selectedIndex].dataset.price) || price;
                    }
                    const quantity = parseInt(quantityInput.value) || 0;
                    total += price * quantity;
                });

                const discountValue = parseFloat(document.getElementById('discount_value').value) || 0;
                const discountType = document.getElementById('discount_type').value;
                let finalPrice = total;

                if (discountType === 'percentage' && discountValue > 0) {
                    finalPrice -= (total * discountValue) / 100;
                } else if (discountType === 'fixed' && discountValue > 0) {
                    finalPrice -= discountValue;
                }

                finalPrice = Math.max(0, finalPrice);
                document.getElementById('total_price').value = Math.round(finalPrice);
            }

            // Initial calculation when the page loads
            document.addEventListener('DOMContentLoaded', calculateTotal);
        </script>
    @endpush
@endsection
