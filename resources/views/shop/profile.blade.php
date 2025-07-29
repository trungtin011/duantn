@extends('layouts.app')

@section('title', 'Thông tin người bán')

@section('content')
<link href="https://cdn.tailwindcss.com" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<div class="max-w-[1200px] mx-auto px-4 pt-6">

    <!-- Banner và Logo -->
    <div class="relative w-full h-52 md:h-64 rounded-lg overflow-hidden mb-6 shadow-md">
        <img src="{{ asset($shop->shop_banner ?? 'images/default-banner.jpg') }}" alt="Banner shop" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end justify-between px-6 py-4">
            <div class="flex items-center">
                <img src="{{ asset($shop->shop_logo ?? 'images/default-logo.png') }}" alt="Logo" class="w-20 h-20 rounded-full border-4 border-white object-cover shadow-md">
                <div class="ml-4 text-white">
                    <h1 class="text-2xl font-bold">{{ $shop->shop_name }}</h1>
                    <p class="text-sm opacity-90">Chủ shop: {{ $shop->owner->fullname ?? 'Chưa cập nhật' }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                @auth
                @php
                $isFollowing = auth()->user()->followedShops->contains($shop->id);
                @endphp

                @if ($isFollowing)
                <form method="POST" action="{{ route('shop.unfollow', $shop->id) }}">
                    @csrf
                    <button class="flex items-center gap-1 border border-white rounded text-white text-[13px] px-3 py-[5px] hover:bg-white hover:text-[#5a4a1a] transition">
                        <i class="fas fa-heart text-red-500"></i> Huỷ theo dõi
                    </button>
                </form>
                @else
                <form method="POST" action="{{ route('shop.follow', $shop->id) }}">
                    @csrf
                    <button class="flex items-center gap-1 border border-white rounded text-white text-[13px] px-3 py-[5px] hover:bg-white hover:text-[#5a4a1a] transition">
                        <i class="fas fa-heart"></i> Theo dõi
                    </button>
                </form>
                @endif
                @endauth
                <button class="flex items-center gap-1 border border-white rounded text-white text-[13px] px-3 py-[5px] hover:bg-white hover:text-[#5a4a1a] transition">
                    <i class="far fa-comment-alt text-[13px]"></i> Chat
                </button>
            </div>
        </div>
    </div>

    <!-- Thông tin chi tiết -->
    <div class="bg-white rounded-lg shadow-md p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm text-gray-700 mb-10">
        <div class="flex items-center gap-2"><i class="fas fa-box-open text-[#e03e2f]"></i> <span>Sản phẩm: <strong>{{ $shop->products->count() }}</strong></span></div>
        <div class="flex items-center gap-2"><i class="fas fa-user-friends text-[#e03e2f]"></i>
            <p class="text-sm">
                Người theo dõi: <strong>{{ $shop->followers->count() }}</strong>
            </p>
        </div>
        <div class="flex items-center gap-2"><i class="fas fa-user-check text-[#e03e2f]"></i> <span>Chủ shop: <strong>{{ $shop->owner->fullname ?? 'Chưa cập nhật' }}</strong></span></div>
        <div class="flex items-center gap-2"><i class="far fa-envelope text-[#e03e2f]"></i> <span>Email: <strong>{{ $shop->shop_email }}</strong></span></div>
        <div class="flex items-center gap-2"><i class="fas fa-phone-alt text-[#e03e2f]"></i> <span>SĐT: <strong>{{ $shop->shop_phone }}</strong></span></div>
        <div class="flex items-center gap-2"><i class="fas fa-map-marker-alt text-[#e03e2f]"></i>
            <span>Địa chỉ:
                <strong>{{ $shop->address->shop_address ?? 'Chưa cập nhật' }}</strong>
            </span>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Sản phẩm từ shop</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        @forelse ($shop->products as $product)
        <a href="{{ route('product.show', $product->slug) }}" class="bg-white border rounded-lg shadow-sm hover:shadow-md p-3 transition block">
            <img src="{{ asset($product->images->first()->image_path ?? 'images/default.jpg') }}" class="rounded-md w-full h-36 object-cover mb-2" alt="{{ $product->name }}">
            <div class="font-semibold text-sm truncate">{{ $product->name }}</div>
            <div class="text-[#e03e2f] font-bold text-sm">{{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}đ</div>
            <div class="text-[11px] text-[#777] mt-1">
                <i class="fas fa-star text-yellow-400"></i>
                {{ number_format($product->reviews()->exists() ? $product->reviews->avg('rating') : 0, 1) }} • Đã bán {{ $product->sold_quantity ?? 0 }}
            </div>
        </a>
        @empty
            <p class="text-gray-500">Không có sản phẩm nào.</p>
        @endforelse
    </div>

    <!-- Danh sách combo -->
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Combo từ shop</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        @forelse ($shop->combos as $combo)
      <a href="{{ route('combo.show', $combo->id) }}" class="bg-white border rounded-lg shadow-sm hover:shadow-md p-3 transition block">
    <img src="{{ asset($combo->products->first()->product->images->first()->image_path ?? 'images/default.jpg') }}" class="rounded-md w-full h-36 object-cover mb-2" alt="{{ $combo->combo_name }}">
    <div class="font-semibold text-sm truncate">{{ $combo->combo_name }}</div>
    <div class="text-[#e03e2f] font-bold text-sm">{{ number_format($combo->total_price, 0, ',', '.') }}đ</div>
    <div class="text-[11px] text-[#777] mt-1">
        <i class="fas fa-star text-yellow-400"></i>
        {{ number_format($combo->reviews()->exists() ? $combo->reviews->avg('rating') : 0, 1) }} • Đã bán {{ $combo->sold_quantity ?? 0 }}
    </div>
</a>
        @empty
            <p class="text-gray-500">Không có combo nào.</p>
        @endforelse
    </div>
</div>

@endsection