@extends('layouts.seller_home')
@push('styles')
    <style>
        .variant-content {
            overflow: hidden;
            max-height: 1000px;
            /* Giới hạn chiều cao tối đa khi mở */
            transition: max-height 0.3s ease-in-out;
        }

        .variant-content.hidden {
            max-height: 0;
        }
    </style>
@endpush
@section('title', 'Chỉnh sửa sản phẩm')
@section('content')
    <div class="">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Chỉnh sửa sản phẩm</h1>
                <div class="text-sm text-gray-500">
                    <a href="#" class="hover:underline">Trang chủ</a> / Chỉnh sửa sản phẩm
                </div>
            </div>
            <div class="flex space-x-3">
                <button type="submit" form="product-form"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Lưu và cập nhật</button>
                <button type="submit" form="product-form" name="save_draft" value="1"
                    class="border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-100">Lưu nháp</button>
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
        <form id="product-form" action="{{ route('seller.products.update', $product->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 lg:col-span-12">
                    <div class="flex gap-6">
                        <!-- Left Column -->
                        <div class="">
                            <!-- General Section -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <h4 class="text-xl font-semibold mb-4">Thông tin chung</h4>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-1">Tên sản phẩm <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="product-name"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Tên sản phẩm" value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                    <span class="text-sm text-gray-500 block mt-1">Tên sản phẩm nên là duy nhất.</span>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Mô tả</label>
                                    <textarea id="description" name="description"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{!! old('description', $product->description) !!}</textarea>
                                    @error('description')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Product Data Section -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <h4 class="text-xl font-semibold mb-4">Dữ liệu sản phẩm</h4>
                                <!-- Pricing and Inventory -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Giá gốc <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" name="price" step="0.01"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Giá gốc của sản phẩm" value="{{ old('price', $product->price) }}"
                                            required>
                                        @error('price')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Giá nhập <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" name="purchase_price" step="0.01"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Giá nhập sản phẩm"
                                            value="{{ old('purchase_price', $product->purchase_price) }}" required>
                                        @error('purchase_price')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Giá bán <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" name="sale_price" step="0.01"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Giá bán sản phẩm"
                                            value="{{ old('sale_price', $product->sale_price) }}" required>
                                        @error('sale_price')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">SKU sản phẩm <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="sku"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="SKU sản phẩm" value="{{ old('sku', $product->sku) }}" required>
                                        @error('sku')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Số lượng tồn kho <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" name="stock_total"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Số lượng tồn kho"
                                            value="{{ old('stock_total', $product->stock_total) }}" required>
                                        @error('stock_total')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Shipping -->
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Chiều dài (inch)</label>
                                        <input type="number" name="length" step="0.01"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Chiều dài sản phẩm"
                                            value="{{ old('length', $product->dimensions->isNotEmpty() ? $product->dimensions->first()->length : 0) }}">
                                        @error('length')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Chiều rộng (inch)</label>
                                        <input type="number" name="width" step="0.01"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Chiều rộng sản phẩm"
                                            value="{{ old('width', $product->dimensions->isNotEmpty() ? $product->dimensions->first()->width : 0) }}">
                                        @error('width')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Chiều cao (inch)</label>
                                        <input type="number" name="height" step="0.01"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Chiều cao sản phẩm"
                                            value="{{ old('height', $product->dimensions->isNotEmpty() ? $product->dimensions->first()->height : 0) }}">
                                        @error('height')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Trọng lượng (kg)</label>
                                        <input type="number" name="weight" step="0.01"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Trọng lượng sản phẩm"
                                            value="{{ old('weight', $product->dimensions->isNotEmpty() ? $product->dimensions->first()->weight : 0) }}">
                                        @error('weight')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Thuộc tính & Biến thể -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <h4 class="text-xl font-semibold mb-4">Thuộc tính sản phẩm</h4>

                                <div id="attribute-container">
                                    @foreach ($attributes as $index => $attribute)
                                        <div class="mb-4 flex items-center gap-4 attribute-row">
                                            <select name="attributes[{{ $index }}][id]"
                                                class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-select"
                                                onchange="updateAttributeValues(this)">
                                                <option value="" disabled selected>Chọn hoặc nhập thuộc tính</option>
                                                <option value="new">Tạo thuộc tính mới</option>
                                                @foreach ($allAttributes as $attr)
                                                    <option value="{{ $attr->id }}"
                                                        {{ $attribute->id == $attr->id ? 'selected' : '' }}>
                                                        {{ $attr->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" name="attributes[{{ $index }}][name]"
                                                value="{{ $attribute->name ?? '' }}"
                                                class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-name {{ $attribute->id == 'new' ? '' : 'hidden' }}"
                                                placeholder="Tên thuộc tính (VD: Màu sắc, Kích thước)">
                                            <input type="text" name="attributes[{{ $index }}][values]"
                                                value="{{ $attribute->values->isNotEmpty() ? $attribute->values->pluck('value')->implode(', ') : '' }}"
                                                class="w-2/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-values"
                                                placeholder="Giá trị (VD: Đỏ, Xanh, Vàng)">
                                            <button type="button"
                                                class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-attribute">Xóa</button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" id="add-attribute-btn"
                                    class="ml-3 bg-green-500 text-white px-3 py-2 rounded-md hover:bg-green-600">Thêm</button>
                            </div>

                            <!-- Khu vực hiển thị biến thể -->
                            <div id="variants-section" class="bg-white p-6 rounded-lg shadow-sm">
                                <h4 class="text-xl font-semibold mb-4">Biến thể sản phẩm</h4>
                                <div id="variant-container">
                                    @foreach ($product->variants as $index => $variant)
                                        <div
                                            class="p-6 border border-gray-300 rounded-md mb-6 bg-white relative variant-item">
                                            <div class="flex justify-between items-center mb-3">
                                                <h5 class="text-lg font-semibold">Biến thể {{ $index + 1 }}:
                                                    {{ $variant->variant_name }}</h5>
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
                                                    value="{{ $variant->variant_name }}">
                                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Giá gốc</label>
                                                        <input type="number" name="variants[{{ $index }}][price]"
                                                            step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Nhập giá gốc"
                                                            value="{{ old("variants.$index.price", $variant->price) }}"
                                                            required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Giá
                                                            nhập</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][purchase_price]"
                                                            step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Nhập giá nhập"
                                                            value="{{ old("variants.$index.purchase_price", $variant->purchase_price) }}"
                                                            required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Giá bán</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][sale_price]"
                                                            step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Nhập giá bán"
                                                            value="{{ old("variants.$index.sale_price", $variant->sale_price) }}"
                                                            required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">SKU</label>
                                                        <input type="text" name="variants[{{ $index }}][sku]"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Nhập SKU"
                                                            value="{{ old("variants.$index.sku", $variant->sku) }}"
                                                            required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Số lượng tồn
                                                            kho</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][stock_total]"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Nhập số lượng"
                                                            value="{{ old("variants.$index.stock_total", $variant->stock) }}"
                                                            required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Chiều dài
                                                            (inch)</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][length]" step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Chiều dài"
                                                            value="{{ old("variants.$index.length", optional($variant->dimensions)->length ?? 0) }}">
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Chiều rộng
                                                            (inch)</label>
                                                        <input type="number" name="variants[{{ $index }}][width]"
                                                            step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Chiều rộng"
                                                            value="{{ old("variants.$index.width", optional($variant->dimensions)->width ?? 0) }}">
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Chiều cao
                                                            (inch)</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][height]" step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Chiều cao"
                                                            value="{{ old("variants.$index.height", optional($variant->dimensions)->height ?? 0) }}">
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Trọng lượng
                                                            (kg)</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][weight]" step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Trọng lượng"
                                                            value="{{ old("variants.$index.weight", optional($variant->dimensions)->weight ?? 0) }}">
                                                    </div>
                                                </div>
                                                <!-- Hiển thị thuộc tính của biến thể -->
                                                <div class="mb-4 hidden">
                                                    <label class="block text-gray-700 font-medium mb-1">Thuộc tính biến
                                                        thể</label>
                                                    @foreach ($variant->attributeValues as $attrIndex => $attrValue)
                                                        <div class="flex items-center gap-4 mb-2">
                                                            <input type="text"
                                                                name="variants[{{ $index }}][attributes][{{ $attrIndex }}][name]"
                                                                value="{{ old("variants.$index.attributes.$attrIndex.name", $attrValue->attribute->name) }}"
                                                                placeholder="Tên thuộc tính"
                                                                class="w-1/3 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                                readonly>
                                                            <input type="text"
                                                                name="variants[{{ $index }}][attributes][{{ $attrIndex }}][value]"
                                                                value="{{ old("variants.$index.attributes.$attrIndex.value", $attrValue->value) }}"
                                                                placeholder="Giá trị thuộc tính"
                                                                class="w-2/3 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                                readonly>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div>
                                                    <label class="block text-gray-700 font-medium mb-1">Hình ảnh</label>
                                                    <input type="file" name="variant_images[{{ $index }}][]"
                                                        multiple
                                                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                        accept="image/*"
                                                        onchange="previewVariantImage(event, {{ $index }})">
                                                    <div id="preview-images-{{ $index }}"
                                                        class="mt-2 flex flex-wrap gap-2">
                                                        @foreach ($variant->images as $image)
                                                            <div class="relative">
                                                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                                                    class="w-24 h-24 object-cover rounded-md border border-gray-300">
                                                                <button type="button"
                                                                    class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-md"
                                                                    onclick="removeImage(this)">✖</button>
                                                                <input type="hidden"
                                                                    name="existing_variant_images[{{ $index }}][]"
                                                                    value="{{ $image->image_path }}">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" id="generate-variants-btn"
                                    class="mt-4 bg-green-500 text-white px-3 py-2 rounded-md hover:bg-green-600">Tạo biến
                                    thể từ thuộc tính</button>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-span-12 lg:col-span-4">
                            <!-- Category & Brand Section -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <p class="text-gray-700 font-medium mb-4">Chi tiết sản phẩm</p>
                                <div class="flex flex-col gap-3 mb-4">
                                    <div class="">
                                        <label for="brand_id" class="block text-gray-700 font-medium mb-1">Thương
                                            hiệu</label>
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
                                                                {{ in_array($brand->id, old('brand_ids', $product->brands->pluck('id')->toArray())) ? 'checked' : '' }}>
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
                                                                        {{ in_array($child->id, old('brand_ids', $product->brands->pluck('id')->toArray())) ? 'checked' : '' }}>
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
                                                                                    {{ in_array($grandchild->id, old('brand_ids', $product->brands->pluck('id')->toArray())) ? 'checked' : '' }}>
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
                                    <div class="">
                                        <label for="category_id" class="block text-gray-700 font-medium mb-1">Danh
                                            mục</label>
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
                                                                {{ in_array($category->id, old('category_ids', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
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
                                                                        {{ in_array($child->id, old('category_ids', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
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
                                                                            <div class="flex items-center gap-2 ">
                                                                                <input type="checkbox"
                                                                                    name="category_ids[]"
                                                                                    id="category_{{ $grandchild->id }}"
                                                                                    class="border border-gray-300 rounded-md p-2 focus:outline-none"
                                                                                    value="{{ $grandchild->id }}"
                                                                                    {{ in_array($grandchild->id, old('category_ids', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                                                <label
                                                                                    for="category_{{ $grandchild->id }}"
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
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_featured" value="1" class="mr-2"
                                            {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                        <span class="text-gray-700 font-medium">Sản phẩm nổi bật</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Từ khóa (Tags)</label>
                                    <input type="text" name="meta_keywords" id="meta-keywords"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Thêm từ khóa (phân cách bằng dấu phẩy)"
                                        value="{{ old('meta_keywords', $product->meta_keywords) }}">
                                    @error('meta_keywords')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- SEO Section -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <h4 class="text-xl font-semibold mb-4">SEO</h4>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-1">Tiêu đề SEO (Meta Title) <span
                                            id="meta-title-count">0/60</span></label>
                                    <input type="text" name="meta_title" id="meta-title"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="{{ old('meta_title', $product->meta_title) }}" maxlength="60"
                                        placeholder="Tiêu đề SEO (tối đa 60 ký tự)">
                                    <span class="text-sm text-gray-500 block mt-1">Tiêu đề hiển thị trên công cụ tìm kiếm,
                                        nên chứa từ khóa chính.</span>
                                    @error('meta_title')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-1">Mô tả SEO (Meta Description) <span
                                            id="meta-description-count">0/160</span></label>
                                    <textarea name="meta_description" id="meta-description"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        maxlength="160" placeholder="Mô tả ngắn gọn (tối đa 160 ký tự)">{{ old('meta_description', $product->meta_description) }}</textarea>
                                    <span class="text-sm text-gray-500 block mt-1">Mô tả hiển thị dưới tiêu đề trên công cụ
                                        tìm kiếm.</span>
                                    @error('meta_description')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-1">Xem trước SEO</label>
                                    <div id="seo-preview" class="card px-3 py-2"
                                        style="max-width: 600px; border: 1px solid #ddd;">
                                        <h5 id="preview-title" class="text-blue-600 mb-1">
                                            {{ $product->meta_title ?: $product->name }}</h5>
                                        <p id="preview-url" class="text-green-600 mb-1">
                                            https://Zynox.com/san-pham/{{ Str::slug(old('name', $product->name)) }}
                                        </p>
                                        <p id="preview-description" class="text-gray-600">
                                            {{ $product->meta_description ?: 'Mô tả ngắn gọn về sản phẩm.' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Image Section (Ảnh chính) -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <p class="text-gray-700 font-medium mb-4">Tải ảnh chính lên</p>
                                <div class="text-center">
                                    <img id="uploadIcon1" class="w-24 h-auto mx-auto mb-2"
                                        src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : 'https://html.hixstudio.net/ebazer/assets/img/icons/upload.png' }}"
                                        alt="{{ $product->images->first() ? 'Uploaded Image' : 'Upload Icon' }}">
                                    <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh phải nhỏ hơn 5Mb</span>
                                    <label for="mainImage"
                                        class="block w-full py-2 px-4 border border-gray-300 rounded-md text-center text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                                        Tải ảnh chính lên
                                    </label>
                                    <input type="hidden" name="default_image_index" value="0">
                                    <input type="file" id="mainImage" name="main_image" class="hidden"
                                        accept="image/*">
                                    @error('images.*')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Upload Nhiều Hình Ảnh Section (Nhiều hình ảnh) -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <p class="text-gray-700 font-medium mb-4">Tải nhiều hình ảnh lên</p>
                                <div class="text-center">
                                    <div id="uploadIconContainer2"
                                        class="mb-2 {{ $product->images->whereNull('variantID')->count() > 1 ? 'hidden' : '' }}">
                                        <img id="uploadIcon2" class="w-24 h-auto mx-auto mb-2"
                                            src="https://html.hixstudio.net/ebazer/assets/img/icons/upload.png"
                                            alt="Upload Icon">
                                    </div>

                                    <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh phải nhỏ hơn 5Mb</span>

                                    <div id="existingImagesPreview" class="flex flex-wrap gap-2 mb-2 w-[415px]">
                                        @foreach ($product->images->whereNull('variantID')->slice(1) as $image)
                                            <div class="relative existing-image">
                                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                                    class="w-24 h-24 object-cover rounded-md border">
                                                <button type="button"
                                                    class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-md"
                                                    onclick="removeImage(this)">✖</button>
                                                <input type="hidden" name="existing_images[]"
                                                    value="{{ $image->image_path }}">
                                            </div>
                                        @endforeach
                                    </div>

                                    <div id="newImagesPreview" class="flex flex-wrap gap-2 mb-2 w-[415px]"></div>

                                    <label for="additionalImages"
                                        class="block w-full py-2 px-4 border border-gray-300 rounded-md text-center text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                                        Thêm ảnh mới
                                    </label>
                                    <input type="file" id="additionalImages" name="images[]" class="hidden" multiple
                                        accept="image/*">

                                    @error('images')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                    @error('images.*')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex justify-start space-x-3 mt-6 col-span-12">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Lưu
                            và
                            cập nhật</button>
                        <button type="submit" name="save_draft" value="1"
                            class="border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-100">Lưu
                            nháp</button>
                    </div>
                </div>
        </form>
    </div>

    @php
        // Lấy các giá trị thuộc tính từ $attributes
        $sanitizedAttributes = $attributes
            ->map(function ($attr) {
                return [
                    'id' => $attr->id ?? '',
                    'name' => $attr->name ?? '',
                    'values' => $attr->values->pluck('value')->toArray(),
                ];
            })
            ->filter(function ($attr) {
                return !empty($attr['id']) && !empty($attr['name']);
            })
            ->values()
            ->toArray();

        // Thêm các thuộc tính khác từ $allAttributes để hiển thị trong dropdown
        $additionalAttributes = $allAttributes
            ->filter(function ($attr) use ($attributes) {
                return !$attributes->contains('id', $attr->id);
            })
            ->map(function ($attr) {
                return [
                    'id' => $attr->id ?? '',
                    'name' => $attr->name ?? '',
                    'values' => $attr->values->pluck('value')->toArray(),
                ];
            })
            ->filter(function ($attr) {
                return !empty($attr['id']) && !empty($attr['name']);
            })
            ->values()
            ->toArray();

        // Kết hợp $sanitizedAttributes và $additionalAttributes
        $sanitizedAttributes = array_merge($sanitizedAttributes, $additionalAttributes);

        // Ghi log JavaScript để kiểm tra
        $sanitizedAttributesLog = json_encode($sanitizedAttributes, JSON_PRETTY_PRINT);
    @endphp

    @push('scripts')
        <script>
            console.log('Sanitized Attributes:', @json($sanitizedAttributes));

            function debugLog(message, data = null) {
                console.log(`[DEBUG] ${message}`, data);
            }

            // Dữ liệu thuộc tính có sẵn từ server
            const allAttributes = @json($sanitizedAttributes);

            // Hàm cập nhật giá trị thuộc tính khi chọn từ dropdown
            function updateAttributeValues(select) {
                debugLog('Updating attribute values', {
                    selectValue: select.value
                });
                const row = select.closest('.attribute-row');
                const nameInput = row.querySelector('.attribute-name');
                const valuesInput = row.querySelector('.attribute-values');

                if (!nameInput || !valuesInput) {
                    debugLog('Attribute name or values input not found');
                    return;
                }

                if (select.value === 'new') {
                    nameInput.classList.remove('hidden');
                    nameInput.value = '';
                    valuesInput.value = '';
                } else {
                    nameInput.classList.add('hidden');
                    const selectedAttribute = allAttributes.find(attr => attr.id == select.value);
                    if (selectedAttribute) {
                        valuesInput.value = selectedAttribute.values.length > 0 ? selectedAttribute.values.join(', ') : '';
                        debugLog('Attribute values updated', {
                            values: selectedAttribute.values
                        });
                    } else {
                        valuesInput.value = '';
                        debugLog('No attribute found for selected ID', {
                            selectValue: select.value
                        });
                    }
                }
            }

            // Hàm xử lý preview ảnh chính
            function handleMainImagePreview(inputId, iconId) {
                debugLog('Initializing main image preview');
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
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            uploadIcon.src = event.target.result;
                            uploadIcon.alt = 'Uploaded Image';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        uploadIcon.src = "https://html.hixstudio.net/ebazer/assets/img/icons/upload.png";
                        uploadIcon.alt = 'Upload Icon';
                    }
                });
            }

            // Hàm xử lý preview nhiều ảnh
            function handleAdditionalImagesPreview(inputId, iconContainerId) {
                debugLog('Initializing additional images preview');
                const input = document.getElementById(inputId);
                const uploadIconContainer = document.getElementById(iconContainerId);
                const newPreviewContainer = document.getElementById('newImagesPreview');

                if (!input || !uploadIconContainer || !newPreviewContainer) {
                    debugLog('Additional images elements not found', {
                        inputId,
                        iconContainerId
                    });
                    return;
                }

                input.addEventListener('change', function(e) {
                    const files = e.target.files;
                    newPreviewContainer.innerHTML = '';

                    if (files.length > 0) {
                        uploadIconContainer.classList.add('hidden');
                        Array.from(files).forEach(file => {
                            if (file.size > 5 * 1024 * 1024) {
                                alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                                input.value = '';
                                newPreviewContainer.innerHTML = '';
                                uploadIconContainer.classList.remove('hidden');
                                return;
                            }
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                const imgContainer = document.createElement('div');
                                imgContainer.className = 'relative';
                                imgContainer.innerHTML = `
                            <img src="${event.target.result}" class="w-24 h-24 object-cover rounded-md border">
                            <button type="button" class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-md" onclick="removeImage(this)">✖</button>
                        `;
                                newPreviewContainer.appendChild(imgContainer);
                            };
                            reader.readAsDataURL(file);
                        });
                    } else {
                        uploadIconContainer.classList.remove('hidden');
                    }
                });
            }

            // Hàm xử lý preview ảnh biến thể
            function previewVariantImage(event, index) {
                debugLog('Previewing variant images', {
                    index
                });
                let previewContainer = document.getElementById(`preview-images-${index}`);
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
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let imgContainer = document.createElement('div');
                        imgContainer.classList.add('relative');
                        imgContainer.innerHTML = `
                    <img src="${e.target.result}" class="w-24 h-24 object-cover rounded-md border border-gray-300">
                    <button type="button" class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded" onclick="removeImage(this)">✖</button>
                `;
                        previewContainer.appendChild(imgContainer);
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Hàm xóa ảnh
            function removeImage(element) {
                debugLog('Removing image');
                element.parentElement.remove();
            }

            document.addEventListener('DOMContentLoaded', function() {
                debugLog('DOM fully loaded');
                debugLog('All attributes loaded', {
                    allAttributes
                });

                // Khởi tạo preview ảnh chính
                handleMainImagePreview('mainImage', 'uploadIcon1');

                // Khởi tạo preview ảnh phụ
                handleAdditionalImagesPreview('additionalImages', 'uploadIconContainer2');

                // Hàm xử lý toggle biến thể
                function initializeToggleButtons() {
                    debugLog('Initializing toggle buttons');
                    const toggleButtons = document.querySelectorAll('.toggle-variants');
                    debugLog('Found toggle buttons', {
                        count: toggleButtons.length
                    });

                    toggleButtons.forEach(button => {
                        button.removeEventListener('click', handleToggleClick);

                        const index = button.getAttribute('data-index');
                        const variantItem = button.closest('.variant-item');
                        const variantContent = variantItem ? variantItem.querySelector('.variant-content') :
                            null;
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
                        const subBrandContainer = document.querySelector(
                            `.sub-brands[data-brand-id="${brandId}"]`);
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

                // Hàm khởi tạo trạng thái ban đầu cho các dropdown thuộc tính
                function initializeAttributeSelects() {
                    debugLog('Initializing attribute selects');
                    const selects = document.querySelectorAll('.attribute-select');
                    selects.forEach(select => {
                        if (select.value) {
                            updateAttributeValues(select);
                        }
                    });
                }

                // Hàm thêm thuộc tính
                function addAttribute() {
                    debugLog('Adding new attribute');
                    let container = document.getElementById('attribute-container');
                    if (!container) {
                        debugLog('Attribute container not found');
                        return;
                    }
                    let index = container.querySelectorAll('.attribute-row').length;
                    let newAttribute = document.createElement('div');
                    newAttribute.classList.add('mb-4', 'flex', 'items-center', 'gap-4', 'attribute-row');
                    newAttribute.innerHTML = `
                <select name="attributes[${index}][id]" class="w-1/3 border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-select" onchange="updateAttributeValues(this)">
                    <option value="" disabled selected>Chọn hoặc nhập thuộc tính</option>
                    <option value="new">Tạo thuộc tính mới</option>
                    ${allAttributes
                        .filter(attr => attr.id && attr.name)
                        .map(attr => `<option value="${attr.id}">${attr.name}</option>`)
                        .join('')}
                </select>
                <input type="text" name="attributes[${index}][name]" class="w-1/3 border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-name hidden" placeholder="Tên thuộc tính (VD: Màu sắc, Kích thước)">
                <input type="text" name="attributes[${index}][values]" class="w-2/3 border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-values" placeholder="Giá trị (VD: Đỏ, Xanh, Vàng)">
                <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-attribute">Xóa</button>
            `;
                    container.appendChild(newAttribute);
                    debugLog('New attribute row added', {
                        index
                    });

                    newAttribute.querySelector('.remove-attribute').addEventListener('click', function() {
                        debugLog('Removing attribute row', {
                            index
                        });
                        newAttribute.remove();
                    });
                }

                // Hàm tạo biến thể
                function generateVariants() {
                    debugLog('Generating variants');
                    let selects = document.querySelectorAll('[name^="attributes["][name$="[id]"]');
                    let names = document.querySelectorAll('[name^="attributes["][name$="[name]"]');
                    let values = document.querySelectorAll('[name^="attributes["][name$="[values]"]');
                    let variantContainer = document.getElementById('variant-container');

                    if (!variantContainer) {
                        debugLog('Variant container not found');
                        return;
                    }

                    variantContainer.innerHTML = '';
                    let attributeData = [];
                    let hasValidAttribute = false;

                    selects.forEach((select, index) => {
                        const attrId = select.value;
                        let attrName = names[index].value.trim();
                        const valuesArray = values[index].value
                            .split(',')
                            .map(v => v.trim())
                            .filter(v => v);

                        if (attrId !== 'new' && attrId) {
                            const selectedAttribute = allAttributes.find(attr => attr.id == attrId);
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

                    let variants = getCombinations(attributeData.map(attr => attr.values));
                    debugLog('Generated variants', variants);

                    variants.forEach((variant, index) => {
                        let variantDiv = document.createElement('div');
                        variantDiv.classList.add('p-6', 'border', 'border-gray-300', 'rounded-md', 'mb-6',
                            'bg-white', 'relative', 'variant-item');
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
                        variantDiv.querySelector('.remove-variant').addEventListener('click', function() {
                            debugLog('Removing variant', {
                                index
                            });
                            variantDiv.remove();
                            updateVariantIndices();
                        });
                    });
                    initializeToggleButtons();
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

                // Hàm tạo tổ hợp biến thể
                function getCombinations(arr) {
                    debugLog('Generating combinations', arr);
                    return arr.reduce((acc, val) => acc.flatMap(a => val.map(v => [...a, v])), [
                        []
                    ]);
                }

                // Gắn sự kiện cho nút xóa thuộc tính
                document.querySelectorAll('.remove-attribute').forEach(button => {
                    button.addEventListener('click', function() {
                        debugLog('Remove attribute button clicked');
                        this.parentElement.remove();
                    });
                });

                // Gắn xử lý sự kiện submit form
                const productForm = document.getElementById('product-form');
                if (productForm) {
                    productForm.addEventListener('submit', function(e) {
                        debugLog('Form submitted');
                        const brandCheckboxes = document.querySelectorAll('input[name="brand_ids[]"]:checked');
                        const categoryCheckboxes = document.querySelectorAll(
                            'input[name="category_ids[]"]:checked');
                        if (brandCheckboxes.length === 0) {
                            e.preventDefault();
                            alert('Vui lòng chọn ít nhất một thương hiệu.');
                            debugLog('Form submission prevented: No brands selected');
                        }
                        if (categoryCheckboxes.length === 0) {
                            e.preventDefault();
                            alert('Vui lòng chọn ít nhất một danh mục.');
                            debugLog('Form submission prevented: No categories selected');
                        }
                        if (typeof tinymce !== 'undefined') {
                            tinymce.triggerSave();
                            debugLog('TinyMCE saved', document.getElementById('description').value);
                        }
                    });
                } else {
                    debugLog('Product form not found');
                }

                // Gắn sự kiện cho nút Thêm thuộc tính
                const addAttributeButton = document.getElementById('add-attribute-btn');
                if (addAttributeButton) {
                    debugLog('Add attribute button found');
                    addAttributeButton.addEventListener('click', function() {
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
                    generateVariantsButton.addEventListener('click', function() {
                        debugLog('Generate variants button clicked');
                        generateVariants();
                    });
                } else {
                    debugLog('Generate variants button NOT found');
                }

                // Khởi tạo trạng thái ban đầu
                initializeAttributeSelects();
                initializeToggleButtons();
                initializeSubCategoryToggles();
                initializeSubBrandToggles();
            });
        </script>
    @endpush
@endsection
