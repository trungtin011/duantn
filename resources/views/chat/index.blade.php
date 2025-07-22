@php
    $shopList = $shops;
    if (isset($shopProduct) && $shopProduct && !$shops->contains('id', $shopProduct->id)) {
        $shopList = $shops->push($shopProduct);
    }
@endphp
@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex" style="min-height:400px;max-height:600px;">
        <!-- Sidebar: List shop -->
        <div class="w-1/3 bg-gray-50 p-4 overflow-y-auto border-r">
            <h2 class="font-bold mb-4">Shop đã chat</h2>
            <ul>
                @foreach($shopList as $shop)
                    <li>
                        <button class="shop-btn w-full text-left p-2 rounded hover:bg-blue-100 flex items-center gap-2"
                                data-shop-id="{{ $shop->id }}">
                            <img src="{{ $shop->shop_logo ? \Illuminate\Support\Facades\Storage::url($shop->shop_logo) : asset('images/default_shop_logo.png') }}"
                                 alt="avatar" class="w-8 h-8 rounded-full object-cover border">
                            <span>{{ $shop->shop_name }}</span>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
        <!-- Main: Chat box -->
        <div class="flex-1 flex flex-col">
            <div id="chat-header" class="p-4 border-b font-bold flex items-center gap-3"></div>
            <div id="chat-messages" class="flex-1 p-4 overflow-y-auto bg-gray-100"></div>
            
            <!-- Product Context Area -->
            @if(isset($productContext))
            <div id="product-context" class="p-2 border-t flex items-center gap-2 bg-gray-50" data-product-id="{{ $productContext->id }}">
                <img src="{{ $productContext->images->first() ? \Illuminate\Support\Facades\Storage::url($productContext->images->first()->image_path) : asset('images/default_product_image.png') }}" class="w-10 h-10 rounded object-cover">
                <div class="flex-1 text-sm truncate">{{ $productContext->name }}</div>
                <button id="remove-product-context" class="text-gray-500 hover:text-red-500">&times;</button>
            </div>
            @endif

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
    let currentShopId = null;
    let currentShopName = '';
    var shopBtns = document.querySelectorAll('.shop-btn');
    var chatForm = document.getElementById('chat-form');
    var chatMessages = document.getElementById('chat-messages');
    var chatHeader = document.getElementById('chat-header');
    if (shopBtns && chatForm && chatMessages && chatHeader) {
        shopBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                currentShopId = this.dataset.shopId;
                currentShopName = this.innerText;
                loadMessages(currentShopId, currentShopName);
                chatForm.style.display = '';
            });
        });
        function loadMessages(shopId, shopName) {
            fetch('/chat/messages/' + shopId)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    chatHeader.innerHTML = `<img src="${data.shop.shop_logo_url}" class="w-8 h-8 rounded-full object-cover border mr-2"> <span>${data.shop.shop_name}</span>`;
                    chatMessages.innerHTML = '';
                    data.messages.forEach(function(msg) {
                        var div = document.createElement('div');
                        var productHtml = '';
                        if (msg.product) {
                            productHtml = `<div class="mt-2 p-2 border rounded-md bg-white flex items-center gap-2 text-sm text-black">
                                <span class="font-semibold">Sản phẩm:</span> ${msg.product.name}
                            </div>`;
                        }
                        // Nếu là user (mình) thì căn phải, màu xanh; shop thì căn trái, màu xám
                        if (msg.sender_type === 'user') {
                            div.className = 'mb-2 flex justify-end';
                            div.innerHTML = `<div class='inline-block bg-blue-500 text-white px-4 py-2 rounded-lg max-w-[70%] text-right shadow'>
                                <b>Bạn:</b> ${msg.message} ${productHtml} <span class='text-xs text-gray-200 block mt-1'>${msg.created_at}</span>
                            </div>`;
                        } else {
                            div.className = 'mb-2 flex justify-start';
                            div.innerHTML = `<div class='inline-block bg-white text-gray-800 px-4 py-2 rounded-lg border max-w-[70%] shadow'>
                                <b>${data.shop.shop_name}:</b> ${msg.message} ${productHtml} <span class='text-xs text-gray-400 block mt-1'>${msg.created_at}</span>
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
            var productContextDiv = document.getElementById('product-context');
            var productId = productContextDiv ? productContextDiv.dataset.productId : null;

            if (!input.value.trim() || !currentShopId) return;
            fetch('/chat/send/' + currentShopId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': chatForm.querySelector('input[name=_token]').value
                },
                body: JSON.stringify({ message: input.value, product_id: productId })
            })
            .then(function(res) { return res.json(); })
            .then(function(msg) {
                loadMessages(currentShopId, chatHeader.innerText);
                input.value = '';
                if (productContextDiv) productContextDiv.style.display = 'none'; // Ẩn product context sau khi gửi
            });
        });
        
        // Remove product context
        var removeProductBtn = document.getElementById('remove-product-context');
        if(removeProductBtn) {
            removeProductBtn.addEventListener('click', function() {
                var productContextDiv = document.getElementById('product-context');
                if (productContextDiv) productContextDiv.style.display = 'none';
            });
        }

        // Tự động chọn shop đầu tiên hoặc shop_id trên URL
        const urlParams = new URLSearchParams(window.location.search);
        const shopIdParam = urlParams.get('shop_id');
        let autoBtn = null;
        if (shopIdParam) {
            autoBtn = document.querySelector('.shop-btn[data-shop-id="' + shopIdParam + '"]');
        }
        if (!autoBtn && shopBtns.length > 0) autoBtn = shopBtns[0];
        if (autoBtn) autoBtn.click();
        
        if (window.Echo && window.Laravel && window.Laravel.user) {
            window.Echo.channel('chat')
                .listen('MessageSent', function(e) {
                    if (currentShopId && e.shop_id == currentShopId && e.user_id == window.Laravel.user.id) {
                        if (typeof loadMessages === 'function') {
                            loadMessages(currentShopId, currentShopName);
                        }
                    }
                });
        }
    }
});
</script>
@endpush 