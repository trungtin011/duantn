@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Quảng cáo từ {{ $shop->name }}</h1>
                @if($query)
                    <p class="text-gray-600 mt-2">Kết quả tìm kiếm cho: "{{ $query }}"</p>
                @endif
            </div>
            <a href="{{ route('shop.show', $shop->id) }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                Xem shop
            </a>
        </div>
    </div>

    @if($shopAds->isNotEmpty())
        @foreach($shopAds as $campaignAds)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $campaignAds['campaign']->name }}</h2>
                    <div class="text-sm text-gray-500">
                        {{ $campaignAds['products']->count() }} sản phẩm
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($campaignAds['products'] as $product)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                            <div class="relative">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                         class="w-full h-48 object-cover rounded-lg mb-3">
                                    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                                        Quảng cáo
                                    </div>
                                </a>
                            </div>
                            
                            <div class="space-y-2">
                                <h3 class="font-medium text-gray-800 line-clamp-2">
                                    <a href="{{ route('product.show', $product->slug) }}" class="hover:text-red-500">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                
                                <div class="flex items-center gap-2">
                                    <span class="text-red-500 font-bold text-lg">
                                        ₫{{ number_format($product->getCurrentPriceAttribute()) }}
                                    </span>
                                    @if($product->getDiscountPercentageAttribute() > 0)
                                        <span class="text-gray-400 line-through text-sm">
                                            ₫{{ number_format($product->price) }}
                                        </span>
                                        <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded">
                                            -{{ $product->getDiscountPercentageAttribute() }}%
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="text-sm text-gray-500">
                                    Đã bán {{ number_format($product->sold_quantity) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-800 mb-2">Không có quảng cáo nào</h3>
            <p class="text-gray-600">
                @if($query)
                    Không tìm thấy quảng cáo nào cho "{{ $query }}" từ {{ $shop->name }}
                @else
                    {{ $shop->name }} chưa có quảng cáo nào
                @endif
            </p>
        </div>
    @endif
</div>
@endsection
