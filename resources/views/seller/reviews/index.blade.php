@extends('layouts.seller_home')
@section('title', 'Danh sách đánh giá')
@section('content')
    <div class="mx-auto py-4 sm:py-6 md:py-8">
        <header class="flex justify-between items-center border-b border-gray-200 p-3 mb-6 bg-white rounded">
            <div>
                <h1 class="font-semibold text-base leading-5 text-gray-900">Đánh Giá Shop</h1>
                <p class="text-xs text-gray-400 mt-0.5">Xem đánh giá Shop của bạn</p>
            </div>
            <div class="text-orange-500 font-semibold text-xl leading-6 select-none">
                {{ number_format($averageRating, 1) }}
                <span class="text-gray-400 font-normal text-base">/ 5</span>
            </div>
        </header>

        <!-- Form lọc -->
        <form action="{{ route('seller.reviews.index') }}" method="GET"
            class="flex flex-wrap gap-x-2 items-center justify-between mb-6 text-xs text-gray-700 bg-white p-3 rounded">
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-1 whitespace-nowrap">
                    <span class="min-w-fit">Tên sản phẩm:</span>
                    <input name="product" type="text" value="{{ request('product') }}"
                        class="border border-gray-200 rounded px-2 py-1 text-xs placeholder:text-gray-300 focus:outline-none focus:ring-1 focus:ring-orange-500"
                        placeholder="Nhập tên sản phẩm" />
                </label>

                <label class="flex items-center gap-1 whitespace-nowrap">
                    <span class="min-w-fit">Phân loại hàng:</span>
                    <input name="variation" type="text" value="{{ request('variation') }}"
                        class="border border-gray-200 rounded px-2 py-1 text-xs placeholder:text-gray-300 focus:outline-none focus:ring-1 focus:ring-orange-500"
                        placeholder="VD: Màu Đen, Size M" />
                </label>

                <label class="flex items-center gap-1 whitespace-nowrap">
                    <span class="min-w-fit">Người mua:</span>
                    <input name="buyer" type="text" value="{{ request('buyer') }}"
                        class="border border-gray-200 rounded px-2 py-1 text-xs placeholder:text-gray-300 focus:outline-none focus:ring-1 focus:ring-orange-500"
                        placeholder="Tên người mua" />
                </label>
            </div>

            <div class="flex items-center gap-3">
                <label class="flex items-center gap-1 whitespace-nowrap">
                    <span class="min-w-fit">Thời gian:</span>
                    <input name="date" type="date" value="{{ request('date') }}"
                        class="border border-gray-200 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-orange-500" />
                </label>

                <div class="flex gap-1 mt-1">
                    <a href="{{ route('seller.reviews.index') }}"
                        class="border border-gray-300 text-gray-700 text-xs rounded px-4 py-1.5 select-none hover:bg-gray-100">
                        Nhập Lại
                        <i class="fas fa-redo ml-1"></i>
                    </a>
                    <button type="submit"
                        class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold rounded px-4 py-1.5 select-none">
                        Tìm
                    </button>
                </div>
            </div>
        </form>

        <!-- Bộ lọc theo sao & phản hồi -->
        <div class="flex flex-wrap justify-between items-center mb-4 bg-white p-3 rounded">
            <ul class="flex gap-3 text-xs font-semibold text-gray-600">
                @foreach ([5, 4, 3, 2, 1] as $s)
                    <li>
                        <a href="{{ route('seller.reviews.index', ['star' => $s]) }}"
                            class="px-2 py-1 border rounded {{ request('star') == $s ? 'text-orange-500 border-orange-500' : 'border-gray-300' }}">
                            {{ $s }} Sao
                        </a>
                    </li>
                @endforeach
            </ul>

            <ul class="flex gap-3 text-xs font-semibold text-gray-600">
                <li>
                    <a href="{{ route('seller.reviews.index') }}"
                        class="px-2 py-1 {{ !request('replied') ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-500' }}">
                        Tất cả
                    </a>
                </li>
                <li>
                    <a href="{{ route('seller.reviews.index', ['replied' => 'no']) }}"
                        class="px-2 py-1 {{ request('replied') == 'no' ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-500' }}">
                        Chưa trả lời
                    </a>
                </li>
                <li>
                    <a href="{{ route('seller.reviews.index', ['replied' => 'yes']) }}"
                        class="px-2 py-1 {{ request('replied') == 'yes' ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-500' }}">
                        Đã trả lời
                    </a>
                </li>
            </ul>
        </div>

        <!-- Danh sách đánh giá -->
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="min-w-full text-xs text-left text-gray-700">
                <thead class="bg-white text-gray-500">
                    <tr>
                        <th class="px-3 py-2">Sản phẩm</th>
                        <th class="px-3 py-2 text-center">Đánh giá</th>
                        <th class="px-3 py-2 text-center">Phản hồi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($reviews as $review)
                        <tr class="border-t">
                            <td class="px-3 py-2">
                                <div class="flex items-start gap-2">
                                    @php
                                        $thumb = optional($review->product->images()->default()->first())->image_path
                                            ?? optional($review->product->images()->first())->image_path
                                            ?? null;
                                    @endphp
                                    <img src="{{ $thumb ? asset('storage/' . $thumb) : asset('images/avatar.png') }}"
                                         alt="{{ $review->product->name }}"
                                         class="w-10 h-10 object-cover rounded"
                                         onerror="this.src='{{ asset('images/avatar.png') }}'" />
                                    <div class="leading-snug text-xs">
                                        <div class="font-semibold truncate max-w-[150px]">{{ $review->product->name }}
                                        </div>
                                        @php
                                            $variantName = $review->variation
                                                ?? optional($review->order?->items?->first()?->variant)->variant_name
                                                ?? optional($review->product->variants()->first())->variant_name
                                                ?? null;
                                        @endphp
                                        <div class="text-gray-400">Phân loại: {{ $variantName ?? 'Không rõ' }}</div>
                                        <div class="text-gray-400">Người mua: {{ $review->user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2 text-center align-top">
                                <div class="flex justify-center gap-1 text-orange-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                <div class="text-[10px] text-gray-400 mt-1">
                                    {{ $review->created_at->format('H:i d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-3 py-2 text-center align-top">
                                <div class="text-gray-500 text-[10px] mb-1">Đơn hàng: {{ $review->order->code ?? '-' }}
                                </div>
                                @if ($review->seller_reply)
                                    <div class="text-sm text-left text-blue-600 italic">{{ $review->seller_reply }}</div>
                                @else
                                    <form method="POST" action="{{ route('seller.reviews.reply', $review->id) }}">
                                        @csrf
                                        <textarea name="seller_reply" rows="2" required
                                            class="border border-gray-300 rounded w-full px-2 py-1 text-xs mb-1"></textarea>
                                        <button type="submit"
                                            class="bg-blue-500 hover:bg-blue-600 text-white text-xs rounded px-3 py-1">
                                            Trả lời
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-gray-400">Chưa có đánh giá nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <div class="mt-6">
            {{ $reviews->withQueryString()->links() }}
        </div>
    </div>
@endsection
