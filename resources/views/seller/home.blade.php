@extends('layouts.seller_home')
<<<<<<< HEAD
{{-- import css --}}
@push('css')
<link rel="stylesheet" href="{{ asset('css/seller/seller-home.css') }}">
@endpush
@section('title', 'Trang chủ')
=======
>>>>>>> b0148619be5e190167082d98a60eb2373fcec04a
@section('content')
<div class="flex min-h-[calc(100vh-40px)]">
  <main class="flex-1 p-4 space-y-6 overflow-y-auto">
    <section class="bg-white rounded-lg p-4 shadow-sm flex flex-col sm:flex-row justify-between text-center sm:text-left space-y-4 sm:space-y-0 sm:space-x-10">
      <h2 class="font-semibold text-lg w-full sm:w-auto sm:flex-shrink-0 sm:self-center">Danh sách cần làm</h2>
      <div class="flex justify-around sm:justify-start flex-1 space-x-10 text-gray-600 text-xs">
        <div>
          <div class="text-blue-600 font-semibold text-xl">0</div>
          <div>Chờ Lấy Hàng</div>
        </div>
        <div>
          <div class="text-blue-600 font-semibold text-xl">0</div>
          <div>Đã Xử Lý</div>
        </div>
        <div>
          <div class="text-blue-600 font-semibold text-xl">0</div>
          <div>Đơn Trả hàng/Hoàn tiền/Huỷ</div>
        </div>
        <div>
          <div class="text-blue-600 font-semibold text-xl">0</div>
          <div>Sản Phẩm Bị Tạm Khóa</div>
        </div>
      </div>
    </section>
    <section class="bg-white rounded-lg p-4 shadow-sm space-y-3">
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-lg">Phân Tích Bán Hàng</h2>
        <div class="text-xs text-gray-400 whitespace-nowrap">
          Hôm nay 00:00 GMT+7 18:00
          <span class="text-gray-300">(Dữ liệu thay đổi so với hôm qua)</span>
        </div>
        <a class="text-blue-600 text-xs font-normal hover:underline whitespace-nowrap" href="#">Xem thêm <i class="fas fa-chevron-right text-[10px]"></i></a>
      </div>
      <div class="grid grid-cols-5 text-center text-gray-600 text-xs font-normal border-t border-b border-gray-200 py-3">
        <div>
          <div class="flex justify-center items-center space-x-1"><span>Doanh số</span><i class="fas fa-question-circle text-[10px]"></i></div>
          <div class="font-semibold text-base mt-1">₫0</div>
          <div class="text-gray-400 text-[10px] mt-0.5">- 0,00%</div>
        </div>
        <div>
          <div class="flex justify-center items-center space-x-1"><span>Lượt truy cập</span><i class="fas fa-question-circle text-[10px]"></i></div>
          <div class="font-semibold text-base mt-1">0</div>
          <div class="text-gray-400 text-[10px] mt-0.5">- 0,00%</div>
        </div>
        <div>
          <div class="flex justify-center items-center space-x-1"><span>Lượt xem</span><i class="fas fa-question-circle text-[10px]"></i></div>
          <div class="font-semibold text-base mt-1">0</div>
          <div class="text-gray-400 text-[10px] mt-0.5">- 0,00%</div>
        </div>
        <div>
          <div class="flex justify-center items-center space-x-1"><span>Đơn hàng</span><i class="fas fa-question-circle text-[10px]"></i></div>
          <div class="font-semibold text-base mt-1">0</div>
          <div class="text-gray-400 text-[10px] mt-0.5">- 0,00%</div>
        </div>
        <div>
          <div class="flex justify-center items-center space-x-1"><span>Tỷ lệ chuyển đổi</span><i class="fas fa-question-circle text-[10px]"></i></div>
          <div class="font-semibold text-base mt-1">0,00%</div>
          <div class="text-gray-400 text-[10px] mt-0.5">- 0,00%</div>
        </div>
      </div>
    </section>
    <section class="bg-white rounded-lg p-4 shadow-sm space-y-3">
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-lg">Quảng cáo Shopee</h2>
        <a class="text-blue-600 text-xs font-normal hover:underline whitespace-nowrap" href="#">Xem thêm <i class="fas fa-chevron-right text-[10px]"></i></a>
      </div>
      <div class="border border-gray-200 rounded-md p-3 text-gray-600 text-xs relative" style="background-image: url('https://placehold.co/100x100/feeaea/feeaea?text='); background-repeat: no-repeat; background-position: right bottom; background-size: 100px 100px;">
        <div class="flex items-center space-x-2 mb-1">
          <img alt="Advertisement icon red circle with white AD letters" class="w-4 h-4" height="16" src="https://storage.googleapis.com/a1aa/image/edf41f8c-c956-422e-817c-1cfaae696afc.jpg" width="16"/>
          <span class="font-semibold text-gray-700">Tối đa hóa doanh số bán hàng của bạn với Quảng cáo Shopee!</span>
        </div>
        <p class="text-gray-400 leading-tight">Tìm hiểu thêm về Quảng cáo Shopee để tạo quảng cáo một cách hiệu quả và tối ưu chi phí quảng cáo.</p>
        <button class="absolute bottom-3 right-3 text-xs text-[#ff4d4f] border border-[#ff4d4f] rounded px-2 py-0.5 hover:bg-[#ff4d4f] hover:text-white transition-colors">Tìm hiểu thêm</button>
      </div>
    </section>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <section class="bg-white rounded-lg p-4 shadow-sm space-y-3 lg:col-span-2 min-h-[300px]">
        <div class="flex justify-between items-center">
          <h2 class="font-semibold text-lg">Tăng đơn cùng KOL</h2>
          <a class="text-blue-600 text-xs font-normal hover:underline whitespace-nowrap" href="#">Thêm <i class="fas fa-chevron-right text-[10px]"></i></a>
        </div>
        <div class="mt-6 flex justify-center">
          <svg class="animate-spin h-6 w-6 text-[#ff4d4f]" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" fill="currentColor"></path>
          </svg>
        </div>
      </section>
      <section aria-hidden="true" class="bg-white rounded-lg p-4 shadow-sm min-h-[300px] hidden lg:block">
        <div class="space-y-2">
          <div class="h-2 bg-gray-200 rounded w-5/6"></div>
          <div class="h-2 bg-gray-200 rounded w-full"></div>
          <div class="h-2 bg-gray-200 rounded w-4/6"></div>
          <div class="h-2 bg-gray-200 rounded w-3/4"></div>
          <div class="h-2 bg-gray-200 rounded w-2/3"></div>
          <div class="h-2 bg-gray-200 rounded w-5/6"></div>
          <div class="h-2 bg-gray-200 rounded w-4/6"></div>
          <div class="h-2 bg-gray-200 rounded w-3/4"></div>
          <div class="h-2 bg-gray-200 rounded w-2/3"></div>
          <div class="h-2 bg-gray-200 rounded w-5/6"></div>
        </div>
      </section>
    </div>
    <aside class="w-72 flex flex-col space-y-6">
      <section class="bg-white rounded-lg p-4 shadow-sm text-gray-700 text-sm" style="min-height: 100px">
        <h3 class="font-semibold text-base mb-2">Hiệu quả bán hàng</h3>
        <a class="text-blue-600 text-xs font-normal flex justify-between items-center hover:underline" href="#"><span>Xuất sắc</span><i class="fas fa-chevron-right text-[10px]"></i></a>
        <p class="text-gray-400 text-xs mt-1">Tất cả chỉ số đều tốt!</p>
      </section>
      <section class="bg-white rounded-lg p-4 shadow-sm text-gray-700 text-sm" style="min-height: 160px">
        <div class="flex justify-between items-center mb-2">
          <h3 class="font-semibold text-base">Tin Nổi Bật</h3>
          <a class="text-blue-600 text-xs font-normal hover:underline whitespace-nowrap" href="#">Xem thêm <i class="fas fa-chevron-right text-[10px]"></i></a>
        </div>
        <img alt="Red banner with text Buôn Hay Bán Giỏi and Shopee KOL Sellers with a cartoon character" class="rounded-md w-full object-cover" height="100" src="https://storage.googleapis.com/a1aa/image/768d6aed-a0c5-4d5a-3bd1-4a0943b9301d.jpg" width="300"/>
      </section>
      <section class="bg-white rounded-lg p-4 shadow-sm text-gray-700 text-sm" style="min-height: 320px">
        <div class="flex justify-between items-center mb-2">
          <h3 class="font-semibold text-base">Nhiệm Vụ Người Bán</h3>
          <a class="text-blue-600 text-xs font-normal hover:underline whitespace-nowrap" href="#">Xem thêm <i class="fas fa-chevron-right text-[10px]"></i></a>
        </div>
        <div class="space-y-4">
          <div class="border border-gray-200 rounded p-3">
            <h4 class="font-semibold text-xs mb-1">Hoàn thành khóa học Bắt đầu</h4>
            <div class="flex items-center space-x-2 text-xs text-gray-500 mb-2">
              <i class="fas fa-arrow-circle-up text-green-600"></i>
              <span>Nhận 5 lần Đẩy sản phẩm</span>
            </div>
            <button class="text-xs text-[#ff4d4f] border border-[#ff4d4f] rounded px-2 py-0.5 hover:bg-[#ff4d4f] hover:text-white transition-colors">Bắt đầu</button>
          </div>
          <div class="border border-gray-200 rounded p-3">
            <h4 class="font-semibold text-xs mb-1">Hoàn thành khóa học Bán hàng trên Shopee</h4>
            <div class="flex items-center space-x-2 text-xs text-gray-500 mb-2">
              <i class="fas fa-arrow-circle-up text-green-600"></i>
              <span>Nhận 5 lần Đẩy sản phẩm</span>
            </div>
            <button class="text-xs text-[#ff4d4f] border border-[#ff4d4f] rounded px-2 py-0.5 hover:bg-[#ff4d4f] hover:text-white transition-colors">Bắt đầu</button>
          </div>
          <div class="border border-gray-200 rounded p-3">
            <h4 class="font-semibold text-xs mb-1">Hoàn thành khóa học Đạt được đơn hàng đầu tiên của bạn</h4>
          </div>
        </div>
      </section>
    </aside>
  </main>
  <div class="fixed right-4 top-1/2 -translate-y-1/2 flex flex-col items-center space-y-4 z-40" style="max-height: 400px">
    <button aria-label="Notification bell" class="w-8 h-8 rounded-full bg-[#ff4d4f] text-white flex items-center justify-center shadow-md hover:brightness-90 transition">
      <i class="fas fa-bell"></i>
    </button>
    <button aria-label="Chat support" class="w-8 h-8 rounded-full bg-[#ff4d4f] text-white flex items-center justify-center shadow-md hover:brightness-90 transition">
      <i class="fas fa-headset"></i>
    </button>
    <button aria-label="Chat message" class="w-8 h-8 rounded-full bg-[#ff4d4f] text-white flex items-center justify-center shadow-md hover:brightness-90 transition">
      <i class="fas fa-comment-alt"></i>
    </button>
  </div>
</div>
@endsection