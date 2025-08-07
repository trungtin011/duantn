@foreach ($products as $product)
    <tr data-product-id="{{ $product->id }}">
        <td class="py-4 pr-6">
            <input class="select-item w-[18px] h-[18px]" aria-label="Chọn {{ $product->name }}" type="checkbox" />
        </td>
        <td class="py-4 flex items-center gap-4">
            <img alt="{{ $product->name }} product image" class="w-10 h-10 rounded-md object-cover" height="40"
                src="{{ $product->image_url }}" width="40" />
            <span class="font-semibold text-[13px]">{{ $product->name }}</span>
        </td>
        <td class="py-4 text-[13px]">{{ $product->shop->shop_name ?? 'N/A' }}</td>
        <td class="py-4 text-[13px]">{{ $product->sku }}</td>
        <td class="py-4 text-[13px]">
            @if ($product->is_variant)
                @php $variantStock = $product->variants->sum('stock'); @endphp
                {{ $variantStock }}
                @if ($variantStock <= 5 && $variantStock > 0)
                    <span
                        class="inline-block bg-orange-100 text-orange-600 text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">Số
                        lượng thấp</span>
                @elseif ($variantStock == 0)
                    <span
                        class="inline-block bg-red-100 text-red-600 text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">Hết
                        hàng</span>
                @endif
            @else
                {{ $product->stock_total }}
                @if ($product->stock_total <= 5 && $product->stock_total > 0)
                    <span
                        class="inline-block bg-orange-100 text-orange-600 text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">Số
                        lượng thấp</span>
                @elseif ($product->stock_total == 0)
                    <span
                        class="inline-block bg-red-100 text-red-600 text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">Hết
                        hàng</span>
                @endif
            @endif
        </td>
        <td class="py-4 text-[13px]">
            @if ($product->is_variant)
                {{-- Nếu là sản phẩm biến thể, hiển thị giá thấp nhất --}}
                @php
                    $minPrice = $product->variants->min('sale_price');
                    $maxPrice = $product->variants->max('sale_price');
                @endphp
                @if ($minPrice == $maxPrice)
                    {{ number_format($minPrice) }} VNĐ
                @else
                    Từ {{ number_format($minPrice) }} VNĐ đến {{ number_format($maxPrice) }} VNĐ
                @endif
            @else
                {{ number_format($product->sale_price) }} VNĐ
            @endif
        </td>
        <td class="py-4">
            <span
                class="inline-block 
                {{ $product->status == 'active'
                    ? 'bg-green-100 text-green-600'
                    : ($product->status == 'pending'
                        ? 'bg-yellow-100 text-yellow-600'
                        : ($product->status == 'inactive'
                            ? 'bg-red-100 text-red-600'
                            : ($product->status == 'scheduled'
                                ? 'bg-blue-100 text-blue-600'
                                : 'bg-gray-100 text-gray-600'))) }} 
                text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                @switch($product->status)
                    @case('pending')
                        Chờ duyệt
                    @break

                    @case('active')
                        Hoạt động
                    @break

                    @case('inactive')
                        Không hoạt động
                    @break

                    @case('scheduled')
                        Lên lịch
                    @break

                    @case('low_stock')
                        Số lượng thấp
                    @break

                    @case('out_of_stock')
                        Hết hàng
                    @break

                    @case('draft')
                        Bản nháp
                    @break

                    @default
                        {{ ucfirst($product->status) }}
                @endswitch
            </span>
        </td>
        <td class="py-4 pr-6 text-right flex items-center gap-2 justify-end">
            @if ($product->status == 'pending')
                <form action="{{ route('admin.products.approve', $product->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" aria-label="Duyệt {{ $product->name }}"
                        class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-md focus:outline-none">
                        <i class="fas fa-check text-xs"></i>
                    </button>
                </form>
                <form action="{{ route('admin.products.reject', $product->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" aria-label="Từ chối {{ $product->name }}"
                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md focus:outline-none">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.products.edit', $product->id) }}"
                class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-md focus:outline-none">
                <i class="fas fa-pencil-alt text-xs"></i>
            </a>
            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                @csrf
                @method('DELETE')
                <button type="submit" aria-label="Xóa {{ $product->name }}"
                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md focus:outline-none">
                    <i class="fas fa-trash-alt text-xs"></i>
                </button>
            </form>
        </td>
    </tr>
@endforeach
@if ($products->isEmpty())
    <tr>
        <td colspan="8" class="text-center text-gray-400 py-4">
            @if (request('search') || request('status') || request('shop_id'))
                Không tìm thấy sản phẩm nào phù hợp với bộ lọc hiện tại
            @else
                Không tìm thấy sản phẩm nào
            @endif
        </td>
    </tr>
@endif
