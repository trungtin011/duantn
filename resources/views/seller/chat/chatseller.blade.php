@extends('layouts.seller_home')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex" style="min-height:400px;max-height:600px;" data-shop-id="{{ $shop->id }}">
        <!-- Sidebar: List customer -->
        <div class="w-1/3 bg-gray-50 p-4 overflow-y-auto border-r">
            <h2 class="font-bold mb-4">Khách đã chat</h2>
            <ul>
                <li class="text-sm text-[#64748b] mb-2">
                    <a href="{{ route('seller.chat') }}" class="link_admin font-semibold text-blue-600">Chat</a>
                </li>
                @foreach($customers as $customer)
                    <li>
                        <button class="customer-btn w-full text-left p-2 rounded hover:bg-blue-100 flex items-center gap-2"
                                data-customer-id="{{ $customer->id }}">
                            <img src="{{ $customer->avatar ? \Illuminate\Support\Facades\Storage::url($customer->avatar) : asset('images/default_avatar.png') }}"
                                 alt="avatar" class="w-8 h-8 rounded-full object-cover border">
                            <span>{{ $customer->fullname ?? $customer->username ?? $customer->email }}</span>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
        <!-- Main: Chat box -->
        <div class="flex-1 flex flex-col">
            <div id="chat-header" class="p-4 border-b font-bold flex items-center gap-3"></div>
            <div id="chat-messages" class="flex-1 p-4 overflow-y-auto bg-gray-100"></div>
            <form id="chat-form" class="p-4 border-t flex" style="display:none">
                @csrf
                <input type="text" id="chat-input" class="flex-1 border rounded p-2" placeholder="Nhập tin nhắn...">
                <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded">Gửi</button>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentCustomerId = null;
    let currentCustomerName = '';
    const chatContainer = document.querySelector('[data-shop-id]');
    const shopId = chatContainer ? chatContainer.dataset.shopId : null;
    var customerBtns = document.querySelectorAll('.customer-btn');
    var chatForm = document.getElementById('chat-form');
    var chatMessages = document.getElementById('chat-messages');
    var chatHeader = document.getElementById('chat-header');
    if (customerBtns && chatForm && chatMessages && chatHeader) {
        customerBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                currentCustomerId = this.dataset.customerId;
                currentCustomerName = this.innerText;
                loadMessages(currentCustomerId, currentCustomerName);
                chatForm.style.display = '';
            });
        });
        function loadMessages(customerId, customerName) {
            fetch('/seller/chat/messages/' + customerId)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    chatHeader.innerHTML = `<img src="${data.customer.avatar_url}" class="w-8 h-8 rounded-full object-cover border mr-2"> <span>${data.customer.fullname || data.customer.username || data.customer.email}</span>`;
                    chatMessages.innerHTML = '';
                    data.messages.forEach(function(msg) {
                        var div = document.createElement('div');
                        if (msg.sender_type === 'seller') {
                            div.className = 'mb-2 flex justify-end';
                            div.innerHTML = `<div class='inline-block bg-blue-500 text-white px-4 py-2 rounded-lg max-w-[70%] text-right shadow'>
                                <b>Bạn:</b> ${msg.message} <span class='text-xs text-gray-200 block mt-1'>${msg.created_at}</span>
                            </div>`;
                        } else {
                            div.className = 'mb-2 flex justify-start';
                            div.innerHTML = `<div class='inline-block bg-white text-gray-800 px-4 py-2 rounded-lg border max-w-[70%] shadow'>
                                <b>${data.customer.fullname || data.customer.username || data.customer.email}:</b> ${msg.message} <span class='text-xs text-gray-400 block mt-1'>${msg.created_at}</span>
                            </div>`;
                        }
                        chatMessages.appendChild(div);
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                });
        }
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var input = document.getElementById('chat-input');
            if (!input.value.trim() || !currentCustomerId) return;
            fetch('/seller/chat/send/' + currentCustomerId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': chatForm.querySelector('input[name=_token]').value
                },
                body: JSON.stringify({ message: input.value })
            })
            .then(function(res) { return res.json(); })
            .then(function(msg) {
                loadMessages(currentCustomerId, chatHeader.innerText);
                input.value = '';
            });
        });
        // Tự động chọn customer đầu tiên
        if (customerBtns.length > 0) customerBtns[0].click();
        
        if (window.Echo && shopId) {
            window.Echo.channel('chat')
                .listen('MessageSent', function(e) {
                    if (currentCustomerId && e.user_id == currentCustomerId && e.shop_id == shopId) {
                        if (typeof loadMessages === 'function') {
                            loadMessages(currentCustomerId, currentCustomerName);
                        }
                    }
                });
        }
    }
});
</script>
@endpush
