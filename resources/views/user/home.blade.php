@extends('layouts.app')

@section('content')
    <!-- Main Banner -->
    <section class="container mx-auto px-4 py-8 flex">
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
        <div class="w-3/4 bg-gray-200 h-96 flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-4xl font-bold">iPhone 14 Giảm Giá Lên Đến 10%</h1>
                <button class="mt-4 px-6 py-2 bg-green-500 text-white rounded buy-now-btn">MUA NGAY</button>
            </div>
        </div>
    </section>

    <!-- Khuyến mãi with Countdown Timer -->
    <section class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">KHUYẾN MÃI</h2>
            <div class="flex space-x-2 text-lg">
                <span id="days" class="bg-gray-200 px-2 py-1 rounded">00</span>
                <span id="hours" class="bg-gray-200 px-2 py-1 rounded">00</span>
                <span id="minutes" class="bg-gray-200 px-2 py-1 rounded">00</span>
                <span id="seconds" class="bg-gray-200 px-2 py-1 rounded">00</span>
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
    <script>
        // Dropdown Toggle with Arrow Rotation
        const dropdownToggle = document.getElementById('dropdownToggle');
        const dropdownToggleSecond = document.getElementById('dropdownToggleSecond');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownMenuSecond = document.getElementById('dropdownMenuSecond');
        const arrowIcon = document.querySelector('.arrow-icon');
        const arrowIconSecond = document.querySelector('.arrow-icon-second');

        dropdownToggle.addEventListener('click', () => {
            dropdownMenu.classList.toggle('show');
            arrowIcon.classList.toggle('rotate');
        });
        
        dropdownToggleSecond.addEventListener('click', () => {
            dropdownMenuSecond.classList.toggle('show');
            arrowIconSecond.classList.toggle('rotate');
        });


        // Countdown Timer
        const countdown = () => {
            const endDate = new Date();
            endDate.setDate(endDate.getDate() + 4); // Đặt thời gian kết thúc sau 4 ngày
            const daysEl = document.getElementById('days');
            const hoursEl = document.getElementById('hours');
            const minutesEl = document.getElementById('minutes');
            const secondsEl = document.getElementById('seconds');

            const updateTimer = () => {
                const now = new Date().getTime();
                const distance = endDate.getTime() - now;

                if (distance < 0) {
                    clearInterval(timerInterval);
                    daysEl.textContent = '00';
                    hoursEl.textContent = '00';
                    minutesEl.textContent = '00';
                    secondsEl.textContent = '00';
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                daysEl.textContent = String(days).padStart(2, '0');
                hoursEl.textContent = String(hours).padStart(2, '0');
                minutesEl.textContent = String(minutes).padStart(2, '0');
                secondsEl.textContent = String(seconds).padStart(2, '0');
            };

            updateTimer();
            const timerInterval = setInterval(updateTimer, 1000);
        };
        countdown();
    </script>
@endsection
