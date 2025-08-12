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
    var customerSearchInput = document.getElementById('customer-search-input');
    let allCustomers = Array.from(customerBtns); // Store all customer buttons initially
    
    // Auto-refresh variables
    let lastMessageId = 0;
    let autoRefreshInterval = null;
    let sentMessageIds = new Set();
    
    // Image upload elements
    var imageUploadBtn = document.getElementById('image-upload-btn');
    var imageInput = document.getElementById('image-input');
    var imagePreviewArea = document.getElementById('image-preview-area');
    var previewImage = document.getElementById('preview-image');
    var imageName = document.getElementById('image-name');
    var removeImage = document.getElementById('remove-image');
    var selectedImageFile = null;

    if (customerBtns && chatForm && chatMessages && chatHeader && allChatsTab && unreadChatsTab && customerListContainer) {
        // Start auto-refresh for new messages
        function startAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
            
            autoRefreshInterval = setInterval(function() {
                if (currentCustomerId) {
                    checkForNewMessages();
                }
            }, 2000); // Check every 2 seconds
        }

        // Stop auto-refresh
        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
                autoRefreshInterval = null;
            }
        }

        // Check for new messages without reloading
        function checkForNewMessages() {
            if (!currentCustomerId) return;
            
            fetch('/seller/chat/messages/' + currentCustomerId)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.messages && data.messages.length > 0) {
                        const latestMessage = data.messages[data.messages.length - 1];
                        
                        // Check if we have new messages
                        if (latestMessage.id > lastMessageId) {
                            // Find new messages to append
                            const newMessages = data.messages.filter(msg => msg.id > lastMessageId);
                            
                            newMessages.forEach(function(msg) {
                                // Only append if not already sent by current seller
                                if (!sentMessageIds.has(msg.id)) {
                                    appendMessage(msg, data.customer);
                                    
                                    // If it's a message from customer, play notification
                                    if (msg.sender_type === 'user') {
                                        playNotificationSound();
                                    }
                                }
                            });
                            
                            lastMessageId = latestMessage.id;
                            
                            // Scroll to bottom to show new messages
                            chatMessages.scrollTop = chatMessages.scrollHeight;
                        }
                    }
                })
                .catch(function(error) {
                    console.error('Error checking for new messages:', error);
                });
        }

        // Function to append a single message
        function appendMessage(msg, customer) {
            // Check if message already exists to prevent duplicates
            const existingMessage = document.querySelector(`[data-message-id="${msg.id}"]`);
            if (existingMessage) {
                return; // Message already exists, don't append again
            }

            const div = document.createElement('div');
            div.setAttribute('data-message-id', msg.id);

            // Seller's messages (right, blue background)
            if (msg.sender_type === 'seller') {
                div.className = 'flex justify-end';
                let messageContent = '';
                if (msg.image_url) {
                    messageContent = `<img src="${msg.image_url}" alt="Image" class="max-w-full h-auto rounded mb-2" style="max-height: 200px;">`;
                }
                if (msg.message) {
                    messageContent += `<p class="text-sm text-gray-800 leading-tight break-words">${msg.message}</p>`;
                }
                div.innerHTML = `
                    <article class="bg-blue-200 rounded-md p-3 max-w-[70%] inline-block break-words">
                        ${messageContent}
                        <time class="block text-xs text-gray-500 mt-1 select-text">
                            ${new Date(msg.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'})}
                        </time>
                    </article>
                `;
            } else { // User's messages (left, white background)
                div.className = 'flex justify-start';
                let messageContent = '';
                if (msg.image_url) {
                    messageContent = `<img src="${msg.image_url}" alt="Image" class="max-w-full h-auto rounded mb-2" style="max-height: 200px;">`;
                }
                if (msg.message) {
                    messageContent += `<p class="text-sm text-gray-800 leading-tight break-words">${msg.message}</p>`;
                }
                div.innerHTML = `
                    <article class="bg-white rounded-md p-3 border border-blue-300 max-w-[70%]">
                        <header class="text-xs text-gray-600 font-semibold mb-1 select-text">
                            ${customer.fullname || customer.username || customer.email}
                        </header>
                        ${messageContent}
                        <time class="text-xs text-gray-400 mt-1 block select-text">
                            ${new Date(msg.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'})}
                        </time>
                    </article>
                `;
            }
            chatMessages.appendChild(div);
        }

        // Function to render customer list based on filter
        function renderCustomerList(filter, searchTerm = '') {
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

            // Apply search filter if search term exists
            if (searchTerm && searchTerm.trim() !== '') {
                const searchLower = searchTerm.toLowerCase().trim();
                filteredCustomers = filteredCustomers.filter(btn => {
                    const customerName = btn.dataset.customerName || '';
                    return customerName.toLowerCase().includes(searchLower);
                });
            }

            if (filteredCustomers.length === 0) {
                const noResultDiv = document.createElement('div');
                noResultDiv.className = 'text-center text-gray-500 mt-5 p-3';
                if (searchTerm && searchTerm.trim() !== '') {
                    noResultDiv.textContent = `Không tìm thấy khách hàng nào phù hợp với "${searchTerm}"`;
                } else if (filter === 'unread') {
                    noResultDiv.textContent = 'Không có tin nhắn chưa đọc.';
                } else {
                    noResultDiv.textContent = 'Không có cuộc trò chuyện nào.';
                }
                customerListContainer.appendChild(noResultDiv);
            } else {
                filteredCustomers.forEach(btn => customerListContainer.appendChild(btn));
            }
        }

        // Function to highlight search results
        function highlightSearchResults(searchTerm) {
            if (!searchTerm || searchTerm.trim() === '') {
                // Remove all highlights if no search term
                document.querySelectorAll('.customer-btn .highlight-search').forEach(el => {
                    el.classList.remove('highlight-search');
                });
                return;
            }

            const customerButtons = document.querySelectorAll('.customer-btn');
            customerButtons.forEach(btn => {
                const customerName = btn.dataset.customerName || '';
                const nameElement = btn.querySelector('.flex-1 .text-sm.font-semibold span');
                
                if (nameElement) {
                    // Remove existing highlights
                    nameElement.classList.remove('highlight-search');
                    
                    // Add highlight if matches search
                    if (customerName.toLowerCase().includes(searchTerm.toLowerCase())) {
                        nameElement.classList.add('highlight-search');
                    }
                }
            });
        }

        // Function to handle customer search
        function handleCustomerSearch() {
            const searchTerm = customerSearchInput.value;
            const currentTab = allChatsTab.classList.contains('border-blue-600') ? 'all' : 'unread';
            renderCustomerList(currentTab, searchTerm);
            highlightSearchResults(searchTerm);
        }

        // Add search functionality
        if (customerSearchInput) {
            // Search on input change with debouncing
            let searchTimeout;
            customerSearchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(handleCustomerSearch, 300); // Debounce search by 300ms
            });

            // Search on Enter key
            customerSearchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    handleCustomerSearch();
                }
            });

            // Clear search on Escape key
            customerSearchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    e.preventDefault();
                    customerSearchInput.value = '';
                    handleCustomerSearch();
                    customerSearchInput.blur();
                }
            });

            // Add clear search button functionality
            customerSearchInput.addEventListener('input', function() {
                // Show/hide clear button based on input value
                const clearButton = this.parentNode.querySelector('.clear-search');
                if (this.value.length > 0) {
                    if (!clearButton) {
                        const clearBtn = document.createElement('button');
                        clearBtn.type = 'button';
                        clearBtn.className = 'clear-search absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600';
                        clearBtn.innerHTML = '<i class="fas fa-times"></i>';
                        clearBtn.addEventListener('click', function() {
                            customerSearchInput.value = '';
                            handleCustomerSearch();
                            customerSearchInput.focus();
                        });
                        this.parentNode.appendChild(clearBtn);
                    }
                } else {
                    if (clearButton) {
                        clearButton.remove();
                    }
                }
            });
        }

        // Initial render
        const initialSearchTerm = customerSearchInput ? customerSearchInput.value : '';
        renderCustomerList('all', initialSearchTerm);

        // Tab click handlers
        allChatsTab.addEventListener('click', function() {
            allChatsTab.classList.add('border-b-2', 'border-blue-600');
            allChatsTab.classList.remove('text-gray-500');
            unreadChatsTab.classList.remove('border-b-2', 'border-blue-600');
            unreadChatsTab.classList.add('text-gray-500');
            const searchTerm = customerSearchInput ? customerSearchInput.value : '';
            renderCustomerList('all', searchTerm);
        });

        unreadChatsTab.addEventListener('click', function() {
            unreadChatsTab.classList.add('border-b-2', 'border-blue-600');
            unreadChatsTab.classList.remove('text-gray-500');
            allChatsTab.classList.remove('border-b-2', 'border-blue-600');
            allChatsTab.classList.add('text-gray-500');
            const searchTerm = customerSearchInput ? customerSearchInput.value : '';
            renderCustomerList('unread', searchTerm);
        });

        customerBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                allCustomers.forEach(b => b.classList.remove('bg-blue-100')); // Use allCustomers here
                // Add active class to the clicked button
                this.classList.add('bg-blue-100');

                currentCustomerId = this.dataset.customerId;
                currentCustomerName = this.dataset.customerName; // Get name from data attribute
                
                // Stop previous auto-refresh and start new one
                stopAutoRefresh();
                loadMessages(currentCustomerId, currentCustomerName);
                startAutoRefresh();
                
                chatForm.style.display = ''; // Ensure form is visible

                // Clear unread count badge immediately on click
                clearUnreadBadge(currentCustomerId);
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
                                  
                                    <span>Đang hoạt động</span> 
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 text-gray-600">
                            
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
                        lastMessageId = 0;
                        sentMessageIds.clear();
                    } else {
                        data.messages.forEach(function(msg) {
                            appendMessage(msg, data.customer);
                        });
                        
                        // Update last message ID and track existing messages
                        lastMessageId = data.messages[data.messages.length - 1].id;
                        sentMessageIds.clear(); // Clear and rebuild sent message tracking
                        data.messages.forEach(function(msg) {
                            if (msg.sender_type === 'seller') {
                                sentMessageIds.add(msg.id);
                            }
                        });
                    }
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
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        imagePreviewArea.classList.remove('hidden');
                        imageName.textContent = file.name;
                    };
                    reader.readAsDataURL(file);
                }
            });

            removeImage.addEventListener('click', function() {
                imagePreviewArea.classList.add('hidden');
                imageInput.value = '';
                selectedImageFile = null;
                imageName.textContent = '';
            });

            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                var input = document.getElementById('chat-input');
                if (!input.value.trim() && !selectedImageFile) return; // Only send if there's text or an image

                const messageText = input.value.trim();
                input.value = ''; // Clear input immediately

                // Create temporary message for immediate display
                const tempMessage = {
                    id: 'temp_' + Date.now(),
                    sender_type: 'seller',
                    message: messageText,
                    image_url: selectedImageFile ? URL.createObjectURL(selectedImageFile) : null,
                    created_at: new Date().toISOString()
                };
                
                // Append temporary message immediately
                appendMessage(tempMessage, { fullname: currentCustomerName });
                chatMessages.scrollTop = chatMessages.scrollHeight;

                if (selectedImageFile) {
                    // Send image with optional message
                    const formData = new FormData();
                    formData.append('image', selectedImageFile);
                    if (messageText) {
                        formData.append('message', messageText);
                    }
                    formData.append('_token', chatForm.querySelector('input[name=_token]').value);

                    fetch('/seller/chat/send/' + currentCustomerId, {
                        method: 'POST',
                        body: formData
                    })
                    .then(function(res) {
                        if (!res.ok) {
                            return res.json().then(err => { throw new Error(err.message || 'Error sending image'); });
                        }
                        return res.json();
                    })
                    .then(function(msg) {
                        // Remove temporary message
                        const tempMessageEl = document.querySelector(`[data-message-id="${tempMessage.id}"]`);
                        if (tempMessageEl) {
                            tempMessageEl.remove();
                        }
                        
                        // Track sent message to prevent duplicates
                        sentMessageIds.add(msg.id);
                        
                        // Append the new message immediately
                        appendMessage(msg, { fullname: currentCustomerName });
                        lastMessageId = Math.max(lastMessageId, msg.id);
                        
                        // Clear image preview
                        imagePreviewArea.classList.add('hidden');
                        imageInput.value = '';
                        selectedImageFile = null;
                        
                        // Scroll to bottom
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    })
                    .catch(function(error) {
                        console.error('Fetch error:', error);
                        alert('Không thể gửi ảnh: ' + error.message);
                        
                        // Remove temporary message on error
                        const tempMessageEl = document.querySelector(`[data-message-id="${tempMessage.id}"]`);
                        if (tempMessageEl) {
                            tempMessageEl.remove();
                        }
                    });
                } else {
                    // Send text message only
                    fetch('/seller/chat/send/' + currentCustomerId, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': chatForm.querySelector('input[name=_token]').value
                        },
                        body: JSON.stringify({ message: messageText })
                    })
                    .then(function(res) {
                        if (!res.ok) {
                            console.error('Error sending message:', res.statusText);
                            return res.json().then(err => { throw new Error(err.message || 'Error sending message'); });
                        }
                        return res.json();
                    })
                    .then(function(msg) {
                        // Remove temporary message
                        const tempMessageEl = document.querySelector(`[data-message-id="${tempMessage.id}"]`);
                        if (tempMessageEl) {
                            tempMessageEl.remove();
                        }
                        
                        // Track sent message to prevent duplicates
                        sentMessageIds.add(msg.id);
                        
                        // Append the new message immediately
                        appendMessage(msg, { fullname: currentCustomerName });
                        lastMessageId = Math.max(lastMessageId, msg.id);
                        
                        // Scroll to bottom
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    })
                    .catch(function(error) {
                        console.error('Fetch error:', error);
                        alert('Không thể gửi tin nhắn: ' + error.message);
                        
                        // Remove temporary message on error
                        const tempMessageEl = document.querySelector(`[data-message-id="${tempMessage.id}"]`);
                        if (tempMessageEl) {
                            tempMessageEl.remove();
                        }
                        
                        // Restore input value if send failed
                        input.value = messageText;
                    });
                }
            });
        }

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
        
        // Pusher Listener - Improved real-time updates
        if (window.Echo && shopId) {
            window.Echo.channel('chat')
                .listen('MessageSent', function(e) {
                    console.log('MessageSent event received on seller side:', e);
                    console.log('currentCustomerId:', currentCustomerId);
                    console.log('shopId:', shopId);
                    
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
                                // Get current unread count
                                const unreadBadge = targetCustomerBtn.querySelector('.bg-red-600');
                                let currentCount = 0;
                                
                                if (unreadBadge && unreadBadge.textContent) {
                                    const badgeText = unreadBadge.textContent;
                                    currentCount = badgeText === '99+' ? 99 : parseInt(badgeText) || 0;
                                }
                                
                                // Increment count
                                const newCount = currentCount + 1;
                                updateUnreadBadge(e.user_id, newCount);
                            }
                            // Re-render the customer list to reflect the updated unread count (if unread tab is active)
                            if (unreadChatsTab.classList.contains('border-blue-600')) {
                                const searchTerm = customerSearchInput ? customerSearchInput.value : '';
                                renderCustomerList('unread', searchTerm);
                            } else {
                                const searchTerm = customerSearchInput ? customerSearchInput.value : '';
                                renderCustomerList('all', searchTerm); // Re-render all to ensure order if necessary
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

        // Function to play notification sound
        function playNotificationSound() {
            try {
                // Create a simple notification sound
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
                
                gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.2);
            } catch (error) {
                console.log('Could not play notification sound:', error);
            }
        }

        // Function to update unread badge for a specific customer
        function updateUnreadBadge(customerId, count) {
            const customerBtn = document.querySelector(`.customer-btn[data-customer-id="${customerId}"]`);
            if (customerBtn) {
                let unreadBadge = customerBtn.querySelector('.bg-red-600');
                
                if (count > 0) {
                    if (!unreadBadge) {
                        unreadBadge = document.createElement('div');
                        unreadBadge.className = 'flex items-center justify-center bg-red-600 text-white rounded-full w-5 h-5 text-xs font-semibold select-none';
                        customerBtn.appendChild(unreadBadge);
                    }
                    unreadBadge.textContent = count > 99 ? '99+' : count;
                    unreadBadge.style.display = 'flex';
                } else {
                    if (unreadBadge) {
                        unreadBadge.style.display = 'none';
                    }
                }
            }
        }

        // Function to clear unread badge for a specific customer
        function clearUnreadBadge(customerId) {
            updateUnreadBadge(customerId, 0);
        }

        // Clean up auto-refresh when page is unloaded
        window.addEventListener('beforeunload', function() {
            stopAutoRefresh();
        });
    }
}); 