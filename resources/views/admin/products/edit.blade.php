@extends('layouts.admin')
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

        .field-error {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        .name-check-spinner {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 14px;
            display: none;
            /* Hidden by default */
            z-index: 10;
        }

        /* Đảm bảo container có position relative */
        .mb-4 {
            /* This class is on the parent div of the name input */
            position: relative;
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
@section('title', 'Chỉnh sửa sản phẩm')
@section('content')
    <div class="mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Chỉnh sửa sản phẩm</h1>
                <div class="text-sm text-gray-500">
                    <a href="{{ route('seller.dashboard') }}" class="hover:underline">Trang chủ</a> / Chỉnh sửa sản phẩm
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

        <!-- Main Content Form -->
        <form id="product-form" action="{{ route('seller.products.update', $product->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- General Product Information (Always Visible) - Top Section -->
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h4 class="text-xl font-semibold mb-4">Thông tin chung</h4>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Tên sản phẩm <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="name" id="product-name"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Tên sản phẩm" value="{{ old('name', $product->name) }}" maxlength="100">
                        <div class="absolute right-2 top-2 text-xs text-gray-400">
                            <span id="name-char-count">0</span>/100
                        </div>
                    </div>
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
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">SKU sản phẩm <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="sku"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="SKU sản phẩm" value="{{ old('sku', $product->sku) }}" required>
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
                                   {{ old('product_type', !$product->is_variant ? 'simple' : 'variant') === 'simple' ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <span class="ml-2 text-sm font-medium text-gray-900">Sản phẩm đơn</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="product_type" value="variant"
                                   {{ old('product_type', !$product->is_variant ? 'simple' : 'variant') === 'variant' ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            <span class="ml-2 text-sm font-medium text-gray-900">Sản phẩm có biến thể</span>
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
                                class="tab-button text-left px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100"
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

                <!-- Right Content Area: Tab Content Sections -->
                <div class="col-span-12 lg:col-span-9">

                    <!-- Tab Content: General Details (Includes original Product Details, SEO, Image Upload) -->
                    <div id="tab-general-details" class="tab-content">
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
                                                            {{ in_array($brand->id, old('brand_ids', $product->brands->pluck('id')->toArray())) ? 'checked' : '' }}
                                                            data-original-required="false">
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
                                                                    {{ in_array($child->id, old('brand_ids', $product->brands->pluck('id')->toArray())) ? 'checked' : '' }}
                                                                    data-original-required="false">
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
                                                                                {{ in_array($grandchild->id, old('brand_ids', $product->brands->pluck('id')->toArray())) ? 'checked' : '' }}
                                                                                data-original-required="false">
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
                                                            {{ in_array($category->id, old('category_ids', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}
                                                            data-original-required="true">
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
                                                                    {{ in_array($child->id, old('category_ids', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}
                                                                    data-original-required="true">
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
                                                                            <input type="checkbox" name="category_ids[]"
                                                                                id="category_{{ $grandchild->id }}"
                                                                                class="border border-gray-300 rounded-md p-2 focus:outline-none"
                                                                                value="{{ $grandchild->id }}"
                                                                                {{ in_array($grandchild->id, old('category_ids', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}
                                                                                data-original-required="true">
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
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_featured" value="1" class="mr-2"
                                        {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                        data-original-required="false">
                                    <span class="text-gray-700 font-medium">Sản phẩm nổi bật</span>
                                </label>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Từ khóa (Tags)</label>
                                <input type="text" name="meta_keywords" id="meta-keywords"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Thêm từ khóa (phân cách bằng dấu phẩy)"
                                    value="{{ old('meta_keywords', $product->meta_keywords) }}"
                                    data-original-required="false">
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
                                    placeholder="Tiêu đề SEO (tối đa 60 ký tự)" data-original-required="false">
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
                                    maxlength="160" placeholder="Mô tả ngắn gọn (tối đa 160 ký tự)" data-original-required="false">{{ old('meta_description', $product->meta_description) }}</textarea>
                                <span class="text-sm text-gray-500 block mt-1">Mô tả hiển thị dưới tiêu đề trên công cụ
                                    tìm kiếm.</span>
                                @error('meta_description')
                                    <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 font-medium mb-1">Xem trước SEO</label>
                                <div id="seo-preview" class="p-3 border border-gray-300 rounded-md">
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
                            <h4 class="text-xl font-semibold mb-4">Hình ảnh sản phẩm</h4>
                            <!-- Ảnh chính -->
                            <div class="mb-4">
                                <label class="block text-gray-700 font-medium mb-1">Ảnh chính <span
                                        class="text-red-500">*</span></label>
                                <div class="text-center border-2 border-dashed border-gray-300 rounded-md p-4">
                                    <img id="uploadIcon1" class="w-24 h-auto mx-auto mb-2"
                                        src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : 'https://html.hixstudio.net/ebazer/assets/img/icons/upload.png' }}"
                                        alt="{{ $product->images->first() ? 'Uploaded Image' : 'Upload Icon' }}">
                                    <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh nhỏ hơn 5Mb</span>
                                    <label for="mainImage"
                                        class="inline-block py-2 px-4 bg-blue-100 text-blue-700 rounded-md cursor-pointer hover:bg-blue-200">Chọn
                                        ảnh chính</label>
                                    <input type="hidden" name="existing_main_image"
                                        value="{{ $product->images->first()->image_path ?? '' }}">
                                    <input type="file" id="mainImage" name="main_image" class="hidden"
                                        accept="image/*" data-original-required="true">
                                    @error('main_image')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ảnh phụ -->
                            <div class="mb-4">
                                <label class="block text-gray-700 font-medium mb-1">Ảnh phụ</label>
                                <div class="text-center border-2 border-dashed border-gray-300 rounded-md p-4">
                                    <div id="additionalImagesPreview" class="mt-2 flex flex-wrap gap-2">
                                        @foreach ($product->images->whereNull('variant_id') as $image)
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
                                    <span class="text-sm text-gray-500 block mb-3">Kích thước ảnh nhỏ hơn 5Mb</span>
                                    <label for="additionalImages"
                                        class="inline-block py-2 px-4 bg-blue-100 text-blue-700 rounded-md cursor-pointer hover:bg-blue-200">Chọn
                                        ảnh phụ</label>
                                    <input type="file" id="additionalImages" name="images[]" multiple class="hidden"
                                        accept="image/*" data-original-required="false">
                                    @error('images.*')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
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
                                        placeholder="Giá gốc của sản phẩm" value="{{ old('price', $product->price) }}"
                                        required data-original-required="true">
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
                                        value="{{ old('purchase_price', $product->purchase_price) }}" required
                                        data-original-required="true">
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
                                        value="{{ old('sale_price', $product->sale_price) }}" required
                                        data-original-required="true">
                                    @error('sale_price')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Số lượng tồn kho <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" name="stock_total"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Số lượng tồn kho"
                                        value="{{ old('stock_total', $product->stock_total) }}" required
                                        data-original-required="true">
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
                                        placeholder="Chiều dài sản phẩm"
                                        value="{{ old('length', $product->dimensions->isNotEmpty() ? $product->dimensions->first()->length : '') }}"
                                        data-original-required="false">
                                    @error('length')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Chiều rộng (inch)</label>
                                    <input type="number" name="width" step="0.01"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Chiều rộng sản phẩm"
                                        value="{{ old('width', $product->dimensions->isNotEmpty() ? $product->dimensions->first()->width : '') }}"
                                        data-original-required="false">
                                    @error('width')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Chiều cao (inch)</label>
                                    <input type="number" name="height" step="0.01"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Chiều cao sản phẩm"
                                        value="{{ old('height', $product->dimensions->isNotEmpty() ? $product->dimensions->first()->height : '') }}"
                                        data-original-required="false">
                                    @error('height')
                                        <span class="text-sm text-red-500 block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Trọng lượng (kg)</label>
                                    <input type="number" name="weight" step="0.01"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Trọng lượng sản phẩm"
                                        value="{{ old('weight', $product->dimensions->isNotEmpty() ? $product->dimensions->first()->weight : '') }}"
                                        data-original-required="false">
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
                                    // Prepare attributes for JavaScript, including existing product attributes and all available attributes
                                    $combinedAttributes = $attributes
                                        ->map(function ($attr) {
                                            return [
                                                'id' => $attr->id,
                                                'name' => $attr->name,
                                                'values' => $attr->values->pluck('value')->toArray(),
                                            ];
                                        })
                                        ->toArray();

                                    $allAttrIds = collect($combinedAttributes)->pluck('id')->toArray();

                                    foreach ($allAttributes as $attr) {
                                        if (!in_array($attr->id, $allAttrIds)) {
                                            $combinedAttributes[] = [
                                                'id' => $attr->id,
                                                'name' => $attr->name,
                                                'values' => $attr->values->pluck('value')->toArray(),
                                            ];
                                        }
                                    }
                                @endphp

                                @foreach ($attributes as $index => $attribute)
                                    <div class="flex items-center gap-4 mb-2 attribute-row">
                                        <select name="attributes[{{ $index }}][id]"
                                            class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-select"
                                            onchange="updateAttributeValues(this)">
                                            <option value="" disabled {{ empty($attribute->id) ? 'selected' : '' }}>
                                                Chọn hoặc nhập thuộc tính
                                            </option>
                                            <option value="new"
                                                {{ ($attribute->id ?? '') === 'new' ? 'selected' : '' }}>
                                                Tạo thuộc tính mới
                                            </option>
                                            @foreach ($combinedAttributes as $attr)
                                                <option value="{{ $attr['id'] }}"
                                                    {{ ($attribute->id ?? '') == $attr['id'] ? 'selected' : '' }}>
                                                    {{ $attr['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="attributes[{{ $index }}][name]"
                                            value="{{ old("attributes.$index.name", $attribute->name ?? '') }}"
                                            class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-name {{ ($attribute->id ?? '') === 'new' ? '' : 'hidden' }}"
                                            placeholder="Tên thuộc tính (VD: Màu sắc, Kích thước)"
                                            data-original-required="true">
                                        <input type="text" name="attributes[{{ $index }}][values]"
                                            value="{{ old("attributes.$index.values", $attribute->values->isNotEmpty() ? $attribute->values->pluck('value')->implode(', ') : '') }}"
                                            class="w-2/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-values"
                                            placeholder="Giá trị (VD: Đỏ, Xanh, Vàng - phân cách bằng dấu phẩy)" required
                                            data-original-required="true">
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
                                                        <input type="number"
                                                            name="variants[{{ $index }}][price]" step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Nhập giá gốc"
                                                            value="{{ old("variants.$index.price", $variant->price) }}"
                                                            required data-original-required="true">
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
                                                            required data-original-required="true">
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Giá bán</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][sale_price]"
                                                            step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Nhập giá bán"
                                                            value="{{ old("variants.$index.sale_price", $variant->sale_price) }}"
                                                            required data-original-required="true">
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">SKU</label>
                                                        <input type="text" name="variants[{{ $index }}][sku]"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Nhập SKU"
                                                            value="{{ old("variants.$index.sku", $variant->sku) }}"
                                                            required data-original-required="true">
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Số lượng tồn
                                                            kho</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][stock_total]"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Nhập số lượng"
                                                            value="{{ old("variants.$index.stock_total", $variant->stock) }}"
                                                            required data-original-required="true">
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Chiều dài
                                                            (inch)</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][length]" step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Chiều dài"
                                                            value="{{ old("variants.$index.length", optional($variant->dimensions)->length ?? '') }}"
                                                            data-original-required="false">
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Chiều rộng
                                                            (inch)</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][width]" step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Chiều rộng"
                                                            value="{{ old("variants.$index.width", optional($variant->dimensions)->width ?? '') }}"
                                                            data-original-required="false">
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Chiều cao
                                                            (inch)</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][height]" step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Chiều cao"
                                                            value="{{ old("variants.$index.height", optional($variant->dimensions)->height ?? '') }}"
                                                            data-original-required="false">
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 font-medium mb-1">Trọng lượng
                                                            (kg)</label>
                                                        <input type="number"
                                                            name="variants[{{ $index }}][weight]" step="0.01"
                                                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                            placeholder="Trọng lượng"
                                                            value="{{ old("variants.$index.weight", optional($variant->dimensions)->weight ?? '') }}"
                                                            data-original-required="false">
                                                    </div>
                                                </div>
                                                <!-- Hiển thị thuộc tính của biến thể -->
                                                <div class="mb-4">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="col-span-12 flex justify-start space-x-3 mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Lưu và
                    cập nhật</button>
                <button type="submit" name="save_draft" value="1"
                    class="border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-100">Lưu nháp</button>
            </div>
        </form>
    </div>

    @php
        // Prepare attributes for JavaScript, including existing product attributes and all available attributes
        $combinedAttributes = $attributes
            ->map(function ($attr) {
                return [
                    'id' => $attr->id,
                    'name' => $attr->name,
                    'values' => $attr->values->pluck('value')->toArray(),
                ];
            })
            ->toArray();

        $allAttrIds = collect($combinedAttributes)->pluck('id')->toArray();

        foreach ($allAttributes as $attr) {
            if (!in_array($attr->id, $allAttrIds)) {
                $combinedAttributes[] = [
                    'id' => $attr->id,
                    'name' => $attr->name,
                    'values' => $attr->values->pluck('value')->toArray(),
                ];
            }
        }
    @endphp

    @push('scripts')
        <script>
            window.allAttributes = @json($combinedAttributes);

            function debugLog(message, data = null) {
                console.log(`[DEBUG] ${message}`, data);
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
                        nameInput.value = selectedAttribute.name; // Keep the name updated
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
                    <input type="text" name="attributes[${attributeIndex}][name]" class="w-1/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-name hidden" placeholder="Tên thuộc tính (VD: Màu sắc, Kích thước)" data-original-required="true">
                    <input type="text" name="attributes[${attributeIndex}][values]" class="w-2/3 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 attribute-values" placeholder="Giá trị (VD: Đỏ, Xanh, Vàng - phân cách bằng dấu phẩy)" required data-original-required="true">
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
                                    <input type="number" name="variants[${index}][price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá gốc" required data-original-required="true">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Giá nhập</label>
                                    <input type="number" name="variants[${index}][purchase_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá nhập" required data-original-required="true">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Giá bán</label>
                                    <input type="number" name="variants[${index}][sale_price]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập giá bán" required data-original-required="true">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">SKU</label>
                                    <input type="text" name="variants[${index}][sku]" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập SKU" required data-original-required="true">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Số lượng tồn kho</label>
                                    <input type="number" name="variants[${index}][stock_total]" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập số lượng" required data-original-required="true">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Chiều dài (inch)</label>
                                    <input type="number" name="variants[${index}][length]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều dài" data-original-required="false">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Chiều rộng (inch)</label>
                                    <input type="number" name="variants[${index}][width]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều rộng" data-original-required="false">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Chiều cao (inch)</label>
                                    <input type="number" name="variants[${index}][height]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Chiều cao" data-original-required="false">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-1">Trọng lượng (kg)</label>
                                    <input type="number" name="variants[${index}][weight]" step="0.01" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Trọng lượng" data-original-required="false">
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

                    // Add toggle button event listener
                    const toggleButton = variantDiv.querySelector('.toggle-variants');
                    if (toggleButton) {
                        toggleButton.addEventListener('click', handleToggleClick);
                    }
                });

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
                debugLog('Initializing main image preview');
                const input = document.getElementById(inputId);
                const uploadIcon = document.getElementById(iconId);
                const existingMainImageInput = document.querySelector('input[name="existing_main_image"]');

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
                            // If there was an existing main image, restore its path to the hidden input
                            if (existingMainImageInput) {
                                uploadIcon.src = `{{ asset('storage/') }}/${existingMainImageInput.value}`;
                                uploadIcon.alt = 'Uploaded Image';
                            }
                            return;
                        }
                        if (!file.type.startsWith('image/')) {
                            alert('Vui lòng chọn file hình ảnh hợp lệ!');
                            input.value = '';
                            uploadIcon.src = "https://html.hixstudio.net/ebazer/assets/img/icons/upload.png";
                            uploadIcon.alt = 'Upload Icon';
                            if (existingMainImageInput) {
                                uploadIcon.src = `{{ asset('storage/') }}/${existingMainImageInput.value}`;
                                uploadIcon.alt = 'Uploaded Image';
                            }
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            uploadIcon.src = event.target.result;
                            uploadIcon.alt = 'Uploaded Image';
                            debugLog('Main image preview updated', {
                                src: event.target.result
                            });
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // If no file is selected, revert to original image or default icon
                        if (existingMainImageInput && existingMainImageInput.value) {
                            uploadIcon.src = `{{ asset('storage/') }}/${existingMainImageInput.value}`;
                            uploadIcon.alt = 'Uploaded Image';
                        } else {
                            uploadIcon.src = "https://html.hixstudio.net/ebazer/assets/img/icons/upload.png";
                            uploadIcon.alt = 'Upload Icon';
                        }
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
                    // Clear only new images preview, keep existing images
                    // There's no separate 'newImagesPreview' div in edit.blade.php as in create.blade.php,
                    // so we need to add new images to the existing previewContainer.
                    // To avoid duplicates, we'll clear only the dynamically added ones.
                    previewContainer.querySelectorAll('.new-image-preview').forEach(el => el.remove());

                    Array.from(e.target.files).forEach(file => {
                        if (file.size > 5 * 1024 * 1024) {
                            alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                            input.value = '';
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = event => {
                            const imgContainer = document.createElement('div');
                            imgContainer.classList.add('relative', 'w-24', 'h-24',
                            'new-image-preview'); // Add class for new images
                            imgContainer.innerHTML = `
                                <img src="${event.target.result}" class="w-full h-full object-cover rounded-md border border-gray-300">
                                <button type="button" class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-full" onclick="this.parentElement.remove()">✖</button>
                                <input type="hidden" name="new_images_data[]" value="${event.target.result}">
                            `; // Store base64 for new images for potential re-submission
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
                // Clear only new images preview for this variant
                previewContainer.querySelectorAll('.new-variant-image-preview').forEach(el => el.remove());

                Array.from(event.target.files).forEach((file, fileIndex) => {
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Kích thước ảnh phải nhỏ hơn 5Mb!');
                        event.target.value = '';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgContainer = document.createElement('div');
                        imgContainer.classList.add('relative', 'w-24', 'h-24',
                        'new-variant-image-preview'); // Add class for new variant images
                        imgContainer.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-cover rounded-md border border-gray-300">
                            <button type="button" class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-full" onclick="this.parentElement.remove()">✖</button>
                            <input type="hidden" name="new_variant_images_data[${index}][]" value="${e.target.result}">
                        `; // Store base64 for new variant images
                        previewContainer.appendChild(imgContainer);
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Hàm xóa ảnh (cho cả ảnh chính, ảnh phụ và ảnh biến thể)
            function removeImage(element) {
                debugLog('Removing image');
                const parent = element.parentElement;
                const hiddenInput = parent.querySelector('input[type="hidden"]');
                if (hiddenInput) {
                    // Check if it's an existing image input (not a new_images_data which would be base64)
                    if (hiddenInput.name.includes('existing_')) {
                        hiddenInput.name = 'deleted_images[]'; // Mark for deletion on backend
                        // Keep the value as the path for the backend to process
                    } else if (hiddenInput.name.includes('new_')) {
                        // This is a new image that was added but not yet saved, just remove it from DOM and don't send to backend
                        parent.remove();
                        return;
                    }
                }
                parent.remove();

                // If removing the main image and no new image is selected, show default upload icon
                const mainImageInput = document.getElementById('mainImage');
                const uploadIcon1 = document.getElementById('uploadIcon1');
                const existingMainImageInput = document.querySelector('input[name="existing_main_image"]');

                if (element.closest('.bg-white')?.querySelector('label[for="mainImage"]')) { // If it's the main image section
                    // Check if there are any current images shown in the main image preview area (including existing or new)
                    const currentMainImageSrc = uploadIcon1.src;
                    const defaultUploadIconSrc = "https://html.hixstudio.net/ebazer/assets/img/icons/upload.png";

                    // If the current image is the one just removed, and no new file is selected, revert to default icon
                    if (currentMainImageSrc !== defaultUploadIconSrc && mainImageInput.files.length === 0) {
                        // If it was an existing image that was removed, restore the previous one if it exists
                        if (existingMainImageInput && existingMainImageInput.value && !document.querySelector(
                                'input[name="deleted_images[]"][value="' + existingMainImageInput.value + '"]')) {
                            uploadIcon1.src = `{{ asset('storage/') }}/${existingMainImageInput.value}`;
                            uploadIcon1.alt = 'Uploaded Image';
                        } else {
                            // No existing image or it was marked for deletion, show default icon
                            uploadIcon1.src = defaultUploadIconSrc;
                            uploadIcon1.alt = 'Upload Icon';
                        }
                    }
                }
            }


            // Hàm xử lý toggle biến thể
            function initializeToggleButtons() {
                debugLog('Initializing toggle buttons');
                const toggleButtons = document.querySelectorAll('.toggle-variants');
                toggleButtons.forEach(button => {
                    // Remove existing event listeners
                    button.removeEventListener('click', handleToggleClick);
                    
                    // Add new event listener
                    button.addEventListener('click', handleToggleClick);
                });
            }

            function handleToggleClick() {
                const variantItem = this.closest('.variant-item');
                const variantContent = variantItem.querySelector('.variant-content');
                const toggleIcon = this.querySelector('.toggle-icon path');
                
                if (!variantContent || !toggleIcon) {
                    debugLog('Missing variantContent or toggleIcon');
                    return;
                }

                const isOpen = !variantContent.classList.contains('hidden');
                
                // Toggle visibility
                variantContent.classList.toggle('hidden', isOpen);
                
                // Update icon
                toggleIcon.setAttribute('d', isOpen ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7');
                
                debugLog('Toggling variant', { isOpen: !isOpen });
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
                const brandCheckboxes = document.querySelectorAll('#tab-general-details input[name="brand_ids[]"]:checked');
                const categoryCheckboxes = document.querySelectorAll(
                    '#tab-general-details input[name="category_ids[]"]:checked');
                let isValid = true;

                // Kiểm tra loại sản phẩm
                const productTypeInput = document.querySelector('input[name="product_type"]:checked');
                if (!productTypeInput) {
                    console.log('Không có radio product_type nào được chọn!');
                } else {
                    console.log('Giá trị product_type được chọn:', productTypeInput.value);
                }
                const productType = productTypeInput ? productTypeInput.value : null;

                // Common validations (now in General Details tab)
                const nameInput = document.querySelector('input[name="name"]');
                if (!nameInput.value.trim()) {
                    e.preventDefault();
                    alert('Vui lòng nhập tên sản phẩm.');
                    isValid = false;
                } else if (nameInput.value.trim().length > 100) {
                    e.preventDefault();
                    alert('Tên sản phẩm không được vượt quá 100 ký tự.');
                    isValid = false;
                } else {
                    // Kiểm tra tên sản phẩm đã tồn tại
                    const nameError = nameInput.parentNode.querySelector('.field-error');
                    if (nameError && nameError.textContent.includes('đã tồn tại')) {
                        e.preventDefault();
                        alert('Tên sản phẩm đã tồn tại trong shop của bạn.');
                        isValid = false;
                    }
                }
                if (!document.querySelector('input[name="sku"]').value.trim()) {
                    e.preventDefault();
                    alert('Vui lòng nhập mã SKU.');
                    isValid = false;
                }
                // Thương hiệu không bắt buộc nữa
                // if (brandCheckboxes.length === 0) {
                //     e.preventDefault();
                //     alert('Vui lòng chọn ít nhất một thương hiệu.');
                //     isValid = false;
                // }
                if (categoryCheckboxes.length === 0) {
                    e.preventDefault();
                    alert('Vui lòng chọn ít nhất một danh mục.');
                    isValid = false;
                }
                // Check main image only if no existing main image AND no new file selected
                const existingMainImage = document.querySelector('input[name="existing_main_image"]').value;
                const newMainImageSelected = document.getElementById('mainImage').files.length > 0;
                if (!existingMainImage && !newMainImageSelected) {
                    e.preventDefault();
                    alert('Vui lòng chọn ảnh chính.');
                    isValid = false;
                }

                // Product type specific validations
                if (productType === 'simple') {
                    const priceInput = document.querySelector('#tab-pricing-inventory input[name="price"]');
                    const purchasePriceInput = document.querySelector('#tab-pricing-inventory input[name="purchase_price"]');
                    const salePriceInput = document.querySelector('#tab-pricing-inventory input[name="sale_price"]');
                    const stockInput = document.querySelector('#tab-pricing-inventory input[name="stock_total"]');

                    if (!priceInput || !priceInput.value || isNaN(priceInput.value) || parseFloat(priceInput.value) < 0) {
                        e.preventDefault();
                        alert('Vui lòng nhập giá gốc hợp lệ cho sản phẩm đơn.');
                        isValid = false;
                    }
                    if (!purchasePriceInput || !purchasePriceInput.value || isNaN(purchasePriceInput.value) || parseFloat(
                            purchasePriceInput.value) < 0) {
                        e.preventDefault();
                        alert('Vui lòng nhập giá nhập hợp lệ cho sản phẩm đơn.');
                        isValid = false;
                    }
                    if (!salePriceInput || !salePriceInput.value || isNaN(salePriceInput.value) || parseFloat(salePriceInput
                            .value) < 0) {
                        e.preventDefault();
                        alert('Vui lòng nhập giá bán hợp lệ cho sản phẩm đơn.');
                        isValid = false;
                    }
                    if (!stockInput || !stockInput.value || isNaN(stockInput.value) || parseInt(stockInput.value) < 0) {
                        e.preventDefault();
                        alert('Vui lòng nhập số lượng tồn kho hợp lệ cho sản phẩm đơn.');
                        isValid = false;
                    }
                } else if (productType === 'variant') {
                    const attributeSelects = document.querySelectorAll(
                        '#tab-attributes-variants .attribute-row .attribute-select');
                    const variantItems = document.querySelectorAll('#variant-container > .variant-item');

                    // Check attributes
                    if (attributeSelects.length === 0) {
                        e.preventDefault();
                        alert('Vui lòng thêm ít nhất một thuộc tính cho sản phẩm biến thể.');
                        isValid = false;
                    } else {
                        attributeSelects.forEach((select, index) => {
                            const nameInput = select.closest('.attribute-row').querySelector('.attribute-name');
                            const valuesInput = select.closest('.attribute-row').querySelector('.attribute-values');
                            if (select.value === 'new' && (!nameInput.value || !nameInput.value.trim())) {
                                e.preventDefault();
                                alert(`Vui lòng nhập tên thuộc tính cho thuộc tính ${index + 1}.`);
                                isValid = false;
                            }
                            if (!valuesInput.value || !valuesInput.value.trim()) {
                                e.preventDefault();
                                alert(`Vui lòng nhập giá trị thuộc tính cho thuộc tính ${index + 1}.`);
                                isValid = false;
                            }
                        });
                    }

                    // Check variants
                    if (variantItems.length === 0) {
                        e.preventDefault();
                        alert('Vui lòng nhấn "Tạo biến thể" để tạo các biến thể trước khi lưu sản phẩm.');
                        isValid = false;
                    } else {
                        variantItems.forEach((item, index) => {
                            const priceInput = item.querySelector(`input[name="variants[${index}][price]"]`);
                            const purchasePriceInput = item.querySelector(
                                `input[name="variants[${index}][purchase_price]"]`);
                            const salePriceInput = item.querySelector(`input[name="variants[${index}][sale_price]"]`);
                            const skuInput = item.querySelector(`input[name="variants[${index}][sku]"]`);
                            const stockInput = item.querySelector(`input[name="variants[${index}][stock_total]"]`);

                            if (!priceInput.value || isNaN(priceInput.value) || parseFloat(priceInput.value) < 0) {
                                e.preventDefault();
                                alert(`Vui lòng nhập giá gốc hợp lệ cho biến thể ${index + 1}.`);
                                isValid = false;
                            }
                            if (!purchasePriceInput.value || isNaN(purchasePriceInput.value) || parseFloat(
                                    purchasePriceInput.value) < 0) {
                                e.preventDefault();
                                alert(`Vui lòng nhập giá nhập hợp lệ cho biến thể ${index + 1}.`);
                                isValid = false;
                            }
                            if (!salePriceInput.value || isNaN(salePriceInput.value) || parseFloat(salePriceInput
                                .value) < 0) {
                                e.preventDefault();
                                alert(`Vui lòng nhập giá bán hợp lệ cho biến thể ${index + 1}.`);
                                isValid = false;
                            }
                            if (!skuInput.value || !skuInput.value.trim()) {
                                e.preventDefault();
                                alert(`Vui lòng nhập SKU cho biến thể ${index + 1}.`);
                                isValid = false;
                            }
                            if (!stockInput.value || isNaN(stockInput.value) || parseInt(stockInput.value) < 0) {
                                e.preventDefault();
                                alert(`Vui lòng nhập số lượng tồn kho hợp lệ cho biến thể ${index + 1}.`);
                                isValid = false;
                            }
                        });
                    }
                }

                if (isValid) {
                    if (typeof tinymce !== 'undefined') {
                        tinymce.triggerSave();
                    }
                    debugLog('Form submission validated successfully');
                }
            }


            // Hàm hiển thị lỗi field
            function showFieldError(field, message) {
                clearFieldError(field);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'field-error';
                errorDiv.textContent = message;
                field.parentNode.appendChild(errorDiv);
                field.classList.add('border-red-500');
            }

            // Hàm xóa lỗi field
            function clearFieldError(field) {
                const existingError = field.parentNode.querySelector('.field-error');
                if (existingError) {
                    existingError.remove();
                }
                field.classList.remove('border-red-500');
            }

            // Hàm kiểm tra tên sản phẩm đã tồn tại
            function checkProductName(name) {
                if (!name.trim()) return;

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
                spinner.style.display = 'block'; // Show spinner

                fetch('{{ route('seller.products.check-name') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            name: name,
                            product_id: {{ $product->id }} // Include product ID for edit scenario
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        spinner.style.display = 'none'; // Hide spinner on success
                        if (data.exists) {
                            showFieldError(nameInput, data.message);
                        } else {
                            clearFieldError(nameInput);
                        }
                    })
                    .catch(error => {
                        spinner.style.display = 'none'; // Hide spinner on error
                        console.error('Error checking product name:', error);
                    });
            }

            // Product Type Toggle Function
            function initializeProductTypeToggle() {
                const productTypeRadios = document.querySelectorAll('input[name="product_type"]');
                
                productTypeRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        console.log('Product type changed to:', this.value);
                        
                        // Remove checked class from all radio buttons
                        productTypeRadios.forEach(r => {
                            r.classList.remove('checked');
                        });
                        
                        // Add checked class to selected radio
                        this.classList.add('checked');
                        
                        // Update tab visibility based on product type
                        updateTabVisibility();
                    });
                });
                
                // Initial setup
                const selectedType = document.querySelector('input[name="product_type"]:checked');
                if (selectedType) {
                    selectedType.classList.add('checked');
                    selectedType.dispatchEvent(new Event('change'));
                }
                
                // Add click event to labels for better UX
                const radioLabels = document.querySelectorAll('input[name="product_type"] + span');
                radioLabels.forEach(label => {
                    label.addEventListener('click', function() {
                        const radio = this.previousElementSibling;
                        if (radio) {
                            radio.checked = true;
                            radio.dispatchEvent(new Event('change'));
                        }
                    });
                });
            }

            function updateTabVisibility() {
                const productType = document.querySelector('input[name="product_type"]:checked')?.value;
                const tabButtons = document.querySelectorAll('.tab-button');
                
                tabButtons.forEach(button => {
                    const tabName = button.getAttribute('data-tab');
                    
                    if (productType === 'simple') {
                        // Hide attributes-variants tab for simple products
                        if (tabName === 'attributes-variants') {
                            button.style.display = 'none';
                        } else {
                            button.style.display = 'block';
                        }
                    } else if (productType === 'variant') {
                        // Hide pricing-inventory tab for variant products
                        if (tabName === 'pricing-inventory') {
                            button.style.display = 'none';
                        } else {
                            button.style.display = 'block';
                        }
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                debugLog('DOM fully loaded');

                // Khởi tạo TinyMCE
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

                // Xử lý submit form
                const productForm = document.getElementById('product-form');
                if (productForm) {
                    productForm.addEventListener('submit', validateForm);
                }

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
                        } else if (this.value.trim().length > 100) {
                            showFieldError(this, 'Tên sản phẩm không được vượt quá 100 ký tự.');
                        } else {
                            // Kiểm tra ngay khi blur
                            checkProductName(this.value.trim());
                        }
                    });
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

                // Initial setup for existing variant images (if any)
                document.querySelectorAll('[id^="preview-images-"]').forEach(container => {
                    // This section will be re-rendered by generateVariants() for new variants.
                    // For existing variants, we ensure old images are loaded correctly.
                    // The existing_variant_images hidden inputs already handle this via blade loop.
                });


                // Khởi tạo trạng thái ban đầu
                initializeToggleButtons(); // For existing variants on load
                initializeSubCategoryToggles();
                initializeSubBrandToggles();
                initializeProductTypeToggle(); // Initialize product type radio buttons

                // Ẩn/hiện thuộc tính và biến thể theo loại sản phẩm
                const productTypeRadios = document.querySelectorAll('input[name="product_type"]');
                const generalDetailsTabButton = document.querySelector('.tab-button[data-tab="general-details"]');
                const pricingInventoryTabButton = document.querySelector('.tab-button[data-tab="pricing-inventory"]');
                const shippingTabButton = document.querySelector('.tab-button[data-tab="shipping"]');
                const attributesVariantsTabButton = document.querySelector(
                    '.tab-button[data-tab="attributes-variants"]');

                const tabGeneralDetails = document.getElementById('tab-general-details');
                const tabPricingInventory = document.getElementById('tab-pricing-inventory');
                const tabShipping = document.getElementById('tab-shipping');
                const tabAttributesVariants = document.getElementById('tab-attributes-variants');

                function toggleProductTypeSections() {
                    const selectedProductType = document.querySelector('input[name="product_type"]:checked').value;

                    // Hide all tab content sections initially
                    tabGeneralDetails.classList.add('hidden');
                    tabPricingInventory.classList.add('hidden');
                    tabShipping.classList.add('hidden');
                    tabAttributesVariants.classList.add('hidden');

                    // Remove active class from all tab buttons initially
                    generalDetailsTabButton.classList.remove('active');
                    pricingInventoryTabButton.classList.remove('active');
                    shippingTabButton.classList.remove('active');
                    attributesVariantsTabButton.classList.remove('active');

                    // Helper to manage required attributes and disabled state
                    function setFieldsState(elements, isRequired, isDisabled) {
                        elements.forEach(el => {
                            if (el.hasAttribute('data-original-required')) {
                                if (el.getAttribute('data-original-required') === 'true' && isRequired) {
                                    el.setAttribute('required', 'required');
                                } else {
                                    el.removeAttribute('required');
                                }
                            }
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
                        // Display relevant tabs and set their active state
                        generalDetailsTabButton.style.display = '';
                        attributesVariantsTabButton.style.display = '';
                        pricingInventoryTabButton.style.display = 'none'; // Hide for variant
                        shippingTabButton.style.display = 'none'; // Hide for variant

                        // Set field states
                        setFieldsState(pricingInputs, false, true); // Disable pricing for variant
                        setFieldsState(shippingInputs, false, true); // Disable shipping for variant (if displayed)
                        setFieldsState(attributesVariantsInputs, true, false); // Enable and require variant fields

                        // Set default active tab and show content
                        generalDetailsTabButton.classList.add('active');
                        tabGeneralDetails.classList.remove('hidden');

                    } else { // simple
                        // Display relevant tabs and set their active state
                        generalDetailsTabButton.style.display = '';
                        pricingInventoryTabButton.style.display = '';
                        shippingTabButton.style.display = '';
                        attributesVariantsTabButton.style.display = 'none'; // Hide for simple

                        // Set field states
                        setFieldsState(pricingInputs, true, false); // Enable and require pricing for simple
                        setFieldsState(shippingInputs, false, false); // Shipping fields are never required, enable
                        setFieldsState(attributesVariantsInputs, false, true); // Disable variant fields

                        // Set default active tab and show content
                        generalDetailsTabButton.classList.add('active');
                        tabGeneralDetails.classList.remove('hidden');
                    }
                    // Ensure main SKU (outside tabs) is always required and enabled
                    document.querySelector('input[name="sku"]').required = true;
                    document.querySelector('input[name="sku"]').disabled = false;
                    
                    // Update tab visibility using the new function
                    updateTabVisibility();
                }

                productTypeRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        // Call the existing toggle function
                        toggleProductTypeSections();
                        
                        // Also call the new product type toggle function
                        initializeProductTypeToggle();
                    });
                });

                // Store original required state for inputs inside tabs, so we can re-apply/remove dynamically
                document.querySelectorAll(
                    '#tab-general-details input, #tab-general-details select, #tab-general-details textarea, #tab-pricing-inventory input, #tab-shipping input, #tab-attributes-variants input, #tab-attributes-variants select, #tab-attributes-variants textarea'
                    ).forEach(input => {
                    if (input.required || input.hasAttribute('data-original-required')) {
                        // If it was already required in HTML, or explicitly marked as original-required
                        input.setAttribute('data-original-required', input.required ? 'true' : 'false');
                    } else {
                        // For fields that were not originally required and don't have the attribute, set it to false.
                        input.setAttribute('data-original-required', 'false');
                    }
                });

                // Initial call on load
                // This ensures the correct tab and input states are set based on the product's current type.
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

                            // It's important NOT to call toggleProductTypeSections() here directly,
                            // as it will reset the active tab. The product type change listener
                            // already handles the initial state and the field enabling/disabling.
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

                // Set initial active tab on page load based on product type
                // After initial toggleProductTypeSections() run, this will ensure the correct tab is visibly active.
                const initialProductType = document.querySelector('input[name="product_type"]:checked').value;
                if (initialProductType === 'variant') {
                    // Default to General Details, but show Attributes & Variants if it's the only option for variants
                    if (attributesVariantsTabButton.style.display !== 'none') {
                        document.querySelector('.tab-button[data-tab="attributes-variants"]').click();
                    } else {
                        document.querySelector('.tab-button[data-tab="general-details"]').click();
                    }
                } else { // simple
                    document.querySelector('.tab-button[data-tab="general-details"]').click();
                }
            });
        </script>
    @endpush
@endsection
