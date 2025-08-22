@extends('layouts.seller_home')

@section('title', 'Thêm Sản Phẩm Mới')
@section('content')
    <div class="mx-auto">
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


        <!-- Main Content Form -->
        <form id="product-form" action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <!-- General Product Information (Always Visible) - Top Section -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h4 class="text-xl font-semibold mb-4">Thông tin chung</h4>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Tên sản phẩm <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="name" id="product-name"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Nhập tên sản phẩm" value="{{ old('name') }}" maxlength="255">
                        <div class="absolute right-2 top-2 text-xs text-gray-400">
                            <span id="name-char-count">0</span>/255
                        </div>
                    </div>
                    @error('name')
                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                    @enderror
                    <span class="text-sm text-gray-500 block mt-1">Tên sản phẩm nên ngắn gọn và duy nhất.</span>
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
                    <div>
                        <label>
                            <input type="radio" name="product_type" value="simple" {{ old('product_type', 'simple') === 'simple' ? 'checked' : '' }}> Sản phẩm đơn
                        </label>
                        <label class="ml-4">
                            <input type="radio" name="product_type" value="variant" {{ old('product_type') === 'variant' ? 'checked' : '' }}> Sản phẩm có biến thể
                        </label>
                    </div>

                </div>
            </div>

            <!-- Two-Column Layout for Product Details (Tabs & Content) -->
            <div class="grid grid-cols-12 gap-6">
                <!-- Left Sidebar for Tabs -->
                <div class="col-span-12 lg:col-span-3">
                    <div class="bg-white p-4 rounded-lg shadow-sm sticky top-4">
                        <nav class="flex flex-col space-y-2">
                            {{-- Tab buttons will be dynamically shown/hidden based on product_type --}}
                            <button type="button"
                                class="tab-button active text-left px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100"
                                data-tab="general-details">
                                <i class="fas fa-info-circle mr-2"></i>Chi tiết sản phẩm
                            </button>
                            <button type="button"
                                class="tab-button text-left px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100"
                                data-tab="seo">
                                <i class="fas fa-search mr-2"></i>SEO
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

                <!-- Right Content Area: Tab Content Sections -->
                <div class="col-span-12 lg:col-span-9">

                    <!-- Tab Content: General Details (Includes original Product Details, SEO, Image Upload) -->
                    <div id="tab-general-details" class="tab-content active">
                        <!-- Category & Brand Section -->
                        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                            <h4 class="text-xl font-semibold mb-4">Chi tiết sản phẩm</h4>
                            <div class="flex flex-col gap-3 mb-4">
                                <!-- Thương hiệu -->
                                <div>
                                    <label for="brand_id" class="block text-gray-700 font-medium mb-1">Thương hiệu</label>
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
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Từ khóa (Tags)</label>
                                <input type="text" name="meta_keywords" id="meta-keywords"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Thêm từ khóa (phân cách bằng dấu phẩy)"
                                    value="{{ old('meta_keywords') }}" data-original-required="false">
                                @error('meta_keywords')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                            <h4 class="text-xl font-semibold mb-4">Hình ảnh sản phẩm</h4>
                            <!-- Ảnh chính -->
                            <div class="mb-4">
                                <label class="block text-gray-700 font-medium mb-1">Ảnh chính <span
                                        class="text-red-500">*</span></label>
                                <div class="text-center border-2 border-dashed border-gray-300 rounded-md p-4">
                                    <img id="uploadIcon1" class="w-24 h-auto mx-auto mb-2 hidden"
                                        src="https://html.hixstudio.net/ebazer/assets/img/icons/upload.png"
                                        alt="Upload Icon">
                                    <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh nhỏ hơn 5Mb</span>
                                    <label for="mainImage"
                                        class="inline-block py-2 px-4 bg-blue-100 text-blue-700 rounded-md cursor-pointer hover:bg-blue-200">Chọn
                                        ảnh chính</label>
                                    <input type="file" id="mainImage" name="main_image" class="hidden"
                                        accept="image/*" value="{{ old('main_image') }}">
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
                                        accept="image/*" value="{{ old('images') }}">
                                </div>
                                @error('images.*')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SEO -->
                    <div id="tab-seo" class="tab-content hidden bg-white p-6 rounded-lg shadow-sm mb-6">
                        <h4 class="text-xl font-semibold mb-4">SEO</h4>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-1">Tiêu đề SEO <span
                                    id="meta-title-count">0/60</span></label>
                            <input type="text" name="meta_title" id="meta-title"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('meta_title') }}" maxlength="60" placeholder="Tối đa 60 ký tự">
                            <span class="text-sm text-gray-500 block mt-1">Tiêu đề hiển thị trên công cụ tìm
                                kiếm.</span>
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
                                    https://zynoxmall.xyz/san-pham/{{ Str::slug(old('name', 'san-pham')) }}</p>
                                <p id="preview-description" class="text-gray-600">Mô tả ngắn gọn về sản phẩm.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content: Pricing & Inventory (for Simple Products) -->
                    <div id="tab-pricing-inventory" class="tab-content hidden">
                        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                            <h4 class="text-xl font-semibold mb-4">Giá & Tồn kho</h4>
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
                        </div>
                    </div>

                    <!-- Tab Content: Shipping -->
                    <div id="tab-shipping" class="tab-content hidden">
                        <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                            <h4 class="text-xl font-semibold mb-4">Vận chuyển</h4>
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
                    </div>

                    <!-- Tab Content: Attributes & Variants (for Variant Products) -->
                    <div id="tab-attributes-variants" class="tab-content hidden">
                        <!-- Thuộc tính sản phẩm -->
                        <div id="attribute-section" class="bg-white p-6 rounded-lg shadow-sm mb-6">
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
                                            <option value="" disabled
                                                {{ empty($attribute['id']) ? 'selected' : '' }}>
                                                Chọn hoặc nhập thuộc tính
                                            </option>
                                            <option value="new"
                                                {{ ($attribute['id'] ?? '') === 'new' ? 'selected' : '' }}>
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
                                            placeholder="Giá trị (VD: Đỏ, Xanh, Vàng - phân cách bằng dấu phẩy)"
                                            {{ old("attributes.$index.values") ? 'data-has-old-value="true"' : '' }}>
                                        <button type="button"
                                            class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-attribute">Xóa</button>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex gap-2 mt-2">
                                <button type="button" id="add-attribute-btn"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Thêm thuộc
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
                                                <div
                                                    class="variant-content transition-all duration-300 ease-in-out hidden">
                                                    <input type="hidden" name="variants[{{ $index }}][index]"
                                                        value="{{ $index }}">
                                                    <input type="hidden" name="variants[{{ $index }}][name]"
                                                        value="{{ $variant['name'] }}">
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                                        <div>
                                                            <label class="block text-gray-700 font-medium mb-1">Giá
                                                                gốc</label>
                                                            <input type="number"
                                                                name="variants[{{ $index }}][price]"
                                                                value="{{ old("variants.$index.price") }}" step="0.01"
                                                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                                placeholder="Nhập giá gốc">
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
                                                                placeholder="Nhập giá nhập">
                                                            @error("variants.$index.purchase_price")
                                                                <span
                                                                    class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div>
                                                            <label class="block text-gray-700 font-medium mb-1">Giá
                                                                bán</label>
                                                            <input type="number"
                                                                name="variants[{{ $index }}][sale_price]"
                                                                value="{{ old("variants.$index.sale_price") }}"
                                                                step="0.01"
                                                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                                placeholder="Nhập giá bán">
                                                            @error("variants.$index.sale_price")
                                                                <span
                                                                    class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div>
                                                            <label class="block text-gray-700 font-medium mb-1">SKU</label>
                                                            <input type="text"
                                                                name="variants[{{ $index }}][sku]"
                                                                value="{{ old("variants.$index.sku") }}"
                                                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-2 focus:ring-blue-500"
                                                                placeholder="Nhập SKU">
                                                            @error("variants.$index.sku")
                                                                <span
                                                                    class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div>
                                                            <label class="block text-gray-700 font-medium mb-1">Số lượng
                                                                tồn
                                                                kho</label>
                                                            <input type="number"
                                                                name="variants[{{ $index }}][stock_total]"
                                                                value="{{ old("variants.$index.stock_total") }}"
                                                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                                placeholder="Nhập số lượng">
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
                                                                value="{{ old("variants.$index.length") }}"
                                                                step="0.01"
                                                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                                placeholder="Chiều dài" data-original-required="true">
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
                                                                value="{{ old("variants.$index.width") }}"
                                                                step="0.01"
                                                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                                placeholder="Chiều rộng" data-original-required="true">
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
                                                                value="{{ old("variants.$index.height") }}"
                                                                step="0.01"
                                                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                                placeholder="Chiều cao" data-original-required="true">
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
                                                                value="{{ old("variants.$index.weight") }}"
                                                                step="0.01"
                                                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                                placeholder="Trọng lượng" data-original-required="true">
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
                                                        <label class="block text-gray-700 font-medium mb-1">Hình
                                                            ảnh</label>
                                                        @if (session('error'))
                                                            <span class="text-sm text-red-500 block mb-3">Vui lòng chọn lại
                                                                ảnh
                                                                biến thể do lỗi trước đó.</span>
                                                        @endif
                                                        <input type="file"
                                                            name="variant_images[{{ $index }}][]" multiple
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
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="col-span-12 flex justify-start space-x-3 mt-6">
                <div class="col-span-12 flex justify-start space-x-3 mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Lưu và
                        đăng</button>
                    <a href="{{ route('seller.products.index') }}"
                        class="border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-100">Hủy</a>
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
                        updateAttributeValues(this, true);
                    });
                    // Chỉ cập nhật giá trị nếu không có dữ liệu old() hoặc giá trị đã nhập
                    const row = select.closest('.attribute-row');
                    const valuesInput = row.querySelector('.attribute-values');
                    if (select.value && (!valuesInput.value.trim() || !valuesInput.hasAttribute('data-has-old-value'))) {
                        updateAttributeValues(select, false);
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
                        validateAttributeName(this);
                    });
                });

                attributeContainer.querySelectorAll('input[name$="[values]"]').forEach(input => {
                    input.addEventListener('input', function() {
                        validateAttributeValues(this);
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
        function updateAttributeValues(select, force = false) {
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
                // Không xóa giá trị đã nhập nếu có
                if (!nameInput.value.trim()) {
                    nameInput.value = '';
                }
                if (!valuesInput.value.trim()) {
                    valuesInput.value = '';
                }
                debugLog('Chọn tạo thuộc tính mới, hiển thị input tên');
            } else {
                nameInput.classList.add('hidden');
                const selectedAttribute = window.allAttributes.find(attr => attr.id == select.value);
                if (selectedAttribute) {
                    nameInput.value = selectedAttribute.name;
                    // Nếu là thao tác đổi thuộc tính (force=true) thì luôn cập nhật giá trị
                    // Nếu khởi tạo ban đầu (force=false) chỉ cập nhật khi input đang trống hoặc chưa có giá trị
                    if (force || !valuesInput.value.trim()) {
                        valuesInput.value = Array.isArray(selectedAttribute.values) ?
                            selectedAttribute.values.join(', ') :
                            (selectedAttribute.values || '');
                        debugLog('Cập nhật giá trị thuộc tính từ dropdown', {
                            name: selectedAttribute.name,
                            values: valuesInput.value
                        });
                    } else {
                        debugLog('Giữ nguyên giá trị đã nhập', {
                            name: selectedAttribute.name,
                            existingValues: valuesInput.value
                        });
                    }
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
                <input type="text" name="attributes[${attributeIndex}][values]" class="w-2/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-values" placeholder="Giá trị (VD: Đỏ, Xanh, Vàng - phân cách bằng dấu phẩy)" data-has-old-value="false">
                <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-attribute">Xóa</button>
            `;
            container.appendChild(newAttribute);

            const newSelect = newAttribute.querySelector('.attribute-select');
            newSelect.addEventListener('change', function() {
                updateAttributeValues(this, true);
            });

            newAttribute.querySelector('.remove-attribute').addEventListener('click', () => {
                debugLog('Removing attribute row', {
                    index: attributeIndex
                });
                newAttribute.remove();
                updateAttributeIndices();
            });

            newAttribute.querySelector('input[name$="[name]"]').addEventListener('input', function() {
                validateAttributeName(this);
            });

            newAttribute.querySelector('input[name$="[values]"]').addEventListener('input', function() {
                validateAttributeValues(this);
            });

            attributeIndex++;
        }

        // Hàm kiểm tra trùng lặp tên thuộc tính
        function validateAttributeName(input) {
            const currentName = input.value.trim().toLowerCase();
            const container = document.getElementById('attribute-container');
            const allNameInputs = container.querySelectorAll('input[name$="[name]"]');
            const currentRow = input.closest('.attribute-row');
            
            // Xóa lỗi cũ
            clearFieldError(input);
            
            if (!currentName) {
                return true; // Không validate nếu trống -> coi như hợp lệ
            }
            
            let duplicateCount = 0;
            allNameInputs.forEach(nameInput => {
                if (nameInput !== input && nameInput.value.trim().toLowerCase() === currentName) {
                    duplicateCount++;
                }
            });
            
            if (duplicateCount > 0) {
                showFieldError(input, 'Tên thuộc tính này đã tồn tại!');
                input.classList.add('border-red-500');
                return false;
            } else {
                input.classList.remove('border-red-500');
                return true;
            }
        }

        // Hàm kiểm tra trùng lặp giá trị thuộc tính
        function validateAttributeValues(input) {
            const currentValues = input.value.split(',').map(v => v.trim().toLowerCase()).filter(v => v);
            const container = document.getElementById('attribute-container');
            const allValueInputs = container.querySelectorAll('input[name$="[values]"]');
            const currentRow = input.closest('.attribute-row');
            
            // Xóa lỗi cũ
            clearFieldError(input);
            
            if (currentValues.length === 0) {
                return true; // Không validate nếu trống -> coi như hợp lệ
            }
            
            // Kiểm tra trùng lặp trong cùng một input
            const uniqueValues = [...new Set(currentValues)];
            if (uniqueValues.length !== currentValues.length) {
                showFieldError(input, 'Có giá trị trùng lặp trong cùng một thuộc tính!');
                input.classList.add('border-red-500');
                return false;
            }
            
            // Kiểm tra trùng lặp với các thuộc tính khác
            let hasDuplicate = false;
            let duplicateWith = '';
            allValueInputs.forEach(valueInput => {
                if (valueInput !== input) {
                    const otherValues = valueInput.value.split(',').map(v => v.trim().toLowerCase()).filter(v => v);
                    const intersection = currentValues.filter(value => otherValues.includes(value));
                    if (intersection.length > 0) {
                        hasDuplicate = true;
                        const otherRow = valueInput.closest('.attribute-row');
                        const otherNameInput = otherRow.querySelector('input[name$="[name]"]');
                        const otherName = otherNameInput.value.trim() || 'Thuộc tính khác';
                        duplicateWith = otherName;
                    }
                }
            });
            
            if (hasDuplicate) {
                showFieldError(input, `Có giá trị trùng lặp với thuộc tính "${duplicateWith}"!`);
                input.classList.add('border-red-500');
                return false;
            } else {
                input.classList.remove('border-red-500');
                return true;
            }
        }

        // Hàm kiểm tra tổng thể tất cả thuộc tính
        function validateAllAttributes() {
            const container = document.getElementById('attribute-container');
            const nameInputs = container.querySelectorAll('input[name$="[name]"]');
            const valueInputs = container.querySelectorAll('input[name$="[values]"]');
            
            let errors = [];
            let hasErrors = false;
            let duplicateNames = new Set();
            let duplicateValues = new Set();

            // Kiểm tra tên thuộc tính
            nameInputs.forEach((input, index) => {
                const name = input.value.trim().toLowerCase();
                if (name) {
                    if (duplicateNames.has(name)) {
                        hasErrors = true;
                        errors.push(`- Tên thuộc tính "${input.value.trim()}" bị trùng lặp`);
                    } else {
                        duplicateNames.add(name);
                    }
                }
            });

            // Kiểm tra giá trị thuộc tính
            valueInputs.forEach((input, index) => {
                const values = input.value.split(',').map(v => v.trim().toLowerCase()).filter(v => v);
                const row = input.closest('.attribute-row');
                const nameInput = row.querySelector('input[name$="[name]"]');
                const attrName = nameInput.value.trim() || `Thuộc tính ${index + 1}`;

                // Kiểm tra trùng lặp trong cùng một thuộc tính
                const uniqueValues = [...new Set(values)];
                if (uniqueValues.length !== values.length) {
                    hasErrors = true;
                    errors.push(`- Thuộc tính "${attrName}" có giá trị trùng lặp trong cùng một thuộc tính`);
                }

                // Kiểm tra trùng lặp với các thuộc tính khác
                values.forEach(value => {
                    if (duplicateValues.has(value)) {
                        hasErrors = true;
                        errors.push(`- Giá trị "${value}" bị trùng lặp giữa các thuộc tính`);
                    } else {
                        duplicateValues.add(value);
                    }
                });
            });

            if (hasErrors) {
                const errorMessage = 'Các lỗi validation:\n' + errors.join('\n');
                alert(errorMessage);
                return false;
            }

            return true;
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

            // Xóa lỗi cũ ở nút tạo biến thể (nếu có)
            const generateBtn = document.getElementById('generate-variants-btn');
            clearFieldError(generateBtn);

            let attributeData = [];
            let hasValidAttribute = false;
            let hasValidationError = false;

            selects.forEach((select, index) => {
                const attrId = select.value;
                let attrName = names[index].value.trim();
                const valuesArray = values[index].value
                    .split(',')
                    .map(v => v.trim())
                    .filter(v => v);

                if (attrId === 'new' && !attrName) {
                    showFieldError(names[index], `Vui lòng nhập tên thuộc tính cho thuộc tính ${index + 1}.`);
                    hasValidationError = true;
                    return;
                }

                if (attrId !== 'new' && attrId) {
                    const selectedAttribute = window.allAttributes.find(attr => attr.id == attrId);
                    if (selectedAttribute) {
                        attrName = selectedAttribute.name;
                    }
                }

                if (!valuesArray.length) {
                    showFieldError(values[index], `Vui lòng nhập giá trị thuộc tính cho thuộc tính ${index + 1}.`);
                    hasValidationError = true;
                    return;
                }

                if (attrName && valuesArray.length) {
                    attributeData.push({
                        name: attrName,
                        values: valuesArray
                    });
                    hasValidAttribute = true;
                }
            });

            if (hasValidationError) {
                showFieldError(generateBtn, 'Vui lòng sửa các lỗi validation trước khi tạo biến thể.');
                debugLog('Validation errors found, cannot generate variants');
                return;
            }

            if (!hasValidAttribute) {
                showFieldError(document.querySelector('#generate-variants-btn'),
                    'Vui lòng nhập ít nhất một thuộc tính hợp lệ với tên và giá trị.');
                debugLog('No valid attributes provided');
                return;
            }

            // Lưu dữ liệu biến thể cũ nếu có
            const oldVariants = {};
            const existingVariants = variantContainer.querySelectorAll('.variant-item');
            existingVariants.forEach((variant, index) => {
                const variantName = variant.querySelector('input[name$="[name]"]')?.value;
                if (variantName) {
                    oldVariants[variantName] = {};
                    const inputs = variant.querySelectorAll('input[type="number"], input[type="text"]');
                    inputs.forEach(input => {
                        const fieldName = input.name.match(/\[([^\]]+)\]$/)?.[1];
                        if (fieldName) {
                            oldVariants[variantName][fieldName] = input.value;
                        }
                    });
                }
            });

            variantContainer.innerHTML = '';
            const variants = getCombinations(attributeData.map(attr => attr.values));
            debugLog('Generated variants', variants);

            const baseSkuInput = document.querySelector('input[name="sku"]');
            const baseSku = (baseSkuInput && baseSkuInput.value ? baseSkuInput.value.trim() : '').toUpperCase();

            variants.forEach((variant, index) => {
                const variantDiv = document.createElement('div');
                variantDiv.classList.add('p-6', 'border', 'border-gray-300', 'rounded-md', 'mb-6', 'bg-white',
                    'relative', 'variant-item');

                const variantName = variant.join(' - ');
                const oldData = oldVariants[variantName] || {};
                const variantSlug = (function(t){
                    return t.toLowerCase()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '')
                        .replace(/đ/g,'d')
                        .replace(/[^a-z0-9 -]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                })(variantName);
                const autoSku = baseSku ? `${baseSku}-${variantSlug.toUpperCase().replace(/-/g,'')}` : variantSlug.toUpperCase().replace(/-/g,'');

                let variantHTML = `
                    <div class="flex justify-between items-center mb-3">
                        <h5 class="text-lg font-semibold">Biến thể ${index + 1}: ${variantName}</h5>
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
                        <input type="hidden" name="variants[${index}][name]" value="${variantName}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Giá gốc</label>
                                <input type="number" name="variants[${index}][price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá gốc" value="${oldData.price || ''}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Giá nhập</label>
                                <input type="number" name="variants[${index}][purchase_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá nhập" value="${oldData.purchase_price || ''}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Giá bán</label>
                                <input type="number" name="variants[${index}][sale_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá bán" value="${oldData.sale_price || ''}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">SKU</label>
                                <input type="text" name="variants[${index}][sku]" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-2 focus:ring-blue-500" placeholder="Nhập SKU" value="${oldData.sku || autoSku}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Số lượng tồn kho</label>
                                <input type="number" name="variants[${index}][stock_total]" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập số lượng" value="${oldData.stock_total || ''}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Chiều dài (inch)</label>
                                <input type="number" name="variants[${index}][length]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều dài" value="${oldData.length || ''}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Chiều rộng (inch)</label>
                                <input type="number" name="variants[${index}][width]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều rộng" value="${oldData.width || ''}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Chiều cao (inch)</label>
                                <input type="number" name="variants[${index}][height]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều cao" value="${oldData.height || ''}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Trọng lượng (kg)</label>
                                <input type="number" name="variants[${index}][weight]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Trọng lượng" value="${oldData.weight || ''}">
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
                            @if (session('error'))
                                <span class="text-sm text-red-500 block mb-3">Vui lòng chọn lại ảnh
                                    biến thể do lỗi trước đó.</span>
                            @endif
                            <input type="file" name="variant_images[${index}][]"
                                multiple
                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                accept="image/*"
                                onchange="previewVariantImage(event, ${index})">
                            <div id="preview-images-${index}"
                                class="mt-2 flex flex-wrap gap-2"></div>
                        </div>
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

        // Hàm tạo tổ hợp biến thể
        function getCombinations(arr) {
            debugLog('Generating combinations', arr);
            return arr.reduce((acc, val) => acc.flatMap(a => val.map(v => [...a, v])), [
                []
            ]);
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

        // Hàm validate form (hiển thị lỗi ở đầu trang)
        function validateForm(e) {
            e.preventDefault();
            const productForm = document.getElementById('product-form');
            if (!productForm) {
                debugLog('Product form not found');
                return;
            }

            const errors = [];
            debugLog('Dữ liệu form trước khi gửi', Object.fromEntries(new FormData(productForm)));

            const categoryCheckboxes = document.querySelectorAll('input[name="category_ids[]"]:checked');

            // Kiểm tra loại sản phẩm
            const productTypeInput = document.querySelector('input[name="product_type"]:checked');
            const productType = productTypeInput ? productTypeInput.value : null;

            // Common validations
            const nameInput = document.querySelector('input[name="name"]');
            if (!nameInput || !nameInput.value.trim()) {
                errors.push('Vui lòng nhập tên sản phẩm.');
            } else if (nameInput.value.trim().length > 255) {
                errors.push('Tên sản phẩm không được vượt quá 255 ký tự.');
            } else {
                const nameError = nameInput.parentNode.querySelector('.field-error');
                if (nameError && nameError.textContent.includes('đã tồn tại')) {
                    errors.push('Tên sản phẩm đã tồn tại trong shop của bạn.');
                }
            }

            const skuValue = (document.querySelector('input[name="sku"]')?.value || '').trim();
            if (!skuValue) {
                errors.push('Vui lòng nhập mã SKU.');
            }

            if (categoryCheckboxes.length === 0) {
                errors.push('Vui lòng chọn ít nhất một danh mục.');
            }

            const mainImageInput = document.getElementById('mainImage');
            if (!mainImageInput?.files.length) {
                errors.push('Vui lòng chọn ảnh chính.');
            }

            if (productType === 'simple') {
                const priceInput = document.querySelector('#tab-pricing-inventory input[name="price"]');
                const purchasePriceInput = document.querySelector('#tab-pricing-inventory input[name="purchase_price"]');
                const salePriceInput = document.querySelector('#tab-pricing-inventory input[name="sale_price"]');
                const stockInput = document.querySelector('#tab-pricing-inventory input[name="stock_total"]');

                if (!priceInput || !priceInput.value || isNaN(priceInput.value) || parseFloat(priceInput.value) < 0) {
                    errors.push('Vui lòng nhập giá gốc hợp lệ cho sản phẩm đơn.');
                }
                if (!purchasePriceInput || !purchasePriceInput.value || isNaN(purchasePriceInput.value) || parseFloat(
                        purchasePriceInput.value) < 0) {
                    errors.push('Vui lòng nhập giá nhập hợp lệ cho sản phẩm đơn.');
                }
                if (!salePriceInput || !salePriceInput.value || isNaN(salePriceInput.value) || parseFloat(salePriceInput
                        .value) < 0) {
                    errors.push('Vui lòng nhập giá bán hợp lệ cho sản phẩm đơn.');
                }
                if (!stockInput || !stockInput.value || isNaN(stockInput.value) || parseInt(stockInput.value) < 0) {
                    errors.push('Vui lòng nhập số lượng tồn kho hợp lệ cho sản phẩm đơn.');
                }
            } else if (productType === 'variant') {
                const attributeSelects = document.querySelectorAll(
                    '#tab-attributes-variants .attribute-row .attribute-select');
                const variantItems = document.querySelectorAll('#variant-container > .variant-item');

                if (attributeSelects.length === 0) {
                    errors.push('Vui lòng thêm ít nhất một thuộc tính cho sản phẩm biến thể.');
                } else {
                    attributeSelects.forEach((select, index) => {
                        const nameField = select.closest('.attribute-row').querySelector('.attribute-name');
                        const valuesField = select.closest('.attribute-row').querySelector('.attribute-values');
                        if (select.value === 'new' && (!nameField.value || !nameField.value.trim())) {
                            errors.push(`Vui lòng nhập tên thuộc tính cho thuộc tính ${index + 1}.`);
                        }
                        if (!valuesField.value || !valuesField.value.trim()) {
                            errors.push(`Vui lòng nhập giá trị thuộc tính cho thuộc tính ${index + 1}.`);
                        }
                    });
                }

                if (variantItems.length === 0) {
                    errors.push('Vui lòng nhấn "Tạo biến thể" để tạo các biến thể trước khi lưu sản phẩm.');
                } else {
                    variantItems.forEach((item, index) => {
                        const priceInput = item.querySelector(`input[name="variants[${index}][price]"]`);
                        const purchasePriceInput = item.querySelector(
                            `input[name="variants[${index}][purchase_price]"]`);
                        const salePriceInput = item.querySelector(`input[name="variants[${index}][sale_price]"]`);
                        const skuInput = item.querySelector(`input[name="variants[${index}][sku]"]`);
                        const stockInput = item.querySelector(`input[name="variants[${index}][stock_total]"]`);

                        if (!priceInput?.value || isNaN(priceInput.value) || parseFloat(priceInput.value) < 0) {
                            errors.push(`Vui lòng nhập giá gốc hợp lệ cho biến thể ${index + 1}.`);
                        }
                        if (!purchasePriceInput?.value || isNaN(purchasePriceInput.value) || parseFloat(
                                purchasePriceInput.value) < 0) {
                            errors.push(`Vui lòng nhập giá nhập hợp lệ cho biến thể ${index + 1}.`);
                        }
                        if (!salePriceInput?.value || isNaN(salePriceInput.value) || parseFloat(salePriceInput
                                .value) < 0) {
                            errors.push(`Vui lòng nhập giá bán hợp lệ cho biến thể ${index + 1}.`);
                        }
                        if (!skuInput?.value || !skuInput.value.trim()) {
                            errors.push(`Vui lòng nhập SKU cho biến thể ${index + 1}.`);
                        }
                        if (!stockInput?.value || isNaN(stockInput.value) || parseInt(stockInput.value) < 0) {
                            errors.push(`Vui lòng nhập số lượng tồn kho hợp lệ cho biến thể ${index + 1}.`);
                        }
                    });
                }
            }

            // Render error list at top of form
            let errorBox = document.getElementById('client-error-box');
            if (!errorBox) {
                errorBox = document.createElement('div');
                errorBox.id = 'client-error-box';
                errorBox.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 hidden';
                const ul = document.createElement('ul');
                ul.id = 'client-error-list';
                ul.className = 'list-disc pl-5 text-sm';
                errorBox.appendChild(ul);
                productForm.parentNode.insertBefore(errorBox, productForm);
            }

            const errorList = document.getElementById('client-error-list');
            errorList.innerHTML = '';

            if (errors.length > 0) {
                errors.forEach(msg => {
                    const li = document.createElement('li');
                    li.textContent = msg;
                    errorList.appendChild(li);
                });
                errorBox.classList.remove('hidden');
                errorBox.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                return; // stop submit
            } else {
                errorBox.classList.add('hidden');
            }

            // Hợp lệ => submit
            tinymce.triggerSave();
            productForm.submit();
        }

        // Hàm hiển thị lỗi cho từng trường
        function showFieldError(field, message) {
            if (!field) return;

            // Xóa lỗi cũ nếu có
            const existingError = field.parentNode.querySelector('.field-error');
            if (existingError) {
                existingError.remove();
            }

            // Thêm class lỗi cho field
            field.classList.add('border-red-500');

            // Tạo element hiển thị lỗi
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error text-sm text-red-500 mt-1';
            errorDiv.textContent = message;

            // Chèn lỗi sau field
            field.parentNode.appendChild(errorDiv);
        }

        // Hàm xóa tất cả lỗi
        function clearAllErrors() {
            // Xóa tất cả thông báo lỗi
            document.querySelectorAll('.field-error').forEach(error => error.remove());

            // Xóa class lỗi khỏi tất cả fields
            document.querySelectorAll('.border-red-500').forEach(field => {
                field.classList.remove('border-red-500');
            });
        }

        // Main initialization
        document.addEventListener('DOMContentLoaded', () => {
            debugLog('DOM fully loaded');

            // Đảm bảo loại sản phẩm được chọn đúng ngay từ đầu
            const productType = '{{ old('product_type', 'simple') }}';
            debugLog('Initial product type from old()', { productType });
            
            // Set radio button ngay lập tức
            const radioButton = document.querySelector(`input[name="product_type"][value="${productType}"]`);
            if (radioButton) {
                radioButton.checked = true;
                debugLog('Radio button set to', { value: radioButton.value });
            }

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
                    `https://zynoxmall.xyz/san-pham/${slugify(productName.value || 'san-pham')}`;
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

            // Nút kiểm tra thuộc tính đã được loại bỏ

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

            // Auto-update empty variant SKUs when product SKU changes
            const baseSkuInput = document.querySelector('input[name="sku"]');
            if (baseSkuInput) {
                baseSkuInput.addEventListener('input', () => {
                    const newBase = baseSkuInput.value.trim().toUpperCase();
                    document.querySelectorAll('#variant-container .variant-item').forEach((item) => {
                        const nameInput = item.querySelector('input[name$="[name]"]');
                        const skuInput = item.querySelector('input[name$="[sku]"]');
                        if (!nameInput || !skuInput) return;
                        if (skuInput.value && skuInput.value.trim()) return; // keep user-entered
                        const variantName = nameInput.value || '';
                        const slug = variantName.toLowerCase()
                            .normalize('NFD')
                            .replace(/[\u0300-\u036f]/g, '')
                            .replace(/đ/g,'d')
                            .replace(/[^a-z0-9 -]/g, '')
                            .replace(/\s+/g, '-')
                            .replace(/-+/g, '-');
                        const autoSku = newBase ? `${newBase}-${slug.toUpperCase().replace(/-/g,'')}` : slug.toUpperCase().replace(/-/g,'');
                        skuInput.value = autoSku;
                    });
                });
            }

            // Khởi tạo trạng thái ban đầu
            initializeAttributesAndVariants();
            initializeSubCategoryToggles();
            initializeSubBrandToggles();

            // Ẩn/hiện thuộc tính và biến thể theo loại sản phẩm
            const productTypeRadios = document.querySelectorAll('input[name="product_type"]');
            const generalDetailsTabButton = document.querySelector('.tab-button[data-tab="general-details"]');
            const pricingInventoryTabButton = document.querySelector('.tab-button[data-tab="pricing-inventory"]');
            const shippingTabButton = document.querySelector('.tab-button[data-tab="shipping"]');
            const attributesVariantsTabButton = document.querySelector(
                '.tab-button[data-tab="attributes-variants"]');
            const seoTabButton = document.querySelector('.tab-button[data-tab="seo"]');

            const tabGeneralDetails = document.getElementById('tab-general-details');
            const tabPricingInventory = document.getElementById('tab-pricing-inventory');
            const tabShipping = document.getElementById('tab-shipping');
            const tabAttributesVariants = document.getElementById('tab-attributes-variants');

            function toggleProductTypeSections() {
                const selectedProductType = document.querySelector('input[name="product_type"]:checked')?.value || '{{ old('product_type', 'simple') }}';

                // Reset all tab content visibility
                tabGeneralDetails.classList.add('hidden');
                tabPricingInventory.classList.add('hidden');
                tabShipping.classList.add('hidden');
                tabAttributesVariants.classList.add('hidden');
                document.getElementById('tab-seo').classList.add('hidden');

                // Reset all tab button active state
                generalDetailsTabButton.classList.remove('active');
                pricingInventoryTabButton.classList.remove('active');
                shippingTabButton.classList.remove('active');
                attributesVariantsTabButton.classList.remove('active');
                seoTabButton.classList.remove('active');

                // Helper to manage disabled state (no more required attributes)
                function setFieldsState(elements, isDisabled) {
                    elements.forEach(el => {
                        el.disabled = isDisabled;
                    });
                }

                // Get all relevant inputs within tabs
                const generalDetailsInputs = document.querySelectorAll(
                    '#tab-general-details input:not([type="hidden"]), #tab-general-details select, #tab-general-details textarea'
                );
                const pricingInputs = document.querySelectorAll(
                    '#tab-pricing-inventory input:not([type="hidden"])');
                const shippingInputs = document.querySelectorAll('#tab-shipping input:not([type="hidden"])');
                const attributesVariantsInputs = document.querySelectorAll(
                    '#tab-attributes-variants input:not([type="hidden"]), #tab-attributes-variants select');

                if (selectedProductType === 'variant') {
                    // Show only General Details by default; enable Attributes & Variants button
                    tabGeneralDetails.classList.remove('hidden');
                    generalDetailsTabButton.classList.add('active');
                    attributesVariantsTabButton.style.display = '';

                    // Disable simple product specific fields
                    setFieldsState(pricingInputs, true);
                    setFieldsState(shippingInputs, true);
                    setFieldsState(attributesVariantsInputs, false); // Enable variant fields

                    // Hide simple product specific tab buttons
                    pricingInventoryTabButton.style.display = 'none';
                    shippingTabButton.style.display = 'none';

                } else { // simple
                    // Show only General Details by default; enable Pricing & Inventory and Shipping buttons
                    tabGeneralDetails.classList.remove('hidden');
                    generalDetailsTabButton.classList.add('active');
                    pricingInventoryTabButton.style.display = '';
                    shippingTabButton.style.display = '';

                    // Enable simple fields, disable variant fields
                    setFieldsState(pricingInputs, false);
                    setFieldsState(shippingInputs, false);
                    setFieldsState(attributesVariantsInputs, true); // Disable variant fields

                    // Hide attributes-variants button in sidebar for simple
                    attributesVariantsTabButton.style.display = 'none';
                }
                // Ensure main SKU (outside tabs) is always enabled
                document.querySelector('input[name="sku"]').disabled = false;
            }

            productTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    console.log('Product type changed to:', this.value);
                    toggleProductTypeSections();
                    
                    // Đảm bảo tab đầu tiên được hiển thị khi thay đổi loại sản phẩm
                    setTimeout(() => {
                        const activeTabButton = document.querySelector('.tab-button.active');
                        if (activeTabButton) {
                            const targetTabId = activeTabButton.dataset.tab;
                            const targetTabContent = document.getElementById(`tab-${targetTabId}`);
                            if (targetTabContent) {
                                targetTabContent.classList.remove('hidden');
                            }
                        }
                    }, 50);
                });
            });

            // Initial call on load
            toggleProductTypeSections();

            // Tab switching logic
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const selectedProductType = document.querySelector(
                        'input[name="product_type"]:checked').value;
                    const targetTabId = button.dataset.tab;
                    let shouldSwitch = true;

                    // Prevent switching to a tab not relevant for the current product type
                    if (selectedProductType === 'simple' && targetTabId === 'attributes-variants') {
                        shouldSwitch = false;
                    }
                    if (selectedProductType === 'variant' && (targetTabId === 'pricing-inventory' ||
                            targetTabId === 'shipping')) {
                        // Allow switching, but the fields will remain disabled/unrequired by toggleProductTypeSections
                        // This case is already handled by setFieldsState, so just allow the tab switch.
                    }

                    if (shouldSwitch) {
                        tabButtons.forEach(btn => btn.classList.remove('active'));
                        tabContents.forEach(content => content.classList.add('hidden'));

                        button.classList.add('active');
                        document.getElementById(`tab-${targetTabId}`).classList.remove('hidden');
                    } else {
                        // If switch is prevented, activate the currently displayed tab button
                        const currentlyDisplayedTab = document.querySelector(
                            '.tab-content:not(.hidden)');
                        if (currentlyDisplayedTab) {
                            document.querySelector(
                                `.tab-button[data-tab="${currentlyDisplayedTab.id.replace('tab-', '')}"]`
                            ).classList.add('active');
                        }
                    }
                });
            });

            // Store original required state for inputs inside tabs, so we can re-apply/remove dynamically
            document.querySelectorAll(
                '#tab-general-details input, #tab-general-details select, #tab-general-details textarea, #tab-pricing-inventory input, #tab-shipping input, #tab-attributes-variants input, #tab-attributes-variants select, #tab-attributes-variants textarea'
            ).forEach(input => {
                if (input.required) {
                    input.setAttribute('data-original-required', 'true');
                }
            });

            // Re-call toggleProductTypeSections after `data-original-required` is set to ensure correct initial state
            toggleProductTypeSections();

            // Thêm validation real-time cho các trường quan trọng
            addRealTimeValidation();

            // Khôi phục dữ liệu cũ nếu có lỗi validation
            restoreFormData();

            // Thêm validation cho logic giá nhập và giá bán khi load trang
            setTimeout(() => {
                validatePriceLogic();
                validateVariantPriceLogic();
            }, 200);

        });

        // Hàm thêm validation real-time
        function addRealTimeValidation() {
            // Validation cho tên sản phẩm
            const nameInput = document.querySelector('input[name="name"]');
            const nameCharCount = document.getElementById('name-char-count');

            if (nameInput) {
                let nameCheckTimeout;

                // Cập nhật character counter
                const updateCharCount = () => {
                    const length = nameInput.value.length;
                    nameCharCount.textContent = length;

                    // Thay đổi màu khi gần đạt giới hạn
                    if (length >= 90) {
                        nameCharCount.classList.add('text-red-500');
                        nameCharCount.classList.remove('text-gray-400');
                    } else if (length >= 80) {
                        nameCharCount.classList.add('text-yellow-500');
                        nameCharCount.classList.remove('text-gray-400', 'text-red-500');
                    } else {
                        nameCharCount.classList.remove('text-yellow-500', 'text-red-500');
                        nameCharCount.classList.add('text-gray-400');
                    }
                };

                // Cập nhật counter khi load trang
                updateCharCount();

                nameInput.addEventListener('input', function() {
                    // Cập nhật character counter
                    updateCharCount();

                    // Clear timeout cũ
                    clearTimeout(nameCheckTimeout);

                    // Clear error nếu có
                    clearFieldError(this);

                    // Set timeout mới để kiểm tra sau 500ms
                    nameCheckTimeout = setTimeout(() => {
                        checkProductName(this.value.trim());
                    }, 500);
                });

                nameInput.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        showFieldError(this, 'Vui lòng nhập tên sản phẩm.');
                    } else if (this.value.trim().length > 255) {
                        showFieldError(this, 'Tên sản phẩm không được vượt quá 255 ký tự.');
                    } else {
                        // Kiểm tra ngay khi blur
                        checkProductName(this.value.trim());
                    }
                });
            }

            // Validation cho SKU
            const skuInput = document.querySelector('input[name="sku"]');
            if (skuInput) {
                skuInput.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        showFieldError(this, 'Vui lòng nhập mã SKU.');
                    } else {
                        clearFieldError(this);
                    }
                });
            }

            // Validation cho giá sản phẩm đơn
            const priceInputs = document.querySelectorAll('#tab-pricing-inventory input[type="number"]');
            priceInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value && (isNaN(this.value) || parseFloat(this.value) < 0)) {
                        showFieldError(this, 'Vui lòng nhập giá trị hợp lệ (>= 0).');
                    } else if (this.value) {
                        clearFieldError(this);
                    }

                    // Kiểm tra logic giá nhập và giá bán
                    validatePriceLogic();
                });
            });

            // Thêm validation cho giá biến thể
            document.addEventListener('blur', function(e) {
                if (e.target.name && e.target.name.includes('variants') && e.target.type === 'number') {
                    if (e.target.value && (isNaN(e.target.value) || parseFloat(e.target.value) < 0)) {
                        showFieldError(e.target, 'Vui lòng nhập giá trị hợp lệ (>= 0).');
                    } else if (e.target.value) {
                        clearFieldError(e.target);
                    }

                    // Kiểm tra logic giá nhập và giá bán cho biến thể
                    validateVariantPriceLogic();
                }
            }, true);

            // Validation cho thuộc tính
            document.addEventListener('blur', function(e) {
                if (e.target.classList.contains('attribute-values')) {
                    if (!e.target.value.trim()) {
                        showFieldError(e.target, 'Vui lòng nhập giá trị thuộc tính.');
                    } else {
                        clearFieldError(e.target);
                    }
                }
            }, true);

            // Validation cho SKU biến thể
            document.addEventListener('blur', function(e) {
                if (e.target.name && e.target.name.includes('variants') && e.target.name.includes('[sku]')) {
                    if (!e.target.value.trim()) {
                        showFieldError(e.target, 'Vui lòng nhập SKU cho biến thể.');
                    } else {
                        clearFieldError(e.target);
                    }
                }
            }, true);
        }

        // Hàm xóa lỗi cho một trường cụ thể
        function clearFieldError(field) {
            if (!field) return;

            // Xóa class lỗi
            field.classList.remove('border-red-500');

            // Xóa thông báo lỗi
            const existingError = field.parentNode.querySelector('.field-error');
            if (existingError) {
                existingError.remove();
            }
        }

        // Hàm validation logic giá nhập và giá bán cho sản phẩm đơn
        function validatePriceLogic() {
            const purchasePriceInput = document.querySelector('#tab-pricing-inventory input[name="purchase_price"]');
            const salePriceInput = document.querySelector('#tab-pricing-inventory input[name="sale_price"]');

            if (purchasePriceInput && salePriceInput && purchasePriceInput.value && salePriceInput.value) {
                const purchasePrice = parseFloat(purchasePriceInput.value);
                const salePrice = parseFloat(salePriceInput.value);

                if (purchasePrice > salePrice) {
                    showFieldError(purchasePriceInput, 'Giá nhập không được lớn hơn giá bán.');
                    showFieldError(salePriceInput, 'Giá bán phải lớn hơn hoặc bằng giá nhập.');
                } else {
                    clearFieldError(purchasePriceInput);
                    clearFieldError(salePriceInput);
                }
            }
        }

        // Hàm validation logic giá nhập và giá bán cho biến thể
        function validateVariantPriceLogic() {
            const variantItems = document.querySelectorAll('#variant-container .variant-item');

            variantItems.forEach((variant, index) => {
                const purchasePriceInput = variant.querySelector(
                    `input[name="variants[${index}][purchase_price]"]`);
                const salePriceInput = variant.querySelector(`input[name="variants[${index}][sale_price]"]`);

                if (purchasePriceInput && salePriceInput && purchasePriceInput.value && salePriceInput.value) {
                    const purchasePrice = parseFloat(purchasePriceInput.value);
                    const salePrice = parseFloat(salePriceInput.value);

                    if (purchasePrice > salePrice) {
                        showFieldError(purchasePriceInput, 'Giá nhập không được lớn hơn giá bán.');
                        showFieldError(salePriceInput, 'Giá bán phải lớn hơn hoặc bằng giá nhập.');
                    } else {
                        clearFieldError(purchasePriceInput);
                        clearFieldError(salePriceInput);
                    }
                }
            });
        }

        // Hàm kiểm tra tên sản phẩm đã tồn tại
        function checkProductName(name) {
            if (!name || name.length < 2) {
                return; // Không kiểm tra nếu tên quá ngắn
            }

            const nameInput = document.querySelector('input[name="name"]');
            const nameContainer = nameInput.parentNode;

            // Tạo hoặc hiển thị spinner
            let spinner = nameContainer.querySelector('.name-check-spinner');
            if (!spinner) {
                spinner = document.createElement('div');
                spinner.className = 'name-check-spinner';
                spinner.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                nameContainer.appendChild(spinner);
            }
            spinner.style.display = 'block';

            fetch('{{ route('seller.products.check-name') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        name: name
                    })
                })
                .then(response => response.json())
                .then(data => {
                    spinner.style.display = 'none';
                    if (data.exists) {
                        showFieldError(nameInput, data.message);
                    } else {
                        clearFieldError(nameInput);
                    }
                })
                .catch(error => {
                    spinner.style.display = 'none';
                    console.error('Error checking product name:', error);
                });
        }

        // Hàm khôi phục dữ liệu form từ session storage (nếu có)
        function restoreFormData() {
            // Kiểm tra xem có lỗi validation không
            const hasErrors = document.querySelector('.bg-red-100');
            if (hasErrors) {
                // Nếu có lỗi, form sẽ tự động giữ dữ liệu cũ thông qua Laravel's old() helper
                console.log('Form có lỗi validation, dữ liệu cũ đã được khôi phục');

                // Khôi phục dữ liệu biến thể nếu có
                restoreVariantData();
            }
        }

        // Hàm khôi phục trạng thái loại sản phẩm
        function restoreProductTypeState() {
            const productType = '{{ old('product_type', 'simple') }}';
            debugLog('Restoring product type state', { productType });
            
            const radioButton = document.querySelector(`input[name="product_type"][value="${productType}"]`);
            if (radioButton) {
                radioButton.checked = true;
                debugLog('Product type radio button checked', { value: radioButton.value });
                
                // Trigger change event để cập nhật UI
                radioButton.dispatchEvent(new Event('change'));
            } else {
                debugLog('Product type radio button not found', { productType });
            }
        }

        // Hàm khôi phục dữ liệu biến thể
        function restoreVariantData() {
            const oldVariants = @json(old('variants', []));
            const oldAttributes = @json(old('attributes', []));

            if (oldVariants && oldVariants.length > 0) {
                // Nếu có dữ liệu biến thể cũ, tạo lại biến thể
                setTimeout(() => {
                    generateVariantsFromOldData(oldVariants);
                }, 100);
            }

            // Khôi phục dữ liệu thuộc tính nếu có
            if (oldAttributes && oldAttributes.length > 0) {
                restoreAttributeData(oldAttributes);
            }
        }

        // Hàm khôi phục dữ liệu thuộc tính
        function restoreAttributeData(oldAttributes) {
            const attributeContainer = document.getElementById('attribute-container');
            if (!attributeContainer) return;

            // Xóa tất cả thuộc tính hiện tại
            attributeContainer.innerHTML = '';

            oldAttributes.forEach((attribute, index) => {
                const attributeRow = document.createElement('div');
                attributeRow.classList.add('flex', 'items-center', 'gap-4', 'mb-2', 'attribute-row');

                let attributeHTML = `
                    <select name="attributes[${index}][id]" class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-select">
                        <option value="" disabled>Chọn hoặc nhập thuộc tính</option>
                        <option value="new" ${attribute.id === 'new' ? 'selected' : ''}>Tạo thuộc tính mới</option>
                        ${window.allAttributes
                            .filter(attr => attr.id && attr.name)
                            .map(attr => `<option value="${attr.id}" ${attribute.id == attr.id ? 'selected' : ''}>${attr.name}</option>`)
                            .join('')}
                    </select>
                    <input type="text" name="attributes[${index}][name]" value="${attribute.name || ''}" class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-name ${attribute.id === 'new' ? '' : 'hidden'}" placeholder="Tên thuộc tính (VD: Màu sắc, Kích thước)">
                    <input type="text" name="attributes[${index}][values]" value="${attribute.values || ''}" class="w-2/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-values" placeholder="Giá trị (VD: Đỏ, Xanh, Vàng - phân cách bằng dấu phẩy)" data-has-old-value="true">
                    <button type="button" class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 remove-attribute">Xóa</button>
                `;

                attributeRow.innerHTML = attributeHTML;
                attributeContainer.appendChild(attributeRow);

                // Thêm event listeners
                const select = attributeRow.querySelector('.attribute-select');
                select.addEventListener('change', function() {
                    updateAttributeValues(this);
                });

                attributeRow.querySelector('.remove-attribute').addEventListener('click', () => {
                    attributeRow.remove();
                    updateAttributeIndices();
                });

                attributeRow.querySelector('input[name$="[name]"]').addEventListener('input', function() {
                    validateAttributeName(this);
                });

                attributeRow.querySelector('input[name$="[values]"]').addEventListener('input', function() {
                    validateAttributeValues(this);
                });
            });

            // Cập nhật index cho thuộc tính
            attributeIndex = oldAttributes.length;
        }

        // Hàm tạo biến thể từ dữ liệu cũ
        function generateVariantsFromOldData(oldVariants) {
            const variantContainer = document.getElementById('variant-container');
            if (!variantContainer) return;

            variantContainer.innerHTML = '';

            oldVariants.forEach((variant, index) => {
                const variantDiv = document.createElement('div');
                variantDiv.classList.add('p-6', 'border', 'border-gray-300', 'rounded-md', 'mb-6', 'bg-white',
                    'relative', 'variant-item');

                let variantHTML = `
                    <div class="flex justify-between items-center mb-3">
                        <h5 class="text-lg font-semibold">Biến thể ${index + 1}: ${variant.name || 'Biến thể'}</h5>
                        <div class="flex space-x-3">
                            <button type="button" class="text-red-500 hover:text-red-600 remove-variant">Xóa</button>
                            <button type="button" class="toggle-variants" data-index="${index}" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                                <svg class="toggle-icon w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="variant-content transition-all duration-300 ease-in-out">
                        <input type="hidden" name="variants[${index}][index]" value="${index}">
                        <input type="hidden" name="variants[${index}][name]" value="${variant.name || ''}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Giá gốc</label>
                                <input type="number" name="variants[${index}][price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá gốc" value="${variant.price || ''}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Giá nhập</label>
                                <input type="number" name="variants[${index}][purchase_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá nhập" value="${variant.purchase_price || ''}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Giá bán</label>
                                <input type="number" name="variants[${index}][sale_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá bán" value="${variant.sale_price || ''}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">SKU</label>
                                <input type="text" name="variants[${index}][sku]" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-2 focus:ring-blue-500" placeholder="Nhập SKU" value="${variant.sku || ''}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Số lượng tồn kho</label>
                                <input type="number" name="variants[${index}][stock_total]" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập số lượng" value="${variant.stock_total || ''}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Chiều dài (inch)</label>
                                <input type="number" name="variants[${index}][length]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều dài" value="${variant.length || ''}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Chiều rộng (inch)</label>
                                <input type="number" name="variants[${index}][width]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều rộng" value="${variant.width || ''}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Chiều cao (inch)</label>
                                <input type="number" name="variants[${index}][height]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều cao" value="${variant.height || ''}">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Trọng lượng (kg)</label>
                                <input type="number" name="variants[${index}][weight]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Trọng lượng" value="${variant.weight || ''}">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-1">Thuộc tính biến thể</label>
                            ${variant.attributes ? variant.attributes.map((attr, attrIndex) => `
                                        <div class="flex items-center gap-4 mb-2">
                                            <input type="text" name="variants[${index}][attributes][${attrIndex}][name]" value="${attr.name || ''}" placeholder="Tên thuộc tính" class="w-1/3 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                                            <input type="text" name="variants[${index}][attributes][${attrIndex}][value]" value="${attr.value || ''}" placeholder="Giá trị thuộc tính" class="w-2/3 border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                                        </div>
                                    `).join('') : ''}
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Hình ảnh</label>
                            <input type="file" name="variant_images[${index}][]"
                                multiple
                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                accept="image/*"
                                onchange="previewVariantImage(event, ${index})">
                            <div id="preview-images-${index}"
                                class="mt-2 flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                `;
                variantDiv.innerHTML = variantHTML;
                variantContainer.appendChild(variantDiv);

                variantDiv.querySelector('.remove-variant').addEventListener('click', () => {
                    variantDiv.remove();
                    updateVariantIndices();
                });
            });

            initializeToggleButtons();
            updateVariantIndices();
        }
    </script>
@endpush
@push('styles')
    <style>
        .tab-button.active {
            background-color: #e0f2fe;
            /* Light blue for active tab */
            color: #2563eb;
            /* Darker blue text */
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
            /* Safari */
            position: sticky;
            top: 1rem;
            /* Adjust as needed */
            align-self: flex-start;
            /* For flex container parent */
        }

        /* Styles cho validation errors */
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

        /* Loading state cho kiểm tra tên sản phẩm */
        .name-check-spinner {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 14px;
            display: none;
            z-index: 10;
        }

        /* Đảm bảo container có position relative */
        .mb-4 {
            position: relative;
        }
    </style>
@endpush
