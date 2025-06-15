@extends('layouts.seller')

@section('content')
    <div class="container">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Chỉnh sửa sản phẩm</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
            id="product-form">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 lg:col-span-12">
                    <div class="flex gap-6">
                        <!-- Left Column -->
                        <div class="w-full lg:w-2/3">
                            <!-- General Section -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <h4 class="text-xl font-semibold mb-4">Thông tin chung</h4>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-1">Tên sản phẩm <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="product-name"
                                        value="{{ old('name', $product->name) }}"
                                        class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Tên sản phẩm" required>
                                    @error('name')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
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
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Giá gốc <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" name="price" step="0.01"
                                            value="{{ old('price', $product->price) }}"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Giá gốc của sản phẩm" required>
                                        @error('price')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Giá nhập <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" name="purchase_price" step="0.01"
                                            value="{{ old('purchase_price', $product->purchase_price) }}"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Giá nhập sản phẩm" required>
                                        @error('purchase_price')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Giá bán <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" name="sale_price" step="0.01"
                                            value="{{ old('sale_price', $product->sale_price) }}"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Giá bán sản phẩm" required>
                                        @error('sale_price')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">SKU sản phẩm <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Mã SKU sản phẩm" required>
                                        @error('sku')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Số lượng tồn kho <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" name="stock_total"
                                            value="{{ old('stock_total', $product->stock_total) }}"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Số lượng tồn kho" required>
                                        @error('stock_total')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Chiều dài (inch)</label>
                                        <input type="number" name="length" step="0.01"
                                            value="{{ old('length', $product->length ?? 0) }}"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Chiều dài sản phẩm">
                                        @error('length')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Chiều rộng (inch)</label>
                                        <input type="number" name="width" step="0.01"
                                            value="{{ old('width', $product->width ?? 0) }}"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Chiều rộng sản phẩm">
                                        @error('width')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Chiều cao (inch)</label>
                                        <input type="number" name="height" step="0.01"
                                            value="{{ old('height', $product->height ?? 0) }}"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Chiều cao sản phẩm">
                                        @error('height')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Trọng lượng (kg)</label>
                                        <input type="number" name="weight" step="0.01"
                                            value="{{ old('weight', $product->weight ?? 0) }}"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Trọng lượng sản phẩm">
                                        @error('weight')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Biến thể -->
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
                                                        value="{{ old("variants.$index.price", $variant->price) }}"
                                                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                        placeholder="Nhập giá gốc">
                                                </div>
                                                <div>
                                                    <label class="block text-gray-700 font-medium mb-1">Giá nhập</label>
                                                    <input type="number"
                                                        name="variants[{{ $index }}][purchase_price]"
                                                        step="0.01"
                                                        value="{{ old("variants.$index.purchase_price", $variant->purchase_price) }}"
                                                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                        placeholder="Nhập giá nhập">
                                                </div>
                                                <div>
                                                    <label class="block text-gray-700 font-medium mb-1">Giá bán</label>
                                                    <input type="number"
                                                        name="variants[{{ $index }}][sale_price]" step="0.01"
                                                        value="{{ old("variants.$index.sale_price", $variant->sale_price) }}"
                                                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                        placeholder="Nhập giá bán">
                                                </div>
                                                <div>
                                                    <label class="block text-gray-700 font-medium mb-1">SKU</label>
                                                    <input type="text" name="variants[{{ $index }}][sku]"
                                                        value="{{ old("variants.$index.sku", $variant->sku) }}"
                                                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                        placeholder="Nhập SKU">
                                                </div>
                                                <div>
                                                    <label class="block text-gray-700 font-medium mb-1">Số lượng tồn
                                                        kho</label>
                                                    <input type="number"
                                                        name="variants[{{ $index }}][stock_total]"
                                                        value="{{ old("variants.$index.stock_total", $variant->stock) }}"
                                                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                        placeholder="Nhập số lượng">
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
                                                            <img src="{{ Storage::url($image->image_path) }}"
                                                                alt="{{ $image->alt_text }}"
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
                                <button type="button" id="add-variant"
                                    class="mt-4 bg-green-500 text-white px-3 py-2 rounded-md hover:bg-green-600">Thêm biến
                                    thể</button>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="w-full lg:w-1/3">
                            <!-- SEO Section -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <h4 class="text-xl font-semibold mb-4">SEO</h4>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-1">Tiêu đề SEO (Meta Title) <span
                                            id="meta-title-count">{{ strlen(old('meta_title', $product->meta_title)) }}/60</span></label>
                                    <input type="text" name="meta_title" id="meta-title"
                                        value="{{ old('meta_title', $product->meta_title) }}"
                                        class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        maxlength="60" placeholder="Tiêu đề SEO (tối đa 60 ký tự)">
                                    <span class="text-sm text-gray-500 block mt-1">Tiêu đề hiển thị trên công cụ tìm kiếm,
                                        nên chứa từ khóa chính.</span>
                                    @error('meta_title')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-1">Mô tả SEO (Meta Description) <span
                                            id="meta-description-count">{{ strlen(old('meta_description', $product->meta_description)) }}/160</span></label>
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
                                            {{ old('meta_title', $product->meta_title) ?: 'Tiêu đề sản phẩm' }}</h5>
                                        <p id="preview-url" class="text-green-600 mb-1">
                                            https://Zynox.com/san-pham/{{ Str::slug(old('name', $product->name)) }}</p>
                                        <p id="preview-description" class="text-gray-600">
                                            {{ old('meta_description', $product->meta_description) ?: 'Mô tả ngắn gọn về sản phẩm.' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Image Section (Ảnh chính) -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <p class="text-gray-700 font-medium mb-4">Tải ảnh chính lên</p>
                                <div class="text-center">
                                    <img id="uploadIcon1" class="w-24 h-auto mx-auto mb-2"
                                        src="{{ $product->images->first() ? Storage::url($product->images->first()->image_path) : 'https://html.hixstudio.net/ebazer/assets/img/icons/upload.png' }}"
                                        alt="Upload Icon">
                                    <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh phải nhỏ hơn 5Mb</span>
                                    <label for="mainImage"
                                        class="block w-full py-2 px-4 border border-gray-300 rounded-md text-center text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">Tải
                                        ảnh chính lên</label>
                                    <input type="file" id="mainImage" name="images[]" class="hidden"
                                        accept="image/*">
                                    @error('images.*')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Upload Nhiều Hình Ảnh Section -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <p class="text-gray-700 font-medium mb-4">Tải nhiều hình ảnh lên</p>
                                <div class="text-center">
                                    <div id="uploadIconContainer2" class="mb-2">
                                        <img id="uploadIcon2" class="w-24 h-auto mx-auto mb-2"
                                            src="https://html.hixstudio.net/ebazer/assets/img/icons/upload.png"
                                            alt="Upload Icon">
                                    </div>
                                    <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh phải nhỏ hơn 5Mb</span>
                                    <div id="additionalImagesPreview"
                                        class="mt-2 mb-2 flex flex-wrap gap-2 @if ($product->images->count() > 1) hidden @endif">
                                        @foreach ($product->images as $image)
                                            @if (!$loop->first)
                                                <!-- Loại bỏ ảnh chính -->
                                                <div class="relative">
                                                    <img src="{{ Storage::url($image->image_path) }}"
                                                        alt="{{ $image->alt_text }}"
                                                        class="w-24 h-24 object-cover rounded-md border">
                                                    <button type="button"
                                                        class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-md"
                                                        onclick="removeImage(this)">✖</button>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <label for="additionalImages"
                                        class="block w-full py-2 px-4 border border-gray-300 rounded-md text-center text-sm text-gray-700 hover:bg-blue-50 cursor-pointer">Tải
                                        nhiều hình ảnh lên</label>
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
                                                    {{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('brand')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Thương hiệu phụ</label>
                                        <select name="sub_brand" id="sub_brand"
                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Chọn thương hiệu phụ</option>
                                        </select>
                                        @error('sub_brand')
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
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Danh mục phụ</label>
                                        <select name="sub_category" id="sub_category"
                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Chọn danh mục phụ</option>
                                        </select>
                                        @error('sub_category')
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
                                        value="{{ old('meta_keywords', $product->meta_keywords) }}"
                                        class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Thêm từ khóa (phân cách bằng dấu phẩy)">
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
                        đăng</button>
                    <button type="submit" name="save_draft" value="1"
                        class="border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-100">Lưu
                        nháp</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Compressor.js -->
    <script src="https://unpkg.com/compressorjs@1.2.1/dist/compressor.min.js"></script>
    <script>
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

            // SEO Preview
            const productName = document.getElementById('product-name');
            const metaTitle = document.getElementById('meta-title');
            const metaTitleCount = document.getElementById('meta-title-count');
            const metaDescription = document.getElementById('meta-description');
            const metaDescriptionCount = document.getElementById('meta-description-count');
            const previewTitle = document.getElementById('preview-title');
            const previewUrl = document.getElementById('preview-url');
            const previewDescription = document.getElementById('preview-description');

            let metaTitleEditedManually = !!metaTitle.value;

            function slugify(text) {
                return text.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/đ/g, "d")
                    .replace(/[^a-z0-9 -]/g, "").replace(/\s+/g, "-").replace(/-+/g, "-");
            }

            function updateSEOPreview() {
                if (!metaTitleEditedManually && productName.value) {
                    metaTitle.value = productName.value.slice(0, 60);
                }
                metaTitleCount.textContent = `${metaTitle.value.length}/60`;
                metaDescriptionCount.textContent = `${metaDescription.value.length}/160`;
                previewTitle.textContent = metaTitle.value || productName.value || 'Tiêu đề sản phẩm';
                previewUrl.textContent = `https://Zynox.com/san-pham/${slugify(productName.value || 'san-pham')}`;
                previewDescription.textContent = metaDescription.value || 'Mô tả ngắn gọn về sản phẩm.';
            }

            productName.addEventListener('input', function() {
                if (!metaTitleEditedManually) {
                    metaTitle.value = productName.value.slice(0, 60);
                }
                updateSEOPreview();
            });

            metaTitle.addEventListener('input', function() {
                metaTitleEditedManually = true;
                updateSEOPreview();
            });

            metaDescription.addEventListener('input', updateSEOPreview);

            updateSEOPreview();

            // Image Preview
            handleMainImagePreview('mainImage', 'uploadIcon1');
            handleAdditionalImagesPreview('additionalImages', 'uploadIconContainer2', 'additionalImagesPreview');

            // Variant Management
            let variantCount = {{ $product->variants->count() }};
            const addVariantButton = document.getElementById('add-variant');
            if (addVariantButton) {
                addVariantButton.addEventListener('click', function() {
                    const container = document.getElementById('variant-container');
                    const variantItem = document.createElement('div');
                    variantItem.className = 'p-6 border border-gray-300 rounded-md mb-6 bg-white relative';
                    variantCount++;
                    variantItem.innerHTML = `
                        <div class="flex justify-between items-center mb-3">
                            <h5 class="text-lg font-semibold">Biến thể #${variantCount}</h5>
                            <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-variant">Xóa</button>
                        </div>
                        <input type="hidden" name="variants[${variantCount}][index]" value="${variantCount}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            <div><input type="text" name="variants[${variantCount}][name]" class="w-full border border-gray-300 rounded-md p-2" placeholder="Tên biến thể"></div>
                            <div><input type="number" name="variants[${variantCount}][price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2" placeholder="Giá gốc"></div>
                            <div><input type="number" name="variants[${variantCount}][purchase_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2" placeholder="Giá nhập"></div>
                            <div><input type="number" name="variants[${variantCount}][sale_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2" placeholder="Giá bán"></div>
                            <div><input type="text" name="variants[${variantCount}][sku]" class="w-full border border-gray-300 rounded-md p-2" placeholder="SKU"></div>
                            <div><input type="number" name="variants[${variantCount}][stock_total]" class="w-full border border-gray-300 rounded-md p-2" placeholder="Số lượng tồn kho"></div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Hình ảnh</label>
                            <input type="file" name="variant_images[${variantCount}][]" multiple class="w-full border border-gray-300 rounded-md p-2" accept="image/*" onchange="previewVariantImage(event, ${variantCount})">
                            <div id="preview-images-${variantCount}" class="mt-2 flex flex-wrap gap-2"></div>
                        </div>
                    `;
                    container.appendChild(variantItem);
                    variantItem.querySelector('.remove-variant').addEventListener('click', function() {
                        container.removeChild(variantItem);
                    });
                });
            }

            // Image Compression
            function handleMainImagePreview(inputId, iconId) {
                const input = document.getElementById(inputId);
                const uploadIcon = document.getElementById(iconId);
                input.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file && file.size > 5 * 1024 * 1024) {
                        alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                        input.value = '';
                        uploadIcon.src = "https://html.hixstudio.net/ebazer/assets/img/icons/upload.png";
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        uploadIcon.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                    new Compressor(file, {
                        quality: 0.6,
                        maxWidth: 1200,
                        maxHeight: 1200,
                        success(result) {
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(new File([result], file.name, {
                                type: result.type
                            }));
                            input.files = dataTransfer.files;
                        },
                        error(err) {
                            console.error('Lỗi nén ảnh:', err);
                        }
                    });
                });
                input.addEventListener('click', function() {
                    if (!input.files.length) uploadIcon.src =
                        "https://html.hixstudio.net/ebazer/assets/img/icons/upload.png";
                });
            }

            function handleAdditionalImagesPreview(inputId, iconContainerId, previewContainerId) {
                const input = document.getElementById(inputId);
                const uploadIconContainer = document.getElementById(iconContainerId);
                const previewContainer = document.getElementById(previewContainerId);
                input.addEventListener('change', function(e) {
                    const files = e.target.files;
                    if (files.length) {
                        uploadIconContainer.classList.add('hidden');
                        previewContainer.classList.remove('hidden');
                        previewContainer.innerHTML = '';
                        Array.from(files).forEach((file) => {
                            if (file.size > 5 * 1024 * 1024) {
                                alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                                input.value = '';
                                return;
                            }
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                const imgContainer = document.createElement('div');
                                imgContainer.className = 'relative';
                                imgContainer.innerHTML =
                                    `<img src="${event.target.result}" class="w-24 h-24 object-cover rounded-md border"><button type="button" class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-md" onclick="removeImage(this)">✖</button>`;
                                previewContainer.appendChild(imgContainer);
                            };
                            reader.readAsDataURL(file);
                            new Compressor(file, {
                                quality: 0.6,
                                maxWidth: 1200,
                                maxHeight: 1200,
                                success(result) {
                                    const dataTransfer = new DataTransfer();
                                    const remainingFiles = Array.from(input.files).map((f,
                                        i) => i === Array.from(input.files).indexOf(
                                        file) ? result : f);
                                    remainingFiles.forEach(f => dataTransfer.items.add(
                                        new File([f], f.name, {
                                            type: f.type
                                        })));
                                    input.files = dataTransfer.files;
                                },
                                error(err) {
                                    console.error('Lỗi nén ảnh:', err);
                                }
                            });
                        });
                    } else {
                        uploadIconContainer.classList.remove('hidden');
                        previewContainer.classList.add('hidden');
                    }
                });
                input.addEventListener('click', function() {
                    if (!input.files.length) {
                        uploadIconContainer.classList.remove('hidden');
                        previewContainer.classList.add('hidden');
                    }
                });
            }

            handleMainImagePreview('mainImage', 'uploadIcon1');
            handleAdditionalImagesPreview('additionalImages', 'uploadIconContainer2', 'additionalImagesPreview');

            window.previewVariantImage = function(event, index) {
                let previewContainer = document.getElementById(`preview-images-${index}`);
                previewContainer.innerHTML = '';
                Array.from(event.target.files).forEach((file) => {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let imgContainer = document.createElement('div');
                        imgContainer.classList.add('relative');
                        imgContainer.innerHTML =
                            `<img src="${e.target.result}" class="w-24 h-24 object-cover rounded-md border border-gray-300"><button type="button" class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-md" onclick="removeImage(this)">✖</button>`;
                        previewContainer.appendChild(imgContainer);
                    };
                    reader.readAsDataURL(file);
                });
            };

            window.removeImage = function(element) {
                element.parentElement.remove();
            };
        });
    </script>
@endsection
