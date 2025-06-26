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

                            {{-- <!-- Product Data Section -->
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
                            </div> --}}

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
                                            <form class="space-y-6">
                                                <!-- Main content for Simple Product -->
                                                <div id="main-content-simple" style="display: block;">
                                                    <!-- Overview section -->
                                                    <div id="overview-section" class="space-y-4">
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="regular_price"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Regular
                                                                price (VNĐ)</label>
                                                            <input id="regular_price" type="text"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" />
                                                            <button type="button" aria-label="Regular price help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="sale_price"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Sale
                                                                price (VNĐ)</label>
                                                            <input id="sale_price" type="text"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" />
                                                            <button type="button" aria-label="Sale price help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                            <a href="#"
                                                                class="col-span-2 text-blue-600 underline">Schedule</a>
                                                        </div>
                                                        <!-- Bổ sung SKU -->
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="sku"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">SKU</label>
                                                            <input id="sku" type="text"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" />
                                                            <button type="button" aria-label="SKU help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- Stock section -->
                                                    <div id="stock-section" class="space-y-4" style="display: none;">
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="stock_quantity"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Số
                                                                lượng tồn kho</label>
                                                            <input id="stock_quantity" type="number"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" />
                                                            <button type="button" aria-label="Stock quantity help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- Shipping section -->
                                                    <div id="shipping-section" class="space-y-4" style="display: none;">
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="weight"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Weight
                                                                (kg)</label>
                                                            <input id="weight" type="number"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                step="0.01" />
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
                                                                <input id="length" type="number"
                                                                    class="w-1/3 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                    placeholder="Length" step="0.01" />
                                                                <input id="width" type="number"
                                                                    class="w-1/3 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                    placeholder="Width" step="0.01" />
                                                                <input id="height" type="number"
                                                                    class="w-1/3 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                    placeholder="Height" step="0.01" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Main content for Variable Product -->
                                                <div id="main-content-variable" style="display: none;">
                                                    <!-- Stock section -->
                                                    <div id="stock-section" class="space-y-4">
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="stock_quantity"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Số
                                                                lượng tồn kho</label>
                                                            <input id="stock_quantity" type="number"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" />
                                                            <button type="button" aria-label="Stock quantity help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- Shipping section -->
                                                    <div id="shipping-section" class="space-y-4" style="display: none;">
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="weight"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Weight
                                                                (kg)</label>
                                                            <input id="weight" type="number"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                step="0.01" />
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
                                                                <input id="length" type="number"
                                                                    class="w-1/3 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                    placeholder="Length" step="0.01" />
                                                                <input id="width" type="number"
                                                                    class="w-1/3 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                    placeholder="Width" step="0.01" />
                                                                <input id="height" type="number"
                                                                    class="w-1/3 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                    placeholder="Height" step="0.01" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Attributes section -->
                                                    <div id="attributes-section" class="space-y-4"
                                                        style="display: none;">
                                                        <div class="flex items-center gap-2 mb-5">
                                                            <label for="product_attributes"
                                                                class="text-xs font-semibold text-gray-700">Thuộc tính sản
                                                                phẩm</label>
                                                            <select id="product_attributes"
                                                                class="border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                                                <option value="">Chọn thuộc tính</option>
                                                                @foreach ($attributes as $attribute)
                                                                    <option value="{{ $attribute->id }}">
                                                                        {{ $attribute->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <input id="select_attribute_value" type="text"
                                                                class="border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 ml-2"
                                                                placeholder="Nhập giá trị" />
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
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Nhập giá trị thuộc tính" />
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
                                                    <div id="variants-section" class="space-y-4" style="display: none;">
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
                                                            <input id="variant_sku" type="text"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Nhập SKU biến thể" />
                                                            <button type="button" aria-label="Variant SKU help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                        <div class="grid grid-cols-12 gap-4 items-center">
                                                            <label for="variant_price"
                                                                class="col-span-3 text-xs font-semibold text-gray-700">Giá
                                                                biến thể (VNĐ)</label>
                                                            <input id="variant_price" type="text"
                                                                class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                                                placeholder="Nhập giá biến thể" />
                                                            <button type="button" aria-label="Variant price help"
                                                                class="col-span-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                                <i class="fas fa-question-circle"></i>
                                                            </button>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <button type="button"
                                                                class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700"
                                                                onclick="addVariant()">Thêm biến thể</button>
                                                        </div>
                                                        <div id="variant-list" class="space-y-4">
                                                            <!-- Danh sách biến thể sẽ được thêm động ở đây -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
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

                sidebarSimple.style.display = 'none';
                sidebarVariable.style.display = 'none';
                mainContentSimple.style.display = 'none';
                mainContentVariable.style.display = 'none';

                if (productType === 'simple') {
                    sidebarSimple.style.display = 'block';
                    mainContentSimple.style.display = 'block';
                    showSection('overview');
                } else if (productType === 'variable') {
                    sidebarVariable.style.display = 'block';
                    mainContentVariable.style.display = 'block';
                    showSection('stock');
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

                    // Khi chuyển sang Variants section, cập nhật dropdown
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
                const attributeId = select.value;
                const attributeValue = document.getElementById('select_attribute_value').value.trim();
                const attributeInputs = document.getElementById('attribute-inputs');

                if (attributeId === '') {
                    alert('Vui lòng chọn một thuộc tính từ danh sách!');
                    return;
                }
                if (!attributeValue) {
                    alert('Vui lòng nhập giá trị thuộc tính!');
                    return;
                }

                const attributeName = attributeMap[attributeId] || 'Không xác định';

                // Lưu vào attributesStorage
                if (!attributesStorage[attributeName]) {
                    attributesStorage[attributeName] = [];
                }
                if (!attributesStorage[attributeName].includes(attributeValue)) {
                    attributesStorage[attributeName].push(attributeValue);
                }

                const div = document.createElement('div');
                div.className = 'grid grid-cols-12 gap-4 items-center bg-gray-50 p-2 rounded-md';
                div.innerHTML = `
            <label class="col-span-3 text-xs font-semibold text-gray-700">${attributeName}</label>
            <input type="text" value="${attributeValue}" class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" readonly>
            <button type="button" class="col-span-1 text-red-500 hover:text-red-700 focus:outline-none" onclick="removeAttribute(this, '${attributeName}', '${attributeValue}')">
                <i class="fas fa-trash"></i>
            </button>
        `;
                attributeInputs.appendChild(div);

                // Reset input
                select.value = '';
                document.getElementById('select_attribute_value').value = '';
            }

            // Thêm thuộc tính nhập tay
            function addCustomAttribute() {
                const attributeName = document.getElementById('custom_attribute_name').value.trim();
                const attributeValue = document.getElementById('custom_attribute_value').value.trim();
                const attributeInputs = document.getElementById('attribute-inputs');

                if (!attributeName || !attributeValue) {
                    alert('Vui lòng nhập cả tên và giá trị thuộc tính!');
                    return;
                }

                // Lưu vào attributesStorage
                if (!attributesStorage[attributeName]) {
                    attributesStorage[attributeName] = [];
                }
                if (!attributesStorage[attributeName].includes(attributeValue)) {
                    attributesStorage[attributeName].push(attributeValue);
                }

                const div = document.createElement('div');
                div.className = 'grid grid-cols-12 gap-4 items-center bg-gray-50 p-2 rounded-md';
                div.innerHTML = `
            <label class="col-span-3 text-xs font-semibold text-gray-700">${attributeName}</label>
            <input type="text" value="${attributeValue}" class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" readonly>
            <button type="button" class="col-span-1 text-red-500 hover:text-red-700 focus:outline-none" onclick="removeAttribute(this, '${attributeName}', '${attributeValue}')">
                <i class="fas fa-trash"></i>
            </button>
        `;
                attributeInputs.appendChild(div);

                // Reset input
                document.getElementById('custom_attribute_name').value = '';
                document.getElementById('custom_attribute_value').value = '';
            }

            // Xóa thuộc tính và cập nhật attributesStorage
            function removeAttribute(button, attributeName, attributeValue) {
                // Xóa khỏi attributesStorage
                if (attributesStorage[attributeName]) {
                    attributesStorage[attributeName] = attributesStorage[attributeName].filter(val => val !== attributeValue);
                    if (attributesStorage[attributeName].length === 0) {
                        delete attributesStorage[attributeName];
                    }
                }
                // Xóa phần tử khỏi giao diện
                button.parentElement.remove();
                // Cập nhật dropdown trong variants-section nếu đang mở
                if (document.getElementById('variants-section').style.display === 'block') {
                    updateVariantAttributes();
                }
            }

            // Cập nhật dropdown trong variants-section
            function updateVariantAttributes() {
                const variantAttributes = document.getElementById('variant-attributes');
                variantAttributes.innerHTML = ''; // Xóa nội dung cũ

                for (const [attributeName, values] of Object.entries(attributesStorage)) {
                    if (values.length === 0) continue;

                    const div = document.createElement('div');
                    div.className = 'grid grid-cols-12 gap-4 items-center';
                    div.innerHTML = `
                <label class="col-span-3 text-xs font-semibold text-gray-700">${attributeName}</label>
                <select class="col-span-6 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 variant-attribute" data-attribute="${attributeName}">
                    <option value="">Chọn ${attributeName}</option>
                    ${values.map(value => `<option value="${value}">${value}</option>`).join('')}
                </select>
            `;
                    variantAttributes.appendChild(div);
                }
            }

            // Thêm biến thể
            function addVariant() {
                const variantSku = document.getElementById('variant_sku').value.trim();
                const variantPrice = document.getElementById('variant_price').value.trim();
                const variantList = document.getElementById('variant-list');

                if (!variantSku || !variantPrice) {
                    alert('Vui lòng nhập SKU và giá biến thể!');
                    return;
                }

                // Lấy tất cả giá trị đã chọn từ dropdown
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

                // Tạo biến thể
                const div = document.createElement('div');
                div.className = 'grid grid-cols-12 gap-4 items-center bg-gray-50 p-2 rounded-md';
                const attributeText = Object.entries(variantAttributes).map(([name, value]) => `${name}: ${value}`).join(', ');
                div.innerHTML = `
            <label class="col-span-3 text-xs font-semibold text-gray-700">${variantSku}</label>
            <input type="text" value="${attributeText}" class="col-span-3 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none" readonly>
            <input type="text" value="${variantPrice} VNĐ" class="col-span-3 border border-gray-300 rounded-md px-3 py-1 text-xs focus:outline-none" readonly>
            <button type="button" class="col-span-1 text-red-500 hover:text-red-700 focus:outline-none" onclick="this.parentElement.remove()">
                <i class="fas fa-trash"></i>
            </button>
        `;
                variantList.appendChild(div);

                // Reset input
                document.getElementById('variant_sku').value = '';
                document.getElementById('variant_price').value = '';
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
