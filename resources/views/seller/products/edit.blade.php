@extends('layouts.seller_home')

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
                <!-- Left Column -->
                <div class="col-span-12 lg:col-span-12">
                    <div class="flex gap-6">
                        <div class="">
                            <!-- General Section -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <h4 class="text-xl font-semibold mb-4">Thông tin chung</h4>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-1">Tên sản phẩm <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="product-name"
                                        class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Tên sản phẩm" value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                    <span class="text-sm text-gray-500 block mt-1">Tên sản phẩm nên là duy nhất.</span>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Mô tả</label>
                                    <textarea id="description" name="description"
                                        class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">{!! old('description', $product->description) !!}</textarea>
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
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="SKU sản phẩm" value="{{ old('sku', $product->sku) }}" required>
                                        @error('sku')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Số lượng tồn kho <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" name="stock_total"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Chiều dài sản phẩm"
                                            value="{{ old('length', $product->dimensions->isNotEmpty() ? $product->dimensions->first()->length : 0) }}">
                                        @error('length')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Chiều rộng (inch)</label>
                                        <input type="number" name="width" step="0.01"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Chiều rộng sản phẩm"
                                            value="{{ old('width', $product->dimensions->isNotEmpty() ? $product->dimensions->first()->width : 0) }}">
                                        @error('width')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Chiều cao (inch)</label>
                                        <input type="number" name="height" step="0.01"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Chiều cao sản phẩm"
                                            value="{{ old('height', $product->dimensions->isNotEmpty() ? $product->dimensions->first()->height : 0) }}">
                                        @error('height')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Trọng lượng (kg)</label>
                                        <input type="number" name="weight" step="0.01"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                                        <div class="mb-4 flex items-center gap-4 attribute-item">
                                            <input type="text" name="attributes[{{ $index }}][name]"
                                                value="{{ old("attributes.$index.name", $attribute->name) }}"
                                                placeholder="Tên thuộc tính (VD: Màu sắc, Kích thước)"
                                                class="w-1/3 border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                required>
                                            <input type="text" name="attributes[{{ $index }}][values]"
                                                value="{{ old("attributes.$index.values", $attribute->values->pluck('value')->implode(', ') ?? '') }}"
                                                placeholder="Giá trị (VD: Đỏ, Xanh, Vàng)"
                                                class="w-2/3 border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                required>
                                            <button type="button"
                                                class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-attribute">Xóa</button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button"
                                    class="ml-3 bg-green-500 text-white px-3 py-2 rounded-md hover:bg-green-600"
                                    onclick="addAttribute()">Thêm</button>
                            </div>

                            <!-- Khu vực hiển thị biến thể -->
                            <div id="variants-section" class="bg-white p-6 rounded-lg shadow-sm">
                                <h4 class="text-xl font-semibold mb-4">Biến thể sản phẩm</h4>
                                <div id="variant-container">
                                    @foreach ($product->variants as $index => $variant)
                                        <div class="p-6 border border-gray-300 rounded-md mb-6 bg-white relative">
                                            <div class="flex justify-between items-center mb-3">
                                                <h5 class="text-lg font-semibold">Biến thể {{ $index + 1 }}:
                                                    {{ $variant->variant_name }}</h5>
                                                <button type="button"
                                                    class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-variant">Xóa</button>
                                            </div>
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
                                                    <label class="block text-gray-700 font-medium mb-1">Giá nhập</label>
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
                                                        name="variants[{{ $index }}][sale_price]" step="0.01"
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
                                                        value="{{ old("variants.$index.sku", $variant->sku) }}" required>
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
                                                <!-- Thêm các trường kích thước cho biến thể -->
                                                <div>
                                                    <label class="block text-gray-700 font-medium mb-1">Chiều dài
                                                        (inch)</label>
                                                    <input type="number" name="variants[{{ $index }}][length]"
                                                        step="0.01"
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
                                                    <input type="number" name="variants[{{ $index }}][height]"
                                                        step="0.01"
                                                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                        placeholder="Chiều cao"
                                                        value="{{ old("variants.$index.height", optional($variant->dimensions)->height ?? 0) }}">
                                                </div>
                                                <div>
                                                    <label class="block text-gray-700 font-medium mb-1">Trọng lượng
                                                        (kg)</label>
                                                    <input type="number" name="variants[{{ $index }}][weight]"
                                                        step="0.01"
                                                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                        placeholder="Trọng lượng"
                                                        value="{{ old("variants.$index.weight", optional($variant->dimensions)->weight ?? 0) }}">
                                                </div>
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
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button"
                                    class="mt-4 bg-green-500 text-white px-3 py-2 rounded-md hover:bg-green-600"
                                    onclick="generateVariants()">Tạo biến thể từ thuộc tính</button>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-span-12 lg:col-span-4">
                            <!-- SEO Section -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <h4 class="text-xl font-semibold mb-4">SEO</h4>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-1">Tiêu đề SEO (Meta Title) <span
                                            id="meta-title-count">0/60</span></label>
                                    <input type="text" name="meta_title" id="meta-title"
                                        class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                                        class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        maxlength="160" placeholder="Mô tả ngắn gọn (tối đa 160 ký tự)">{{ old('meta_description', $product->meta_description) }}</textarea>
                                    <span class="text-sm text-gray-500 block mt-1">Mô tả hiển thị dưới tiêu đề trên công cụ
                                        tìm kiếm.</span>
                                    @error('meta_description')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-1">Xem trước SEO</label>
                                    <div id="seo-preview" class="card p-3"
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
                                    <input type="file" id="mainImage" name="images[]" class="hidden"
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
                                        class="mb-2 {{ $product->images->count() > 1 ? 'hidden' : '' }}">
                                        <img id="uploadIcon2" class="w-24 h-auto mx-auto mb-2"
                                            src="https://html.hixstudio.net/ebazer/assets/img/icons/upload.png"
                                            alt="Upload Icon">
                                    </div>
                                    <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh phải nhỏ hơn 5Mb</span>
                                    <div id="additionalImagesPreview"
                                        class="mt-2 mb-2 flex flex-wrap gap-2 {{ $product->images->count() > 1 ? '' : 'hidden' }}">
                                        @foreach ($product->images->slice(1) as $image)
                                            <div class="relative">
                                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                                    class="w-24 h-24 object-cover rounded-md border">
                                                <button type="button"
                                                    class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-md"
                                                    onclick="removeImage(this)">✖</button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <label for="additionalImages"
                                        class="block w-full py-2 px-4 border border-gray-300 rounded-md text-center text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">
                                        Tải nhiều hình ảnh lên
                                    </label>
                                    <input type="file" id="additionalImages" name="images[]" class="hidden" multiple
                                        accept="image/*">
                                    @error('images.*')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Product Details Section -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <p class="text-gray-700 font-medium mb-4">Chi tiết sản phẩm</p>
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Thương hiệu</label>
                                        <select name="brand" id="brand"
                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                            <option value="">Chọn thương hiệu</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->name }}"
                                                    {{ old('brand', $product->brand) == $brand->name ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('brand')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Danh mục</label>
                                        <select name="category" id="category"
                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                            <option value="">Chọn danh mục</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->name }}"
                                                    {{ old('category', $product->category) == $category->name ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category')
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
                                        class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Thêm từ khóa (phân cách bằng dấu phẩy)"
                                        value="{{ old('meta_keywords', $product->meta_keywords) }}">
                                    @error('meta_keywords')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="flex justify-start space-x-3 mt-6 col-span-12">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Lưu và
                        cập nhật</button>
                    <button type="submit" name="save_draft" value="1"
                        class="border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-100">Lưu
                        nháp</button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Tương tự script trong edit.blade.php
            document.addEventListener('DOMContentLoaded', function() {
                tinymce.init({
                    selector: '#description',
                    height: 300,
                    plugins: 'image imagetools code link lists table media',
                    toolbar: 'undo redo | styles | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | forecolor backcolor | code | removeformat',
                    images_upload_url: '{{ route('seller.upload.image') }}',
                    image_advtab: true,
                    image_caption: true,
                    file_picker_types: 'image',
                    file_picker_callback: function(cb, value, meta) {
                        const input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/*');
                        input.onchange = function() {
                            const file = this.files[0];
                            const reader = new FileReader();
                            reader.onload = function() {
                                const id = 'blobid' + (new Date()).getTime();
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
                    setup: function(editor) {
                        editor.on('change', function() {
                            editor.save();
                        });
                    }
                });

                const productForm = document.getElementById('product-form');
                if (productForm) {
                    productForm.addEventListener('submit', function(e) {
                        tinymce.triggerSave();
                        console.log('Description:', document.getElementById('description').value);
                    });
                }

                // Xử lý SEO Preview
                const productName = document.getElementById('product-name');
                const metaTitle = document.getElementById('meta-title');
                const metaTitleCount = document.getElementById('meta-title-count');
                const previewTitle = document.getElementById('preview-title');
                const previewUrl = document.getElementById('preview-url');
                const previewDescription = document.getElementById('preview-description');
                const metaDescription = document.getElementById('meta-description');
                const metaDescriptionCount = document.getElementById('meta-description-count');

                let metaTitleEditedManually = false;

                function slugify(text) {
                    return text
                        .toLowerCase()
                        .normalize("NFD")
                        .replace(/[\u0300-\u036f]/g, "")
                        .replace(/đ/g, "d")
                        .replace(/[^a-z0-9 -]/g, "")
                        .replace(/\s+/g, "-")
                        .replace(/-+/g, "-");
                }

                function updateSEOPreview() {
                    if (!metaTitleEditedManually) {
                        metaTitle.value = productName.value;
                    }
                    metaTitleCount.textContent = `${metaTitle.value.length}/60`;
                    metaDescriptionCount.textContent = `${metaDescription.value.length}/160`;
                    previewTitle.textContent = metaTitle.value || productName.value || 'Tiêu đề sản phẩm';
                    previewUrl.textContent = `https://Zynox.com/san-pham/${slugify(productName.value || 'san-pham')}`;
                    previewDescription.textContent = metaDescription.value || 'Mô tả ngắn gọn về sản phẩm.';
                }

                productName.addEventListener('input', function() {
                    if (!metaTitleEditedManually) {
                        metaTitle.value = productName.value;
                        updateSEOPreview();
                    }
                });

                metaTitle.addEventListener('input', function() {
                    metaTitleEditedManually = true;
                    updateSEOPreview();
                });

                metaDescription.addEventListener('input', updateSEOPreview);
                updateSEOPreview();

                // Xử lý preview ảnh chính
                function handleMainImagePreview(inputId, iconId) {
                    const input = document.getElementById(inputId);
                    const uploadIcon = document.getElementById(iconId);

                    input.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            if (file.size > 5 * 1024 * 1024) {
                                alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                                input.value = '';
                                uploadIcon.src =
                                    "https://html.hixstudio.net/ebazer/assets/img/icons/upload.png";
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

                // Xử lý preview nhiều hình ảnh
                function handleAdditionalImagesPreview(inputId, iconContainerId, previewContainerId) {
                    const input = document.getElementById(inputId);
                    const uploadIconContainer = document.getElementById(iconContainerId);
                    const previewContainer = document.getElementById(previewContainerId);

                    input.addEventListener('change', function(e) {
                        const files = e.target.files;
                        if (files.length > 0) {
                            uploadIconContainer.classList.add('hidden');
                            previewContainer.classList.remove('hidden');
                            previewContainer.innerHTML = '';

                            for (let i = 0; i < files.length; i++) {
                                const file = files[i];
                                if (file.size > 5 * 1024 * 1024) {
                                    alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                                    input.value = '';
                                    uploadIconContainer.classList.remove('hidden');
                                    previewContainer.classList.add('hidden');
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
                                    previewContainer.appendChild(imgContainer);
                                };
                                reader.readAsDataURL(file);
                            }
                        } else {
                            uploadIconContainer.classList.remove('hidden');
                            previewContainer.classList.add('hidden');
                        }
                    });
                }

                handleMainImagePreview('mainImage', 'uploadIcon1');
                handleAdditionalImagesPreview('additionalImages', 'uploadIconContainer2', 'additionalImagesPreview');

                // Xử lý thuộc tính
                document.querySelectorAll('.remove-attribute').forEach(button => {
                    button.addEventListener('click', function() {
                        this.parentElement.remove();
                    });
                });

                // Xử lý biến thể
                document.querySelectorAll('.remove-variant').forEach(button => {
                    button.addEventListener('click', function() {
                        this.closest('.p-6').remove();
                        updateVariantIndices();
                    });
                });
            });

            function addAttribute() {
                let container = document.getElementById('attribute-container');
                let index = container.querySelectorAll('.mb-4').length;
                let newAttribute = document.createElement('div');
                newAttribute.classList.add('mb-4', 'flex', 'items-center', 'gap-4');
                newAttribute.innerHTML = `
                <input type="text" name="attributes[${index}][name]" placeholder="Tên thuộc tính (VD: Màu sắc, Kích thước)"
                    class="w-1/3 border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <input type="text" name="attributes[${index}][values]" placeholder="Giá trị (VD: Đỏ, Xanh, Vàng)"
                    class="w-2/3 border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-attribute">Xóa</button>
            `;
                container.appendChild(newAttribute);
                newAttribute.querySelector('.remove-attribute').addEventListener('click', function() {
                    newAttribute.remove();
                });
            }

            function generateVariants() {
                let attributes = document.querySelectorAll('[name^="attributes["][name$="[name]"]');
                let values = document.querySelectorAll('[name^="attributes["][name$="[values]"]');
                let variantContainer = document.getElementById('variant-container');

                variantContainer.innerHTML = '';

                let attributeData = [];
                let hasValidAttribute = false;

                attributes.forEach((attr, index) => {
                    const attrName = attr.value.trim();
                    const valuesArray = values[index].value
                        .split(',')
                        .map(v => v.trim())
                        .filter(v => v);
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
                    return;
                }

                let variants = getCombinations(attributeData.map(attr => attr.values));

                variants.forEach((variant, index) => {
                    let variantDiv = document.createElement('div');
                    variantDiv.classList.add('p-6', 'border', 'border-gray-300', 'rounded-md', 'mb-6', 'bg-white',
                        'relative');
                    let variantHTML = `
            <div class="flex justify-between items-center mb-3">
                <h5 class="text-lg font-semibold">Biến thể ${index + 1}: ${variant.join(' - ')}</h5>
                <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-variant">Xóa</button>
            </div>
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
            <div>
                <label class="block text-gray-700 font-medium mb-1">Hình ảnh</label>
                <input type="file" name="variant_images[${index}][]" multiple class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" accept="image/*" onchange="previewVariantImage(event, ${index})">
                <div id="preview-images-${index}" class="mt-2 flex flex-wrap gap-2"></div>
            </div>
        `;

                    // Thêm input ẩn cho thuộc tính của biến thể
                    variant.forEach((value, attrIndex) => {
                        variantHTML += `
                <input type="hidden" name="variants[${index}][attributes][${attrIndex}][name]" value="${attributeData[attrIndex].name}">
                <input type="hidden" name="variants[${index}][attributes][${attrIndex}][value]" value="${value}">
            `;
                    });

                    variantDiv.innerHTML = variantHTML;
                    variantContainer.appendChild(variantDiv);

                    variantDiv.querySelector('.remove-variant').addEventListener('click', function() {
                        variantDiv.remove();
                        updateVariantIndices();
                    });
                });

                updateVariantIndices();
            }

            function updateVariantIndices() {
                const variantItems = document.querySelectorAll('#variant-container > div');
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
                    item.querySelector('div[id^="preview-images-"]').id = `preview-images-${index}`;
                    item.querySelector('input[type="file"]').setAttribute('onchange',
                        `previewVariantImage(event, ${index})`);
                });
            }

            function getCombinations(arr) {
                return arr.reduce((acc, val) => acc.flatMap(a => val.map(v => [...a, v])), [
                    []
                ]);
            }

            function previewVariantImage(event, index) {
                let previewContainer = document.getElementById(`preview-images-${index}`);
                previewContainer.innerHTML = '';
                Array.from(event.target.files).forEach((file, fileIndex) => {
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

            function removeImage(element) {
                element.parentElement.remove();
            }
        </script>
    @endpush
@endsection
