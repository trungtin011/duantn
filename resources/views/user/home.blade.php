@extends('layouts.app')

@section('content')
    <!-- Main Banner -->
    <section class="container mx-auto py-8 flex">
        <!-- Sidebar Menu with Dropdown -->
        <div class="w-1/4 pr-10">
            <ul class="space-y-2">
                <li>
                    <button id="dropdownToggle"
                        class="text-gray-700 hover:text-black flex items-center justify-between w-full mb-2">
                        Danh mục sản phẩm
                        <svg class="w-[21px] h-[21px] ml-1 arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <ul id="dropdownMenu" class="dropdown-content pl-4 space-y-2 mb-3">
                        <li><a href="#" class="text-gray-700 hover:text-black">Điện thoại - di động</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-black">Máy tính</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-black">Phụ kiện</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-black">Máy ảnh</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-black">Tổng hợp</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-black">Gaming</a></li>
                    </ul>

                    <button id="dropdownToggleSecond"
                        class="text-gray-700 hover:text-black flex items-center justify-between w-full mb-2">
                        Thương hiệu
                        <svg class="w-[21px] h-[21px] ml-1 arrow-icon-second" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <ul id="dropdownMenuSecond" class="dropdown-content pl-4 space-y-2">
                        <li><a href="#" class="text-gray-700 hover:text-black">Apple</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-black">Samsung</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-black">Sony</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-black">LG</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-black">Dell</a></li>
                        <li><a href="#" class="text-gray-700 hover:text-black">Asus</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Banner -->
        <div class="relative w-3/4">
            <div class="slider-container">
                <div class="slides" id="slides">
                    <!-- Slide 1 -->
                    <div class="slide bg-black h-[400px] flex items-center p-5">
                        <div class="text-star w-[400px]">
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('images/apple.png') }}" alt="Apple" class="mr-2"
                                    style="width: 40px; height: 49px;">
                                <span class="text-white text-[16px]">iPhone 14 Series</span>
                            </div>
                            <h1 class="text-[48px] font-bold text-white w-[295px]">Giảm Giá Lên Đến 10%</h1>
                            <button class="mt-4 text-white buy-now-btn flex items-center">
                                <span style="border-bottom: 1px solid #fff; padding-bottom: 5px; font-size: 18px;">Mua
                                    ngay</span>
                                <svg class="ml-3 size-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-center w-[600px]">
                            <img src="{{ asset('images/banner.png') }}" alt="iPhone 14">
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="slide bg-black h-[400px] flex items-center p-5">
                        <div class="text-star w-[400px]">
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('images/samsung.png') }}" alt="Samsung" class="mr-2"
                                    style="width: 40px; height: 49px;">
                                <span class="text-white text-[16px]">Samsung Galaxy S23</span>
                            </div>
                            <h1 class="text-[48px] font-bold text-white w-[295px]">Ưu Đãi Đặc Biệt 15%</h1>
                            <button class="mt-4 text-white buy-now-btn flex items-center">
                                <span style="border-bottom: 1px solid #fff; padding-bottom: 5px; font-size: 18px;">Mua
                                    ngay</span>
                                <svg class="ml-3 size-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-center w-[600px]">
                            <img src="{{ asset('images/samsung-banner.png') }}" alt="Samsung Galaxy S23">
                        </div>
                    </div>
                    <!-- Slide 3 -->
                    <div class="slide bg-black h-[400px] flex items-center p-5">
                        <div class="text-star w-[400px]">
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('images/sony.png') }}" alt="Sony" class="mr-2"
                                    style="width: 40px; height: 49px;">
                                <span class="text-white text-[16px]">Sony PlayStation 5</span>
                            </div>
                            <h1 class="text-[48px] font-bold text-white w-[295px]">Giảm Giá 20%</h1>
                            <button class="mt-4 text-white buy-now-btn flex items-center">
                                <span style="border-bottom: 1px solid #fff; padding-bottom: 5px; font-size: 18px;">Mua
                                    ngay</span>
                                <svg class="ml-3 size-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-center w-[600px]">
                            <img src="{{ asset('images/sony-banner.png') }}" alt="Sony PlayStation 5">
                        </div>
                    </div>
                    <!-- Slide 4 -->
                    <div class="slide bg-black h-[400px] flex items-center p-5">
                        <div class="text-star w-[400px]">
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('images/dell.png') }}" alt="Dell" class="mr-2"
                                    style="width: 40px; height: 49px;">
                                <span class="text-white text-[16px]">Dell XPS 13</span>
                            </div>
                            <h1 class="text-[48px] font-bold text-white w-[295px]">Ưu Đãi Lên Đến 25%</h1>
                            <button class="mt-4 text-white buy-now-btn flex items-center">
                                <span style="border-bottom: 1px solid #fff; padding-bottom: 5px; font-size: 18px;">Mua
                                    ngay</span>
                                <svg class="ml-3 size-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-center w-[600px]">
                            <img src="{{ asset('images/dell-banner.png') }}" alt="Dell XPS 13">
                        </div>
                    </div>
                    <!-- Slide 5 -->
                    <div class="slide bg-black h-[400px] flex items-center p-5">
                        <div class="text-star w-[400px]">
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('images/asus.png') }}" alt="Asus" class="mr-2"
                                    style="width: 40px; height: 49px;">
                                <span class="text-white text-[16px]">Asus ROG Gaming</span>
                            </div>
                            <h1 class="text-[48px] font-bold text-white w-[295px]">Giảm Giá 30%</h1>
                            <button class="mt-4 text-white buy-now-btn flex items-center">
                                <span style="border-bottom: 1px solid #fff; padding-bottom: 5px; font-size: 18px;">Mua
                                    ngay</span>
                                <svg class="ml-3 size-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-center w-[600px]">
                            <img src="{{ asset('images/asus-banner.png') }}" alt="Asus ROG Gaming">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center gap-3 mt-4 absolute bottom-3 left-0 right-0">
                <button style="background-color: #BDBDBD;"
                    class="pagination-button p-2 w-[20px] h-[20px] rounded-full active" data-index="0"></button>
                <button style="background-color: #BDBDBD;" class="pagination-button p-2 w-[20px] h-[20px] rounded-full"
                    data-index="1"></button>
                <button style="background-color: #BDBDBD;" class="pagination-button p-2 w-[20px] h-[20px] rounded-full"
                    data-index="2"></button>
                <button style="background-color: #BDBDBD;" class="pagination-button p-2 w-[20px] h-[20px] rounded-full"
                    data-index="3"></button>
                <button style="background-color: #BDBDBD;" class="pagination-button p-2 w-[20px] h-[20px] rounded-full"
                    data-index="4"></button>
            </div>
        </div>
    </section>

    <!-- Khuyến mãi with Countdown Timer -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex gap-3 items-center mb-3">
            <div style="background-color: #BDBDBD;" class="rounded h-[45px] w-[20px]"></div>
            <span style="color: #BDBDBD;" class="font-bold">Hôm nay</span>
        </div>
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center justify-center">
                <h2 class="text-[36px] font-bold mr-[87px]">Khuyến mãi</h2>
                <div class="flex space-x-4 text-lg">
                    <div class="">
                        <p class="text-timer px-2 py-1">Ngày</p>
                        <span id="days" class="text-timer-number px-2 py-1 rounded">00</span>
                    </div>
                    <div class="">
                        <p class="text-timer px-2 py-1">Giờ</p>
                        <span id="hours" class="text-timer-number px-2 py-1 rounded">00</span>
                    </div>
                    <div class="">
                        <p class="text-timer px-2 py-1">Phút</p>
                        <span id="minutes" class="text-timer-number px-2 py-1 rounded">00</span>
                    </div>
                    <div class="">
                        <p class="text-timer px-2 py-1">Giây</p>
                        <span id="seconds" class="text-timer-number px-2 py-1 rounded">00</span>
                    </div>
                </div>
            </div>
            {{-- Button slide --}}
            <div class="flex items-center gap-2">
                <button style="background-color: #F5F5F5;"
                    class="prev-slide p-2 w-[46px] h-[46px] rounded-full flex items-center justify-center"
                    data-index="1"><i class="fa-solid fa-arrow-left text-[20px]"></i></button>
                <button style="background-color: #F5F5F5;"
                    class="next-slide p-2 w-[46px] h-[46px] rounded-full flex items-center justify-center"
                    data-index="1"><i class="fa-solid fa-arrow-right text-[20px]"></i></button>
            </div>
        </div>
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded shadow product-card">
                <div class="h-48 bg-gray-200"></div>
                <h3 class="mt-2">Tay cầm PS5</h3>
                <p class="text-gray-700">$40.00</p>
                <div class="flex space-x-1 mt-1">★★★★★</div>
            </div>
            <div class="bg-white p-4 rounded shadow product-card">
                <div class="h-48 bg-gray-200"></div>
                <h3 class="mt-2">Bàn phím gaming</h3>
                <p class="text-gray-700">$80.00</p>
                <div class="flex space-x-1 mt-1">★★★★★</div>
            </div>
            <div class="bg-white p-4 rounded shadow product-card">
                <div class="h-48 bg-gray-200"></div>
                <h3 class="mt-2">Màn hình gaming</h3>
                <p class="text-gray-700">$150.00</p>
                <div class="flex space-x-1 mt-1">★★★★★</div>
            </div>
            <div class="bg-white p-4 rounded shadow product-card">
                <div class="h-48 bg-gray-200"></div>
                <h3 class="mt-2">Ghế gaming</h3>
                <p class="text-gray-700">$200.00</p>
                <div class="flex space-x-1 mt-1">★★★★★</div>
            </div>
        </div>
    </section>

    <!-- Danh mục sản phẩm -->
    <section class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">DANH MỤC SẢN PHẨM</h2>
        <div class="flex justify-around">
            <div class="text-center">
                <div class="h-16 w-16 bg-gray-200 rounded-full mx-auto"></div>
                <p>Điện thoại</p>
            </div>
            <div class="text-center">
                <div class="h-16 w-16 bg-gray-200 rounded-full mx-auto"></div>
                <p>Máy tính</p>
            </div>
            <div class="text-center">
                <div class="h-16 w-16 bg-gray-200 rounded-full mx-auto"></div>
                <p>Phụ kiện</p>
            </div>
            <div class="text-center">
                <div class="h-16 w-16 bg-gray-200 rounded-full mx-auto"></div>
                <p>Máy ảnh</p>
            </div>
            <div class="text-center">
                <div class="h-16 w-16 bg-gray-200 rounded-full mx-auto"></div>
                <p>Tổng hợp</p>
            </div>
            <div class="text-center">
                <div class="h-16 w-16 bg-gray-200 rounded-full mx-auto"></div>
                <p>Gaming</p>
            </div>
        </div>
    </section>

    <!-- Sản phẩm bán chạy -->
    <section class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">SẢN PHẨM BÁN CHẠY</h2>
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded shadow product-card">
                <div class="h-48 bg-gray-200"></div>
                <h3 class="mt-2">Áo khoác</h3>
                <p class="text-gray-700">$50.00</p>
                <div class="flex space-x-1 mt-1">★★★★★</div>
            </div>
            <div class="bg-white p-4 rounded shadow product-card">
                <div class="h-48 bg-gray-200"></div>
                <h3 class="mt-2">Túi xách Gucci</h3>
                <p class="text-gray-700">$300.00</p>
                <div class="flex space-x-1 mt-1">★★★★★</div>
            </div>
            <div class="bg-white p-4 rounded shadow product-card">
                <div class="h-48 bg-gray-200"></div>
                <h3 class="mt-2">Máy làm mát</h3>
                <p class="text-gray-700">$120.00</p>
                <div class="flex space-x-1 mt-1">★★★★★</div>
            </div>
            <div class="bg-white p-4 rounded shadow product-card">
                <div class="h-48 bg-gray-200"></div>
                <h3 class="mt-2">Kệ sách nhỏ</h3>
                <p class="text-gray-700">$40.00</p>
                <div class="flex space-x-1 mt-1">★★★★★</div>
            </div>
        </div>
    </section>

    <!-- Banner phụ -->
    <section class="container mx-auto px-4 py-8">
        <div class="bg-gray-800 h-64 flex items-center justify-center text-white">
            <div class="text-center">
                <h2 class="text-3xl font-bold">NĂNG CAO TRẢI NGHIỆM ÂM THANH</h2>
                <button class="mt-4 px-6 py-2 bg-green-500 text-white rounded buy-now-btn">MUA NGAY</button>
            </div>
        </div>
    </section>

    <!-- Khám phá sản phẩm -->
    <section class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">KHÁM PHÁ SẢN PHẨM CỦA CHÚNG TÔI</h2>
        <div class="grid grid-cols-5 gap-4">
            <div class="bg-white p-4 rounded shadow product-card">
                <div class="h-48 bg-gray-200"></div>
                <h3 class="mt-2">Thức ăn chó</h3>
                <p class="text-gray-700">$20.00</p>
                <div class="flex space-x-1 mt-1">★★★★★</div>
            </div>
            <div class="bg-white p-4 rounded shadow product-card">
                <div class="h-48 bg-gray-200"></div>
                <h3 class="mt-2">Máy ảnh Canon</h3>
                <p class="text-gray-700">$500.00</p>
                <div class="flex space-x-1 mt-1">★★★★★</div>
            </div>
            <div class="bg-white p-4 rounded shadow product-card">
                <div class="h-48 bg-gray-200"></div>
                <h3 class="mt-2">Laptop ASUS</h3>
                <p class="text-gray-700">$800.00</p>
                <div class="flex space-x-1 mt-1">★★★★★</div>
            </div>
            <div class="bg-white p-4 rounded shadow product-card">
                <div class="h-48 bg-gray-200"></div>
                <h3 class="mt-2">Nước hoa</h3>
                <p class="text-gray-700">$100.00</p>
                <div class="flex space-x-1 mt-1">★★★★★</div>
            </div>
            <div class="bg-white p-4 rounded shadow product-card">
                <div class="h-48 bg-gray-200"></div>
                <h3 class="mt-2">Xe điện trẻ em</h3>
                <p class="text-gray-700">$150.00</p>
                <div class="flex space-x-1 mt-1">★★★★★</div>
            </div>
        </div>
    </section>

    <!-- Hàng mới về -->
    <section class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-4">HÀNG MỚI VỀ</h2>
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-gray-200 h-64 flex items-center justify-center">
                <div class="text-center">
                    <h3 class="text-xl font-bold">PlayStation 5</h3>
                    <button class="mt-2 px-4 py-2 bg-green-500 text-white rounded buy-now-btn">MUA NGAY</button>
                </div>
            </div>
            <div class="bg-gray-200 h-64 flex items-center justify-center">
                <div class="text-center">
                    <h3 class="text-xl font-bold">Loa thông minh</h3>
                    <button class="mt-2 px-4 py-2 bg-green-500 text-white rounded buy-now-btn">MUA NGAY</button>
                </div>
            </div>
            <div class="bg-gray-200 h-64 flex items-center justify-center">
                <div class="text-center">
                    <h3 class="text-xl font-bold">Nước hoa Gucci</h3>
                    <button class="mt-2 px-4 py-2 bg-green-500 text-white rounded buy-now-btn">MUA NGAY</button>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript -->
    <script src="{{ asset('js/home.js') }}"></script>
@endsection
