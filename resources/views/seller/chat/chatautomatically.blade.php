@extends('layouts.seller_home')
@section('title', 'Trợ Lý Chat - Tin nhắn tự động')
@section('content')
<div class="bg-white text-gray-900 font-sans px-4 py-6 max-w-3xl mx-auto">
  <h1 class="text-lg font-semibold leading-6">Trợ Lý Chat</h1>
  <p class="text-sm text-gray-500 mt-1 max-w-[480px]">
    Sử dụng các công cụ khác nhau trong trợ lý chat để làm cho dịch vụ hỗ trợ khách hàng của bạn hiệu quả hơn,
  </p>
  <nav class="flex space-x-6 mt-6 border-b border-gray-200">
    <button
      class="text-sm font-semibold text-[#D14324] border-b-2 border-[#D14324] pb-2"
      type="button"
      aria-current="page"
    >
      Tin nhắn tự động
    </button>
    <a href="{{ route('seller.chat.QA') }}" class="text-sm font-normal text-gray-700 pb-2">Hỏi - Đáp</a>
    <button class="text-sm font-normal text-gray-700 pb-2" type="button">
      Phím tắt tin nhắn
    </button>
  </nav>
  <div
    class="mt-4 rounded border border-yellow-300 bg-yellow-50 p-3 text-xs text-yellow-900 max-w-[720px]"
    role="alert"
  >
    <p>1.Trả lời tự động mặc định sẽ chỉ được kích hoạt 24 giờ một lần cho mỗi người mua.</p>
    <p>2.Trả lời tự động ngoại tuyến sẽ chỉ được kích hoạt mỗi ngày một lần cho mỗi người mua.</p>
  </div>
  <section class="mt-8 space-y-6 max-w-[720px]">
    <div class="flex items-center space-x-3">
      <button
        aria-label="Icon for Tin nhắn tự động ngoài giờ làm việc"
        class="text-gray-300 cursor-not-allowed"
        disabled
      >
        <i class="fas fa-reply fa-lg"></i>
      </button>
      <div class="flex-1">
        <p class="text-sm font-semibold text-gray-400">Tin nhắn tự động ngoài giờ làm việc</p>
        <p class="text-xs text-gray-400 mt-1">
          Sau khi kích hoạt, tin nhắn trả lời tự động này sẽ được gửi nếu người mua bắt đầu cuộc trò chuyện ngoài giờ làm việc.
        </p>
      </div>
      <!-- Công tắc gạt cho ngoài giờ làm việc -->
      <label for="auto-reply-offtime-checkbox" class="relative inline-flex items-center cursor-pointer select-none ml-2">
        <input id="auto-reply-offtime-checkbox" type="checkbox" class="sr-only peer" />
        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-orange-500 rounded-full peer peer-checked:bg-orange-500 transition-all duration-200"></div>
        <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white border border-gray-300 rounded-full shadow-sm transition-all duration-200 peer-checked:translate-x-5"></div>
      </label>
    </div>
    <div class="flex items-center space-x-3 mt-4">
      <button
        aria-label="Icon for Tin nhắn tự động tiêu chuẩn"
        class="text-gray-300 cursor-not-allowed"
        disabled
      >
        <i class="fas fa-reply fa-lg"></i>
      </button>
      <div class="flex-1">
        <p class="text-sm font-semibold text-gray-400">Tin nhắn tự động tiêu chuẩn</p>
        <p class="text-xs text-gray-400 mt-1">
          Sau khi kích hoạt, tin nhắn sẽ được tự động gửi đến Người mua khi họ bắt đầu chat với bạn.
        </p>
      </div>
      <label for="auto-reply-checkbox" class="relative inline-flex items-center cursor-pointer select-none ml-2">
        <input id="auto-reply-checkbox" type="checkbox" class="sr-only peer" />
        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-orange-500 rounded-full peer peer-checked:bg-orange-500 transition-all duration-200"></div>
        <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white border border-gray-300 rounded-full shadow-sm transition-all duration-200 peer-checked:translate-x-5"></div>
      </label>
    </div>
  </section>
  <style>
    .peer:checked ~ div.absolute {
      transform: translateX(20px);
    }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const checkbox = document.getElementById('auto-reply-checkbox');
      fetch('/seller/chat/auto-chat-setting/status')
        .then(res => res.json())
        .then(data => {
          if (data.success) checkbox.checked = !!data.enabled;
        });
      checkbox.addEventListener('change', function () {
        fetch('/seller/chat/auto-chat-setting/toggle', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ enabled: checkbox.checked ? 1 : 0 })
        })
          .then(res => res.json())
          .then(data => {
            if (!data.success) {
              alert('Có lỗi xảy ra!');
              checkbox.checked = !checkbox.checked;
            }
          })
          .catch(() => {
            alert('Có lỗi xảy ra!');
            checkbox.checked = !checkbox.checked;
          });
      });

      const offtimeCheckbox = document.getElementById('auto-reply-offtime-checkbox');
      fetch('/seller/chat/auto-chat-setting/status-offtime')
        .then(res => res.json())
        .then(data => {
          if (data.success) offtimeCheckbox.checked = !!data.enabled;
        });
      offtimeCheckbox.addEventListener('change', function () {
        fetch('/seller/chat/auto-chat-setting/toggle-offtime', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ enabled: offtimeCheckbox.checked ? 1 : 0 })
        })
          .then(res => res.json())
          .then(data => {
            if (!data.success) {
              alert('Có lỗi xảy ra!');
              offtimeCheckbox.checked = !offtimeCheckbox.checked;
            }
          })
          .catch(() => {
            alert('Có lỗi xảy ra!');
            offtimeCheckbox.checked = !offtimeCheckbox.checked;
          });
      });
    });
  </script>
</div>
@endsection
@section('scripts')
<script src="/js/seller/chat-auto-reply.js"></script>
@endsection