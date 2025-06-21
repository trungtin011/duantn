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

        <!-- Main Content -->
        <form id="product-form" action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Thêm trường ẩn is_variant -->
            <input type="hidden" name="is_variant" id="is_variant" value="0">

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

                            <div class="max-w-full mx-auto">
                                <div class="border border-gray-300 rounded-md shadow-sm">
                                    <!-- Header -->
                                    <div class="flex items-center border-b border-gray-300 px-4 py-2 select-none bg-white">
                                        <span class="font-semibold text-sm text-gray-800 mr-2">Dữ liệu sản phẩm</span>
                                        <div class="relative inline-block text-left">
                                            <select id="product_type" aria-label="Product data type"
                                                class="border border-gray-300 rounded-md text-sm text-gray-700 pl-3 pr-8 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="simple" selected>Sản phẩm Đơn</option>
                                                <option value="variable">Sản phẩm Biến thể</option>
                                            </select>
                                        </div>
                                        <div class="flex-grow border-l border-gray-300 ml-4"></div>
                                        <button aria-label="Toggle panel"
                                            class="text-gray-400 hover:text-gray-600 focus:outline-none" type="button">
                                            <i class="fas fa-chevron-up"></i>
                                        </button>
                                    </div>

                                    <div class="flex flex-col md:flex-row bg-white">
                                        <!-- Sidebar -->
                                        <div id="sidebar-container">
                                            <div id="sidebar-simple">
                                                @include('partials.sidebar-simple')
                                            </div>
                                            <div id="sidebar-variable" style="display: none;">
                                                @include('partials.sidebar-variable')
                                            </div>
                                        </div>

                                        <!-- Main content -->
                                        <section class="flex-grow p-6 text-sm text-gray-700">
                                            <div class="space-y-6">
                                                <!-- Main content for Simple Product -->
                                                <div id="main-content-simple" style="display: block;">
                                                    <div id="overview-section" class="space-y-4">
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="regular_price"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Giá
                                                                gốc (VNĐ)</label>
                                                            <input id="regular_price" type="number" name="price"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                value="{{ old('price') }}" />
                                                            <button type="button" aria-label="Regular price help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="sale_price"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Giá
                                                                sale (VNĐ)</label>
                                                            <input id="sale_price" type="number" name="sale_price"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                value="{{ old('sale_price') }}" />
                                                            <button type="button" aria-label="Sale price help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                            <a href="#"
                                                                class="col-span-2 text-blue-600 underline">Schedule</a>
                                                        </div>
                                                        <!-- SKU cho sản phẩm đơn -->
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="sku"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">SKU</label>
                                                            <input id="sku" type="text" name="sku"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                value="{{ old('sku') }}" required />
                                                            <button type="button" aria-label="SKU help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- Stock section -->
                                                    <div id="stock-section" class="space-y-4">
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="stock_quantity"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Số
                                                                lượng tồn kho</label>
                                                            <input id="stock_quantity" type="number" name="stock_total"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                value="{{ old('stock_total') }}" required />
                                                            <button type="button" aria-label="Stock quantity help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- Shipping section -->
                                                    <div id="shipping-section" class="space-y-4">
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="weight"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Weight
                                                                (kg)</label>
                                                            <input id="weight" type="number" name="weight"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                step="0.01" value="{{ old('weight') }}" />
                                                            <button type="button" aria-label="Weight help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Dimensions
                                                                (cm)</label>
                                                            <div class="col-span-9 flex gap-2">
                                                                <input id="length" type="number" name="length"
                                                                    class="w-1/3 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                    placeholder="Length" step="0.01"
                                                                    value="{{ old('length') }}" />
                                                                <input id="width" type="number" name="width"
                                                                    class="w-1/3 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                    placeholder="Width" step="0.01"
                                                                    value="{{ old('width') }}" />
                                                                <input id="height" type="number" name="height"
                                                                    class="w-1/3 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                    placeholder="Height" step="0.01"
                                                                    value="{{ old('height') }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Main content for Variable Product -->
                                                <div id="main-content-variable" style="display: none;">
                                                    <!-- Stock section -->
                                                    <div id="stock-section" class="space-y-4">
                                                        <!-- SKU cho sản phẩm biến thể (ẩn, tự động tạo nếu cần) -->
                                                        <input type="hidden" name="sku"
                                                            value="{{ Str::slug(old('name', 'product')) . '-' . time() }}">
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="stock_quantity"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Số
                                                                lượng tồn kho</label>
                                                            <input id="stock_quantity" type="number" name="stock_total"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                value="{{ old('stock_total') }}" required />
                                                            <button type="button" aria-label="Stock quantity help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- Shipping section -->
                                                    <div id="shipping-section" class="space-y-4">
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="weight"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Weight
                                                                (kg)</label>
                                                            <input id="weight" type="number" name="weight"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                step="0.01" value="{{ old('weight') }}" />
                                                            <button type="button" aria-label="Weight help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Dimensions
                                                                (cm)</label>
                                                            <div class="col-span-9 flex gap-2">
                                                                <input id="length" type="number" name="length"
                                                                    class="w-1/3 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                    placeholder="Length" step="0.01"
                                                                    value="{{ old('length') }}" />
                                                                <input id="width" type="number" name="width"
                                                                    class="w-1/3 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                    placeholder="Width" step="0.01"
                                                                    value="{{ old('width') }}" />
                                                                <input id="height" type="number" name="height"
                                                                    class="w-1/3 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                    placeholder="Height" step="0.01"
                                                                    value="{{ old('height') }}" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Attributes section -->
                                                    <div id="attributes-section" class="space-y-4">
                                                        <div class="flex items-center gap-2 mb-5">
                                                            <label for="product_attributes"
                                                                class="text-xs font-semibold text-gray-700">Thuộc tính sản
                                                                phẩm</label>
                                                            <select id="product_attributes" name="attributes[][name]"
                                                                class="border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                                                <option value="">Chọn thuộc tính</option>
                                                                @foreach ($attributes as $attribute)
                                                                    <option value="{{ $attribute->name }}">
                                                                        {{ $attribute->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <input id="select_attribute_value" name="attributes[][values]"
                                                                type="text"
                                                                class="border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 ml-2"
                                                                placeholder="Nhập giá trị (cách nhau bằng dấu phẩy)" />
                                                            <button type="button"
                                                                class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700"
                                                                onclick="addAttributeFromSelect()">Thêm từ danh
                                                                sách</button>
                                                        </div>
                                                        <div class="w-full bg-gray-200 p-2">
                                                            <span class="text-xs italic">Thêm thuộc tính thủ công</span>
                                                        </div>
                                                        <div class="grid grid-cols-12 gap-4 items-center mt-3">
                                                            <label for="custom_attribute_name"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Tên
                                                                thuộc tính</label>
                                                            <input id="custom_attribute_name" type="text"
                                                                name="attributes[][name]"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Nhập tên thuộc tính" />
                                                            <button type="button" aria-label="Attribute name help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="custom_attribute_value"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Giá
                                                                trị thuộc tính</label>
                                                            <input id="custom_attribute_value" type="text"
                                                                name="attributes[][values]"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Nhập giá trị (cách nhau bằng dấu phẩy)" />
                                                            <button type="button" aria-label="Attribute value help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <button type="button"
                                                                class="bg-green-600 text-white px-3 py-1 rounded-md hover:bg-green-700"
                                                                onclick="addCustomAttribute()">Thêm thuộc tính nhập
                                                                tay</button>
                                                        </div>
                                                        <div id="attribute-inputs" class="space-y-4">
                                                            <!-- Các thuộc tính sẽ được thêm động ở đây -->
                                                        </div>
                                                    </div>

                                                    <!-- Variants section -->
                                                    <div id="variants-section" class="space-y-4">
                                                        <div class="w-full bg-gray-200 p-2">
                                                            <span class="text-xs italic">Tạo biến thể từ thuộc tính</span>
                                                        </div>
                                                        <div id="variant-attributes" class="space-y-4">
                                                            <!-- Dropdown thuộc tính sẽ được thêm động ở đây -->
                                                        </div>
                                                        <div class="grid grid-cols-12 gap-4 items-center mt-3">
                                                            <label for="variant_sku"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">SKU
                                                                biến thể</label>
                                                            <input id="variant_sku" type="text" name="variants[][sku]"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Nhập SKU biến thể" />
                                                            <button type="button" aria-label="Variant SKU help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="variant_regular_price"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Giá
                                                                gốc (VNĐ)</label>
                                                            <input id="variant_regular_price" type="number"
                                                                name="variants[][price]" min="0" step="1000"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Nhập giá gốc" />
                                                            <button type="button" aria-label="Variant regular price help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="variant_sale_price"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Giá
                                                                sale (VNĐ)</label>
                                                            <input id="variant_sale_price" type="number"
                                                                name="variants[][sale_price]" min="0"
                                                                step="1000"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Nhập giá sale" />
                                                            <button type="button" aria-label="Variant sale price help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="variant_purchase_price"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Giá
                                                                nhập (VNĐ)</label>
                                                            <input id="variant_purchase_price" type="number"
                                                                name="variants[][purchase_price]" min="0"
                                                                step="1000"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Nhập giá nhập" />
                                                            <button type="button"
                                                                aria-label="Variant purchase price help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="variant_stock"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Số
                                                                lượng tồn kho</label>
                                                            <input id="variant_stock" type="number"
                                                                name="variants[][stock]" min="0"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Nhập số lượng" />
                                                            <button type="button" aria-label="Variant stock help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                        <div class="grid grid-cols-12 gap-4 items-center mt-3">
                                                            <label for="variant_images"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Hình
                                                                ảnh</label>
                                                            <input id="variant_images" type="file"
                                                                name="variants[][images][]"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                multiple />
                                                            <button type="button" aria-label="Variant images help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                        <div id="preview-images" class="grid grid-cols-5 gap-2 mt-2">
                                                            <!-- Hình ảnh xem trước sẽ được thêm động ở đây -->
                                                        </div>
                                                        <div class="flex items-center mt-3">
                                                            <button type="button"
                                                                class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700"
                                                                onclick="addVariant()">Thêm biến thể</button>
                                                        </div>
                                                        <div id="variant-list" class="space-y-4">
                                                            <!-- Danh sách biến thể sẽ được thêm động ở đây -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
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

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
        <script>
            // Khởi tạo attributeMap và attributesStorage
            const attributeMap = {
                @if ($attributes->isNotEmpty())
                    @foreach ($attributes as $attribute)
                        {{ $attribute->id }}: "{{ $attribute->name }}",
                    @endforeach
                @endif
                'default': 'Không xác định'
            };

            // Lưu trữ thuộc tính đã thêm (name: [values])
            const attributesStorage = {};

            document.getElementById('product_type').addEventListener('change', function() {
                const productType = this.value;
                const sidebarSimple = document.getElementById('sidebar-simple');
                const sidebarVariable = document.getElementById('sidebar-variable');
                const mainContentSimple = document.getElementById('main-content-simple');
                const mainContentVariable = document.getElementById('main-content-variable');
                const isVariantInput = document.getElementById('is_variant');

                sidebarSimple.style.display = 'none';
                sidebarVariable.style.display = 'none';
                mainContentSimple.style.display = 'none';
                mainContentVariable.style.display = 'none';

                if (productType === 'simple') {
                    sidebarSimple.style.display = 'block';
                    mainContentSimple.style.display = 'block';
                    showSection('overview');
                    isVariantInput.value = '0';
                    // Thêm required cho các trường của Sản phẩm Đơn
                    document.getElementById('regular_price').setAttribute('required', 'required');
                    document.getElementById('sku').setAttribute('required', 'required');
                    document.getElementById('stock_quantity').setAttribute('required', 'required');
                } else if (productType === 'variable') {
                    sidebarVariable.style.display = 'block';
                    mainContentVariable.style.display = 'block';
                    showSection('stock');
                    isVariantInput.value = '1';
                    // Xóa required cho các trường của Sản phẩm Đơn
                    document.getElementById('regular_price').removeAttribute('required');
                    document.getElementById('sku').removeAttribute('required');
                    document.getElementById('stock_quantity').removeAttribute('required');
                    // Đặt giá trị mặc định để tránh lỗi
                    document.getElementById('regular_price').value = '0';
                    document.getElementById('sku').value = '{{ Str::slug(old('name', 'product')) . '-' . time() }}';
                    document.getElementById('stock_quantity').value = '0';
                }
            });

            document.querySelectorAll('.active-section').forEach(button => {
                button.addEventListener('click', function() {
                    const section = this.getAttribute('data-section');
                    const mainContent = document.getElementById('product_type').value === 'simple' ?
                        'main-content-simple' : 'main-content-variable';
                    showSection(section, mainContent);
                    document.querySelectorAll(`#${mainContent} + div .active-section`).forEach(btn => btn
                        .classList.remove('bg-white', 'text-blue-600', 'font-semibold', 'border-l-4',
                            'border-blue-600'));
                    this.classList.add('bg-white', 'text-blue-600', 'font-semibold', 'border-l-4',
                        'border-blue-600');

                    if (section === 'variants') {
                        updateVariantAttributes();
                    }
                });
            });

            function showSection(section, mainContent) {
                const sections = ['overview', 'stock', 'shipping', 'attributes', 'variants'];
                sections.forEach(s => {
                    const element = document.querySelector(`#${mainContent} #${s}-section`);
                    if (element) element.style.display = section === s ? 'block' : 'none';
                });
            }

            // Thêm thuộc tính từ dropdown
            function addAttributeFromSelect() {
                const select = document.getElementById('product_attributes');
                const attributeName = select.value;
                const inputValue = document.getElementById('select_attribute_value').value.trim();
                const attributeInputs = document.getElementById('attribute-inputs');

                if (!attributeName) {
                    alert('Vui lòng chọn một thuộc tính từ danh sách!');
                    return;
                }
                if (!inputValue) {
                    alert('Vui lòng nhập giá trị thuộc tính!');
                    return;
                }

                const values = inputValue.split(',').map(val => val.trim()).filter(val => val);

                if (values.length === 0) {
                    alert('Vui lòng nhập ít nhất một giá trị!');
                    return;
                }

                if (!attributesStorage[attributeName]) {
                    attributesStorage[attributeName] = [];
                }
                values.forEach(value => {
                    if (!attributesStorage[attributeName].includes(value)) {
                        attributesStorage[attributeName].push(value);
                    }
                });

                values.forEach(value => {
                    const div = document.createElement('div');
                    div.className = 'grid grid-cols-12 gap-4 items-center bg-gray-50 p-2 rounded-md';
                    div.innerHTML = `
                    <label class="col-span-3 text-xs font-semibold text-gray-700">${attributeName}</label>
                    <input type="text" value="${value}" class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" readonly>
                    <button type="button" class="col-span-1 text-red-500 hover:text-red-700 focus:outline-none" onclick="removeAttribute(this, '${attributeName}', '${value}')">
                        <i class="fas fa-trash"></i>
                    </button>
                    <input type="hidden" name="attributes[][name]" value="${attributeName}">
                    <input type="hidden" name="attributes[][values]" value="${value}">
                `;
                    attributeInputs.appendChild(div);
                });

                select.value = '';
                document.getElementById('select_attribute_value').value = '';
            }

            // Thêm thuộc tính nhập tay
            function addCustomAttribute() {
                const attributeName = document.getElementById('custom_attribute_name').value.trim();
                const inputValue = document.getElementById('custom_attribute_value').value.trim();
                const attributeInputs = document.getElementById('attribute-inputs');

                if (!attributeName || !inputValue) {
                    alert('Vui lòng nhập cả tên và giá trị thuộc tính!');
                    return;
                }

                const values = inputValue.split(',').map(val => val.trim()).filter(val => val);

                if (values.length === 0) {
                    alert('Vui lòng nhập ít nhất một giá trị!');
                    return;
                }

                if (!attributesStorage[attributeName]) {
                    attributesStorage[attributeName] = [];
                }
                values.forEach(value => {
                    if (!attributesStorage[attributeName].includes(value)) {
                        attributesStorage[attributeName].push(value);
                    }
                });

                values.forEach(value => {
                    const div = document.createElement('div');
                    div.className = 'grid grid-cols-12 gap-4 items-center bg-gray-50 p-2 rounded-md';
                    div.innerHTML = `
                    <label class="col-span-3 text-xs font-semibold text-gray-700">${attributeName}</label>
                    <input type="text" value="${value}" class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" readonly>
                    <button type="button" class="col-span-1 text-red-500 hover:text-red-700 focus:outline-none" onclick="removeAttribute(this, '${attributeName}', '${value}')">
                        <i class="fas fa-trash"></i>
                    </button>
                    <input type="hidden" name="attributes[][name]" value="${attributeName}">
                    <input type="hidden" name="attributes[][values]" value="${value}">
                `;
                    attributeInputs.appendChild(div);
                });

                document.getElementById('custom_attribute_name').value = '';
                document.getElementById('custom_attribute_value').value = '';
            }

            // Xóa thuộc tính và cập nhật attributesStorage
            function removeAttribute(button, attributeName, attributeValue) {
                if (attributesStorage[attributeName]) {
                    attributesStorage[attributeName] = attributesStorage[attributeName].filter(val => val !== attributeValue);
                    if (attributesStorage[attributeName].length === 0) {
                        delete attributesStorage[attributeName];
                    }
                }
                button.parentElement.remove();
                if (document.getElementById('variants-section').style.display === 'block') {
                    updateVariantAttributes();
                }
            }

            // Cập nhật dropdown trong variants-section
            function updateVariantAttributes() {
                const variantAttributes = document.getElementById('variant-attributes');
                variantAttributes.innerHTML = '';

                for (const [attributeName, values] of Object.entries(attributesStorage)) {
                    if (values.length === 0) continue;

                    const div = document.createElement('div');
                    div.className = 'grid grid-cols-12 gap-4 items-center';
                    div.innerHTML = `
                    <label class="col-span-3 text-xs font-semibold text-gray-700">${attributeName}</label>
                    <select class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 variant-attribute" data-attribute="${attributeName}" name="variants[][attributes][${attributeName}]">
                        <option value="">Chọn ${attributeName}</option>
                        ${values.map(value => `<option value="${value}">${value}</option>`).join('')}
                    </select>
                `;
                    variantAttributes.appendChild(div);
                }
            }

            // Xử lý khi chọn file ảnh
            document.getElementById('variant_images').addEventListener('change', function(e) {
                const previewImages = document.getElementById('preview-images');
                previewImages.innerHTML = '';

                const files = Array.from(e.target.files);
                files.forEach(file => {
                    const imgUrl = URL.createObjectURL(file);
                    const img = document.createElement('img');
                    img.src = imgUrl;
                    img.className = 'w-full h-16 object-cover rounded-md';
                    previewImages.appendChild(img);
                });
            });

            function addVariant() {
                const variantSku = document.getElementById('variant_sku').value.trim();
                const variantRegularPrice = document.getElementById('variant_regular_price').value.trim();
                const variantSalePrice = document.getElementById('variant_sale_price').value.trim();
                const variantPurchasePrice = document.getElementById('variant_purchase_price').value.trim();
                const variantStock = document.getElementById('variant_stock').value.trim();
                const variantImagesInput = document.getElementById('variant_images');
                const variantList = document.getElementById('variant-list');

                // Thu thập thuộc tính từ dropdown
                const variantAttributes = {};
                const dropdowns = document.querySelectorAll('.variant-attribute');
                dropdowns.forEach(dropdown => {
                    const attributeName = dropdown.getAttribute('data-attribute');
                    const value = dropdown.value;
                    if (value) {
                        variantAttributes[attributeName] = value;
                    }
                });

                if (Object.keys(variantAttributes).length === 0) {
                    alert('Vui lòng chọn ít nhất một giá trị thuộc tính!');
                    return;
                }

                const imageFiles = Array.from(variantImagesInput.files);
                const imageNames = imageFiles.length > 0 ? imageFiles.map(file => file.name) : [];

                if (variantSalePrice && parseFloat(variantSalePrice) > parseFloat(variantRegularPrice)) {
                    alert('Giá sale không được lớn hơn giá gốc!');
                    return;
                }

                // Tạo object variant hoàn chỉnh
                const variantData = {
                    sku: variantSku || '',
                    price: variantRegularPrice || '0',
                    purchase_price: variantPurchasePrice || '0',
                    sale_price: variantSalePrice || '',
                    stock: variantStock || '0',
                    attributes: variantAttributes,
                    images: imageNames
                };

                // Thêm input ẩn để gửi dữ liệu
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `variants[${variantList.children.length}]`;
                input.value = JSON.stringify(variantData);
                document.getElementById('product-form').appendChild(input);

                // Cập nhật giao diện
                const div = document.createElement('div');
                div.className = 'grid grid-cols-12 gap-4 items-center bg-gray-50 p-2 rounded-md';
                const attributeText = Object.entries(variantAttributes).map(([name, value]) => `${name}: ${value}`).join(', ');
                div.innerHTML = `
        <label class="col-span-2 text-xs font-semibold text-gray-700">${variantSku || 'Chưa nhập SKU'}</label>
        <span class="col-span-3 text-xs">${attributeText}</span>
        <span class="col-span-2 text-xs">${variantRegularPrice ? variantRegularPrice + ' VNĐ' : 'Chưa nhập giá'}</span>
        <span class="col-span-2 text-xs">${variantSalePrice ? variantSalePrice + ' VNĐ' : 'Không có sale'}</span>
        <span class="col-span-2 text-xs">${variantPurchasePrice ? variantPurchasePrice + ' VNĐ' : 'Chưa nhập giá nhập'}</span>
        <div class="col-span-1 flex gap-1">
            ${imageFiles.map(file => `<img src="${URL.createObjectURL(file)}" class="w-12 h-12 object-cover rounded-md">`).join('')}
        </div>
        <button type="button" class="col-span-1 text-red-500 hover:text-red-700 focus:outline-none" onclick="this.parentElement.remove(); document.getElementById('product-form').removeChild(this.parentElement.querySelector('input[type=hidden]'))">
            <i class="fas fa-trash"></i>
        </button>
    `;
                variantList.appendChild(div);

                // Reset form
                document.getElementById('variant_sku').value = '';
                document.getElementById('variant_regular_price').value = '';
                document.getElementById('variant_sale_price').value = '';
                document.getElementById('variant_purchase_price').value = '';
                document.getElementById('variant_stock').value = '';
                document.getElementById('variant_images').value = '';
                document.getElementById('preview-images').innerHTML = '';
                dropdowns.forEach(dropdown => dropdown.value = '');
            }

            // Khởi tạo mặc định
            document.getElementById('main-content-simple').style.display = 'block';
            document.getElementById('sidebar-simple').style.display = 'block';
            showSection('overview', 'main-content-simple');
            document.querySelector('#sidebar-simple .active-section[data-section="overview"]').classList.add('bg-white',
                'text-blue-600');
        </script>
    @endpush
@endsection
