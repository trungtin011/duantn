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

                    <!-- Thuộc tính & Biến thể -->
                    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                        <h4 class="text-xl font-semibold mb-4">Thuộc tính sản phẩm</h4>
                        <div id="attribute-container" class="mb-4">
                            <label class="block text-gray-700 font-medium mb-1">Thuộc tính sản phẩm</label>
                            @if (old('attributes'))
                                @foreach (old('attributes') as $index => $attribute)
                                    <div class="flex items-center gap-4 mb-2">
                                        <select name="attributes[{{ $index }}][id]"
                                            class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-select">
                                            <option value="" disabled>Chọn hoặc nhập thuộc tính</option>
                                            <option value="new" {{ $attribute['id'] === 'new' ? 'selected' : '' }}>Tạo
                                                thuộc tính mới</option>
                                            @foreach ($allAttributes as $attr)
                                                <option value="{{ $attr->id }}"
                                                    {{ $attribute['id'] == $attr->id ? 'selected' : '' }}>
                                                    {{ $attr->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="attributes[{{ $index }}][name]"
                                            value="{{ $attribute['name'] ?? '' }}"
                                            class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-name {{ $attribute['id'] === 'new' ? '' : 'hidden' }}"
                                            placeholder="Tên thuộc tính (VD: Màu sắc, Kích thước)">
                                        <input type="text" name="attributes[{{ $index }}][values]"
                                            value="{{ $attribute['values'] ?? '' }}"
                                            class="w-2/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-values"
                                            placeholder="Giá trị (VD: Đỏ, Xanh, Vàng - phân cách bằng dấu phẩy)" required>
                                        <button type="button"
                                            class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-attribute">Xóa</button>
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-center gap-4 mb-2">
                                    <select name="attributes[0][id]"
                                        class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-select">
                                        <option value="" disabled selected>Chọn hoặc nhập thuộc tính</option>
                                        <option value="new">Tạo thuộc tính mới</option>
                                        @foreach ($allAttributes as $attr)
                                            <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="attributes[0][name]"
                                        class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-name hidden"
                                        placeholder="Tên thuộc tính (VD: Màu sắc, Kích thước)">
                                    <input type="text" name="attributes[0][values]"
                                        class="w-2/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-values"
                                        placeholder="Giá trị (VD: Đỏ, Xanh, Vàng - phân cách bằng dấu phẩy)" required>
                                    <button type="button"
                                        class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-attribute">Xóa</button>
                                </div>
                            @endif
                            <button type="button" id="add-attribute-btn"
                                class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Thêm thuộc
                                tính</button>
                        </div>
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
                                                            value="{{ $variant['price'] ?? '' }}" step="0.01"
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
                                                            value="{{ $variant['purchase_price'] ?? '' }}"
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
                                                            value="{{ $variant['sale_price'] ?? '' }}" step="0.01"
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
                                                            value="{{ $variant['sku'] ?? '' }}"
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
                                                            value="{{ $variant['stock_total'] ?? '' }}"
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
                                                            value="{{ $variant['length'] ?? '' }}" step="0.01"
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
                                                        <input type="number"
                                                            name="variants[{{ $index }}][width]"
                                                            value="{{ $variant['width'] ?? '' }}" step="0.01"
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
                                                            value="{{ $variant['height'] ?? '' }}" step="0.01"
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
                                                            value="{{ $variant['weight'] ?? '' }}" step="0.01"
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
    </script>
    @vite('resources/js/seller/product.js')
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
