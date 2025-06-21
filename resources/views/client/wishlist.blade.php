@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container mx-auto px-4 mt-8 md:mt-12 lg:mt-16">
        <!-- Wishlist Section -->
        <div class="wishlist-section mb-6 md:mb-8 lg:mb-10">
            <div class="w-full flex flex-col sm:flex-row justify-between items-center mb-4 md:mb-6">
                <h2 class="text-xl md:text-2xl font-semibold mb-2 sm:mb-0">Danh sách đã yêu thích ({{$wishlist->count()}})</h2>
                <button
                    class="border border-black rounded-[4px] px-4 py-2 sm:px-6 sm:py-4 text-sm sm:text-base hover:bg-black hover:text-white">
                    Chuyển tất cả vào giỏ hàng
                </button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 lg:gap-[50px]">
                
                <!-- Product Card 1 -->
                <div class="relative bg-white rounded-lg overflow-hidden">
                    @if (isset($wishlist) && $wishlist->count() > 0)
                        @foreach ($wishlist as $item)
                        <div
                        class="relative bg-[#F5F5F5] w-full sm:w-[300px] lg:w-[350px] h-[250px] sm:h-[300px] lg:h-[330px] mx-auto">
                        <div class="absolute top-3 sm:top-5 flex items-center justify-end px-3 sm:px-5 w-full">
                        <form action="{{ route('wishlist.remove', $item->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-white rounded-full p-1">
                                <svg width="28" height="28" class="sm:w-[34px] sm:h-[34px]" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="17" cy="17" r="17" fill="white"></circle>
                                    <path
                                        d="M25 10.5714H10.3333L11.6667 26H22.3333L23.6667 10.5714H9M17 14.4286V22.1429M20.3333 14.4286L19.6667 22.1429M13.6667 14.4286L14.3333 22.1429M14.3333 10.5714L15 8H19L19.6667 10.5714"
                                        stroke="black" stroke-width="1.56" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </button>
                        </form>
                        </div>
                        <div class="flex items-center justify-center w-full h-full p-6 sm:p-8 lg:p-[60px]">
                            <img src="{{ $item->product->images->first()->image ?? 'https://cdn.builder.io/api/v1/image/assets/TEMP/7cc2d9ab6d6f8dcf8f4d69264eca4c8110a4dadc?placeholderIfAbsent=true' }}" alt="{{ $item->product->name }}"
                                class="object-contain w-full h-full">
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 flex">
 
                            <a href="{{ route('product_detail', $item->product->id) }}" class="w-full bg-black text-white flex items-center justify-center py-2 sm:py-2 text-sm sm:text-base"
                                style="border-radius: 0px 0px 4px 0px;">
                                <svg width="20" height="20" sm:width="24" sm:height="24" viewBox="0 0 24 24" 
                                    fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-1 sm:mr-2">
                                    <path d="M2.25 8.25h19.5M2.25 9h19.5m-19.5 0a2.25 2.25 0 0 1 2.25-2.25h15A2.25 2.25 0 0 1 21 9v11.25a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 20.25V9Zm0 0V5.25A2.25 2.25 0 0 1 5.25 3h13.5A2.25 2.25 0 0 1 21 5.25V9m-19.5 0h19.5" 
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>Xem sản phẩm</span>
                            </a>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4">
                        <h3 class="text-base sm:text-lg font-medium">{{ $item->product->name}}</h3>
                        <div class="mt-1 sm:mt-2">
                            <span class="text-base sm:text-lg font-bold text-black"> {{ number_format($item->product->price, 0, ',', '.') }} VNĐ</span>
                        </div>
                        <small class="text-gray-500 text-xs sm:text-sm">
                            {{ $item->product->shop->shop_name ?? 'Cửa hàng không có tên' }}
                        </small>
                    </div>
                        @endforeach
                    @else
                        <div class="flex items-center justify-center w-full h-full">
                            <span class="text-gray-500">Không có sản phẩm nào trong danh sách đã yêu thích</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>


        

        <!-- Recommendations Section -->
        <div class="recommendations-section">
            <div class="w-full flex flex-col sm:flex-row justify-between items-center mb-3">
                <div class="flex gap-3 items-center mb-2 sm:mb-0">
                    <div style="background-color: #BDBDBD;" class="rounded h-[30px] sm:h-[45px] w-[15px] sm:w-[20px]">
                    </div>
                    <span style="color: #BDBDBD;" class="font-bold text-lg sm:text-xl">Có thể bạn sẽ quan tâm</span>
                </div>
                <button
                    class="border border-black rounded-[4px] px-6 py-2 sm:px-10 sm:py-4 text-sm sm:text-base hover:bg-black hover:text-white">
                    Xem tất cả
                </button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 lg:gap-[50px]">
                <!-- Product Card 1 -->
                <div class="relative bg-white rounded-lg overflow-hidden">
                    <div
                        class="relative bg-[#F5F5F5] w-full sm:w-[300px] lg:w-[350px] h-[250px] sm:h-[300px] lg:h-[330px] mx-auto">
                        <div class="absolute top-3 sm:top-5 flex items-center justify-between px-3 sm:px-5 w-full">
                            <span class="bg-[#BDBDBD] text-white text-xs px-2 py-1 rounded">-35%</span>
                            <button class="bg-white rounded-full p-1">
                                <svg width="28" height="28" sm:width="34" sm:height="34" viewBox="0 0 34 34"
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
                        <div class="flex items-center justify-center w-full h-full p-6 sm:p-8 lg:p-[60px]">
                            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/7cc2d9ab6d6f8dcf8f4d69264eca4c8110a4dadc?placeholderIfAbsent=true"
                                alt="Gucci duffle bag" class="object-contain w-full h-full">
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 flex">
                            <button
                                class="w-1/2 bg-black text-white flex items-center justify-center py-2 sm:py-2 text-sm sm:text-base"
                                style="border-radius: 0px 0px 0px 4px;">
                                <svg width="20" height="20" sm:width="24" sm:height="24" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-1 sm:mr-2">
                                    <path
                                        d="M8.25 20.25C8.66421 20.25 9 19.9142 9 19.5C9 19.0858 8.66421 18.75 8.25 18.75C7.83579 18.75 7.5 19.0858 7.5 19.5C7.5 19.9142 7.83579 20.25 8.25 20.25Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                    <path
                                        d="M18.75 20.25C19.1642 20.25 19.5 19.9142 19.5 19.5C19.5 19.0858 19.1642 18.75 18.75 18.75C18.3358 18.75 18 19.0858 18 19.5C18 19.9142 18.3358 20.25 18.75 20.25Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                    <path d="M2.25 3.75H5.25L7.5 16.5H19.5" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path
                                        d="M7.5 12.5H19.1925C19.2792 12.5001 19.3633 12.4701 19.4304 12.4151C19.4975 12.3601 19.5434 12.2836 19.5605 12.1986L20.9105 5.44859C20.9214 5.39417 20.92 5.338 20.9066 5.28414C20.8931 5.23029 20.8679 5.18009 20.8327 5.13717C20.7975 5.09426 20.7532 5.05969 20.703 5.03597C20.6528 5.01225 20.598 4.99996 20.5425 5H6"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                </svg>
                                <span>Thêm vào giỏ hàng</span>
                            </button>
                            <button class="w-1/2 bg-black text-white flex items-center justify-center py-2 sm:py-2 text-sm sm:text-base"
                                style="border-radius: 0px 0px 4px 0px;">
                                <span>Mua ngay</span>
                            </button>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4">
                        <h3 class="text-base sm:text-lg font-medium">Gucci duffle bag</h3>
                        <div class="flex items-center mt-1 sm:mt-2">
                            <span class="text-base sm:text-lg font-bold text-black">$960</span>
                            <span class="text-xs sm:text-sm text-gray訆-500 line-through ml-2">$1160</span>
                        </div>
                        <div class="flex items-center mt-1 sm:mt-2 text-[#FFAD33]">
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <span class="ml-2 text-gray-500 text-xs sm:text-sm">(65)</span>
                        </div>
                    </div>
                </div>

                <!-- Product Card 2 -->
                <div class="relative bg-white rounded-lg overflow-hidden">
                    <div
                        class="relative bg-[#F5F5F5] w-full sm:w-[300px] lg:w-[350px] h-[250px] sm:h-[300px] lg:h-[330px] mx-auto">
                        <div class="absolute top-3 sm:top-5 flex items-center justify-end px-3 sm:px-5 w-full">
                            <button class="bg-white rounded-full p-1">
                                <svg width="28" height="28" sm:width="34" sm:height="34" viewBox="0 0 34 34"
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
                        <div class="flex items-center justify-center w-full h-full p-6 sm:p-8 lg:p-[60px]">
                            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/bf84cddb31b0f222a69d9e6da9b3c4c87a2110df?placeholderIfAbsent=true"
                                alt="RGB liquid CPU Cooler" class="object-contain w-full h-full">
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 flex">
                            <button
                                class="w-1/2 bg-black text-white flex items-center justify-center py-2 sm:py-2 text-sm sm:text-base"
                                style="border-radius: 0px 0px 0px 4px;">
                                <svg width="20" height="20" sm:width="24" sm:height="24" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-1 sm:mr-2">
                                    <path
                                        d="M8.25 20.25C8.66421 20.25 9 19.9142 9 19.5C9 19.0858 8.66421 18.75 8.25 18.75C7.83579 18.75 7.5 19.0858 7.5 19.5C7.5 19.9142 7.83579 20.25 8.25 20.25Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                    <path
                                        d="M18.75 20.25C19.1642 20.25 19.5 19.9142 19.5 19.5C19.5 19.0858 19.1642 18.75 18.75 18.75C18.3358 18.75 18 19.0858 18 19.5C18 19.9142 18.3358 20.25 18.75 20.25Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                    <path d="M2.25 3.75H5.25L7.5 16.5H19.5" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path
                                        d="M7.5 12.5H19.1925C19.2792 12.5001 19.3633 12.4701 19.4304 12.4151C19.4975 12.3601 19.5434 12.2836 19.5605 12.1986L20.9105 5.44859C20.9214 5.39417 20.92 5.338 20.9066 5.28414C20.8931 5.23029 20.8679 5.18009 20.8327 5.13717C20.7975 5.09426 20.7532 5.05969 20.703 5.03597C20.6528 5.01225 20.598 4.99996 20.5425 5H6"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                </svg>
                                <span>Thêm vào giỏ hàng</span>
                            </button>
                            <button class="w-1/2 bg-black text-white flex items-center justify-center py-2 sm:py-2 text-sm sm:text-base"
                                style="border-radius: 0px 0px 4px 0px;">
                                <span>Mua ngay</span>
                            </button>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4">
                        <h3 class="text-base sm:text-lg font-medium">RGB liquid CPU Cooler</h3>
                        <div class="mt-1 sm:mt-2">
                            <span class="text-base sm:text-lg font-bold text-black">$1960</span>
                        </div>
                        <div class="flex items-center mt-1 sm:mt-2 text-[#FFAD33]">
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <span class="ml-2 text-gray-500 text-xs sm:text-sm">(65)</span>
                        </div>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div class="relative bg-white rounded-lg overflow-hidden">
                    <div
                        class="relative bg-[#F5F5F5] w-full sm:w-[300px] lg:w-[350px] h-[250px] sm:h-[300px] lg:h-[330px] mx-auto">
                        <div class="absolute top-3 sm:top-5 flex items-center justify-between px-3 sm:px-5 w-full">
                            <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">Mới</span>
                            <button class="bg-white rounded-full p-1">
                                <svg width="28" height="28" sm:width="34" sm:height="34" viewBox="0 0 34 34"
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
                        <div class="flex items-center justify-center w-full h-full p-6 sm:p-8 lg:p-[60px]">
                            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/c294479147fb98a8501bd8b224b5735f97c19f58?placeholderIfAbsent=true"
                                alt="RGB liquid CPU Cooler" class="object-contain w-full h-full">
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 flex">
                            <button
                                class="w-1/2 bg-black text-white flex items-center justify-center py-2 sm:py-2 text-sm sm:text-base"
                                style="border-radius: 0px 0px 0px 4px;">
                                <svg width="20" height="20" sm:width="24" sm:height="24" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-1 sm:mr-2">
                                    <path
                                        d="M8.25 20.25C8.66421 20.25 9 19.9142 9 19.5C9 19.0858 8.66421 18.75 8.25 18.75C7.83579 18.75 7.5 19.0858 7.5 19.5C7.5 19.9142 7.83579 20.25 8.25 20.25Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                    <path
                                        d="M18.75 20.25C19.1642 20.25 19.5 19.9142 19.5 19.5C19.5 19.0858 19.1642 18.75 18.75 18.75C18.3358 18.75 18 19.0858 18 19.5C18 19.9142 18.3358 20.25 18.75 20.25Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                    <path d="M2.25 3.75H5.25L7.5 16.5H19.5" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path
                                        d="M7.5 12.5H19.1925C19.2792 12.5001 19.3633 12.4701 19.4304 12.4151C19.4975 12.3601 19.5434 12.2836 19.5605 12.1986L20.9105 5.44859C20.9214 5.39417 20.92 5.338 20.9066 5.28414C20.8931 5.23029 20.8679 5.18009 20.8327 5.13717C20.7975 5.09426 20.7532 5.05969 20.703 5.03597C20.6528 5.01225 20.598 4.99996 20.5425 5H6"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                </svg>
                                <span>Thêm vào giỏ hàng</span>
                            </button>
                            <button class="w-1/2 bg-black text-white flex items-center justify-center py-2 sm:py-2 text-sm sm:text-base"
                                style="border-radius: 0px 0px 4px 0px;">
                                <span>Mua ngay</span>
                            </button>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4">
                        <h3 class="text-base sm:text-lg font-medium">RGB liquid CPU Cooler</h3>
                        <div class="mt-1 sm:mt-2">
                            <span class="text-base sm:text-lg font-bold text-black">$1960</span>
                        </div>
                        <div class="flex items-center mt-1 sm:mt-2 text-[#FFAD33]">
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <span class="ml-2 text-gray-500 text-xs sm:text-sm">(65)</span>
                        </div>
                    </div>
                </div>

                <!-- Product Card 4 -->
                <div class="relative bg-white rounded-lg overflow-hidden">
                    <div
                        class="relative bg-[#F5F5F5] w-full sm:w-[300px] lg:w-[350px] h-[250px] sm:h-[300px] lg:h-[330px] mx-auto">
                        <div class="absolute top-3 sm:top-5 flex items-center justify-end px-3 sm:px-5 w-full">
                            <button class="bg-white rounded-full p-1">
                                <svg width="28" height="28" sm:width="34" sm:height="34" viewBox="0 0 34 34"
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
                        <div class="flex items-center justify-center w-full h-full p-6 sm:p-8 lg:p-[60px]">
                            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/bc4214fc345cbcc392ba2adc1b5ec88b6e6df170?placeholderIfAbsent=true"
                                alt="RGB liquid CPU Cooler" class="object-contain w-full h-full">
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 flex">
                            <button
                                class="w-1/2 bg-black text-white flex items-center justify-center py-2 sm:py-2 text-sm sm:text-base"
                                style="border-radius: 0px 0px 0px 4px;">
                                <svg width="20" height="20" sm:width="24" sm:height="24" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-1 sm:mr-2">
                                    <path
                                        d="M8.25 20.25C8.66421 20.25 9 19.9142 9 19.5C9 19.0858 8.66421 18.75 8.25 18.75C7.83579 18.75 7.5 19.0858 7.5 19.5C7.5 19.9142 7.83579 20.25 8.25 20.25Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                    <path
                                        d="M18.75 20.25C19.1642 20.25 19.5 19.9142 19.5 19.5C19.5 19.0858 19.1642 18.75 18.75 18.75C18.3358 18.75 18 19.0858 18 19.5C18 19.9142 18.3358 20.25 18.75 20.25Z"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                    <path d="M2.25 3.75H5.25L7.5 16.5H19.5" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path
                                        d="M7.5 12.5H19.1925C19.2792 12.5001 19.3633 12.4701 19.4304 12.4151C19.4975 12.3601 19.5434 12.2836 19.5605 12.1986L20.9105 5.44859C20.9214 5.39417 20.92 5.338 20.9066 5.28414C20.8931 5.23029 20.8679 5.18009 20.8327 5.13717C20.7975 5.09426 20.7532 5.05969 20.703 5.03597C20.6528 5.01225 20.598 4.99996 20.5425 5H6"
                                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                </svg>
                                <span>Thêm vào giỏ hàng</span>
                            </button>
                            <button class="w-1/2 bg-black text-white flex items-center justify-center py-2 sm:py-2 text-sm sm:text-base"
                                style="border-radius: 0px 0px 4px 0px;">
                                <span>Mua ngay</span>
                            </button>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4">
                        <h3 class="text-base sm:text-lg font-medium">RGB liquid CPU Cooler</h3>
                        <div class="mt-1 sm:mt-2">
                            <span class="text-base sm:text-lg font-bold text-black">$1960</span>
                        </div>
                        <div class="flex items-center mt-1 sm:mt-2 text-[#FFAD33]">
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <i class="fa-solid fa-star text-xs sm:text-sm"></i>
                            <span class="ml-2 text-gray-500 text-xs sm:text-sm">(65)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
