document.addEventListener('DOMContentLoaded', function() {
    let currentShopId = null;
    let currentShopName = '';
    var shopBtns = document.querySelectorAll('.shop-btn');
    var chatForm = document.getElementById('chat-form');
    var chatMessages = document.getElementById('chat-messages');
    var chatHeader = document.getElementById('chat-header');
    var productContextDiv = document.getElementById('product-context');

    if (shopBtns && chatForm && chatMessages && chatHeader) {
        shopBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                shopBtns.forEach(b => b.classList.remove('bg-blue-100'));
                // Add active class to the clicked button
                this.classList.add('bg-blue-100');

                currentShopId = this.dataset.shopId;
                currentShopName = this.dataset.shopName; // Get name from data attribute
                loadMessages(currentShopId, currentShopName);
                chatForm.style.display = ''; // Ensure form is visible
            });
        });

        function loadMessages(shopId, shopName) {
            fetch('/chat/messages/' + shopId)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    chatHeader.innerHTML = `
                        <div class="flex items-center space-x-2">
                            <img src="${data.shop.shop_logo_url}" alt="Shop Logo" class="rounded-full w-10 h-10" height="40" width="40"/>
                            <div>
                                <h2 class="text-sm font-semibold text-gray-900 leading-none">${data.shop.shop_name}</h2>
                                <div class="flex items-center text-xs text-gray-600 space-x-1">
                                    {{-- You might want to add shop status or other info here --}}
                                    <span>Đang hoạt động</span> {{-- Placeholder --}}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 text-gray-600">
                            {{-- Add chat header buttons here if needed --}}
                            {{-- <button aria-label="Add friend" class="hover:text-gray-900"><i class="fas fa-user-plus text-lg"></i></button> --}}
                            {{-- <button aria-label="Video call" class="hover:text-gray-900"><i class="fas fa-video text-lg"></i></button> --}}
                            <button aria-label="Search" class="hover:text-gray-900"><i class="fas fa-search text-lg"></i></button>
                            <button aria-label="More options" class="hover:text-gray-900"><i class="fas fa-bars text-lg"></i></button>
                        </div>
                    `;
                    chatMessages.innerHTML = ''; // Clear previous messages

                    // Append product context if it exists and is not hidden
                    if (productContextDiv && productContextDiv.style.display !== 'none') {
                        chatMessages.appendChild(productContextDiv);
                    }

                    if (data.messages.length === 0) {
                        var noMessagesDiv = document.createElement('div');
                        noMessagesDiv.className = 'text-center text-gray-500 mt-10';
                        noMessagesDiv.textContent = 'Bạn chưa có cuộc trò chuyện nào với shop này. Hãy bắt đầu nhắn tin!';
                        chatMessages.appendChild(noMessagesDiv);
                    } else {
                        data.messages.forEach(function(msg) {
                            var div = document.createElement('div');
                            var productHtml = '';
                            if (msg.product) {
                                productHtml = `
                                    <div class="mt-2 p-2 border rounded-md bg-white flex items-center gap-2 text-sm text-black">
                                        <span class="font-semibold">Sản phẩm:</span> <a href="/product/${msg.product.slug}" class="text-blue-600 hover:underline">${msg.product.name}</a>
                                    </div>
                                `;
                            }

                            // User's messages (right, blue background)
                            if (msg.sender_type === 'user') {
                                div.className = 'flex justify-end';
                                div.innerHTML = `
                                    <article class="bg-blue-200 rounded-md p-3 max-w-[70%] inline-block break-words">
                                        ${msg.message}
                                        ${productHtml}
                                        <time class="block text-xs text-gray-500 mt-1 select-text">
                                            ${new Date(msg.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'})}
                                        </time>
                                    </article>
                                `;
                            } else { // Shop's messages (left, white background)
                                div.className = 'flex justify-start';
                                div.innerHTML = `
                                    <article class="bg-white rounded-md p-3 border border-blue-300 max-w-[70%]">
                                        <header class="text-xs text-gray-600 font-semibold mb-1 select-text">
                                            ${data.shop.shop_name}
                                        </header>
                                        <p class="text-sm text-gray-800 leading-tight break-words">
                                            ${msg.message}
                                            ${productHtml}
                                        </p>
                                        <time class="text-xs text-gray-400 mt-1 block select-text">
                                            ${new Date(msg.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'})}
                                        </time>
                                    </article>
                                `;
                            }
                            chatMessages.appendChild(div);
                        });
                    }
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                });
        }

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var input = document.getElementById('chat-input');
            var productId = productContextDiv && productContextDiv.style.display !== 'none' ? productContextDiv.dataset.productId : null;

            if (!input.value.trim() || !currentShopId) return;

            fetch('/chat/send/' + currentShopId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': chatForm.querySelector('input[name=_token]').value
                },
                body: JSON.stringify({ message: input.value, product_id: productId })
            })
            .then(function(res) {
                if (!res.ok) {
                    console.error('Error sending message:', res.statusText);
                    return res.json().then(err => { throw new Error(err.message || 'Error sending message'); });
                }
                return res.json();
            })
            .then(function(msg) {
                loadMessages(currentShopId, currentShopName); // Reload messages to include new one
                input.value = '';
                if (productContextDiv) productContextDiv.style.display = 'none'; // Hide product context after sending
            })
            .catch(function(error) {
                console.error('Fetch error:', error);
                alert('Không thể gửi tin nhắn: ' + error.message);
            });
        });

        // Remove product context
        var removeProductBtn = document.getElementById('remove-product-context');
        if(removeProductBtn) {
            removeProductBtn.addEventListener('click', function() {
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
        
        if (autoBtn) {
            // Simulate a click on the button to load messages and set active state
            autoBtn.click();
        } else {
            // If no shop is selected, hide the chat form
            chatForm.style.display = 'none';
            chatHeader.innerHTML = '<div class="text-center text-gray-500 w-full">Vui lòng chọn một shop để bắt đầu trò chuyện.</div>';
            chatMessages.innerHTML = '<div class="text-center text-gray-500 mt-10">Bạn chưa có cuộc trò chuyện nào với shop này. Hãy bắt đầu nhắn tin!</div>';
        }
        
        // Pusher Listener
        if (window.Echo && window.Laravel && window.Laravel.user) {
            window.Echo.channel('chat')
                .listen('MessageSent', function(e) {
                    console.log('MessageSent event received on customer side:', e);
                    console.log('currentShopId:', currentShopId);
                    console.log('window.Laravel.user.id:', window.Laravel.user.id);
                    // Check if the message is for the currently viewed shop and from a different user (seller)
                    // Or if it's from the current user, ensure it's for the current shop
                    const isMessageForCurrentShop = currentShopId && e.shop_id == currentShopId;
                    const isMessageFromOtherUser = e.user_id != window.Laravel.user.id;
                    const isMessageFromCurrentUser = e.user_id == window.Laravel.user.id;

                    if (isMessageForCurrentShop && (isMessageFromOtherUser || isMessageFromCurrentUser)) {
                        console.log('Condition met, calling loadMessages...');
                        if (typeof loadMessages === 'function') {
                            loadMessages(currentShopId, currentShopName);
                        }
                    } else {
                        console.log('Condition NOT met on customer side. Event details:', {
                            e_shop_id: e.shop_id,
                            currentShopId: currentShopId,
                            e_user_id: e.user_id,
                            current_user_id: window.Laravel.user.id
                        });
                    }
                });
        }
    }
}); 