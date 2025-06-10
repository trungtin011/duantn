<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>Shopee Seller Registration</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
   /* Custom scrollbar for right vertical icons */
    ::-webkit-scrollbar {
      display: none;
    }
  </style>
 </head>
 <body class="bg-[#f5f5f7] min-h-screen flex flex-col">
  <!-- Top bar -->
  <header class="flex justify-between items-center bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8 h-12">
   <div class="flex items-center space-x-1">
    <img alt="Shopee logo, orange square with white S letter" class="w-5 h-5" height="20" src="https://storage.googleapis.com/a1aa/image/56011200-bb98-42ff-92be-38b7224af1e7.jpg" width="20"/>
    <span class="text-sm font-normal text-[#ee4d2d]">Zynox</span>
    <span class="text-sm font-normal text-black">Đăng ký trở thành Người bán Zynox</span>
   </div>
   <div class="flex items-center space-x-2 text-base text-gray-700 cursor-pointer select-none">
    @auth
      <img alt="User profile picture" class="w-10 h-10 rounded-full" height="40" width="40" src="{{ Auth::user()->avatar_url ?? 'https://storage.googleapis.com/a1aa/image/661de284-8af8-4675-a870-9b3a3edf56c5.jpg' }}" />
      <span class="font-semibold">{{ Auth::user()->fullname ?? Auth::user()->username }}</span>
    @else
      <img alt="User profile picture, grayscale circular photo" class="w-10 h-10 rounded-full" height="40" width="40" src="https://storage.googleapis.com/a1aa/image/661de284-8af8-4675-a870-9b3a3edf56c5.jpg" />
      <span>Khách</span>
    @endauth
    <i class="fas fa-chevron-down text-base"></i>
   </div>
  </header>
  <!-- Main content -->
  <main class="flex-grow flex justify-center items-center px-4 py-10">
   <div class="bg-white rounded-md shadow-sm max-w-2xl w-full p-12 flex flex-col items-center">
    <img alt="Illustration of a webpage with orange notification boxes and icons, circular background in light orange" class="mb-8" height="140" src="https://storage.googleapis.com/a1aa/image/89b54820-1ec5-4b42-a62c-497751536c8a.jpg" width="140"/>
    <h2 class="text-2xl font-semibold mb-3">Chào mừng đến với Zynox!</h2>
    <p class="text-base text-gray-500 mb-8 text-center max-w-md leading-tight">
      Vui lòng cung cấp thông tin để thành lập tài khoản người bán trên Zynox
    </p>
    <a href="{{ route('seller.register') }}" class="bg-[#ee4d2d] text-white text-base rounded px-6 py-2 hover:bg-[#d43f22] transition block text-center font-semibold">Bắt đầu đăng ký</a>
   </div>
  </main>
  <!-- Right vertical icons -->
  <aside class="fixed right-0 top-1/3 flex flex-col space-y-6 pr-2 z-10">
   <button aria-label="Notification bell" class="text-[#ee4d2d] hover:text-[#d43f22] transition text-lg" type="button">
    <i class="fas fa-bell"></i>
   </button>
   <button aria-label="Customer support headset" class="text-[#ee4d2d] hover:text-[#d43f22] transition text-lg" type="button">
    <i class="fas fa-headset"></i>
   </button>
   <button aria-label="Chat messages with 1 new message" class="relative text-[#ee4d2d] hover:text-[#d43f22] transition text-lg" type="button">
    <i class="fas fa-comment-alt"></i>
    <span class="absolute -top-2 -right-2 bg-[#ee4d2d] text-white text-[10px] font-semibold rounded-full w-4 h-4 flex items-center justify-center leading-none">1</span>
   </button>
  </aside>
  @php
    if (!auth()->check()) {
      header('Location: '.route('login'));
      exit;
    }
  @endphp
 </body>
</html>
