<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Đăng ký - Exclusive</title>
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
<div class="bg-black text-white text-sm py-2 px-4 flex flex-col md:flex-row justify-between items-center text-center md:text-left">
  <div>
    SUMMER SALE FOR ALL SWIM SUITS AND FREE EXPRESS DELIVERY - OFF 50%!
    <a href="#" class="underline ml-2">ShopNow</a>
  </div>
  <select class="bg-black text-white border-none outline-none mt-2 md:mt-0">
    <option>English</option>
    <option selected>Tiếng Việt</option>
  </select>
</div>

<!-- Header -->
<header class="bg-white shadow-md py-4 px-4 md:px-6 flex flex-col md:flex-row justify-between items-center">
  <h1 class="text-2xl font-bold text-black">EXCLUSIVE</h1>
  <nav class="space-x-4 text-gray-600 my-2 md:my-0">
    <a href="#" class="hover:text-blue-600">Trang chủ</a>
    <a href="#" class="hover:text-blue-600">Liên hệ</a>
    <a href="#" class="hover:text-blue-600">Về chúng tôi</a>
    <a href="#" class="hover:text-blue-600">Đăng ký</a>
  </nav>
  <div class="relative w-full max-w-md hidden md:block">
    <input type="text" placeholder="Bạn muốn tìm kiếm gì?" class="w-full border rounded px-3 py-1 pl-10 focus:ring-2 focus:ring-blue-400" />
    <svg class="w-5 h-5 text-gray-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
  </div>
</header>

<!-- Main đăng ký -->
<main class="flex items-center justify-center min-h-screen bg-gray-100 px-4">
  <div class="flex flex-col md:flex-row w-full max-w-7xl shadow-2xl rounded-2xl overflow-hidden">
    <!-- Hình ảnh bên trái -->
    <div class="w-full md:w-1/2 bg-cover bg-center min-h-[300px] md:min-h-[600px]" style="background-image: url('https://e-commerce-website-muzaffar-ali.vercel.app/_next/image?url=%2Fimages%2Fsignup%2Fmobile.png&w=828&q=75');">
    </div>

    <!-- Form bên phải -->
    <div class="w-full md:w-1/2 bg-white p-6 md:p-16">
      <h2 class="text-3xl md:text-4xl font-bold mb-4">Tạo một tài khoản</h2>
      <p class="text-gray-600 mb-8 text-base md:text-lg">Nhập thông tin của bạn bên dưới</p>
      <form>
        <div class="mb-6">
          <input type="text" placeholder="Tên" class="text-base md:text-lg w-full border rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
        </div>
        <div class="mb-6">
          <input type="email" placeholder="Email hoặc số điện thoại" class="text-base md:text-lg w-full border rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
        </div>
        <div class="mb-8">
          <input type="password" placeholder="Mật khẩu" class="text-base md:text-lg w-full border rounded px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400" />
        </div>

        <!-- Nút -->
        <button type="submit" class="w-full bg-black hover:bg-gray-800 text-white py-3 text-base md:text-lg rounded">Tạo tài khoản</button>
        <button type="button" class="w-full flex items-center justify-center border border-gray-300 bg-white hover:bg-gray-100 text-black py-3 text-base md:text-lg rounded mt-4">
          <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google icon" class="h-5 w-5 mr-2">
          Đăng ký với Google
        </button>

        <div class="flex justify-between mt-6 text-sm flex-wrap gap-2">
          <a href="#" class="text-gray-600 hover:underline">Đã có tài khoản?</a>
          <a href="/login" class="text-gray-600 hover:underline">Đăng nhập</a>
        </div>
      </form>
    </div>
  </div>
</main>

<!-- Footer -->
<footer class="bg-black text-white pt-10 pb-6 px-6 mt-12">
  <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
    <!-- Cột 1 -->
    <div>
      <h3 class="text-lg font-bold mb-4">Exclusive</h3>
      <p class="mb-2">Đăng ký</p>
      <p class="mb-4">Giảm giá 10% cho đơn hàng đầu tiên</p>
      <div class="flex items-center border border-white rounded px-3 py-2">
        <input type="email" placeholder="Nhập email của bạn" class="bg-black text-white placeholder-white focus:outline-none w-full" />
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="white">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
        </svg>
      </div>
    </div>

    <!-- Các cột khác -->
    <div>
      <h3 class="text-lg font-bold mb-4">Hỗ trợ</h3>
      <p>403 Quang Trung,</p>
      <p>Buôn Ma Thuột,</p>
      <p>Daklak</p>
      <p class="mt-4">exclusive@gmail.com</p>
      <p>0915571415</p>
    </div>
    <div>
      <h3 class="text-lg font-bold mb-4">Tài khoản</h3>
      <p>Tài khoản của tôi</p>
      <p>Đăng nhập/Đăng ký</p>
      <p>Giỏ hàng</p>
      <p>Danh sách ước</p>
      <p>Cửa hàng</p>
    </div>
    <div>
      <h3 class="text-lg font-bold mb-4">Liên kết nhanh</h3>
      <p>Chính sách bảo mật</p>
      <p>Điều khoản sử dụng</p>
      <p>Câu hỏi thường gặp</p>
      <p>Liên hệ</p>
    </div>
    <div>
      <h3 class="text-lg font-bold mb-4">Tải App</h3>
      <p class="text-sm">Tiết kiệm $3 ứng dụng dành cho người dùng mới</p>
      <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=https://example.com" alt="QR Code" class="my-2 w-20 h-20">
      <div class="flex space-x-2 mt-2">
        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" class="h-10" alt="Google Play">
        <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" class="h-10" alt="App Store">
      </div>
      <div class="flex space-x-4 mt-4 text-white">
        <a href="#">f</a>
        <a href="#">t</a>
        <a href="#">i</a>
        <a href="#">in</a>
      </div>
    </div>
  </div>

  <p class="text-center mt-8 text-gray-500 text-sm">
    © Copyright Rimel 2022. All right reserved
  </p>
</footer>

</body>
</html>
