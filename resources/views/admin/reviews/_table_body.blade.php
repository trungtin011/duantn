@forelse($reviews as $review)
    <tr data-review-id="{{ $review->id }}">
        <td class="py-4 flex items-center gap-4">
            <img alt="{{ $review->product->name ?? 'Product' }} image" 
                 class="w-10 h-10 rounded-md object-cover"
                 height="40" 
                 src="{{ $review->product->image_url ?? 'https://via.placeholder.com/40' }}" 
                 width="40" />
            <div>
                <span class="font-semibold text-[13px]">
                    {{ $review->product->name ?? 'N/A' }}
                </span>
                <div class="text-[11px] text-gray-500 mt-1">
                    SKU: {{ $review->product->sku ?? 'N/A' }}
                </div>
            </div>
        </td>
        <td class="py-4 text-[13px]">
            <div class="font-semibold">{{ $review->user->username }}</div>
            <div class="text-[11px] text-gray-500">ID: {{ $review->user->id }}</div>
        </td>
        <td class="py-4 text-[13px]">
            <div class="flex items-center gap-1 text-yellow-400 mb-2">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }} text-xs"></i>
                @endfor
                <span class="ml-1 text-gray-600">({{ $review->rating }}/5)</span>
            </div>
            @if($review->comment)
                <div class="text-[11px] text-gray-600 bg-gray-50 p-2 rounded max-w-xs">
                    {{ Str::limit($review->comment, 100) }}
                </div>
            @endif
        </td>
        <td class="py-4 text-[13px]">
            {{ $review->product->shop->shop_name ?? 'N/A' }}
        </td>
        <td class="py-4 text-[13px]">
            {{ $review->created_at->format('d/m/Y') }}
            <div class="text-[11px] text-gray-500">
                {{ $review->created_at->format('H:i') }}
            </div>
        </td>
        <td class="py-4 text-[13px]">
            @if ($review->seller_reply)
                <div class="bg-blue-50 border border-blue-200 rounded p-2 max-w-xs">
                    <div class="text-[10px] font-semibold text-blue-600 mb-1">Phản hồi từ seller:</div>
                    <div class="text-[11px] text-gray-700">{{ Str::limit($review->seller_reply, 80) }}</div>
                </div>
            @else
                <span class="inline-block bg-gray-100 text-gray-600 text-[10px] font-semibold px-2 py-0.5 rounded-md">
                    Chưa phản hồi
                </span>
            @endif
        </td>
        <td class="py-4 pr-6 text-right">
            <div class="flex items-center gap-2 justify-end flex-wrap">
                <!-- Ban Customer -->
                <form method="POST" action="{{ route('admin.reviews.banCustomer', $review->user->id) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('Xác nhận ban khách hàng này?')"
                            class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded-md text-[10px] focus:outline-none"
                            title="Ban khách hàng">
                        <i class="fas fa-user-slash"></i>
                    </button>
                </form>

                <!-- Warn/Ban Seller -->
                @if ($review->product->shop)
                    <form method="POST" action="{{ route('admin.reviews.warnSeller', $review->product->shop->id) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Cảnh cáo hoặc ban seller?')"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded-md text-[10px] focus:outline-none"
                                title="Cảnh cáo seller">
                            <i class="fas fa-exclamation-triangle"></i>
                        </button>
                    </form>
                @endif

                <!-- Ban Seller Manual -->
                @if ($review->product->shop && $review->product->shop->owner)
                    <form method="POST" action="{{ route('admin.reviews.banSeller', $review->product->shop->owner->id) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Xác nhận ban seller này?')"
                                class="bg-orange-500 hover:bg-orange-600 text-white px-2 py-1 rounded-md text-[10px] focus:outline-none"
                                title="Ban seller">
                            <i class="fas fa-store-slash"></i>
                        </button>
                    </form>
                @endif

                <!-- Delete Review -->
                <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Bạn có chắc muốn xoá đánh giá này?')"
                            class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded-md text-[10px] focus:outline-none"
                            title="Xóa đánh giá">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-gray-400 py-8">
            @if (request('search') || request('rating') || request('shop_id') || request('filter_date'))
                Không tìm thấy đánh giá nào phù hợp với bộ lọc hiện tại
            @else
                Không tìm thấy đánh giá nào
            @endif
        </td>
    </tr>
@endforelse
