@foreach ($products as $product)
    <tr data-product-id="{{ $product->id }}">
        <td class="py-4 pr-6">
            <input class="select-item w-[18px] h-[18px]" type="checkbox" />
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
                    : ($product->status == 'pending'
                        ? 'bg-yellow-100 text-yellow-600'
                        : ($product->status == 'inactive'
                            ? 'bg-red-100 text-red-600'
                            : ($product->status == 'scheduled'
                                ? 'bg-blue-100 text-blue-600'
                                : 'bg-gray-200 text-gray-500'))) }} 
                text-[10px] font-semibold px-2 py-0.5 rounded-md select-none">
                {{ $product->status == 'active'
                    ? 'Hoạt động'
                    : ($product->status == 'pending'
                        ? 'Chờ duyệt'
                        : ($product->status == 'inactive'
                            ? 'Không hoạt động'
                            : ($product->status == 'scheduled'
                                ? 'Lên lịch'
                                : 'Không xác định'))) }}
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
        <td colspan="7" class="text-center text-gray-400 py-4">
            @if (request('search') || request('status'))
                Không tìm thấy sản phẩm nào phù hợp với bộ lọc hiện tại
            @else
                Không có sản phẩm nào.
            @endif
        </td>
    </tr>
@endif
