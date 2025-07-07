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
                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Nhập tên sản phẩm" value="{{ old('name') }}">
                            @error('name')
                                <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                            @enderror
                            <span class="text-sm text-gray-500 block mt-1">Tên sản phẩm nên ngắn gọn và duy nhất.</span>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Mô tả sản phẩm</label>
                            <textarea id="description" name="description"
                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: 100000" value="{{ old('price') }}">
                                @error('price')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Giá nhập <span
                                        class="text-red-500">*</span></label>
                                <input type="number" name="purchase_price" step="0.01"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: 80000" value="{{ old('purchase_price') }}">
                                @error('purchase_price')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Giá bán <span
                                        class="text-red-500">*</span></label>
                                <input type="number" name="sale_price" step="0.01"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: 120000" value="{{ old('sale_price') }}">
                                @error('sale_price')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">SKU sản phẩm <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="sku"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: SP001" value="{{ old('sku') }}">
                                @error('sku')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Số lượng tồn kho <span
                                        class="text-red-500">*</span></label>
                                <input type="number" name="stock_total"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: 10" value="{{ old('length') }}">
                                @error('length')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Chiều rộng (inch)</label>
                                <input type="number" name="width" step="0.01"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: 5" value="{{ old('width') }}">
                                @error('width')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Chiều cao (inch)</label>
                                <input type="number" name="height" step="0.01"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: 3" value="{{ old('height') }}">
                                @error('height')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Trọng lượng (kg)</label>
                                <input type="number" name="weight" step="0.01"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="VD: 0.5" value="{{ old('weight') }}">
                                @error('weight')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Attributes & Variants -->
                    <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                        <h4 class="text-xl font-semibold mb-4">Thuộc tính sản phẩm</h4>
                        <div id="attribute-container" class="space-y-4">
                            <div class="flex items-center gap-4">
                                <input type="text" name="attributes[0][name]"
                                    placeholder="Tên thuộc tính (VD: Màu sắc)"
                                    class="w-1/3 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <input type="text" name="attributes[0][values]"
                                    placeholder="Giá trị (VD: Đỏ, Xanh, Vàng - phân cách bằng dấu phẩy)"
                                    class="w-2/3 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <button type="button"
                            class="mt-4 bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600"
                            onclick="addAttribute()">Thêm thuộc tính</button>
                    </div>

                    <!-- Variants Section -->
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
                        <div id="variant-content" class="hidden">
                            <div id="variant-container" class="space-y-4"></div>
                            <button type="button"
                                class="mt-4 bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600"
                                onclick="generateVariants()">Tạo biến thể từ thuộc tính</button>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-span-12 lg:col-span-4 space-y-6">
                    <!-- Product Details -->
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h4 class="text-xl font-semibold mb-4">Chi tiết sản phẩm</h4>
                        <div class="grid grid-cols-1 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Thương hiệu <span
                                        class="text-red-500">*</span></label>
                                <select name="brand" id="brand"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Chọn thương hiệu</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->name }}"
                                            {{ old('brand') == $brand->name ? 'selected' : '' }}>{{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Danh mục <span
                                        class="text-red-500">*</span></label>
                                <select name="category" id="category"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-1">Ảnh chính <span
                                    class="text-red-500">*</span></label>
                            <div class="text-center border-2 border-dashed border-gray-300 rounded-md p-4">
                                <img id="uploadIcon1" class="w-24 h-auto mx-auto mb-2 hidden" src=""
                                    alt="Uploaded Image">
                                <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh nhỏ hơn 5Mb</span>
                                <label for="mainImage"
                                    class="inline-block py-2 px-4 bg-blue-100 text-blue-700 rounded-md cursor-pointer hover:bg-blue-200">Chọn
                                    ảnh chính</label>
                                <input type="file" id="mainImage" name="images[]" class="hidden" accept="image/*">
                            </div>
                            @error('images.*')
                                <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Ảnh phụ</label>
                            <div class="text-center border-2 border-dashed border-gray-300 rounded-md p-4">
                                <div id="additionalImagesPreview" class="mt-2 mb-2 flex flex-wrap gap-2"></div>
                                <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh nhỏ hơn 5Mb</span>
                                <label for="additionalImages"
                                    class="inline-block py-2 px-4 bg-blue-100 text-blue-700 rounded-md cursor-pointer hover:bg-blue-200">Chọn
                                    ảnh phụ</label>
                                <input type="file" id="additionalImages" name="images[]" class="hidden" multiple
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

@section('scripts')
    <script src="{{ asset('js/product.js') }}"></script>
@endsection
@endsection
