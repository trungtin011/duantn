document.addEventListener('DOMContentLoaded', function() {
    let currentShopId = null;
    let currentShopName = '';
    let lastMessageId = 0; // Track last message ID for auto-refresh
    let autoRefreshInterval = null; // Auto-refresh interval
    let sentMessageIds = new Set(); // Track sent message IDs to prevent duplicates
    let isSubmitting = false; // Prevent double form submission
    var shopBtns = document.querySelectorAll('.shop-btn');
    var chatForm = document.getElementById('chat-form');
    var chatMessages = document.getElementById('chat-messages');
    var chatHeader = document.getElementById('chat-header');
    var productContextDiv = document.getElementById('product-context');
    
    // Image upload elements
    var imageUploadBtn = document.getElementById('image-upload-btn');
    var imageInput = document.getElementById('image-input');
    var imagePreviewModal = document.getElementById('image-preview-modal');
    var previewImage = document.getElementById('preview-image');
    var closePreview = document.getElementById('close-preview');
    var cancelUpload = document.getElementById('cancel-upload');
    var confirmUpload = document.getElementById('confirm-upload');
    var selectedImageFile = null;

    if (shopBtns && chatForm && chatMessages && chatHeader) {
        // Add active state styling for popup
        function setActiveShop(btn) {
            // Remove active class from all shop items
            document.querySelectorAll('.shop-item').forEach(item => {
                item.classList.remove('bg-red-50', 'border-r-2', 'border-[#ef3248]');
            });
            
            // Add active class to current shop item
            btn.closest('.shop-item').classList.add('bg-red-50', 'border-r-2', 'border-[#ef3248]');
        }

        // Start auto-refresh for new messages
        function startAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
            
            autoRefreshInterval = setInterval(function() {
                if (currentShopId) {
                    checkForNewMessages();
                }
            }, 2000); // Check every 2 seconds for faster response
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
            if (!currentShopId) return;
            
            fetch('/chat/messages/' + currentShopId)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.messages && data.messages.length > 0) {
                        const latestMessage = data.messages[data.messages.length - 1];
                        
                        // Check if we have new messages
                        if (latestMessage.id > lastMessageId) {
                            // Find new messages to append
                            const newMessages = data.messages.filter(msg => msg.id > lastMessageId);
                            
                            newMessages.forEach(function(msg) {
                                // Only append if not already sent by current user
                                if (!sentMessageIds.has(msg.id)) {
                                    appendMessage(msg, data.shop.shop_name);
                                    
                                    // If it's a message from shop (not user), play notification
                                    if (msg.sender_type === 'shop') {
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

        // Check for new messages in all shops (for unread badges)
        function checkAllShopsForNewMessages() {
            // This would be called periodically to update unread badges
            // For now, we'll rely on Pusher events for real-time updates
        }

        shopBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                currentShopId = this.dataset.shopId;
                currentShopName = this.dataset.shopName;
                
                setActiveShop(this);
                loadMessages(currentShopId, currentShopName);
                chatForm.style.display = 'block';
                
                // Clear unread badge for this shop
                clearUnreadBadge(currentShopId);
                
                // Start auto-refresh for this shop
                startAutoRefresh();
                
                // Hide welcome message
                const welcomeMessage = document.getElementById('welcome-message');
                if (welcomeMessage) {
                    welcomeMessage.style.display = 'none';
                }
            });
        });

        // Function to clear unread badge for a specific shop
        function clearUnreadBadge(shopId) {
            const shopItem = document.querySelector(`[data-shop-id="${shopId}"]`);
            if (shopItem) {
                // Clear both unread badges
                const unreadBadge = shopItem.querySelector('.unread-badge');
                const unreadCountBadge = shopItem.querySelector('.unread-count-badge');
                
                if (unreadBadge) {
                    unreadBadge.style.display = 'none';
                }
                if (unreadCountBadge) {
                    unreadCountBadge.style.display = 'none';
                }
            }
        }

        // Function to update unread badge for a specific shop
        function updateUnreadBadge(shopId, count) {
            const shopItem = document.querySelector(`[data-shop-id="${shopId}"]`);
            if (shopItem) {
                let unreadBadge = shopItem.querySelector('.unread-badge');
                let unreadCountBadge = shopItem.querySelector('.unread-count-badge');
                
                if (count > 0) {
                    // Update or create unread badge (existing one)
                    if (!unreadBadge) {
                        const badgeContainer = shopItem.querySelector('.flex.items-center.space-x-1');
                        if (badgeContainer) {
                            unreadBadge = document.createElement('span');
                            unreadBadge.className = 'w-4 h-4 bg-[#ef3248] text-white text-xs font-bold rounded-full flex items-center justify-center unread-badge';
                            badgeContainer.appendChild(unreadBadge);
                        }
                    }
                    
                    // Update or create unread count badge (new one next to shop name)
                    if (!unreadCountBadge) {
                        const shopNameContainer = shopItem.querySelector('.flex.items-center.justify-between');
                        if (shopNameContainer) {
                            unreadCountBadge = document.createElement('span');
                            unreadCountBadge.className = 'unread-count-badge ml-2 w-4 h-4 bg-[#ef3248] text-white text-xs font-bold rounded-full flex items-center justify-center flex-shrink-0';
                            shopNameContainer.appendChild(unreadCountBadge);
                        }
                    }
                    
                    // Update both badges
                    if (unreadBadge) {
                        unreadBadge.textContent = count > 99 ? '99+' : count;
                        unreadBadge.style.display = 'flex';
                    }
                    if (unreadCountBadge) {
                        unreadCountBadge.textContent = count > 99 ? '99+' : count;
                        unreadCountBadge.style.display = 'flex';
                    }
                } else {
                    // Hide both badges
                    if (unreadBadge) {
                        unreadBadge.style.display = 'none';
                    }
                    if (unreadCountBadge) {
                        unreadCountBadge.style.display = 'none';
                    }
                }
            }
        }

        function loadMessages(shopId, shopName) {
            // Show loading state
            chatMessages.innerHTML = '<div class="flex items-center justify-center py-8"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#ef3248]"></div></div>';
            
            fetch('/chat/messages/' + shopId)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    // Update header with shop info
                    const currentShopNameEl = document.getElementById('current-shop-name');
                    const currentShopAvatarEl = document.getElementById('current-shop-avatar');
                    
                    if (currentShopNameEl) {
                        currentShopNameEl.textContent = data.shop.shop_name;
                    }
                    if (currentShopAvatarEl && data.shop.shop_logo_url) {
                        currentShopAvatarEl.src = data.shop.shop_logo_url;
                    }
                    
                    // Clear messages but preserve product context
                    const productContext = document.getElementById('product-context');
                    chatMessages.innerHTML = '';
                    
                    // Re-add product context if it exists
                    if (productContext && productContext.style.display !== 'none') {
                        chatMessages.appendChild(productContext);
                    }

                    if (data.messages.length === 0) {
                        chatMessages.innerHTML = `
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-comment-dots text-2xl mb-2"></i>
                                <p class="text-sm">Chưa có tin nhắn nào</p>
                                    </div>
                                `;
                        lastMessageId = 0;
                        sentMessageIds.clear(); // Clear sent message tracking
                        return;
                    }

                    data.messages.forEach(function(msg) {
                        appendMessage(msg, data.shop.shop_name);
                    });
                    
                    // Update last message ID and track existing messages
                    lastMessageId = data.messages[data.messages.length - 1].id;
                    sentMessageIds.clear(); // Clear and rebuild sent message tracking
                    data.messages.forEach(function(msg) {
                            if (msg.sender_type === 'user') {
                            sentMessageIds.add(msg.id);
                        }
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                })
                .catch(function(error) {
                    console.error('Error loading messages:', error);
                    chatMessages.innerHTML = `
                        <div class="text-center py-8 text-red-400">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <p class="text-sm">Không thể tải tin nhắn</p>
                        </div>
                    `;
                });
        }

        // Function to append a single message (old logic)
        function appendMessage(msg, shopName) {
            // Check if message already exists to prevent duplicates
            const existingMessage = document.querySelector(`[data-message-id="${msg.id}"]`);
            if (existingMessage) {
                return; // Message already exists, don't append again
            }

            const isUser = msg.sender_type === 'user';
            const messageDiv = document.createElement('div');
            messageDiv.className = `mb-3 ${isUser ? 'text-right' : 'text-left'}`;
            messageDiv.setAttribute('data-message-id', msg.id); // Add message ID for deduplication
            
            let messageContent = '';
            if (msg.image_url) {
                messageContent = `<img src="${msg.image_url}" alt="Image" class="max-w-full h-auto rounded-lg mb-2" style="max-height: 150px;">`;
            }
            if (msg.message && msg.message.trim() !== '') {
                messageContent += `<p class="text-sm">${msg.message}</p>`;
            }
            
            // If no content at all, don't display the message
            if (!messageContent.trim() && !msg.image_url) {
                return;
            }
            
            const time = new Date(msg.created_at).toLocaleTimeString('vi-VN', {
                hour: '2-digit',
                minute: '2-digit'
            });
            
            messageDiv.innerHTML = `
                <div class="inline-block max-w-xs lg:max-w-md">
                    <div class="${isUser ? 'bg-[#ef3248] text-white' : 'bg-white border border-gray-200'} rounded-lg px-3 py-2 shadow-sm">
                        ${messageContent}
                    </div>
                    <div class="text-xs text-gray-400 mt-1">${time}</div>
                </div>
            `;
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
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
                        imagePreviewModal.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });

            closePreview.addEventListener('click', function() {
                imagePreviewModal.classList.add('hidden');
                imageInput.value = '';
                selectedImageFile = null;
            });

            cancelUpload.addEventListener('click', function() {
                imagePreviewModal.classList.add('hidden');
                imageInput.value = '';
                selectedImageFile = null;
            });

            confirmUpload.addEventListener('click', function() {
                if (selectedImageFile && currentShopId && !isSubmitting) {
                    sendImage(selectedImageFile);
                }
            });
        }

        function sendImage(imageFile) {
            if (isSubmitting) return; // Prevent double submission
            
            isSubmitting = true;
            const formData = new FormData();
            formData.append('image', imageFile);
            formData.append('_token', chatForm.querySelector('input[name=_token]').value);
            
            const productId = productContextDiv && productContextDiv.style.display !== 'none' ? productContextDiv.dataset.productId : null;
            if (productId) {
                formData.append('product_id', productId);
            }

            fetch('/chat/send/' + currentShopId, {
                method: 'POST',
                body: formData
            })
            .then(function(res) {
                if (!res.ok) {
                    return res.json().then(err => { throw new Error(err.message || 'Error sending image'); });
                }
                return res.json();
            })
            .then(function(response) {
                console.log('Image send response:', response);
                
                // Use response directly since it's already in the correct format
                const msg = {
                    id: response.id,
                    sender_type: response.sender_type,
                    message: response.message || '',
                    image_url: response.image_url,
                    created_at: response.created_at
                };
                
                // Track sent message to prevent duplicates
                sentMessageIds.add(msg.id);
                
                // Append the new message immediately
                appendMessage(msg, currentShopName);
                lastMessageId = Math.max(lastMessageId, msg.id); // Update last message ID
                
                imagePreviewModal.classList.add('hidden');
                imageInput.value = '';
                selectedImageFile = null;
                if (productContextDiv) productContextDiv.style.display = 'none';
            })
            .catch(function(error) {
                console.error('Fetch error:', error);
                alert('Không thể gửi ảnh: ' + error.message);
            })
            .finally(function() {
                isSubmitting = false; // Reset submission flag
            });
        }

        // Auto-resize textarea
        const chatInput = document.getElementById('chat-input');
        if (chatInput) {
            chatInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 80) + 'px';
            });
            
            // Handle Enter key
            chatInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (!isSubmitting) {
                        chatForm.dispatchEvent(new Event('submit'));
                    }
                }
            });
        }

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (isSubmitting) return; // Prevent double submission
            
            var input = document.getElementById('chat-input');
            var productId = productContextDiv && productContextDiv.style.display !== 'none' ? productContextDiv.dataset.productId : null;

            if (!input.value.trim() || !currentShopId) return;

            isSubmitting = true;
            const messageText = input.value.trim();
            
            // Clear input immediately for better UX
            input.value = '';
            input.style.height = 'auto';

            fetch('/chat/send/' + currentShopId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': chatForm.querySelector('input[name=_token]').value
                },
                body: JSON.stringify({ message: messageText, product_id: productId })
            })
            .then(function(res) {
                if (!res.ok) {
                    console.error('Error sending message:', res.statusText);
                    return res.json().then(err => { throw new Error(err.message || 'Error sending message'); });
                }
                return res.json();
            })
            .then(function(response) {
                console.log('Message send response:', response);
                
                // Use response directly since it's already in the correct format
                const msg = {
                    id: response.id,
                    sender_type: response.sender_type,
                    message: response.message,
                    image_url: response.image_url,
                    created_at: response.created_at
                };
                
                // Track sent message to prevent duplicates
                sentMessageIds.add(msg.id);
                
                // Append the new message immediately
                appendMessage(msg, currentShopName);
                lastMessageId = Math.max(lastMessageId, msg.id); // Update last message ID
                
                if (productContextDiv) productContextDiv.style.display = 'none';
            })
            .catch(function(error) {
                console.error('Fetch error:', error);
                alert('Không thể gửi tin nhắn: ' + error.message);
                // Restore input value if send failed
                input.value = messageText;
            })
            .finally(function() {
                isSubmitting = false; // Reset submission flag
            });
        });

        // Remove product context
        var removeProductBtn = document.getElementById('remove-product-context');
        if(removeProductBtn) {
            removeProductBtn.addEventListener('click', function() {
                if (productContextDiv) productContextDiv.style.display = 'none';
            });
        }

        // Auto-select first shop
        if (shopBtns.length > 0) {
            shopBtns[0].click();
        }
        
        // Pusher Listener - Improved real-time updates
        if (window.Echo && window.Laravel && window.Laravel.user) {
            window.Echo.channel('chat')
                .listen('MessageSent', function(e) {
                    console.log('MessageSent event received on customer side:', e);
                    console.log('currentShopId:', currentShopId);
                    console.log('window.Laravel.user.id:', window.Laravel.user.id);
                    
                    const isMessageForCurrentShop = currentShopId && e.shop_id == currentShopId;
                    const isMessageFromOtherUser = e.user_id != window.Laravel.user.id;
                    const isMessageFromCurrentUser = e.user_id == window.Laravel.user.id;

                    // Handle message from current shop (immediate display)
                    if (isMessageForCurrentShop && isMessageFromOtherUser) {
                        console.log('New message from current shop, displaying immediately...');
                        
                        // Create message object from event data
                        const newMessage = {
                            id: e.id || Date.now(),
                            sender_type: 'shop',
                            message: e.message || '',
                            image_url: e.image_url || null,
                            created_at: new Date().toISOString()
                        };
                        
                        // Append immediately without checking sentMessageIds (since it's from other user)
                        appendMessage(newMessage, currentShopName);
                        lastMessageId = Math.max(lastMessageId, newMessage.id);
                        
                        // Scroll to bottom to show new message
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                        
                        // Play notification sound (optional)
                        playNotificationSound();
                        
                    } else if (isMessageForCurrentShop && isMessageFromCurrentUser) {
                        // Handle own message confirmation
                        console.log('Own message confirmed...');
                        const newMessage = {
                            id: e.id || Date.now(),
                            sender_type: 'user',
                            message: e.message || '',
                            image_url: e.image_url || null,
                            created_at: new Date().toISOString()
                        };
                        
                        // Only append if not already sent by current user
                        if (!sentMessageIds.has(newMessage.id)) {
                            appendMessage(newMessage, currentShopName);
                            lastMessageId = Math.max(lastMessageId, newMessage.id);
                        }
                        
                    } else if (isMessageFromOtherUser && e.shop_id && e.shop_id != currentShopId) {
                        // Message from other shop - update unread badge
                        console.log('New message from other shop, updating unread badge...');
                        
                        // Get current unread count for this shop
                        const shopItem = document.querySelector(`[data-shop-id="${e.shop_id}"]`);
                        if (shopItem) {
                            const unreadBadge = shopItem.querySelector('.unread-badge');
                            let currentCount = 0;
                            
                            if (unreadBadge && unreadBadge.textContent) {
                                const badgeText = unreadBadge.textContent;
                                currentCount = badgeText === '99+' ? 99 : parseInt(badgeText) || 0;
                            }
                            
                            // Increment count
                            const newCount = currentCount + 1;
                            updateUnreadBadge(e.shop_id, newCount);
                            
                            // Play notification sound for messages from other shops
                            playNotificationSound();
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

        // Clean up auto-refresh when page is unloaded
        window.addEventListener('beforeunload', function() {
            stopAutoRefresh();
        });
    }
}); 