@extends('user.account.profile')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush

@section('account-content')
    <div class="container mx-auto px-4">
        <!-- Wishlist Section -->
        <div class=" mb-5 md:mb-7 lg:mb-9">
            <div class="w-full flex flex-col sm:flex-row justify-between items-center mb-3 md:mb-5">
                <h2 class="text-lg md:text-xl font-semibold mb-2 sm:mb-0">Danh sách đã yêu thích
                    ({{ $wishlistItems->count() }})</h2>
                <button
                    class="border border-black rounded-[4px] px-3 py-1 sm:px-5 sm:py-3 text-xs sm:text-sm hover:bg-black hover:text-white">
                    Chuyển tất cả vào giỏ hàng
                </button>
            </div>
            @if ($wishlistItems->isEmpty())
                <p class="text-center text-gray-500">Danh sách yêu thích của bạn đang trống.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-7 lg:gap-[40px]">
                    @foreach ($wishlistItems as $item)
                        <div class="relative bg-white rounded-lg overflow-hidden w-fit">
                            <div
                                class="relative bg-[#F5F5F5] w-full sm:w-[280px] lg:w-[300px] h-[230px] sm:h-[280px] lg:h-[300px] mx-auto">
                                <div class="absolute top-2 sm:top-4 flex items-center justify-between px-2 sm:px-4 w-full">
                                    @if ($item->product->price != $item->product->sale_price)
                                        <span class="bg-[#BDBDBD] text-white text-xs px-1.5 py-0.5 rounded">
                                            -{{ round((($item->product->price - $item->product->sale_price) / $item->product->price) * 100) }}%
                                        </span>
                                    @endif
                                    <button class="bg-white rounded-full p-1">
                                        <svg width="24" height="24" sm:width="30" sm:height="30" viewBox="0 0 34 34"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="17" cy="17" r="17" fill="white"></circle>
                                            <path
                                                d="M25 10.5714H10.3333L11.6667 26H22.3333L23.6667 10.5714H9M17 14.4286V22.1429M20.3333 14.4286L19.6667 22.1429M13.6667 14.4286L14.3333 22.1429M14.3333 10.5714L15 8H19L19.6667 10.5714"
                                                stroke="black" stroke-width="1.56" stroke-linecap="round"
                                                stroke-linejoin="round">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex items-center justify-center w-full h-full p-5 sm:p-7 lg:p-[50px]">
                                    <img src="{{ asset($item->product->images->first()->image_path ?? 'images/placeholder.png') }}"
                                        alt="{{ $item->product->name }}" class="object-contain w-full h-full">
                                </div>
                                <button
                                    class="absolute bottom-0 left-0 w-full bg-black text-white flex items-center justify-center py-1.5 sm:py-1.5 text-xs sm:text-sm"
                                    style="border-radius: 0px 0px 4px 4px;">
                                    <svg width="18" height="18" sm:width="22" sm:height="22" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-1 sm:mr-1.5">
                                        <path
                                            d="M8.25 20.25C8.66421 20.25 9 19.9142 9 19.5C9 19.0858 8.66421 18.75 8.25 18.75C7.83579 18.75 7.5 19.0858 7.5 19.5C7.5 19.9142 7.83579 20.25 8.25 20.25Z"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path
                                            d="M18.75 20.25C19.1642 20.25 19.5 19.9142 19.5 19.5C19.5 19.0858 19.1642 18.75 18.75 18.75C18.3358 18.75 18 19.0858 18 19.5C18 19.9142 18.3358 20.25 18.75 20.25Z"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M2.25 3.75H5.25L7.5 16.5H19.5" stroke="white" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path
                                            d="M7.5 12.5H19.1925C19.2792 12.5001 19.3633 12.4701 19.4304 12.4151C19.4975 12.3601 19.5434 12.2836 19.5605 12.1986L20.9105 5.44859C20.9214 5.39417 20.92 5.338 20.9066 5.28414C20.8931 5.23029 20.8679 5.18009 20.8327 5.13717C20.7975 5.09426 20.7532 5.05969 20.703 5.03597C20.6528 5.01225 20.598 4.99996 20.5425 5H6"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </svg>
                                    <span>Thêm vào giỏ hàng</span>
                                </button>
                            </div>
                            <div class="p-2 sm:p-3">
                                <h3 class="text-sm sm:text-base font-medium">{{ $item->product->name }}</h3>
                                <div class="flex items-center mt-1 sm:mt-1.5">
                                    <span
                                        class="text-sm sm:text-base font-bold text-black">${{ number_format($item->product->sale_price, 0, '.', ',') }}</span>
                                    @if ($item->product->price != $item->product->sale_price)
                                        <span
                                            class="text-xs text-gray-500 line-through ml-1.5">${{ number_format($item->product->price, 0, '.', ',') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recommendations Section -->
        <div class="recommendations-section">
            <div class="w-full flex flex-col sm:flex-row justify-between items-center mb-2">
                <div class="flex gap-2 items-center mb-2 sm:mb-0">
                    <div style="background-color: #BDBDBD;" class="rounded h-[25px] sm:h-[40px] w-[12px] sm:w-[18px]">
                    </div>
                    <span style="color: #BDBDBD;" class="font-bold text-base sm:text-lg">Gợi ý sản phẩm</span>
                </div>
                <button
                    class="border border-black rounded-[4px] px-5 py-1.5 sm:px-8 sm:py-3 text-xs sm:text-sm hover:bg-black hover:text-white">
                    Xem tất cả
                </button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-7 lg:gap-[40px]">
                @foreach (\App\Models\Product::inRandomOrder()->take(4)->with([
                'images' => function ($q) {
                    $q->where('is_default', 1);
                },
            ])->get() as $product)
                    <div class="relative bg-white rounded-lg overflow-hidden w-fit">
                        <div
                            class="relative bg-[#F5F5F5] w-full sm:w-[280px] lg:w-[300px] h-[230px] sm:h-[280px] lg:h-[300px] mx-auto">
                            <div class="absolute top-2 sm:top-4 flex items-center justify-between px-2 sm:px-4 w-full">
                                @if ($product->price != $product->sale_price)
                                    <span class="bg-[#BDBDBD] text-white text-xs px-1.5 py-0.5 rounded">
                                        -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                    </span>
                                @endif
                                <button class="bg-white rounded-full p-1">
                                    <svg width="24" height="24" sm:width="30" sm:height="30" viewBox="0 0 34 34"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="17" cy="17" r="17" fill="white"></circle>
                                        <path
                                            d="M26.257 15.962C26.731 16.582 26.731 17.419 26.257 18.038C24.764 19.987 21.182 24 17 24C12.818 24 9.23601 19.987 7.74301 18.038C7.51239 17.7411 7.38721 17.3759 7.38721 17C7.38721 16.6241 7.51239 16.2589 7.74301 15.962C9.23601 14.013 12.818 10 17 10C21.182 10 24.764 14.013 26.257 15.962V15.962Z"
                                            stroke="black" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path
                                            d="M17 20C18.6569 20 20 18.6569 20 17C20 15.3431 18.6569 14 17 14C15.3431 14 14 15.3431 14 17C14 18.6569 15.3431 20 17 20Z"
                                            stroke="black" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="flex items-center justify-center w-full h-full p-5 sm:p-7 lg:p-[50px]">
                                <img src="{{ asset($product->images->first()->image_path ?? 'images/placeholder.png') }}"
                                    alt="{{ $product->name }}" class="object-contain w-full h-full">
                            </div>
                            <button
                                class="absolute bottom-0 left-0 w-full bg-black text-white flex items-center justify-center py-1.5 sm:py-1.5 text-xs sm:text-sm"
                                style="border-radius: 0px 0px 4px 4px;">
                                <svg width="18" height="18" sm:width="22" sm:height="22" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-1 sm:mr-1.5">
                                    <path
                                        d="M8.25 20.25C8.66421 20.25 9 19.9142 9 19.5C9 19.0858 8.66421 18.75 8.25 18.75C7.83579 18.75 7.5 19.0858 7.5 19.5C7.5 19.9142 7.83579 20.25 8.25 20.25Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path
                                        d="M18.75 20.25C19.1642 20.25 19.5 19.9142 19.5 19.5C19.5 19.0858 19.1642 18.75 18.75 18.75C18.3358 18.75 18 19.0858 18 19.5C18 19.9142 18.3358 20.25 18.75 20.25Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path d="M2.25 3.75H5.25L7.5 16.5H19.5" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path
                                        d="M7.5 12.5H19.1925C19.2792 12.5001 19.3633 12.4701 19.4304 12.4151C19.4975 12.3601 19.5434 12.2836 19.5605 12.1986L20.9105 5.44859C20.9214 5.39417 20.92 5.338 20.9066 5.28414C20.8931 5.23029 20.8679 5.18009 20.8327 5.13717C20.7975 5.09426 20.7532 5.05969 20.703 5.03597C20.6528 5.01225 20.598 4.99996 20.5425 5H6"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                                <span>Thêm vào giỏ hàng</span>
                            </button>
                        </div>
                        <div class="p-2 sm:p-3">
                            <h3 class="text-sm sm:text-base font-medium">{{ $product->name }}</h3>
                            <div class="flex items-center mt-1 sm:mt-1.5">
                                <span
                                    class="text-sm sm:text-base font-bold text-black">${{ number_format($product->sale_price, 0, '.', ',') }}</span>
                                @if ($product->price != $product->sale_price)
                                    <span
                                        class="text-xs text-gray-500 line-through ml-1.5">${{ number_format($product->price, 0, '.', ',') }}</span>
                                @endif
                            </div>
                            <div class="flex items-center mt-1 sm:mt-1.5 text-[#FFAD33]">
                                @for ($i = 0; $i < 5; $i++)
                                    <i class="fa-solid fa-star text-xs"></i>
                                @endfor
                                <span class="ml-1.5 text-gray-500 text-xs">(65)</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
