@extends('layouts.seller_home')

@section('title', 'Thêm Sản Phẩm Mới')
@section('content')
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Thêm sản phẩm mới</h1>
                <div class="text-sm text-gray-500">
                    <a href="{{ route('seller.dashboard') }}" class="hover:underline">Trang chủ</a> / Thêm sản phẩm
                </div>
            </div>
            <div class="flex space-x-3">
                <button type="submit" form="product-form"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Lưu và đăng</button>
                <a href="{{ route('seller.products.index') }}"
                    class="border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-100">Hủy</a>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Content -->
        <form id="product-form" action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-12 gap-6">
                <!-- Left Column -->
                <div class="col-span-12 lg:col-span-8">
                    <!-- General Section -->
                    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                        <h4 class="text-xl font-semibold mb-4">Thông tin chung</h4>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-1">Tên sản phẩm <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" id="product-name"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Nhập tên sản phẩm" value="{{ old('name') }}">
                            @error('name')
                                <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                            @enderror
                            <span class="text-sm text-gray-500 block mt-1">Tên sản phẩm nên ngắn gọn và duy nhất.</span>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Mô tả sản phẩm</label>
                            <textarea id="description" name="description"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                rows="6">{!! old('description') !!}</textarea>
                            @error('description')
                                <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Data Section -->
                    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                        <h4 class="text-xl font-semibold mb-4">Dữ liệu sản phẩm</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Giá gốc <span
                                        class="text-red-500">*</span></label>
                                <input type="number" name="price" step="0.01"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: 100000" value="{{ old('price') }}">
                                @error('price')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Giá nhập <span
                                        class="text-red-500">*</span></label>
                                <input type="number" name="purchase_price" step="0.01"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: 80000" value="{{ old('purchase_price') }}">
                                @error('purchase_price')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Giá bán <span
                                        class="text-red-500">*</span></label>
                                <input type="number" name="sale_price" step="0.01"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: 120000" value="{{ old('sale_price') }}">
                                @error('sale_price')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">SKU sản phẩm <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="sku"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: SP001" value="{{ old('sku') }}">
                                @error('sku')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Số lượng tồn kho <span
                                        class="text-red-500">*</span></label>
                                <input type="number" name="stock_total"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: 100" value="{{ old('stock_total') }}">
                                @error('stock_total')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Chiều dài (inch)</label>
                                <input type="number" name="length" step="0.01"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: 10" value="{{ old('length') }}">
                                @error('length')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Chiều rộng (inch)</label>
                                <input type="number" name="width" step="0.01"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: 5" value="{{ old('width') }}">
                                @error('width')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Chiều cao (inch)</label>
                                <input type="number" name="height" step="0.01"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: 3" value="{{ old('height') }}">
                                @error('height')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Trọng lượng (kg)</label>
                                <input type="number" name="weight" step="0.01"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: 0.5" value="{{ old('weight') }}">
                                @error('weight')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Thuộc tính sản phẩm -->
                    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                        <h4 class="text-xl font-semibold mb-4">Thuộc tính sản phẩm</h4>
                        <div id="attribute-container" class="mb-4">
                            <label class="block text-gray-700 font-medium mb-1">Thuộc tính sản phẩm</label>
                            @php
                                // Nếu có old('attributes'), sử dụng nó; nếu không, khởi tạo một thuộc tính mặc định
                                $attributes = old('attributes', [[]]);
                            @endphp
                            @foreach ($attributes as $index => $attribute)
                                <div class="flex items-center gap-4 mb-2 attribute-row">
                                    <select name="attributes[{{ $index }}][id]"
                                        class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-select"
                                        onchange="updateAttributeValues(this)">
                                        <option value="" disabled {{ empty($attribute['id']) ? 'selected' : '' }}>
                                            Chọn hoặc nhập thuộc tính
                                        </option>
                                        <option value="new" {{ ($attribute['id'] ?? '') === 'new' ? 'selected' : '' }}>
                                            Tạo thuộc tính mới
                                        </option>
                                        @foreach ($allAttributes as $attr)
                                            <option value="{{ $attr['id'] }}"
                                                {{ ($attribute['id'] ?? '') == $attr['id'] ? 'selected' : '' }}>
                                                {{ $attr['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="attributes[{{ $index }}][name]"
                                        value="{{ old("attributes.$index.name", $attribute['name'] ?? '') }}"
                                        class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-name {{ ($attribute['id'] ?? '') === 'new' ? '' : 'hidden' }}"
                                        placeholder="Tên thuộc tính (VD: Màu sắc, Kích thước)">
                                    <input type="text" name="attributes[{{ $index }}][values]"
                                        value="{{ old("attributes.$index.values", $attribute['values'] ?? '') }}"
                                        class="w-2/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-values"
                                        placeholder="Giá trị (VD: Đỏ, Xanh, Vàng - phân cách bằng dấu phẩy)" required>
                                    <button type="button"
                                        class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-attribute">Xóa</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-attribute-btn"
                            class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Thêm thuộc
                            tính</button>
                    </div>

                    <!-- Khu vực hiển thị biến thể -->
                    <div id="variants-section" class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-xl font-semibold">Biến thể sản phẩm</h4>
                            <button type="button" id="toggle-variants"
                                class="text-gray-600 hover:text-gray-800 focus:outline-none">
                                <svg id="toggle-icon" class="w-6 h-6" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="variant-content" class="mb-4">
                            <label class="block text-gray-700 font-medium mb-1">Biến thể sản phẩm</label>
                            <button type="button" id="generate-variants-btn"
                                class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Tạo biến
                                thể</button>
                            <div id="variant-container" class="mt-4">
                                @if (old('variants'))
                                    @foreach (old('variants') as $index => $variant)
                                        <div
                                            class="p-6 border border-gray-300 rounded-md mb-6 bg-white relative variant-item">
                                            <div class="flex justify-between items-center mb-3">
                                                <h5 class="text-lg font-semibold">Biến thể {{ $index + 1 }}:
                                                    {{ $variant['name'] }}</h5>
                                                <div class="flex space-x-3">
                                                    <button type="button"
                                                        class="text-red-500 hover:text-red-600 remove-variant">Xóa</button>
                                                    <button type="button" class="toggle-variants"
                                                        data-index="{{ $index }}"
                                                        class="text-gray-600 hover:text-gray-800 focus:outline-none">
                                                        <svg class="toggle-icon w-6 h-6" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="variant-content transition-all duration-300 ease-in-out hidden">
                                                <input type="hidden" name="variants[{{ $index }}][index]"
                                                    value="{{ $index }}">
                                                <input type="hidden" name="variants[{{ $index }}][name]"
                                                    value="{{ $variant['name'] }}">
                                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Giá gốc</label>
                                                        <input type="number" name="variants[{{ $index }}][price]"
                                                            value="{{ old("variants.$index.price") }}" step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Nhập giá gốc" required>
                                                        @error("variants.$index.price")
                                                            <span
                                                                class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Giá
                                                            nhập</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][purchase_price]"
                                                            value="{{ old("variants.$index.purchase_price") }}"
                                                            step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Nhập giá nhập" required>
                                                        @error("variants.$index.purchase_price")
                                                            <span
                                                                class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Giá bán</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][sale_price]"
                                                            value="{{ old("variants.$index.sale_price") }}"
                                                            step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Nhập giá bán" required>
                                                        @error("variants.$index.sale_price")
                                                            <span
                                                                class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">SKU</label>
                                                        <input type="text" name="variants[{{ $index }}][sku]"
                                                            value="{{ old("variants.$index.sku") }}"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Nhập SKU" required>
                                                        @error("variants.$index.sku")
                                                            <span
                                                                class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Số lượng tồn
                                                            kho</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][stock_total]"
                                                            value="{{ old("variants.$index.stock_total") }}"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Nhập số lượng" required>
                                                        @error("variants.$index.stock_total")
                                                            <span
                                                                class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Chiều dài
                                                            (inch)
                                                        </label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][length]"
                                                            value="{{ old("variants.$index.length") }}" step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Chiều dài">
                                                        @error("variants.$index.length")
                                                            <span
                                                                class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Chiều rộng
                                                            (inch)</label>
                                                        <input type="number" name="variants[{{ $index }}][width]"
                                                            value="{{ old("variants.$index.width") }}" step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Chiều rộng">
                                                        @error("variants.$index.width")
                                                            <span
                                                                class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Chiều cao
                                                            (inch)</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][height]"
                                                            value="{{ old("variants.$index.height") }}" step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Chiều cao">
                                                        @error("variants.$index.height")
                                                            <span
                                                                class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Trọng lượng
                                                            (kg)</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][weight]"
                                                            value="{{ old("variants.$index.weight") }}" step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Trọng lượng">
                                                        @error("variants.$index.weight")
                                                            <span
                                                                class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="mb-4">
                                                    <label class="block text-gray-700 font-medium mb-1">Thuộc tính biến
                                                        thể</label>
                                                    @foreach ($variant['attributes'] ?? [] as $attrIndex => $attr)
                                                        <div class="flex items-center gap-4 mb-2">
                                                            <input type="text"
                                                                name="variants[{{ $index }}][attributes][{{ $attrIndex }}][name]"
                                                                value="{{ $attr['name'] ?? '' }}"
                                                                placeholder="Tên thuộc tính"
                                                                class="w-1/3 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                                readonly>
                                                            <input type="text"
                                                                name="variants[{{ $index }}][attributes][{{ $attrIndex }}][value]"
                                                                value="{{ $attr['value'] ?? '' }}"
                                                                placeholder="Giá trị thuộc tính"
                                                                class="w-2/3 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                                readonly>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div>
                                                    <label class="block text-gray-700 font-medium mb-1">Hình ảnh</label>
                                                    @if (session('error'))
                                                        <span class="text-sm text-red-500 block mb-3">Vui lòng chọn lại ảnh
                                                            biến thể do lỗi trước đó.</span>
                                                    @endif
                                                    <input type="file" name="variant_images[{{ $index }}][]"
                                                        multiple
                                                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                        accept="image/*"
                                                        onchange="previewVariantImage(event, {{ $index }})">
                                                    <div id="preview-images-{{ $index }}"
                                                        class="mt-2 flex flex-wrap gap-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-span-12 lg:col-span-4 space-y-6">
                    <!-- Product Details -->
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h4 class="text-xl font-semibold mb-4">Chi tiết sản phẩm</h4>
                        <!-- Category & Brand Section -->
                        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                            <p class="text-gray-700 font-medium mb-4">Chi tiết sản phẩm</p>
                            <div class="flex flex-col gap-3 mb-4">
                                <!-- Thương hiệu -->
                                <div>
                                    <label for="brand_id" class="block text-gray-700 font-medium mb-1">Thương hiệu <span
                                            class="text-red-500">*</span></label>
                                    <div
                                        class="flex flex-col gap-2 items-start bg-gray-100 rounded-md p-2 overflow-y-scroll h-auto max-h-40">
                                        @foreach ($brands as $brand)
                                            @if (is_null($brand->parent_id))
                                                <div class="relative flex items-center justify-between w-full">
                                                    <div class="flex items-center gap-2">
                                                        <input type="checkbox" name="brand_ids[]"
                                                            id="brand_{{ $brand->id }}"
                                                            class="border border-gray-300 rounded-md p-2 focus:outline-none"
                                                            value="{{ $brand->id }}"
                                                            {{ in_array($brand->id, old('brand_ids', [])) ? 'checked' : '' }}>
                                                        <label for="brand_{{ $brand->id }}"
                                                            class="text-gray-700 font-medium">
                                                            {{ $brand->name }}
                                                        </label>
                                                    </div>
                                                    @if ($brand->children->isNotEmpty())
                                                        <button type="button"
                                                            class="toggle-sub-brands text-gray-600 hover:text-gray-800"
                                                            data-brand-id="{{ $brand->id }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor" class="size-5 toggle-icon">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
                                                @if ($brand->children->isNotEmpty())
                                                    <div class="sub-brands hidden w-[calc(100%-1.5rem)] rounded-md bg-white p-2 ml-4"
                                                        data-brand-id="{{ $brand->id }}">
                                                        @foreach ($brand->children as $child)
                                                            <div class="flex items-center gap-2">
                                                                <input type="checkbox" name="brand_ids[]"
                                                                    id="brand_{{ $child->id }}"
                                                                    class="border border-gray-300 rounded-md p-2 focus:outline-none"
                                                                    value="{{ $child->id }}"
                                                                    {{ in_array($child->id, old('brand_ids', [])) ? 'checked' : '' }}>
                                                                <label for="brand_{{ $child->id }}"
                                                                    class="text-gray-700 font-medium">
                                                                    {{ $child->name }}
                                                                </label>
                                                                @if ($child->children->isNotEmpty())
                                                                    <button type="button"
                                                                        class="toggle-sub-brands text-gray-600 hover:text-gray-800"
                                                                        data-brand-id="{{ $child->id }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 24 24"
                                                                            stroke-width="1.5" stroke="currentColor"
                                                                            class="size-5 toggle-icon">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                                                        </svg>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                            @if ($child->children->isNotEmpty())
                                                                <div class="sub-brands hidden ml-8"
                                                                    data-brand-id="{{ $child->id }}">
                                                                    @foreach ($child->children as $grandchild)
                                                                        <div class="flex items-center gap-2">
                                                                            <input type="checkbox" name="brand_ids[]"
                                                                                id="brand_{{ $grandchild->id }}"
                                                                                class="border border-gray-300 rounded-md p-2 focus:outline-none"
                                                                                value="{{ $grandchild->id }}"
                                                                                {{ in_array($grandchild->id, old('brand_ids', [])) ? 'checked' : '' }}>
                                                                            <label for="brand_{{ $grandchild->id }}"
                                                                                class="text-gray-700 font-medium">
                                                                                {{ $grandchild->name }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                    @error('brand_ids')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                    @error('brand_ids.*')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Danh mục -->
                                <div>
                                    <label for="category_id" class="block text-gray-700 font-medium mb-1">Danh mục <span
                                            class="text-red-500">*</span></label>
                                    <div
                                        class="flex flex-col gap-2 items-start bg-gray-100 rounded-md p-2 overflow-y-scroll h-auto max-h-40">
                                        @foreach ($categories as $category)
                                            @if (is_null($category->parent_id))
                                                <div class="relative flex items-center justify-between w-full">
                                                    <div class="flex items-center gap-2">
                                                        <input type="checkbox" name="category_ids[]"
                                                            id="category_{{ $category->id }}"
                                                            class="border border-gray-300 rounded-md p-2 focus:outline-none"
                                                            value="{{ $category->id }}"
                                                            {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
                                                        <label for="category_{{ $category->id }}"
                                                            class="text-gray-700 font-medium">
                                                            {{ $category->name }}
                                                        </label>
                                                    </div>
                                                    @if ($category->children->isNotEmpty())
                                                        <button type="button"
                                                            class="toggle-sub-categories text-gray-600 hover:text-gray-800"
                                                            data-category-id="{{ $category->id }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor" class="size-5 toggle-icon">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
                                                @if ($category->children->isNotEmpty())
                                                    <div class="sub-categories hidden w-[calc(100%-1.5rem)] rounded-md bg-white p-2 ml-4"
                                                        data-category-id="{{ $category->id }}">
                                                        @foreach ($category->children as $child)
                                                            <div class="flex items-center gap-2">
                                                                <input type="checkbox" name="category_ids[]"
                                                                    id="category_{{ $child->id }}"
                                                                    class="border border-gray-300 rounded-md p-2 focus:outline-none"
                                                                    value="{{ $child->id }}"
                                                                    {{ in_array($child->id, old('category_ids', [])) ? 'checked' : '' }}>
                                                                <label for="category_{{ $child->id }}"
                                                                    class="text-gray-700 font-medium">
                                                                    {{ $child->name }}
                                                                </label>
                                                                @if ($child->children->isNotEmpty())
                                                                    <button type="button"
                                                                        class="toggle-sub-categories text-gray-600 hover:text-gray-800"
                                                                        data-category-id="{{ $child->id }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 24 24"
                                                                            stroke-width="1.5" stroke="currentColor"
                                                                            class="size-5 toggle-icon">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                                                        </svg>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                            @if ($child->children->isNotEmpty())
                                                                <div class="sub-categories hidden ml-8"
                                                                    data-category-id="{{ $child->id }}">
                                                                    @foreach ($child->children as $grandchild)
                                                                        <div class="flex items-center gap-2">
                                                                            <input type="checkbox" name="category_ids[]"
                                                                                id="category_{{ $grandchild->id }}"
                                                                                class="border border-gray-300 rounded-md p-2 focus:outline-none"
                                                                                value="{{ $grandchild->id }}"
                                                                                {{ in_array($grandchild->id, old('category_ids', [])) ? 'checked' : '' }}>
                                                                            <label for="category_{{ $grandchild->id }}"
                                                                                class="text-gray-700 font-medium">
                                                                                {{ $grandchild->name }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                    @error('category_ids')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                    @error('category_ids.*')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_featured" value="1" class="mr-2"
                                    {{ old('is_featured') ? 'checked' : '' }}>
                                <span class="text-gray-700 font-medium">Sản phẩm nổi bật</span>
                            </label>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Từ khóa (Tags)</label>
                            <input type="text" name="meta_keywords" id="meta-keywords"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="VD: áo thun, thời trang, mùa hè" value="{{ old('meta_keywords') }}">
                            @error('meta_keywords')
                                <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h4 class="text-xl font-semibold mb-4">SEO</h4>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-1">Tiêu đề SEO <span
                                    id="meta-title-count">0/60</span></label>
                            <input type="text" name="meta_title" id="meta-title"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('meta_title') }}" maxlength="60" placeholder="Tối đa 60 ký tự">
                            <span class="text-sm text-gray-500 block mt-1">Tiêu đề hiển thị trên công cụ tìm kiếm.</span>
                            @error('meta_title')
                                <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-1">Mô tả SEO <span
                                    id="meta-description-count">0/160</span></label>
                            <textarea name="meta_description" id="meta-description"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                maxlength="160" rows="4" placeholder="Tối đa 160 ký tự">{{ old('meta_description') }}</textarea>
                            <span class="text-sm text-gray-500 block mt-1">Mô tả hiển thị dưới tiêu đề trên công cụ tìm
                                kiếm.</span>
                            @error('meta_description')
                                <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Xem trước SEO</label>
                            <div id="seo-preview" class="p-3 border border-gray-300 rounded-md">
                                <h5 id="preview-title" class="text-blue-600 mb-1">Tiêu đề sản phẩm</h5>
                                <p id="preview-url" class="text-green-600 mb-1">
                                    https://Zynox.com/san-pham/{{ Str::slug(old('name', 'san-pham')) }}</p>
                                <p id="preview-description" class="text-gray-600">Mô tả ngắn gọn về sản phẩm.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h4 class="text-xl font-semibold mb-4">Hình ảnh sản phẩm</h4>
                        <!-- Ảnh chính -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-1">Ảnh chính <span
                                    class="text-red-500">*</span></label>
                            <div class="text-center border-2 border-dashed border-gray-300 rounded-md p-4">
                                <img id="uploadIcon1" class="w-24 h-auto mx-auto mb-2 hidden"
                                    src="https://html.hixstudio.net/ebazer/assets/img/icons/upload.png" alt="Upload Icon">
                                <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh nhỏ hơn 5Mb</span>
                                <label for="mainImage"
                                    class="inline-block py-2 px-4 bg-blue-100 text-blue-700 rounded-md cursor-pointer hover:bg-blue-200">Chọn
                                    ảnh chính</label>
                                <input type="file" id="mainImage" name="main_image" class="hidden" accept="image/*">
                            </div>
                            @error('main_image')
                                <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Ảnh phụ -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-1">Ảnh phụ</label>
                            <div class="text-center border-2 border-dashed border-gray-300 rounded-md p-4">
                                <div id="additionalImagesPreview" class="mt-2 flex flex-wrap gap-2"></div>
                                <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh nhỏ hơn 5Mb</span>
                                <label for="additionalImages"
                                    class="inline-block py-2 px-4 bg-blue-100 text-blue-700 rounded-md cursor-pointer hover:bg-blue-200">Chọn
                                    ảnh phụ</label>
                                <input type="file" id="additionalImages" name="images[]" multiple class="hidden"
                                    accept="image/*">
                            </div>
                            @error('images.*')
                                <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="col-span-12 flex justify-start space-x-3 mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Lưu và
                        đăng</button>
                    <a href="{{ route('seller.products.index') }}"
                        class="border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-100">Hủy</a>
                </div>
            </div>
        </form>
    </div>

@endsection
@push('scripts')
    <script>
        window.allAttributes = @json($allAttributes);

        function debugLog(message, data = null) {
            console.log(`[DEBUG] ${message}`, data);
        }

        // Hàm khởi tạo trạng thái ban đầu cho thuộc tính và biến thể
        function initializeAttributesAndVariants() {
            debugLog('Initializing attributes and variants');

            // Khởi tạo thuộc tính
            const attributeContainer = document.getElementById('attribute-container');
            if (attributeContainer) {
                const selects = attributeContainer.querySelectorAll('.attribute-select');
                selects.forEach(select => {
                    select.addEventListener('change', function() {
                        updateAttributeValues(this);
                    });
                    if (select.value) {
                        updateAttributeValues(select);
                    }
                });

                attributeContainer.querySelectorAll('.remove-attribute').forEach(button => {
                    button.addEventListener('click', () => {
                        debugLog('Removing attribute row');
                        button.closest('.attribute-row').remove();
                        updateAttributeIndices();
                    });
                });

                attributeContainer.querySelectorAll('input[name$="[name]"]').forEach(input => {
                    input.addEventListener('input', function() {
                        const names = Array.from(attributeContainer.querySelectorAll(
                                'input[name$="[name]"]'))
                            .map(i => i.value.trim().toLowerCase());
                        if (names.filter(name => name === this.value.trim().toLowerCase()).length > 1) {
                            alert('Tên thuộc tính đã tồn tại!');
                            this.value = '';
                        }
                    });
                });
            }

            // Khởi tạo biến thể
            const variantContainer = document.getElementById('variant-container');
            if (variantContainer) {
                variantContainer.querySelectorAll('.remove-variant').forEach(button => {
                    button.addEventListener('click', () => {
                        debugLog('Removing variant');
                        button.closest('.variant-item').remove();
                        updateVariantIndices();
                    });
                });
                initializeToggleButtons();
            }
        }

        // Hàm cập nhật giá trị thuộc tính khi chọn từ dropdown
        function updateAttributeValues(select) {
            debugLog('Cập nhật giá trị thuộc tính', {
                selectValue: select.value
            });
            const row = select.closest('.attribute-row');
            const nameInput = row.querySelector('.attribute-name');
            const valuesInput = row.querySelector('.attribute-values');

            if (!nameInput || !valuesInput) {
                debugLog('Không tìm thấy input tên hoặc giá trị thuộc tính');
                return;
            }

            if (select.value === 'new') {
                nameInput.classList.remove('hidden');
                nameInput.value = '';
                valuesInput.value = '';
                debugLog('Chọn tạo thuộc tính mới, hiển thị input tên');
            } else {
                nameInput.classList.add('hidden');
                const selectedAttribute = window.allAttributes.find(attr => attr.id == select.value);
                if (selectedAttribute) {
                    nameInput.value = selectedAttribute.name;
                    valuesInput.value = Array.isArray(selectedAttribute.values) ?
                        selectedAttribute.values.join(', ') :
                        (selectedAttribute.values || '');
                    debugLog('Cập nhật giá trị thuộc tính', {
                        name: selectedAttribute.name,
                        values: valuesInput.value
                    });
                } else {
                    nameInput.value = '';
                    valuesInput.value = '';
                    select.value = '';
                    alert('Thuộc tính được chọn không hợp lệ. Vui lòng chọn lại.');
                    debugLog('Không tìm thấy thuộc tính cho ID được chọn', {
                        selectValue: select.value
                    });
                }
            }
        }

        // Hàm thêm thuộc tính mới
        let attributeIndex = document.querySelectorAll('#attribute-container .attribute-row').length;

        function addAttribute() {
            debugLog('Adding new attribute');
            const container = document.getElementById('attribute-container');
            const newAttribute = document.createElement('div');
            newAttribute.classList.add('flex', 'items-center', 'gap-4', 'mb-2', 'attribute-row');
            newAttribute.innerHTML = `
                <select name="attributes[${attributeIndex}][id]" class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-select">
                    <option value="" disabled selected>Chọn hoặc nhập thuộc tính</option>
                    <option value="new">Tạo thuộc tính mới</option>
                    ${window.allAttributes
                        .filter(attr => attr.id && attr.name)
                        .map(attr => `<option value="${attr.id}">${attr.name}</option>`)
                        .join('')}
                </select>
                <input type="text" name="attributes[${attributeIndex}][name]" class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-name hidden" placeholder="Tên thuộc tính (VD: Màu sắc, Kích thước)">
                <input type="text" name="attributes[${attributeIndex}][values]" class="w-2/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-values" placeholder="Giá trị (VD: Đỏ, Xanh, Vàng - phân cách bằng dấu phẩy)" required>
                <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-attribute">Xóa</button>
            `;
            container.appendChild(newAttribute);

            const newSelect = newAttribute.querySelector('.attribute-select');
            newSelect.addEventListener('change', function() {
                updateAttributeValues(this);
            });

            newAttribute.querySelector('.remove-attribute').addEventListener('click', () => {
                debugLog('Removing attribute row', {
                    index: attributeIndex
                });
                newAttribute.remove();
                updateAttributeIndices();
            });

            newAttribute.querySelector('input[name$="[name]"]').addEventListener('input', function() {
                const names = Array.from(container.querySelectorAll('input[name$="[name]"]'))
                    .map(input => input.value.trim().toLowerCase());
                if (names.filter(name => name === this.value.trim().toLowerCase()).length > 1) {
                    alert('Tên thuộc tính đã tồn tại!');
                    this.value = '';
                }
            });

            attributeIndex++;
        }

        // Hàm cập nhật chỉ số thuộc tính
        function updateAttributeIndices() {
            debugLog('Updating attribute indices');
            const attributeItems = document.querySelectorAll('#attribute-container .attribute-row');
            attributeItems.forEach((item, index) => {
                item.querySelector('select[name$="[id]"]').name = `attributes[${index}][id]`;
                item.querySelector('input[name$="[name]"]').name = `attributes[${index}][name]`;
                item.querySelector('input[name$="[values]"]').name = `attributes[${index}][values]`;
            });
            attributeIndex = attributeItems.length;
        }

        // Hàm tạo tổ hợp biến thể
        function getCombinations(arr) {
            debugLog('Generating combinations', arr);
            return arr.reduce((acc, val) => acc.flatMap(a => val.map(v => [...a, v])), [
                []
            ]);
        }

        // Hàm tạo biến thể
        function generateVariants() {
            debugLog('Generating variants');
            const selects = document.querySelectorAll('[name^="attributes["][name$="[id]"]');
            const names = document.querySelectorAll('[name^="attributes["][name$="[name]"]');
            const values = document.querySelectorAll('[name^="attributes["][name$="[values]"]');
            const variantContainer = document.getElementById('variant-container');

            if (!variantContainer) {
                debugLog('Variant container not found');
                return;
            }

            let attributeData = [];
            let hasValidAttribute = false;

            selects.forEach((select, index) => {
                const attrId = select.value;
                let attrName = names[index].value.trim();
                const valuesArray = values[index].value
                    .split(',')
                    .map(v => v.trim())
                    .filter(v => v);

                if (attrId === 'new' && !attrName) {
                    alert(`Vui lòng nhập tên thuộc tính cho thuộc tính ${index + 1}.`);
                    return;
                }

                if (attrId !== 'new' && attrId) {
                    const selectedAttribute = window.allAttributes.find(attr => attr.id == attrId);
                    if (selectedAttribute) {
                        attrName = selectedAttribute.name;
                    }
                }

                if (attrName && valuesArray.length) {
                    attributeData.push({
                        name: attrName,
                        values: valuesArray
                    });
                    hasValidAttribute = true;
                }
            });

            if (!hasValidAttribute) {
                alert('Vui lòng nhập ít nhất một thuộc tính hợp lệ với tên và giá trị.');
                debugLog('No valid attributes provided');
                return;
            }

            variantContainer.innerHTML = '';
            const variants = getCombinations(attributeData.map(attr => attr.values));
            debugLog('Generated variants', variants);

            variants.forEach((variant, index) => {
                const variantDiv = document.createElement('div');
                variantDiv.classList.add('p-6', 'border', 'border-gray-300', 'rounded-md', 'mb-6', 'bg-white',
                    'relative', 'variant-item');
                let variantHTML = `
                    <div class="flex justify-between items-center mb-3">
                        <h5 class="text-lg font-semibold">Biến thể ${index + 1}: ${variant.join(' - ')}</h5>
                        <div class="flex space-x-3">
                            <button type="button" class="text-red-500 hover:text-red-600 remove-variant">Xóa</button>
                            <button type="button" class="toggle-variants" data-index="${index}" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                                <svg class="toggle-icon w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="variant-content transition-all duration-300 ease-in-out hidden">
                        <input type="hidden" name="variants[${index}][index]" value="${index}">
                        <input type="hidden" name="variants[${index}][name]" value="${variant.join(' - ')}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Giá gốc</label>
                                <input type="number" name="variants[${index}][price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá gốc" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Giá nhập</label>
                                <input type="number" name="variants[${index}][purchase_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá nhập" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Giá bán</label>
                                <input type="number" name="variants[${index}][sale_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá bán" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">SKU</label>
                                <input type="text" name="variants[${index}][sku]" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập SKU" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Số lượng tồn kho</label>
                                <input type="number" name="variants[${index}][stock_total]" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập số lượng" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Chiều dài (inch)</label>
                                <input type="number" name="variants[${index}][length]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều dài">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Chiều rộng (inch)</label>
                                <input type="number" name="variants[${index}][width]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều rộng">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Chiều cao (inch)</label>
                                <input type="number" name="variants[${index}][height]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều cao">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Trọng lượng (kg)</label>
                                <input type="number" name="variants[${index}][weight]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Trọng lượng">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-1">Thuộc tính biến thể</label>
                            ${variant.map((value, attrIndex) => `
                                                <div class="flex items-center gap-4 mb-2">
                                                    <input type="text" name="variants[${index}][attributes][${attrIndex}][name]" value="${attributeData[attrIndex].name}" placeholder="Tên thuộc tính" class="w-1/3 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                                                    <input type="text" name="variants[${index}][attributes][${attrIndex}][value]" value="${value}" placeholder="Giá trị thuộc tính" class="w-2/3 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                                                </div>
                                            `).join('')}
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Hình ảnh</label>
                            <input type="file" name="variant_images[${index}][]" multiple class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" accept="image/*" onchange="previewVariantImage(event, ${index})">
                            <div id="preview-images-${index}" class="mt-2 flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                `;
                variantDiv.innerHTML = variantHTML;
                variantContainer.appendChild(variantDiv);

                variantDiv.querySelector('.remove-variant').addEventListener('click', () => {
                    debugLog('Removing variant', {
                        index
                    });
                    variantDiv.remove();
                    updateVariantIndices();
                });
            });

            initializeToggleButtons();
            updateVariantIndices();
            debugLog('Variants generated', {
                count: variants.length
            });
        }

        // Hàm cập nhật chỉ số biến thể
        function updateVariantIndices() {
            debugLog('Updating variant indices');
            const variantItems = document.querySelectorAll('#variant-container > .variant-item');
            variantItems.forEach((item, index) => {
                const label = item.querySelector('h5');
                const nameInput = item.querySelector('input[name$="[name]"]');
                label.textContent = `Biến thể ${index + 1}: ${nameInput.value}`;
                const inputs = item.querySelectorAll('input[name]');
                inputs.forEach(input => {
                    let oldName = input.getAttribute('name');
                    let newName = oldName.replace(/\[\d+\]/, `[${index}]`);
                    input.setAttribute('name', newName);
                });
                const toggleButton = item.querySelector('.toggle-variants');
                if (toggleButton) {
                    toggleButton.setAttribute('data-index', index);
                }
                const previewImages = item.querySelector('div[id^="preview-images-"]');
                if (previewImages) {
                    previewImages.id = `preview-images-${index}`;
                }
                const fileInput = item.querySelector('input[type="file"]');
                if (fileInput) {
                    fileInput.setAttribute('onchange', `previewVariantImage(event, ${index})`);
                }
            });
        }

        // Hàm xử lý preview ảnh chính
        function handleMainImagePreview(inputId, iconId) {
            debugLog('Initializing main image preview', {
                inputId,
                iconId
            });
            const input = document.getElementById(inputId);
            const uploadIcon = document.getElementById(iconId);

            if (!input || !uploadIcon) {
                debugLog('Main image input or icon not found', {
                    inputId,
                    iconId
                });
                return;
            }

            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                        input.value = '';
                        uploadIcon.src = "https://html.hixstudio.net/ebazer/assets/img/icons/upload.png";
                        uploadIcon.alt = 'Upload Icon';
                        uploadIcon.classList.add('hidden');
                        return;
                    }
                    if (!file.type.startsWith('image/')) {
                        alert('Vui lòng chọn file hình ảnh hợp lệ!');
                        input.value = '';
                        uploadIcon.src = "https://html.hixstudio.net/ebazer/assets/img/icons/upload.png";
                        uploadIcon.alt = 'Upload Icon';
                        uploadIcon.classList.add('hidden');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        uploadIcon.src = event.target.result;
                        uploadIcon.alt = 'Uploaded Image';
                        uploadIcon.classList.remove('hidden');
                        debugLog('Main image preview updated', {
                            src: event.target.result
                        });
                    };
                    reader.readAsDataURL(file);
                } else {
                    uploadIcon.src = "https://html.hixstudio.net/ebazer/assets/img/icons/upload.png";
                    uploadIcon.alt = 'Upload Icon';
                    uploadIcon.classList.add('hidden');
                }
            });
        }

        // Hàm xử lý preview nhiều ảnh
        function handleAdditionalImagesPreview(inputId, previewContainerId) {
            debugLog('Initializing additional images preview');
            const input = document.getElementById(inputId);
            const previewContainer = document.getElementById(previewContainerId);

            if (!input || !previewContainer) {
                debugLog('Additional images elements not found', {
                    inputId,
                    previewContainerId
                });
                return;
            }

            input.addEventListener('change', function(e) {
                previewContainer.innerHTML = '';
                Array.from(e.target.files).forEach(file => {
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                        input.value = '';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = event => {
                        const imgContainer = document.createElement('div');
                        imgContainer.classList.add('relative', 'w-24', 'h-24');
                        imgContainer.innerHTML = `
                            <img src="${event.target.result}" class="w-full h-full object-cover rounded-md border border-gray-300">
                            <button type="button" class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-full" onclick="this.parentElement.remove()">✖</button>
                        `;
                        previewContainer.appendChild(imgContainer);
                    };
                    reader.readAsDataURL(file);
                });
            });
        }

        // Hàm xử lý preview ảnh biến thể
        function previewVariantImage(event, index) {
            debugLog('Previewing variant images', {
                index
            });
            const previewContainer = document.getElementById(`preview-images-${index}`);
            if (!previewContainer) {
                debugLog('Preview container not found', {
                    index
                });
                return;
            }
            previewContainer.innerHTML = '';
            Array.from(event.target.files).forEach((file, fileIndex) => {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                    event.target.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.classList.add('relative', 'w-24', 'h-24');
                    imgContainer.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover rounded-md border border-gray-300">
                        <button type="button" class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-full" onclick="this.parentElement.remove()">✖</button>
                    `;
                    previewContainer.appendChild(imgContainer);
                };
                reader.readAsDataURL(file);
            });
        }

        // Hàm xử lý toggle biến thể
        function initializeToggleButtons() {
            debugLog('Initializing toggle buttons');
            const toggleButtons = document.querySelectorAll('.toggle-variants');
            toggleButtons.forEach(button => {
                button.removeEventListener('click', handleToggleClick);

                const index = button.getAttribute('data-index');
                const variantItem = button.closest('.variant-item');
                const variantContent = variantItem ? variantItem.querySelector('.variant-content') : null;
                const toggleIcon = button.querySelector('.toggle-icon path');

                if (!variantContent || !toggleIcon) {
                    debugLog('Missing variantContent or toggleIcon', {
                        index
                    });
                    return;
                }

                let isOpen = !variantContent.classList.contains('hidden');
                if (!isOpen) {
                    variantContent.classList.add('hidden');
                    toggleIcon.setAttribute('d', 'M5 15l7-7 7 7');
                } else {
                    toggleIcon.setAttribute('d', 'M19 9l-7 7-7-7');
                }

                function handleToggleClick() {
                    debugLog('Toggling variant', {
                        index
                    });
                    isOpen = !isOpen;
                    variantContent.classList.toggle('hidden', !isOpen);
                    toggleIcon.setAttribute('d', isOpen ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7');
                }

                button.addEventListener('click', handleToggleClick);
            });
        }

        // Hàm khởi tạo toggle danh mục
        function initializeSubCategoryToggles() {
            debugLog('Initializing sub-category toggles');
            const toggleButtons = document.querySelectorAll('.toggle-sub-categories');
            toggleButtons.forEach(button => {
                const categoryId = button.getAttribute('data-category-id');
                const subCategoryContainer = document.querySelector(
                    `.sub-categories[data-category-id="${categoryId}"]`);
                const toggleIcon = button.querySelector('.toggle-icon path');

                if (!subCategoryContainer || !toggleIcon) {
                    debugLog('Sub-category container or toggle icon not found', {
                        categoryId
                    });
                    return;
                }

                let isOpen = false;
                subCategoryContainer.classList.add('hidden');
                toggleIcon.setAttribute('d', 'm8.25 4.5 7.5 7.5-7.5 7.5');

                button.addEventListener('click', function() {
                    debugLog('Toggling sub-category dropdown', {
                        categoryId
                    });
                    isOpen = !isOpen;
                    subCategoryContainer.classList.toggle('hidden', !isOpen);
                    toggleIcon.setAttribute('d', isOpen ? 'm8.25 19.5 7.5-7.5-7.5-7.5' :
                        'm8.25 4.5 7.5 7.5-7.5 7.5');
                });
            });
        }

        // Hàm khởi tạo toggle thương hiệu
        function initializeSubBrandToggles() {
            debugLog('Initializing sub-brand toggles');
            const toggleButtons = document.querySelectorAll('.toggle-sub-brands');
            toggleButtons.forEach(button => {
                const brandId = button.getAttribute('data-brand-id');
                const subBrandContainer = document.querySelector(`.sub-brands[data-brand-id="${brandId}"]`);
                const toggleIcon = button.querySelector('.toggle-icon path');

                if (!subBrandContainer || !toggleIcon) {
                    debugLog('Sub-brand container or toggle icon not found', {
                        brandId
                    });
                    return;
                }

                let isOpen = false;
                subBrandContainer.classList.add('hidden');
                toggleIcon.setAttribute('d', 'm8.25 4.5 7.5 7.5-7.5 7.5');

                button.addEventListener('click', function() {
                    debugLog('Toggling sub-brand dropdown', {
                        brandId
                    });
                    isOpen = !isOpen;
                    subBrandContainer.classList.toggle('hidden', !isOpen);
                    toggleIcon.setAttribute('d', isOpen ? 'm8.25 19.5 7.5-7.5-7.5-7.5' :
                        'm8.25 4.5 7.5 7.5-7.5 7.5');
                });
            });
        }

        // Hàm validate form
        function validateForm(e) {
            const productForm = document.getElementById('product-form');
            if (!productForm) {
                debugLog('Product form not found');
                return;
            }

            debugLog('Dữ liệu form trước khi gửi', Object.fromEntries(new FormData(productForm)));
            const brandCheckboxes = document.querySelectorAll('input[name="brand_ids[]"]:checked');
            const categoryCheckboxes = document.querySelectorAll('input[name="category_ids[]"]:checked');
            const attributeSelects = document.querySelectorAll('select[name^="attributes["][name$="[id]"]');
            const variantItems = document.querySelectorAll('#variant-container > .variant-item');
            let isValid = true;

            // Kiểm tra thương hiệu
            if (brandCheckboxes.length === 0) {
                e.preventDefault();
                alert('Vui lòng chọn ít nhất một thương hiệu.');
                debugLog('Form submission prevented: No brands selected');
                isValid = false;
            }

            // Kiểm tra danh mục
            if (categoryCheckboxes.length === 0) {
                e.preventDefault();
                alert('Vui lòng chọn ít nhất một danh mục.');
                debugLog('Form submission prevented: No categories selected');
                isValid = false;
            }

            // Kiểm tra ảnh chính
            if (!document.getElementById('mainImage').files.length) {
                e.preventDefault();
                alert('Vui lòng chọn ảnh chính.');
                debugLog('Form submission prevented: No main image selected');
                isValid = false;
            }

            // Kiểm tra thuộc tính
            attributeSelects.forEach((select, index) => {
                const nameInput = document.querySelector(`input[name="attributes[${index}][name]"]`);
                const valuesInput = document.querySelector(`input[name="attributes[${index}][values]"]`);
                if (select.value === 'new' && (!nameInput.value || !nameInput.value.trim())) {
                    e.preventDefault();
                    alert(`Vui lòng nhập tên thuộc tính cho thuộc tính ${index + 1}.`);
                    debugLog('Form submission prevented: Empty attribute name', {
                        index
                    });
                    isValid = false;
                }
                if (!valuesInput.value || !valuesInput.value.trim()) {
                    e.preventDefault();
                    alert(`Vui lòng nhập giá trị thuộc tính cho thuộc tính ${index + 1}.`);
                    debugLog('Form submission prevented: Empty attribute values', {
                        index
                    });
                    isValid = false;
                }
            });

            // Kiểm tra biến thể
            if (attributeSelects.length > 0 && variantItems.length === 0) {
                e.preventDefault();
                alert('Vui lòng nhấn "Tạo biến thể" để tạo các biến thể trước khi lưu sản phẩm.');
                debugLog('Form submission prevented: No variants created despite having attributes');
                isValid = false;
            }

            variantItems.forEach((item, index) => {
                const priceInput = item.querySelector(`input[name="variants[${index}][price]"]`);
                const purchasePriceInput = item.querySelector(`input[name="variants[${index}][purchase_price]"]`);
                const salePriceInput = item.querySelector(`input[name="variants[${index}][sale_price]"]`);
                const skuInput = item.querySelector(`input[name="variants[${index}][sku]"]`);
                const stockInput = item.querySelector(`input[name="variants[${index}][stock_total]"]`);

                if (!priceInput.value || isNaN(priceInput.value) || parseFloat(priceInput.value) < 0) {
                    e.preventDefault();
                    alert(`Vui lòng nhập giá gốc hợp lệ cho biến thể ${index + 1}.`);
                    debugLog('Form submission prevented: Invalid price for variant', {
                        index
                    });
                    isValid = false;
                }
                if (!purchasePriceInput.value || isNaN(purchasePriceInput.value) || parseFloat(purchasePriceInput
                        .value) < 0) {
                    e.preventDefault();
                    alert(`Vui lòng nhập giá nhập hợp lệ cho biến thể ${index + 1}.`);
                    debugLog('Form submission prevented: Invalid purchase price for variant', {
                        index
                    });
                    isValid = false;
                }
                if (!salePriceInput.value || isNaN(salePriceInput.value) || parseFloat(salePriceInput.value) < 0) {
                    e.preventDefault();
                    alert(`Vui lòng nhập giá bán hợp lệ cho biến thể ${index + 1}.`);
                    debugLog('Form submission prevented: Invalid sale price for variant', {
                        index
                    });
                    isValid = false;
                }
                if (!skuInput.value || !skuInput.value.trim()) {
                    e.preventDefault();
                    alert(`Vui lòng nhập SKU cho biến thể ${index + 1}.`);
                    debugLog('Form submission prevented: Empty SKU for variant', {
                        index
                    });
                    isValid = false;
                }
                if (!stockInput.value || isNaN(stockInput.value) || parseInt(stockInput.value) < 0) {
                    e.preventDefault();
                    alert(`Vui lòng nhập số lượng tồn kho hợp lệ cho biến thể ${index + 1}.`);
                    debugLog('Form submission prevented: Invalid stock for variant', {
                        index
                    });
                    isValid = false;
                }
            });

            if (isValid) {
                tinymce.triggerSave();
                debugLog('Form submission validated successfully');
            }
        }

        // Main initialization
        document.addEventListener('DOMContentLoaded', () => {
            debugLog('DOM fully loaded');

            // Khởi tạo TinyMCE
            tinymce.init({
                selector: '#description',
                height: 300,
                plugins: 'image link lists table',
                toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image table',
                images_upload_url: '{{ route('seller.upload.image') }}',
                file_picker_types: 'image',
                file_picker_callback: (cb, value, meta) => {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.onchange = function() {
                        const file = this.files[0];
                        const reader = new FileReader();
                        reader.onload = () => {
                            const id = 'blobid' + new Date().getTime();
                            const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            const base64 = reader.result.split(',')[1];
                            const blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);
                            cb(blobInfo.blobUri(), {
                                title: file.name
                            });
                        };
                        reader.readAsDataURL(file);
                    };
                    input.click();
                },
                setup: editor => editor.on('change', () => editor.save())
            });

            // Xử lý submit form
            const productForm = document.getElementById('product-form');
            if (productForm) {
                productForm.addEventListener('submit', validateForm);
            }

            // Xử lý SEO preview
            const productName = document.getElementById('product-name');
            const metaTitle = document.getElementById('meta-title');
            const metaTitleCount = document.getElementById('meta-title-count');
            const previewTitle = document.getElementById('preview-title');
            const previewUrl = document.getElementById('preview-url');
            const previewDescription = document.getElementById('preview-description');
            const metaDescription = document.getElementById('meta-description');
            const metaDescriptionCount = document.getElementById('meta-description-count');

            let metaTitleEditedManually = false;

            const slugify = text => text
                .toLowerCase()
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .replace(/đ/g, "d")
                .replace(/[^a-z0-9 -]/g, "")
                .replace(/\s+/g, "-")
                .replace(/-+/g, "-");

            const updateSEOPreview = () => {
                if (!metaTitleEditedManually) metaTitle.value = productName.value.slice(0, 60);
                metaTitleCount.textContent = `${metaTitle.value.length}/60`;
                metaDescriptionCount.textContent = `${metaDescription.value.length}/160`;
                previewTitle.textContent = metaTitle.value || 'Tiêu đề sản phẩm';
                previewUrl.textContent =
                    `https://Zynox.com/san-pham/${slugify(productName.value || 'san-pham')}`;
                previewDescription.textContent = metaDescription.value || 'Mô tả ngắn gọn về sản phẩm.';
            };

            if (productName && metaTitle && metaDescription) {
                productName.addEventListener('input', updateSEOPreview);
                metaTitle.addEventListener('input', () => {
                    metaTitleEditedManually = true;
                    updateSEOPreview();
                });
                metaDescription.addEventListener('input', updateSEOPreview);
                updateSEOPreview();
            }

            // Khởi tạo preview ảnh
            handleMainImagePreview('mainImage', 'uploadIcon1');
            handleAdditionalImagesPreview('additionalImages', 'additionalImagesPreview');

            // Khởi tạo toggle nút chính của khu vực biến thể
            const toggleButton = document.getElementById('toggle-variants');
            const toggleIcon = document.getElementById('toggle-icon')?.querySelector('path');
            const variantContent = document.getElementById('variant-content');

            if (toggleButton && toggleIcon && variantContent) {
                toggleButton.addEventListener('click', () => {
                    variantContent.classList.toggle('hidden');
                    toggleIcon.setAttribute('d', variantContent.classList.contains('hidden') ?
                        'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7');
                });
            }

            // Gắn sự kiện cho nút Thêm thuộc tính
            const addAttributeButton = document.getElementById('add-attribute-btn');
            if (addAttributeButton) {
                debugLog('Add attribute button found');
                addAttributeButton.addEventListener('click', () => {
                    debugLog('Add attribute button clicked');
                    addAttribute();
                });
            } else {
                debugLog('Add attribute button NOT found');
            }

            // Gắn sự kiện cho nút Tạo biến thể
            const generateVariantsButton = document.getElementById('generate-variants-btn');
            if (generateVariantsButton) {
                debugLog('Generate variants button found');
                generateVariantsButton.addEventListener('click', () => {
                    debugLog('Generate variants button clicked');
                    generateVariants();
                });
            } else {
                debugLog('Generate variants button NOT found');
            }

            // Khởi tạo trạng thái ban đầu
            initializeAttributesAndVariants();
            initializeSubCategoryToggles();
            initializeSubBrandToggles();
        });
    </script>
@endpush
@push('styles')
    <style>
        .toggle-icon {
            transition: transform 0.3s ease;
        }

        .toggle-sub-categories:hover,
        .toggle-sub-brands:hover {
            color: #1f2937;
            /* text-gray-800 */
        }
    </style>
@endpush
