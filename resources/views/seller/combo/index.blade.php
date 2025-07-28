
@extends('layouts.seller_home')


@section('content')
    <div class="admin-page-header mb-5">
        <h1 class="admin-page-title text-2xl">Combo sản phẩm</h1>
        <div class="admin-breadcrumb">
            <a href="{{ route('seller.dashboard') }}" class="admin-breadcrumb-link">Home</a> / Quản lý Combo
        </div>
    </div>

    @include('layouts.notification')

    <section class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4 h-[72px]">
            <form method="GET" action="{{ route('seller.combo.index') }}" class="w-full md:w-[223px] relative">
                <input name="search" type="text"
                    class="w-full h-[42px] border border-[#F2F2F6] rounded-md py-2 pl-10 pr-4 text-xs placeholder:text-gray-400 focus:outline-none"
                    placeholder="Tìm tên combo" value="{{ request('search') }}" />
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">
                    <i class="fas fa-search text-[#55585b]"></i>
                </span>
            </form>

            <div class="flex gap-4 items-center h-full">
                <form method="GET" action="{{ route('seller.combo.index') }}">
                    <div class="flex items-center gap-2 text-xs text-gray-500 select-none">
                        <span>Trạng thái:</span>
                        <select name="status" onchange="this.form.submit()"
                            class="dropdown border border-gray-300 rounded-md px-3 py-2 text-gray-600 text-xs focus:outline-none">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động
                            </option>
                        </select>
                    </div>
                </form>

                <a href="{{ route('seller.combo.create') }}"
                    class="h-[44px] text-[15px] bg-blue-500 text-white px-4 py-2 flex items-center justify-center rounded-md hover:bg-blue-700">
                    Tạo Combo mới
                </a>
            </div>
        </div>

        <table class="w-full text-xs text-left text-gray-400 border-t border-gray-100">
            <thead class="text-gray-300 font-semibold border-b border-gray-100">
                <tr>
                    <th class="py-3">Hình ảnh</th>
                    <th class="py-3">Tên Combo</th>
                    <th class="py-3">Giá đã giảm</th>
                    <th class="py-3">Giảm giá</th>
                    <th class="py-3">Tồn kho</th>
                    <th class="py-3">Trạng thái</th>
                    <th class="py-3">Sản phẩm</th>
                    <th class="py-3 pr-6 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                @foreach ($combos as $combo)
                    <tr>
                        <td class="py-4">
                            @if ($combo->image)
                                <img src="{{ asset('storage/' . $combo->image) }}" alt="{{ $combo->combo_name }}"
                                    class="w-12 h-12 object-cover rounded-md border border-gray-200">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" alt="{{ $combo->combo_name }}"
                                    class="w-12 h-12 object-cover rounded-md border border-gray-200">
                            @endif
                        </td>
                        <td class="py-4 text-[13px] font-semibold">{{ $combo->combo_name }}</td>
                        <td class="py-4 text-[13px]">
                            @php
                                $basePrice = 0;
                                foreach ($combo->products as $cp) {
                                    // Debugging individual product data
                                    echo "<!-- CP ID: {$cp->id}, Product ID: {$cp->productID}, Variant ID: {$cp->variantID}, Quantity: {$cp->quantity} -->";
                                    echo "<!-- Product Data: " . json_encode($cp->product) . " -->";
                                    
                                    $productPrice = $cp->product->price; // Default to product original price
                                    if ($cp->variantID && $cp->variant) {
                                        $productPrice = $cp->variant->sale_price ?? $cp->variant->price; // Use variant's sale or original price
                                    } else {
                                        $productPrice = $cp->product->sale_price ?? $cp->product->price; // Use product's sale or original price for simple products
                                    }

                                    $basePrice += $productPrice * $cp->quantity;
                                }
                                $discountedPrice = $combo->total_price; // Giá đã giảm từ database
                            @endphp
                            <!-- Debug: Raw basePrice: {{ $basePrice }}, Raw discountedPrice: {{ $discountedPrice }} -->
                            <span class="line-through text-gray-400">{{ number_format((float)$basePrice) }}đ</span><br>
                            <span class="text-red-500 font-semibold">{{ number_format((float)$discountedPrice) }}đ</span>
                        </td>
                        <td class="py-4 text-[13px]">
                            @if ($combo->discount_value)
                                {{ number_format($combo->discount_value) }} {{ $combo->discount_type == 'percentage' ? '%' : 'VNĐ' }}
                            @else
                                Không giảm giá
                            @endif
                        </td>
                        <td class="py-4 text-[13px]">{{ $combo->quantity }}</td>
                        <td class="py-4">
                            <span
                                class="inline-block {{ $combo->status == 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                                {{ ucfirst($combo->status) }}
                            </span>
                        </td>
                        <td class="py-4">
                            <ul class="space-y-2">
                                @foreach ($combo->products as $cp)
                                    <li class="flex items-center gap-2">
                                        <img src="{{ $cp->product->image_url ?? asset('images/no-image.png') }}"
                                            alt="{{ $cp->product->name }}"
                                            class="w-8 h-8 object-cover rounded-md border border-gray-200">
                                        <span class="text-[13px]">{{ $cp->product->name }} (x{{ $cp->quantity }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="py-4 pr-6 text-right flex items-center gap-2 justify-end">
                            <a href="{{ route('seller.combo.edit', $combo->id) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-md focus:outline-none">
                                <i class="fas fa-pencil-alt text-xs"></i>
                            </a>
                            <form action="{{ route('seller.combo.destroy', $combo->id) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn xóa combo này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md focus:outline-none">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if ($combos->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center text-gray-400 py-4">Không có combo nào</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="mt-6 flex items-center justify-between text-[11px] text-gray-500 select-none">
            <div>
                Hiển thị {{ $combos->count() }} combo trên {{ $combos->total() }} combo
            </div>
            {{ $combos->links('pagination::bootstrap-5') }}
        </div>
    </section>
@endsection
