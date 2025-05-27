<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Exclusive</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-image {
            background-image: url('https://images.unsplash.com/photo-1591337676887-a217a3fca0ed?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="font-sans text-gray-800">

    <!-- Top Banner -->
    <div class="bg-black text-white text-sm py-2 px-4 flex justify-between items-center">
        <div>
            SUMMER SALE FOR ALL SWIM SUITS AND FREE EXPRESS DELIVERY - OFF 50%!
            <a href="#" class="underline ml-2">ShopNow</a>
        </div>
        <select class="bg-black text-white border-none outline-none">
            <option>English</option>
            <option selected>Tiếng Việt</option>
        </select>
    </div>

    <!-- Header -->
    <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-blue-700">EXCLUSIVE</h1>
        <nav class="space-x-4 text-gray-600">
            <a href="#" class="hover:text-blue-600">Trang chủ</a>
            <a href="#" class="hover:text-blue-600">Liên hệ</a>
            <a href="#" class="hover:text-blue-600">Về chúng tôi</a>
            <a href="#" class="hover:text-blue-600">Đăng ký</a>
        </nav>
        <div class="relative w-64 hidden md:block">
            <input type="text" placeholder="Bạn muốn tìm kiếm gì?" class="w-full border rounded px-3 py-1 pl-10 focus:ring-2 focus:ring-blue-400">
            <svg class="w-5 h-5 text-gray-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </header>

<main class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="flex w-full max-w-5xl shadow-lg rounded-lg overflow-hidden">
        <!-- Left Side with full background image -->
        <div class="w-1/2 bg-cover bg-center" style="background-image: url('https://e-commerce-website-muzaffar-ali.vercel.app/_next/image?url=%2Fimages%2Fsignup%2Fmobile.png&w=828&q=75');">
        </div>

        <!-- Right Side (Form) -->
        <div class="w-1/2 bg-white p-10">
            <h2 class="text-3xl font-bold mb-2">Tạo một tài khoản</h2>
            <p class="text-gray-600 mb-6">Nhập thông tin của bạn bên dưới</p>
            <form>
                <div class="mb-4">
                    <input type="text" placeholder="Tên" class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <input type="email" placeholder="Email hoặc số điện thoại" class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <input type="password" placeholder="Mật khẩu" class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-black hover:bg-gray-800 text-white py-2 rounded">Tạo tài khoản</button>

                <!-- Google Sign Up Button -->
                <button type="button" class="w-full flex items-center justify-center border border-gray-300 bg-white hover:bg-gray-100 text-black py-2 rounded mt-4">
                    <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google icon" class="h-5 w-5 mr-2">
                    Đăng ký với Google
                </button>

                <div class="flex justify-between mt-4 text-sm">
                    <a href="#" class="text-gray-600 hover:underline">Đã có tài khoản ?</a>
                    <a href="\login" class="text-gray-600 hover:underline">Đăng nhập</a>
                </div>
            </form>
        </div>
    </div>
</main>




    <!-- Footer -->
    <footer class="bg-black text-white pt-10 pb-6 px-6 mt-12">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-5 gap-8">
        <!-- Cột 1 -->
        <div>
            <h3 class="text-lg font-bold mb-4">Exclusive</h3>
            <p class="mb-2">Đăng ký</p>
            <p class="mb-4">Giảm giá 10% cho đơn hàng đầu tiên</p>
            <div class="flex items-center border border-white rounded px-3 py-2">
                <input type="email" placeholder="Nhập email của bạn" class="bg-black text-white placeholder-white focus:outline-none w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="white">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </div>
        </div>

        <!-- Cột 2 -->
        <div>
            <h3 class="text-lg font-bold mb-4">Hỗ trợ</h3>
            <p>403 Quang Trung,</p>
            <p>Buôn Ma Thuột,</p>
            <p>Daklak</p>
            <p class="mt-4">exclusive@gmail.com</p>
            <p>0915571415</p>
        </div>

        <!-- Cột 3 -->
        <div>
            <h3 class="text-lg font-bold mb-4">Tài khoản</h3>
            <p>Tài khoản của tôi</p>
            <p>Đăng nhập/Đăng ký</p>
            <p>Giỏ hàng</p>
            <p>Danh sách ước</p>
            <p>Cửa hàng</p>
        </div>

        <!-- Cột 4 -->
        <div>
            <h3 class="text-lg font-bold mb-4">Liên kết nhanh</h3>
            <p>Chính sách bảo mật</p>
            <p>Điều khoản sử dụng</p>
            <p>Câu hỏi thường gặp</p>
            <p>Liên hệ</p>
        </div>

        <!-- Cột 5 -->
        <div>
            <h3 class="text-lg font-bold mb-4">Tải App</h3>
            <p class="text-sm">Tiết kiệm $3 ứng dụng dành cho người dùng mới</p>
            <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=https://example.com" alt="QR Code" class="my-2 w-20 h-20">
            <div class="flex space-x-2 mt-2">
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" class="h-10" alt="Google Play">
                <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" class="h-10" alt="App Store">
            </div>
            <div class="flex space-x-4 mt-4 text-white">
                <a href="#"><i class="fab fa-facebook-f">f</i></a>
                <a href="#"><i class="fab fa-twitter">t</i></a>
                <a href="#"><i class="fab fa-instagram">i</i></a>
                <a href="#"><i class="fab fa-linkedin-in">in</i></a>
            </div>
        </div>
    </div>

    <p class="text-center mt-8 text-gray-500 text-sm">
        © Copyright Rimel 2022. All right reserved
    </p>
</footer>

</body>
</html>
