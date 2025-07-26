document.addEventListener('DOMContentLoaded', function() {
    let currentCustomerId = null;
    let currentCustomerName = '';
    const chatContainer = document.querySelector('[data-shop-id]');
    const shopId = chatContainer ? chatContainer.dataset.shopId : null;
    var customerBtns = document.querySelectorAll('.customer-btn');
    var chatForm = document.getElementById('chat-form');
    var chatMessages = document.getElementById('chat-messages');
    var chatHeader = document.getElementById('chat-header');
    var allChatsTab = document.getElementById('all-chats-tab');
    var unreadChatsTab = document.getElementById('unread-chats-tab');
    var customerListContainer = document.getElementById('customer-list');
    let allCustomers = Array.from(customerBtns); // Store all customer buttons initially

    if (customerBtns && chatForm && chatMessages && chatHeader && allChatsTab && unreadChatsTab && customerListContainer) {
        // Function to render customer list based on filter
        function renderCustomerList(filter) {
            customerListContainer.innerHTML = ''; // Clear current list
            let filteredCustomers = [];

            if (filter === 'all') {
                filteredCustomers = allCustomers;
            } else if (filter === 'unread') {
                filteredCustomers = allCustomers.filter(btn => {
                    const unreadBadge = btn.querySelector('.bg-red-600');
                    return unreadBadge && parseInt(unreadBadge.textContent) > 0;
                });
            }

            if (filteredCustomers.length === 0) {
                const noResultDiv = document.createElement('div');
                noResultDiv.className = 'text-center text-gray-500 mt-5 p-3';
                if (filter === 'unread') {
                    noResultDiv.textContent = 'Không có tin nhắn chưa đọc.';
                } else {
                    noResultDiv.textContent = 'Không có cuộc trò chuyện nào.';
                }
                customerListContainer.appendChild(noResultDiv);
            } else {
                filteredCustomers.forEach(btn => customerListContainer.appendChild(btn));
            }
        }

        // Initial render
        renderCustomerList('all');

        // Tab click handlers
        allChatsTab.addEventListener('click', function() {
            allChatsTab.classList.add('border-b-2', 'border-blue-600');
            allChatsTab.classList.remove('text-gray-500');
            unreadChatsTab.classList.remove('border-b-2', 'border-blue-600');
            unreadChatsTab.classList.add('text-gray-500');
            renderCustomerList('all');
        });

        unreadChatsTab.addEventListener('click', function() {
            unreadChatsTab.classList.add('border-b-2', 'border-blue-600');
            unreadChatsTab.classList.remove('text-gray-500');
            allChatsTab.classList.remove('border-b-2', 'border-blue-600');
            allChatsTab.classList.add('text-gray-500');
            renderCustomerList('unread');
        });

        customerBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                allCustomers.forEach(b => b.classList.remove('bg-blue-100')); // Use allCustomers here
                // Add active class to the clicked button
                this.classList.add('bg-blue-100');

                currentCustomerId = this.dataset.customerId;
                currentCustomerName = this.dataset.customerName; // Get name from data attribute
                loadMessages(currentCustomerId, currentCustomerName);
                chatForm.style.display = ''; // Ensure form is visible

                // Remove unread count badge immediately on click
                const unreadBadge = this.querySelector('.bg-red-600');
                if (unreadBadge) {
                    unreadBadge.remove();
                    // Potentially decrement a global unread count if you had one
                }
            });
        });

        function loadMessages(customerId, customerName) {
            fetch('/seller/chat/messages/' + customerId)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    chatHeader.innerHTML = `
                        <div class="flex items-center space-x-2">
                            <img src="${data.customer.avatar_url}" alt="Customer Avatar" class="rounded-full w-10 h-10" height="40" width="40"/>
                            <div>
                                <h2 class="text-sm font-semibold text-gray-900 leading-none">${data.customer.fullname || data.customer.username || data.customer.email}</h2>
                                <div class="flex items-center text-xs text-gray-600 space-x-1">
                                    {{-- You might want to add customer status or other info here --}}
                                    <span>Đang hoạt động</span> {{-- Placeholder --}}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 text-gray-600">
                            {{-- Add chat header buttons here if needed --}}
                            <button aria-label="Search" class="hover:text-gray-900"><i class="fas fa-search text-lg"></i></button>
                            <button aria-label="More options" class="hover:text-gray-900"><i class="fas fa-bars text-lg"></i></button>
                        </div>
                    `;
                    chatMessages.innerHTML = ''; // Clear previous messages

                    if (data.messages.length === 0) {
                        var noMessagesDiv = document.createElement('div');
                        noMessagesDiv.className = 'text-center text-gray-500 mt-10';
                        noMessagesDiv.textContent = 'Chưa có cuộc trò chuyện nào với khách hàng này.';
                        chatMessages.appendChild(noMessagesDiv);
                    } else {
                        data.messages.forEach(function(msg) {
                            var div = document.createElement('div');

                            // Seller's messages (right, blue background)
                            if (msg.sender_type === 'seller') {
                                div.className = 'flex justify-end';
                                div.innerHTML = `
                                    <article class="bg-blue-200 rounded-md p-3 max-w-[70%] inline-block break-words">
                                        ${msg.message}
                                        <time class="block text-xs text-gray-500 mt-1 select-text">
                                            ${new Date(msg.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'})}
                                        </time>
                                    </article>
                                `;
                            } else { // User's messages (left, white background)
                                div.className = 'flex justify-start';
                                div.innerHTML = `
                                    <article class="bg-white rounded-md p-3 border border-blue-300 max-w-[70%]">
                                        <header class="text-xs text-gray-600 font-semibold mb-1 select-text">
                                            ${data.customer.fullname || data.customer.username || data.customer.email}
                                        </header>
                                        <p class="text-sm text-gray-800 leading-tight break-words">
                                            ${msg.message}
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
            if (!input.value.trim() || !currentCustomerId) return;

            fetch('/seller/chat/send/' + currentCustomerId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': chatForm.querySelector('input[name=_token]').value
                },
                body: JSON.stringify({ message: input.value })
            })
            .then(function(res) {
                if (!res.ok) {
                    console.error('Error sending message:', res.statusText);
                    return res.json().then(err => { throw new Error(err.message || 'Error sending message'); });
                }
                return res.json();
            })
            .then(function(msg) {
                loadMessages(currentCustomerId, currentCustomerName);
                input.value = '';
            })
            .catch(function(error) {
                console.error('Fetch error:', error);
                alert('Không thể gửi tin nhắn: ' + error.message);
            });
        });

        // Tự động chọn customer đầu tiên hoặc customer_id trên URL
        const urlParams = new URLSearchParams(window.location.search);
        const customerIdParam = urlParams.get('customer_id');
        let autoBtn = null;
        if (customerIdParam) {
            autoBtn = document.querySelector('.customer-btn[data-customer-id="' + customerIdParam + '"]');
        }
        if (!autoBtn && customerBtns.length > 0) autoBtn = customerBtns[0];
        
        if (autoBtn) {
            // Simulate a click on the button to load messages and set active state
            autoBtn.click();
        } else {
            // If no customer is selected, hide the chat form
            chatForm.style.display = 'none';
            chatHeader.innerHTML = '<div class="text-center text-gray-500 w-full">Vui lòng chọn một khách hàng để bắt đầu trò chuyện.</div>';
            chatMessages.innerHTML = '<div class="text-center text-gray-500 mt-10">Chưa có cuộc trò chuyện nào với khách hàng này.</div>';
        }
        
        if (window.Echo && shopId) {
            window.Echo.channel('chat')
                .listen('MessageSent', function(e) {
                    console.log('MessageSent event received on seller side:', e);
                    console.log('currentCustomerId:', currentCustomerId);
                    console.log('shopId:', shopId);
                    // Check if the message is for the currently viewed customer and from a different user (customer)
                    // Or if it's from the current seller, ensure it's for the current customer
                    const isMessageForCurrentCustomer = currentCustomerId && e.user_id == currentCustomerId;
                    const isMessageFromOtherUser = e.sender_type === 'user'; // Message from customer
                    const isMessageFromCurrentSeller = e.sender_type === 'seller'; // Message from seller

                    if ((isMessageForCurrentCustomer && isMessageFromOtherUser) || (isMessageForCurrentCustomer && isMessageFromCurrentSeller)) {
                        console.log('Condition met, calling loadMessages...');
                        if (typeof loadMessages === 'function') {
                            loadMessages(currentCustomerId, currentCustomerName);
                        }
                    } else {
                        // If the message is not for the currently open chat, but it's from a new customer or for a different customer,
                        // we should update the unread count badge for that customer in the sidebar.
                        // This requires fetching unread counts again or having a more sophisticated client-side state management.
                        // For now, if the message is from a user (customer) but not for the current chat, we'll try to update the unread badge.
                        if (e.sender_type === 'user' && e.shop_id == shopId) {
                            const targetCustomerBtn = document.querySelector(`.customer-btn[data-customer-id="${e.user_id}"]`);
                            if (targetCustomerBtn) {
                                let unreadBadge = targetCustomerBtn.querySelector('.bg-red-600');
                                if (!unreadBadge) {
                                    unreadBadge = document.createElement('div');
                                    unreadBadge.className = 'flex items-center justify-center bg-red-600 text-white rounded-full w-5 h-5 text-xs font-semibold select-none';
                                    targetCustomerBtn.appendChild(unreadBadge);
                                    unreadBadge.textContent = '1';
                                } else {
                                    let currentCount = parseInt(unreadBadge.textContent);
                                    unreadBadge.textContent = (currentCount < 99 ? currentCount + 1 : '99+').toString();
                                }
                            }
                            // Re-render the customer list to reflect the updated unread count (if unread tab is active)
                            if (unreadChatsTab.classList.contains('border-blue-600')) {
                                renderCustomerList('unread');
                            } else {
                                renderCustomerList('all'); // Re-render all to ensure order if necessary
                            }
                        }
                        console.log('Condition NOT met on seller side. Event details:', {
                            e_user_id: e.user_id,
                            currentCustomerId: currentCustomerId,
                            e_shop_id: e.shop_id,
                            current_shop_id: shopId
                        });
                    }
                });
        }
    }
}); 