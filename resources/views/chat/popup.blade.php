@php
    $shopList = $shops;
    if (isset($shopProduct) && $shopProduct && !$shops->contains('id', $shopProduct->id)) {
        $shopList = $shops->push($shopProduct);
    }
@endphp
@if($shopList->isEmpty())
    <div class="text-center text-gray-400 mt-10">Bạn chưa chat với shop nào.</div>
@else
<div class="flex" style="min-height:300px;max-height:400px;">
    <!-- Sidebar: List shop -->
    <div class="w-1/3 bg-gray-50 p-2 overflow-y-auto border-r">
        <ul>
            @foreach($shopList as $shop)
                <li>
                    <button class="shop-btn w-full text-left p-2 rounded hover:bg-blue-100"
                            data-shop-id="{{ $shop->id }}">
                        {{ $shop->shop_name }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>
    <!-- Main: Chat box -->
    <div class="flex-1 flex flex-col">
        <div id="chat-header" class="p-2 border-b font-bold"></div>
        <div id="chat-messages" class="flex-1 p-2 overflow-y-auto" style="min-height:180px;"></div>
        <form id="chat-form" class="p-2 border-t flex" style="display:none" enctype="multipart/form-data">
            @csrf
            <input type="text" id="chat-input" class="flex-1 border rounded p-2" placeholder="Nhập tin nhắn...">
            <button type="button" id="image-upload-btn" class="ml-2 text-gray-500 hover:text-gray-700">
                <i class="far fa-image"></i>
            </button>
            <input type="file" id="image-input" accept="image/*" style="display: none;">
            <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded">Gửi</button>
        </form>
    </div>
</div>
@endif

<script>
(function() {
    let currentShopId = null;
    var shopBtns = document.querySelectorAll('.shop-btn');
    var chatForm = document.getElementById('chat-form');
    var chatMessages = document.getElementById('chat-messages');
    var chatHeader = document.getElementById('chat-header');
    var imageUploadBtn = document.getElementById('image-upload-btn');
    var imageInput = document.getElementById('image-input');
    var selectedImageFile = null;
    if (shopBtns && chatForm && chatMessages && chatHeader) {
        shopBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                currentShopId = this.dataset.shopId;
                loadMessages(currentShopId, this.innerText);
                chatForm.style.display = '';
            });
        });
        function loadMessages(shopId, shopName) {
            fetch('/chat/messages/' + shopId)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    chatHeader.innerText = data.shop.shop_name;
                    chatMessages.innerHTML = '';
                    data.messages.forEach(function(msg) {
                        var div = document.createElement('div');
                        div.className = 'mb-2';
                        let messageContent = '';
                        if (msg.image_url) {
                            messageContent = '<img src="' + msg.image_url + '" alt="Image" class="max-w-full h-auto rounded mb-1" style="max-height: 150px;">';
                        }
                        if (msg.message) {
                            messageContent += msg.message;
                        }
                        div.innerHTML = '<b>' + (msg.sender_type === 'user' ? 'Bạn' : data.shop.shop_name) + ':</b> ' + messageContent + ' <span class="text-xs text-gray-400">' + msg.created_at + '</span>';
                        chatMessages.appendChild(div);
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                });
        }
        // Image upload functionality
        if (imageUploadBtn && imageInput) {
            imageUploadBtn.addEventListener('click', function() {
                imageInput.click();
            });

            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) { // 2MB limit
                        alert('Kích thước ảnh không được vượt quá 2MB');
                        return;
                    }
                    selectedImageFile = file;
                    sendImage(file);
                }
            });
        }

        function sendImage(imageFile) {
            const formData = new FormData();
            formData.append('image', imageFile);
            formData.append('_token', chatForm.querySelector('input[name=_token]').value);

            fetch('/chat/send/' + currentShopId, {
                method: 'POST',
                body: formData
            })
            .then(function(res) { return res.json(); })
            .then(function(msg) {
                loadMessages(currentShopId, chatHeader.innerText);
                imageInput.value = '';
                selectedImageFile = null;
            })
            .catch(function(error) {
                console.error('Error sending image:', error);
                alert('Không thể gửi ảnh');
            });
        }

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var input = document.getElementById('chat-input');
            if (!input.value.trim() || !currentShopId) return;
            fetch('/chat/send/' + currentShopId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': chatForm.querySelector('input[name=_token]').value
                },
                body: JSON.stringify({ message: input.value })
            })
            .then(function(res) { return res.json(); })
            .then(function(msg) {
                loadMessages(currentShopId, chatHeader.innerText);
                input.value = '';
            });
        });
        // Tự động chọn shop đầu tiên khi mở popup
        if (shopBtns.length > 0) {
            shopBtns[0].click();
        }
    }
})();
</script> 