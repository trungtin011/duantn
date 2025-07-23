@extends('layouts.seller_home')

@section('head')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @endpush
@endsection

@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Sản phẩm</h1>
        <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Home</a> / Danh sách sản phẩm</div>
    </div>

    @include('layouts.notification')

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form class="w-full md:w-[223px] relative" method="GET" action="{{ route('seller.products.index') }}">
                <input name="search"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm kiếm sản phẩm" type="text" value="{{ request('search') }}" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </form>

            <div class="flex gap-4 items-center h-full">
                <form method="GET" action="{{ route('seller.products.index') }}">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Trạng thái:</span>
                        <select name="status" id="statusFilter" onchange="this.form.submit()"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động
                            </option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Số lượng thấp
                            </option>
                            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Hết
                                hàng</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Lên lịch
                            </option>
                        </select>
                    </div>
                </form>
                <a href="{{ route('seller.products.create') }}"
                    class="h-[44px] text-[15px] bg-blue-500 text-white px-4 py-2 flex items-center justify-center rounded-md hover:bg-blue-700 focus:outline-none">
                    Thêm sản phẩm
                </a>
            </div>
        </div>

        <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                <tr>
                    <th class="w-6 py-3 pr-6">
                        <input id="select-all" class="w-[18px] h-[18px]" aria-label="Select all products" type="checkbox" />
                    </th>
                    <th class="py-3">Sản phẩm</th>
                    <th class="py-3">Mã sản phẩm</th>
                    <th class="py-3">Số lượng</th>
                    <th class="py-3">Giá</th>
                    <th class="py-3">Trạng thái</th>
                    <th class="py-3 pr-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                @foreach ($products as $product)
                    <tr>
                        <td class="py-4 pr-6">
                            <input class="select-item w-[18px] h-[18px]" aria-label="Select {{ $product->name }}"
                                type="checkbox" />
                        </td>
                        <td class="py-4 flex items-center gap-4">
                            <img alt="{{ $product->name }} product image" class="w-10 h-10 rounded-md object-cover"
                                height="40" src="{{ $product->image_url }}" width="40" />
                            <span class="font-semibold text-[13px]">
                                {{ $product->name }}
                            </span>
                        </td>
                        <td class="py-4 text-[13px]">
                            {{ $product->sku }}
                        </td>
                        <td class="py-4 text-[13px]">
                            @php
                                $displayStock = $product->stock_total ?? 0;
                                if ($product->is_variant && (!$displayStock || $displayStock == 0)) {
                                    $variantStock = $product->variants->sum('stock') ?? 0;
                                    $displayStock = $variantStock > 0 ? $variantStock : 0;
                                }
                            @endphp
                            {{ $displayStock }}
                            @if ($displayStock <= 5 && $displayStock > 0)
                                <span
                                    class="inline-block bg-orange-100 text-orange-600 text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                    Sản phẩm sắp hết hàng
                                </span>
                            @elseif ($displayStock == 0)
                                <span
                                    class="inline-block bg-red-100 text-red-600 text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                    Sản phẩm hết hàng
                                </span>
                            @endif
                        </td>
                        <td class="py-4 text-[13px]">
                            @php
                                $displayPrice = $product->sale_price ?? 0;
                                if ($product->is_variant && (!$displayPrice || $displayPrice == 0)) {
                                    $variantPrice = $product->variants->min('sale_price') ?? 0;
                                    $displayPrice = $variantPrice > 0 ? $variantPrice : 0;
                                }
                            @endphp
                            {{ number_format($displayPrice) }}
                        </td>
                        <td class="py-4">
                            <span
                                class="inline-block 
                                {{ $product->status == 'active'
                                    ? 'bg-green-100 text-green-600'
                                    : ($product->status == 'inactive'
                                        ? 'bg-red-100 text-red-600'
                                        : ($product->status == 'scheduled'
                                            ? 'bg-blue-100 text-blue-600'
                                            : 'bg-gray-200 text-gray-500')) }} 
                                text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                {{ $product->status == 'active'
                                    ? 'Hoạt động'
                                    : ($product->status == 'inactive'
                                        ? 'Không hoạt động'
                                        : ($product->status == 'scheduled'
                                            ? 'Lên lịch'
                                            : 'Không xác định')) }}
                            </span>
                        </td>
                        <td class="py-4 pr-6 text-right flex items-center gap-2 justify-end">
                            <a href="{{ route('seller.products.edit', $product->id) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-md focus:outline-none">
                                <i class="fas fa-pencil-alt text-xs"></i>
                            </a>
                            <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" aria-label="Delete {{ $product->name }}"
                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md focus:outline-none">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if ($products->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center text-gray-400 py-4">No products found</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
            <div>
                Hiển thị {{ $products->count() }} sản phẩm trên {{ $products->total() }} sản phẩm
            </div>
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </section>
@endsection
