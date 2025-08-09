@if($saved->isEmpty())
    <div class="text-center text-gray-500 py-10">
        Chưa có mã giảm giá nào.
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($saved as $item)
            @php($coupon = $item->coupon)
            <div class="border rounded-lg p-4 flex gap-4 items-center">
                <div class="w-16 h-16 flex-shrink-0 rounded bg-gray-100 flex items-center justify-center overflow-hidden">
                    @if($coupon->image)
                        <img src="{{ Storage::url($coupon->image) }}" alt="{{ $coupon->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-xs text-gray-500">VOUCHER</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h3 class="font-semibold text-gray-900 truncate w-[200px]">{{ $coupon->name ?? $coupon->code }}</h3>
                        @if($item->status === 'used')
                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">Đã dùng</span>
                        @elseif($item->status === 'expired')
                            <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-600">Hết hạn</span>
                        @else
                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-600 truncate w-[50px]">Có thể dùng</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 mt-1">
                        @if($coupon->discount_type === 'percentage')
                            Giảm {{ (float)$coupon->discount_value }}%
                        @else
                            Giảm {{ number_format((float)$coupon->discount_value, 0, ',', '.') }}đ
                        @endif
                        @if($coupon->max_discount_amount)
                            (tối đa {{ number_format((float)$coupon->max_discount_amount, 0, ',', '.') }}đ)
                        @endif
                    </p>
                    <p class="text-xs text-gray-500 mt-1">HSD: {{ optional($coupon->end_date)->format('d/m/Y') }}</p>
                    @if($coupon->min_order_amount)
                        <p class="text-xs text-gray-500">ĐH tối thiểu: {{ number_format((float)$coupon->min_order_amount, 0, ',', '.') }}đ</p>
                    @endif
                    @if($coupon->shop)
                        <p class="text-xs text-gray-500">Cửa hàng: {{ $coupon->shop->shop_name }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $saved->appends(request()->query())->links() }}
    </div>
@endif


