@extends('layouts.seller_home')

@section('title', 'Thêm Sản Phẩm Mới')
@section('content')
    <div class="">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Thêm sản phẩm mới</h1>
                <div class="text-sm text-gray-500">
                    <a href="#" class="hover:underline">Trang chủ</a> / Thêm sản phẩm
                </div>
            </div>
            <div class="flex space-x-3">
                <button type="submit" form="product-form"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Lưu và đăng</button>
                <button type="submit" form="product-form" name="save_draft" value="1"
                    class="border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-100">Lưu nháp</button>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul>
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
                                        class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Tên sản phẩm" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                    <span class="text-sm text-gray-500 block mt-1">Tên sản phẩm nên là duy nhất.</span>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Mô tả</label>
                                    <textarea id="description" name="description"
                                        class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">{!! old('description') !!}</textarea>
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
                                            placeholder="Giá gốc của sản phẩm" value="{{ old('price') }}" required>
                                        @error('price')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Giá nhập <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" name="purchase_price" step="0.01"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Giá nhập sản phẩm" value="{{ old('purchase_price') }}" required>
                                        @error('purchase_price')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Giá bán <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" name="sale_price" step="0.01"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Giá bán sản phẩm" value="{{ old('sale_price') }}" required>
                                        @error('sale_price')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">SKU sản phẩm <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="sku"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Mã SKU sản phẩm" value="{{ old('sku') }}" required>
                                        @error('sku')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Số lượng tồn kho <span
                                                class="text-red-500">*</span></label>
                                        <input type="number" name="stock_total"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Số lượng tồn kho" value="{{ old('stock_total') }}" required>
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
                                            placeholder="Chiều dài sản phẩm" value="{{ old('length') }}">
                                        @error('length')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Chiều rộng (inch)</label>
                                        <input type="number" name="width" step="0.01"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Chiều rộng sản phẩm" value="{{ old('width') }}">
                                        @error('width')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Chiều cao (inch)</label>
                                        <input type="number" name="height" step="0.01"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Chiều cao sản phẩm" value="{{ old('height') }}">
                                        @error('height')
                                            <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">Trọng lượng (kg)</label>
                                        <input type="number" name="weight" step="0.01"
                                            class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Trọng lượng sản phẩm" value="{{ old('weight') }}">
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
                                    <div class="mb-4 flex items-center gap-4">
                                        <input type="text" name="attributes[][name]"
                                            placeholder="Tên thuộc tính (VD: Màu sắc, Kích thước)"
                                            class="w-1/3 border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                        <input type="text" name="attributes[][values]"
                                            placeholder="Giá trị (VD: Đỏ, Xanh, Vàng)"
                                            class="w-2/3 border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                    </div>
                                </div>
                                <button type="button"
                                    class="ml-3 bg-green-500 text-white px-3 py-2 rounded-md hover:bg-green-600"
                                    onclick="addAttribute()">Thêm</button>
                            </div>

                            <!-- Khu vực hiển thị biến thể -->
                            <div id="variants-section" class="bg-white p-6 rounded-lg shadow-sm">
                                <h4 class="text-xl font-semibold mb-4">Biến thể sản phẩm</h4>
                                <div id="variant-container"></div>
                                <button type="button"
                                    class="mt-4 bg-green-500 text-white px-3 py-2 rounded-md hover:bg-green-600"
                                    onclick="generateVariants()">Tạo biến thể từ thuộc tính</button>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="w-full lg:w-1/3">
                            <!-- SEO Section -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <h4 class="text-xl font-semibold mb-4">SEO</h4>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium mb-1">Tiêu đề SEO (Meta Title) <span
                                            id="meta-title-count">0/60</span></label>
                                    <input type="text" name="meta_title" id="meta-title"
                                        class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        value="{{ old('meta_title') }}" maxlength="60"
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
                                        maxlength="160" placeholder="Mô tả ngắn gọn (tối đa 160 ký tự)">{{ old('meta_description') }}</textarea>
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
                                        <h5 id="preview-title" class="text-blue-600 mb-1">Tiêu đề sản phẩm</h5>
                                        <p id="preview-url" class="text-green-600 mb-1">
                                            https://Zynox.com/san-pham/{{ Str::slug(old('name', 'san-pham')) }}
                                        </p>
                                        <p id="preview-description" class="text-gray-600">Mô tả ngắn gọn về sản phẩm.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Upload Image Section (Ảnh chính) -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                                <p class="text-gray-700 font-medium mb-4">Tải ảnh chính lên</p>
                                <div class="text-center">
                                    <img id="uploadIcon1" class="w-24 h-auto mx-auto mb-2"
                                        src="https://html.hixstudio.net/ebazer/assets/img/icons/upload.png"
                                        alt="Upload Icon">
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
                                    <div id="uploadIconContainer2" class="mb-2">
                                        <img id="uploadIcon2" class="w-24 h-auto mx-auto mb-2"
                                            src="https://html.hixstudio.net/ebazer/assets/img/icons/upload.png"
                                            alt="Upload Icon">
                                    </div>
                                    <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh phải nhỏ hơn 5Mb</span>
                                    <div id="additionalImagesPreview" class="mt-2 mb-2 flex flex-wrap gap-2 hidden"></div>
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
                                                    {{ old('brand') == $brand->name ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
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
                                                    {{ old('category') == $category->name ? 'selected' : '' }}>
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
                                            {{ old('is_featured') ? 'checked' : '' }}>
                                        <span class="text-gray-700 font-medium">Sản phẩm nổi bật</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Từ khóa (Tags)</label>
                                    <input type="text" name="meta_keywords" id="meta-keywords"
                                        class="w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Thêm từ khóa (phân cách bằng dấu phẩy)"
                                        value="{{ old('meta_keywords') }}">
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
                        nháp
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
