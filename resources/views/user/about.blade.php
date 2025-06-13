@extends('layouts.app')

@section('title', 'Về chúng tôi')
@section('content')
    <div class="container mx-auto px-0 relative">
        <!-- breadcrumb -->
        <div class="flex flex-wrap items-center gap-2 b-10 px-[10px] sm:px-0 md:mb-20 text-sm md:text-base">
            <a href="{{ route('home') }}" class="text-gray-500 hover:underline">Trang chủ</a>
            <span>/</span>
            <span>Về chúng tôi</span>
        </div>

        <!-- about section -->
        <div class="flex flex-col lg:flex-row justify-center items-center mb-[240px]">
            <!-- About Info -->
            <div class="w-full md:w-1/2 lg:w-1/2 bg-white rounded-lg mb-[40px] px-[10px] sm:px-0 sm:pr-[100px]">
                <!-- Description -->
                <div class="flex flex-col gap-[40px]">
                    <h3 class="text-[40px] font-semibold sm:text-[65px]">Câu chuyện của chúng tôi</h3>
                    <div class="flex flex-col gap-[40px] text-[20px] text-justify sm:text-normal">
                        <p>
                            Ra mắt vào năm 2015, Exclusive là trang mua sắm trực tuyến hàng đầu Nam Á với sự hiện diện
                            tích cực tại Bangladesh. Được hỗ trợ bởi nhiều giải pháp tiếp thị, dữ liệu và dịch vụ được thiết
                            kế
                            riêng,
                            Exclusive có 10.500 nhân viên bán hàng và 300 thương hiệu và phục vụ 3 triệu khách hàng trên
                            khắp
                            khu vực.
                        </p>
                        <p>
                            Exclusive cung cấp hơn 1 triệu sản phẩm, tăng trưởng rất nhanh. Exclusive cung cấp
                            nhiều loại sản phẩm trong các danh mục từ người tiêu dùng.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Image About -->
            <div class="w-full lg:w-1/2 bg-white rounded-lg px-[10px] sm:px-0">
                <img src="{{ asset('images/about.png') }}" alt="About"
                    class="w-[837px] md:h-[709px] sm:absolute sm:left-[883px] sm:top-[0px]">
            </div>
        </div>

        

        <!-- Service section -->
        <div class="flex flex-col lg:flex-row justify-center items-center gap-[112px] mb-[140px]">
            <div
                class="flex flex-col justify-center items-center gap-[12px] border border-gray-300 w-[300px] px-[30px] py-[20px] rounded-[4px]">
                <div class="flex items-center justify-center gap-[12px] bg-[#C1C1C1] rounded-full p-2 w-[80px] h-[80px]">
                    <i
                        class="fa-solid fa-store bg-[#000] rounded-full p-2 text-white w-[58px] h-[58px] flex items-center justify-center text-[26px]">
                    </i>
                </div>
                <h3 class="text-[28px] font-bold">10.5k</h3>
                <p class="text-[18px] text-justify sm:text-center">
                    Người bán hàng đang hoạt động trang web
                </p>
            </div>
            <div
                class="flex flex-col justify-center items-center gap-[12px] border border-gray-300 w-[300px] px-[30px] py-[20px] rounded-[4px]">
                <div class="flex items-center justify-center gap-[12px] bg-[#C1C1C1] rounded-full p-2 w-[80px] h-[80px]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="bg-[#000] rounded-full p-2 text-white w-[58px] h-[58px] flex items-center justify-center text-[26px]">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <h3 class="text-[28px] font-bold">10.5k</h3>
                <p class="text-[18px] text-justify sm:text-center">
                    Người bán hàng đang hoạt động trang web
                </p>
            </div>
            <div
                class="flex flex-col justify-center items-center gap-[12px] border border-gray-300 w-[300px] px-[30px] py-[20px] rounded-[4px]">
                <div class="flex items-center justify-center gap-[12px] bg-[#C1C1C1] rounded-full p-2 w-[80px] h-[80px]">
                    <i
                        class="fa-solid fa-bag-shopping bg-[#000] rounded-full p-2 text-white w-[58px] h-[58px] flex items-center justify-center text-[26px]">
                    </i>
                </div>
                <h3 class="text-[28px] font-bold">10.5k</h3>
                <p class="text-[18px] text-justify sm:text-center">
                    Người bán hàng đang hoạt động trang web
                </p>
            </div>
            <div
                class="flex flex-col justify-center items-center gap-[12px] border border-gray-300 w-[300px] px-[30px] py-[20px] rounded-[4px]">
                <div class="flex items-center justify-center gap-[12px] bg-[#C1C1C1] rounded-full p-2 w-[80px] h-[80px]">
                    <i
                        class="fa-solid fa-sack-dollar bg-[#000] rounded-full p-2 text-white w-[58px] h-[58px] flex items-center justify-center text-[26px]">
                    </i>
                </div>
                <h3 class="text-[28px] font-bold">10.5k</h3>
                <p class="text-[18px] text-justify sm:text-center">
                    Người bán hàng đang hoạt động trang web
                </p>
            </div>
        </div>

        <!-- Our Team Slider -->
        <div class="swiper myTeamSwiper mb-[140px] flex">
            <div class="swiper-wrapper mb-[40px]">
                <!-- Slide 1 -->
                <div class="swiper-slide">
                    <div class="flex flex-col gap-[32px] bg-white rounded-lg px-[10px] sm:px-0">
                        <div class="bg-[#F5F5F5] p-[67px] pt-[39px] pb-0 flex items-end justify-center">
                            <img src="{{ asset('images/image46.png') }}" alt="About" />
                        </div>
                        <div class="flex flex-col gap-[6px] p-4">
                            <h3 class="text-[23px] font-semibold sm:text-[32px]">Y Khoa Êban</h3>
                            <p class="text-[18px]">Founder & Chairman</p>
                        </div>
                    </div>
                </div>
                <!-- Slide 2 -->
                <div class="swiper-slide">
                    <div class="flex flex-col gap-[32px] bg-white rounded-lg px-[10px] sm:px-0">
                        <div class="bg-[#F5F5F5] p-[67px] pt-[39px] pb-0 flex items-end justify-center">
                            <img src="{{ asset('images/image51.png') }}" alt="About" />
                        </div>
                        <div class="flex flex-col gap-[6px] p-4">
                            <h3 class="text-[23px] font-semibold sm:text-[32px]">Y Khoa Êban</h3>
                            <p class="text-[18px]">Founder & Chairman</p>
                        </div>
                    </div>
                </div>
                <!-- Slide 3 -->
                <div class="swiper-slide">
                    <div class="flex flex-col gap-[32px] bg-white rounded-lg px-[10px] sm:px-0">
                        <div class="bg-[#F5F5F5] p-[67px] pt-[39px] pb-0 flex items-end justify-center">
                            <img src="{{ asset('images/image47.png') }}" alt="About" />
                        </div>
                        <div class="flex flex-col gap-[6px] p-4">
                            <h3 class="text-[23px] font-semibold sm:text-[32px]">Y Khoa Êban</h3>
                            <p class="text-[18px]">Founder & Chairman</p>
                        </div>
                    </div>
                </div>
                <!-- Slide 1 -->
                <div class="swiper-slide">
                    <div class="flex flex-col gap-[32px] bg-white rounded-lg px-[10px] sm:px-0">
                        <div class="bg-[#F5F5F5] p-[67px] pt-[39px] pb-0 flex items-end justify-center">
                            <img src="{{ asset('images/image46.png') }}" alt="About" />
                        </div>
                        <div class="flex flex-col gap-[6px] p-4">
                            <h3 class="text-[23px] font-semibold sm:text-[32px]">Y Khoa Êban</h3>
                            <p class="text-[18px]">Founder & Chairman</p>
                        </div>
                    </div>
                </div>
                <!-- Slide 2 -->
                <div class="swiper-slide">
                    <div class="flex flex-col gap-[32px] bg-white rounded-lg px-[10px] sm:px-0">
                        <div class="bg-[#F5F5F5] p-[67px] pt-[39px] pb-0 flex items-end justify-center">
                            <img src="{{ asset('images/image51.png') }}" alt="About" />
                        </div>
                        <div class="flex flex-col gap-[6px] p-4">
                            <h3 class="text-[23px] font-semibold sm:text-[32px]">Y Khoa Êban</h3>
                            <p class="text-[18px]">Founder & Chairman</p>
                        </div>
                    </div>
                </div>
                <!-- Slide 3 -->
                <div class="swiper-slide">
                    <div class="flex flex-col gap-[32px] bg-white rounded-lg px-[10px] sm:px-0">
                        <div class="bg-[#F5F5F5] p-[67px] pt-[39px] pb-0 flex items-end justify-center">
                            <img src="{{ asset('images/image47.png') }}" alt="About" />
                        </div>
                        <div class="flex flex-col gap-[6px] p-4">
                            <h3 class="text-[23px] font-semibold sm:text-[32px]">Y Khoa Êban</h3>
                            <p class="text-[18px]">Founder & Chairman</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination (tùy chọn) -->
            <div class="swiper-pagination">
                <div class="swiper-pagination-bullet"></div>
            </div>
        </div>


        <section class="container mx-auto px-4 py-8">
            <div class="flex flex-col sm:flex md:flex-row gap-3 items-center gap-[88px]">
                <div class="flex flex-col items-center justify-center gap-[30px] w-full sm:w-1/3">
                    <div class="bg-[#C1C1C1] rounded-full flex items-center justify-center w-[80px] h-[80px]">
                        <div class="bg-[#000] rounded-full flex items-center justify-center w-[58px] h-[58px]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                class="bi bi-truck text-center text-[30px] text-[#fff] w-[30px]" viewBox="0 0 16 16">
                                <path
                                    d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col gap-[10px] text-center">
                        <h2 class="text-[24px] font-bold">Giao hàng miễn phí và nhanh chóng</h2>
                        <p class="text-[14px]">Miễn phí vận chuyển cho đơn hàng trên 1 trăm nghìn đồng</p>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-center gap-[30px] w-full sm:w-1/3">
                    <div class="bg-[#C1C1C1] rounded-full flex items-center justify-center w-[80px] h-[80px]">
                        <div class="bg-[#000] rounded-full flex items-center justify-center w-[58px] h-[58px]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                class="bi bi-headset text-center text-[30px] text-[#fff] w-[30px]" viewBox="0 0 16 16">
                                <path
                                    d="M8 1a5 5 0 0 0-5 5v1h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a6 6 0 1 1 12 0v6a2.5 2.5 0 0 1-2.5 2.5H9.366a1 1 0 0 1-.866.5h-1a1 1 0 1 1 0-2h1a1 1 0 0 1 .866.5H11.5A1.5 1.5 0 0 0 13 12h-1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h1V6a5 5 0 0 0-5-5" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col gap-[10px] text-center">
                        <h2 class="text-[24px] font-bold">Chăm sóc khách hàng 24/7</h2>
                        <p class="text-[14px]">Hỗ trợ khách hàng thân thiện 24/7</p>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-center gap-[30px] w-full sm:w-1/3">
                    <div class="bg-[#C1C1C1] rounded-full flex items-center justify-center w-[80px] h-[80px]">
                        <div class="bg-[#000] rounded-full flex items-center justify-center w-[58px] h-[58px]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                class="bi bi-shield-check text-center text-[30px] text-[#fff] w-[30px]"
                                viewBox="0 0 16 16">
                                <path
                                    d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56" />
                                <path
                                    d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col gap-[10px] text-center">
                        <h2 class="text-[24px] font-bold">Đảm bảo hoàn tiền</h2>
                        <p class="text-[14px]">Đảm bảo hoàn tiền trong 30 ngày</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper(".myTeamSwiper", {
            loop: true,
            spaceBetween: 30,
            slidesPerView: 1,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                768: {
                    slidesPerView: 3,
                },
            },
        });
    </script>


@endsection
