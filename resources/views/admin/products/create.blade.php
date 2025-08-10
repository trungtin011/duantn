@extends('layouts.admin')

@section('title', 'Thêm Sản Phẩm Mới')

@section('content')
    <div class="mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Thêm sản phẩm mới</h1>
                <div class="text-sm text-gray-500">
                    <a href="{{ route('admin.dashboard') }}" class="hover:underline">Trang chủ</a> /
                    <a href="{{ route('admin.products.index') }}" class="hover:underline">Sản phẩm</a> /
                    <a href="{{ route('admin.products.select-shop') }}" class="hover:underline">Chọn cửa hàng</a> /
                    Thêm sản phẩm
                </div>
            </div>
            <div class="flex space-x-3">
                <button type="submit" form="product-form"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    Lưu và đăng
                </button>
                <a href="{{ route('admin.products.index') }}"
                    class="border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-100">
                    Hủy
                </a>
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

        <!-- Main Content Form -->
        <form id="product-form" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">

            <!-- Selected Shop Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-blue-900">Cửa hàng được chọn: {{ $shop->shop_name }}</h3>
                        <p class="text-xs text-blue-700 mt-1">
                            Email: {{ $shop->shop_email }} |
                            SĐT: {{ $shop->shop_phone }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- General Product Information -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h4 class="text-xl font-semibold mb-4">Thông tin chung</h4>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Tên sản phẩm <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="name" id="product-name"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Nhập tên sản phẩm" value="{{ old('name') }}">
                        <div class="absolute right-2 top-2 text-xs text-gray-400">
                            <span id="name-char-count">0</span>/100
                        </div>
                    </div>
                    @error('name')
                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                    @enderror
                     <span id="name-length-warning" class="text-sm text-red-500 mt-1 hidden">Tên sản phẩm không được vượt quá 100 ký tự.</span>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Mô tả sản phẩm</label>
                    <textarea id="description" name="description"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        rows="6">{!! old('description') !!}</textarea>
                    @error('description')
                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">SKU sản phẩm <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="sku"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="VD: SP001" value="{{ old('sku') }}">
                    @error('sku')
                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Loại sản phẩm <span
                            class="text-red-500">*</span></label>
                    <div class="flex space-x-6">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="product_type" value="simple"
                                {{ old('product_type', 'simple') === 'simple' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <span class="ml-2 text-sm font-medium text-gray-900">Sản phẩm đơn</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="product_type" value="variant"
                                {{ old('product_type') === 'variant' ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <span class="ml-2 text-sm font-medium text-gray-900">Sản phẩm có biến thể</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Two-Column Layout -->
            <div class="grid grid-cols-12 gap-6">
                <!-- Left Sidebar for Tabs -->
                <div class="col-span-12 lg:col-span-3">
                    <div class="bg-white p-4 rounded-lg shadow-sm sticky top-4">
                        <nav class="flex flex-col space-y-2">
                            <button type="button"
                                class="tab-button active text-left px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100"
                                data-tab="general-details">
                                <i class="fas fa-info-circle mr-2"></i>Chi tiết sản phẩm & SEO
                            </button>
                            <button type="button"
                                class="tab-button text-left px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100"
                                data-tab="pricing-inventory">
                                <i class="fas fa-boxes mr-2"></i>Giá & Tồn kho
                            </button>
                            <button type="button"
                                class="tab-button text-left px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100"
                                data-tab="shipping">
                                <i class="fas fa-truck mr-2"></i>Vận chuyển
                            </button>
                            <button type="button"
                                class="tab-button text-left px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100"
                                data-tab="attributes-variants">
                                <i class="fas fa-tags mr-2"></i>Thuộc tính & Biến thể
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Right Content Area -->
                <div class="col-span-12 lg:col-span-9">
                    <!-- Tab Content: General Details -->
                    <div id="tab-general-details" class="tab-content active">
                        <!-- Category & Brand Section -->
                        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                            <h4 class="text-xl font-semibold mb-4">Chi tiết sản phẩm</h4>

                            <!-- Categories -->
                            <div class="mb-4">
                                <label class="block text-gray-700 font-medium mb-1">Danh mục <span
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
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="size-5 toggle-icon">
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
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                                @error('category_ids')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Brands -->
                            <div class="mb-4">
                                <label class="block text-gray-700 font-medium mb-1">Thương hiệu</label>
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
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="size-5 toggle-icon">
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
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
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
                            </div>
                        </div>

                        <!-- SEO Section -->
                        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                            <h4 class="text-xl font-semibold mb-4">SEO</h4>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-medium mb-1">Tiêu đề SEO <span
                                        id="meta-title-count">0/60</span></label>
                                <input type="text" name="meta_title" id="meta-title"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="{{ old('meta_title') }}" maxlength="60" placeholder="Tối đa 60 ký tự">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 font-medium mb-1">Mô tả SEO <span
                                        id="meta-description-count">0/160</span></label>
                                <textarea name="meta_description" id="meta-description"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    maxlength="160" rows="4" placeholder="Tối đa 160 ký tự">{{ old('meta_description') }}</textarea>
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
                        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                            <h4 class="text-xl font-semibold mb-4">Hình ảnh sản phẩm</h4>

                            <!-- Main Image -->
                            <div class="mb-4">
                                <label class="block text-gray-700 font-medium mb-1">Ảnh chính <span
                                        class="text-red-500">*</span></label>
                                <div class="text-center border-2 border-dashed border-gray-300 rounded-md p-4">
                                    <img id="uploadIcon1" class="w-24 h-auto mx-auto mb-2 hidden"
                                        src="https://html.hixstudio.net/ebazer/assets/img/icons/upload.png"
                                        alt="Upload Icon">
                                    <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh nhỏ hơn 5Mb</span>
                                    <label for="mainImage"
                                        class="inline-block py-2 px-4 bg-blue-100 text-blue-700 rounded-md cursor-pointer hover:bg-blue-200">
                                        Chọn ảnh chính
                                    </label>
                                    <input type="file" id="mainImage" class="hidden" accept="image/*">
                                    <input type="hidden" name="main_image_temp" id="mainImageTemp" value="{{ old('main_image_temp') }}">
                                </div>
                                @error('main_image')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Additional Images -->
                            <div class="mb-4">
                                <label class="block text-gray-700 font-medium mb-1">Ảnh phụ</label>
                                <div class="text-center border-2 border-dashed border-gray-300 rounded-md p-4">
                                    <div id="additionalImagesPreview" class="mt-2 flex flex-wrap gap-2"></div>
                                    <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh nhỏ hơn 5Mb</span>
                                    <label for="additionalImages"
                                        class="inline-block py-2 px-4 bg-blue-100 text-blue-700 rounded-md cursor-pointer hover:bg-blue-200">
                                        Chọn ảnh phụ
                                    </label>
                                    <input type="file" id="additionalImages" multiple class="hidden" accept="image/*">
                                    <input type="hidden" name="images_temp" id="additionalImagesTemp" value='@json(old("images_temp", []))'>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content: Pricing & Inventory -->
                    <div id="tab-pricing-inventory" class="tab-content hidden">
                        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                            <h4 class="text-xl font-semibold mb-4">Giá & Tồn kho</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Giá gốc <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" name="price" step="0.01"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="VD: 100000" value="{{ old('price') }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Giá nhập <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" name="purchase_price" step="0.01"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="VD: 80000" value="{{ old('purchase_price') }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Giá bán <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" name="sale_price" step="0.01"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="VD: 120000" value="{{ old('sale_price') }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Số lượng tồn kho <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" name="stock_total"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="VD: 100" value="{{ old('stock_total') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content: Shipping -->
                    <div id="tab-shipping" class="tab-content hidden">
                        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                            <h4 class="text-xl font-semibold mb-4">Vận chuyển</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Chiều dài (inch)</label>
                                    <input type="number" name="length" step="0.01"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="VD: 10" value="{{ old('length') }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Chiều rộng (inch)</label>
                                    <input type="number" name="width" step="0.01"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="VD: 5" value="{{ old('width') }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Chiều cao (inch)</label>
                                    <input type="number" name="height" step="0.01"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="VD: 3" value="{{ old('height') }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Trọng lượng (kg)</label>
                                    <input type="number" name="weight" step="0.01"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="VD: 0.5" value="{{ old('weight') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content: Attributes & Variants -->
                    <div id="tab-attributes-variants" class="tab-content hidden">
                        <!-- Attributes Section -->
                        <div id="attribute-section" class="bg-white p-6 rounded-lg shadow-sm mb-6">
                            <h4 class="text-xl font-semibold mb-4">Thuộc tính sản phẩm</h4>
                            <div id="attribute-container" class="mb-4">
                                <!-- Attributes will be added here dynamically -->
                            </div>
                            <button type="button" id="add-attribute-btn"
                                class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                Thêm thuộc tính
                            </button>
                        </div>

                        <!-- Variants Section -->
                        <div id="variants-section" class="bg-white p-6 rounded-lg shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-xl font-semibold">Biến thể sản phẩm</h4>
                                <button type="button" id="toggle-variants"
                                    class="text-gray-600 hover:text-gray-800 focus:outline-none">
                                    <svg id="toggle-icon" class="w-6 h-6" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </button>
                            </div>
                            <div id="variant-content" class="mb-4">
                                <button type="button" id="generate-variants-btn"
                                    class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                    Tạo biến thể
                                </button>
                                <div id="variant-container" class="mt-4">
                                    <!-- Variants will be generated here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex justify-start space-x-3 mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    Lưu và đăng
                </button>
                <a href="{{ route('admin.products.index') }}"
                    class="border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-100">
                    Hủy
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Provide old inputs for attributes and variants to JS for rehydration after validation errors
        window.oldAttributesData = @json(old('attributes', []));
        window.oldVariantsData = @json(old('variants', []));
        // Pass PHP data to JavaScript
        window.allAttributes = @json($allAttributes);

        // Initialize TinyMCE for product description
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof tinymce !== 'undefined') {
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
            } else {
                console.warn('TinyMCE not loaded, using fallback textarea');
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .tab-button.active {
            background-color: #e0f2fe;
            color: #2563eb;
            font-weight: 600;
        }

        .tab-content {
            transition: all 0.3s ease-in-out;
        }

        .tab-content.hidden {
            display: none;
        }

        .sticky {
            position: -webkit-sticky;
            position: sticky;
            top: 1rem;
            align-self: flex-start;
        }

        .field-error {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .border-red-500 {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 1px #ef4444;
        }

        .border-red-500:focus {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
        }

        /* Radio button styles */
        input[type="radio"] {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 16px;
            height: 16px;
            border: 2px solid #d1d5db;
            border-radius: 50%;
            outline: none;
            cursor: pointer;
            position: relative;
        }

        input[type="radio"]:checked,
        input[type="radio"].checked {
            border-color: #3b82f6;
            background-color: #3b82f6;
        }

        input[type="radio"]:checked::after,
        input[type="radio"].checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 6px;
            height: 6px;
            background-color: white;
            border-radius: 50%;
        }

        input[type="radio"]:focus {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }
    </style>
@endpush
