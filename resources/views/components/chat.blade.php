<!-- Demo Chat UI Shopee Style (Bubble minimized like chatbot, custom button, draggable resize) -->
<div id="chat-demo" x-data="chatDemo()" class="fixed bottom-6 right-6 z-50">
    <!-- Bubble button (custom style) -->
    <button x-show="!open" @click="open = true"
        class="flex items-center space-x-2 px-4 py-2 rounded-md shadow-md bg-white border border-gray-200 hover:shadow-lg transition-all duration-200"
        style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <i class="fas fa-comment-alt text-[#f15a40] text-lg"></i>
        <span class="text-[#f15a40] font-semibold text-base leading-none">Chat</span>
    </button>
    <!-- Chat box -->
    <div x-show="open" x-transition :style="'width: ' + width + 'px; max-width: 98vw;'" class="shadow-lg relative">
        <div class="max-w-4xl border border-gray-200 rounded-md flex flex-col bg-white" :style="'height: 600px; width: 100%;'">
            <!-- Header -->
            <div class="flex justify-between items-center px-4 py-2 border-b border-gray-200 relative">
                <h1 class="text-orange-600 font-semibold text-lg select-none">Chat</h1>
                <div class="flex items-center space-x-2 text-gray-600 text-base cursor-pointer">
                    <!-- Nút kéo dày/thu hẹp chat (hiệu ứng động khi mở rộng/thu hẹp) -->
                    <button aria-label="Toggle width" class="hover:text-orange-600 px-1 transition-transform duration-300"
                        @mousedown.prevent="startResize($event)"
                        @click="toggleWidth()"
                        :class="{'rotate-90': width >= 650, 'rotate-0': width < 650}"
                        title="Kéo dày/thu hẹp khung chat">
                        <i class="fas fa-grip-lines-vertical"></i>
                    </button>
                    <!-- Nút đóng -->
                    <button aria-label="Minimize chat" class="hover:text-gray-900" @click="open = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="flex flex-1 overflow-hidden">
                <!-- Left panel -->
                <div class="w-80 border-r border-gray-200 flex flex-col">
                    <!-- Search and filter -->
                    <div class="flex items-center px-3 py-2 border-b border-gray-200 space-x-2">
                        <div class="flex items-center bg-gray-50 rounded-md border border-gray-200 px-2 py-1 flex-grow">
                            <i class="fas fa-search text-gray-400 text-sm"></i>
                            <input aria-label="Search by name" class="bg-transparent placeholder-gray-400 text-xs ml-2 w-full focus:outline-none" placeholder="Tìm theo tê" type="text"/>
                        </div>
                        <button aria-expanded="false" aria-haspopup="listbox" class="flex items-center text-xs text-gray-700 font-semibold hover:text-gray-900">
                            Tất cả
                            <svg aria-hidden="true" class="ml-1 w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Chat list -->
                    <div class="flex-1 overflow-y-auto scrollbar-thin">
                        <template x-for="conversation in conversations" :key="conversation.id || conversation.user_id">
                            <div class="flex items-start px-3 py-3 border-b border-gray-100 cursor-pointer hover:bg-gray-50"
                                :class="{'bg-orange-50': isCustomer ? conversation.id === selectedConversation.id : conversation.user_id === selectedConversation.user_id}"
                                @click="selectConversation(conversation)">
                                <img :src="(isCustomer ? (conversation.shop_logo ? '/storage/' + conversation.shop_logo : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(conversation.shop_name)) : (conversation.user_avatar ? '/storage/' + conversation.user_avatar : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(conversation.user_name)))" class="w-6 h-6 flex-shrink-0 rounded-sm object-cover bg-gray-200" />
                                <div class="ml-3 flex-1 min-w-0">
                                    <div class="flex justify-between items-center">
                                        <p class="text-xs font-bold text-gray-900 truncate max-w-[130px]" x-text="isCustomer ? conversation.shop_name : conversation.user_name"></p>
                                    </div>
                                    <p class="text-xs text-gray-600 truncate max-w-[130px]" x-text="conversation.last_message"></p>
                                </div>
                            </div>
                        </template>
                        <template x-if="conversations.length === 0">
                            <div class="text-xs text-gray-400 px-3 py-4">Không có cuộc trò chuyện nào.</div>
                        </template>
                    </div>
                </div>
                <!-- Right panel -->
                <div x-show="selectedConversation" class="flex-1 flex flex-col bg-gray-50">
                    <!-- Chat header -->
                    <div class="flex items-center px-4 py-2 border-b border-gray-200">
                        <img :src="(isCustomer ? (selectedConversation.shop_logo ? '/storage/' + selectedConversation.shop_logo : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(selectedConversation.shop_name)) : (selectedConversation.user_avatar ? '/storage/' + selectedConversation.user_avatar : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(selectedConversation.user_name)))" class="w-8 h-8 rounded-full object-cover" />
                        <p class="text-sm font-semibold ml-3" x-text="isCustomer ? selectedConversation.shop_name : selectedConversation.user_name"></p>
                    </div>
                    <!-- Messages -->
                    <div class="flex-1 overflow-y-auto scrollbar-thin p-4 space-y-4" x-ref="messagesContainer">
                        <template x-for="message in messages" :key="message.id">
                            <div class="flex" :class="{'justify-end': (userRole === 'customer' && message.sender_type === 'user') || (userRole === 'seller' && message.sender_type === 'shop'), 'justify-start': !((userRole === 'customer' && message.sender_type === 'user') || (userRole === 'seller' && message.sender_type === 'shop'))}">
                                <div class="flex items-end" :class="{'flex-row-reverse': (userRole === 'customer' && message.sender_type === 'user') || (userRole === 'seller' && message.sender_type === 'shop')}">
                                    <img :src="message.sender_avatar ? '/storage/' + message.sender_avatar : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(message.sender_name)" class="w-6 h-6 rounded-full object-cover flex-shrink-0" />
                                    <div class="rounded-lg p-2 max-w-xs mx-2 text-sm"
                                        :class="{'bg-orange-500 text-white': (userRole === 'customer' && message.sender_type === 'user') || (userRole === 'seller' && message.sender_type === 'shop'), 'bg-gray-200 text-gray-800': !((userRole === 'customer' && message.sender_type === 'user') || (userRole === 'seller' && message.sender_type === 'shop'))}">
                                        <p x-text="message.message"></p>
                                        <img x-show="message.image_url" :src="message.image_url" class="max-w-full h-auto mt-2 rounded-md" />
                                        <span class="block text-right text-xs mt-1" :class="{'text-orange-200': (userRole === 'customer' && message.sender_type === 'user') || (userRole === 'seller' && message.sender_type === 'shop'), 'text-gray-500': !((userRole === 'customer' && message.sender_type === 'user') || (userRole === 'seller' && message.sender_type === 'shop'))}" x-text="new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <!-- Message input -->
                    <div class="border-t border-gray-200 p-4">
                        <form @submit.prevent="sendMessage" class="flex items-center space-x-2">
                            <input type="file" x-ref="imageInput" @change="handleImageChange" class="hidden" accept="image/*" />
                            <button type="button" @click="$refs.imageInput.click()" class="text-gray-500 hover:text-orange-500">
                                <i class="fas fa-image text-lg"></i>
                            </button>
                            <input type="text" x-model="newMessage" placeholder="Nhập tin nhắn..." class="flex-1 border rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-orange-500" />
                            <button type="submit" class="bg-orange-600 text-white rounded-full p-2 w-9 h-9 flex items-center justify-center hover:bg-orange-700">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                        <div x-show="previewImage" class="mt-2 relative">
                            <img :src="previewImage" class="max-w-[100px] h-auto rounded-md" />
                            <button @click="removeImage" class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div x-show="!selectedConversation" class="flex-1 bg-gray-100 flex flex-col justify-center items-center px-6">
                    <img alt="Illustration of a laptop with a blue chat bubble and a red chat bubble with three white dots" class="mb-4" draggable="false" height="120" src="https://storage.googleapis.com/a1aa/image/0ed79df3-c2cf-4dc0-2a97-e1c61b1597a1.jpg" width="150"/>
                    <p class="text-sm font-semibold text-gray-900 mb-1 select-none">Chào mừng bạn đến với Shopee Chat</p>
                    <p class="text-xs text-gray-700 select-none">Bắt đầu trả lời người mua!</p>
                </div>
            </div>
            <!-- Thanh kéo resize (hiện rõ, cho phép kéo ra/kéo vào) -->
            <div @mousedown.prevent="startResize($event)"
                style="position: absolute; top: 0; right: 0; width: 10px; height: 100%; cursor: ew-resize; z-index: 20; background: linear-gradient(to left, #f3f4f6 60%, transparent); border-top-right-radius: 6px; border-bottom-right-radius: 6px; opacity: 0.7; transition: background 0.2s;"
                @mouseenter="hovering=true" @mouseleave="hovering=false">
            </div>
        </div>
        <style>
            .scrollbar-thin::-webkit-scrollbar { width: 6px; }
            .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
            .scrollbar-thin::-webkit-scrollbar-thumb { background-color: #d1d5db; border-radius: 3px; }
        </style>
    </div>
    <!-- End Demo Chat UI -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('chatDemo', () => ({
            open: false,
            width: 420,
            animating: false,
            conversations: [], // Generic for shops or users
            selectedConversation: null,
            messages: [],
            newMessage: '',
            selectedImage: null,
            previewImage: null,
            userRole: '{{ Auth::user()->role ?? null }}',
            currentUserId: '{{ Auth::id() ?? null }}',
            userShopId: '{{ Auth::user()->shop->id ?? null }}', // Add user's shop ID for sellers
            isCustomer: false,
            isSeller: false,
            currentChannel: null, // To store the current Pusher channel subscription

            init() {
                this.isCustomer = this.userRole === 'customer';
                this.isSeller = this.userRole === 'seller';
                this.fetchConversations();

                // For sellers, listen to general shop notifications for new incoming chats
                if (this.isSeller && this.userShopId) {
                    window.Echo.private(`seller.shop.notifications.${this.userShopId}`)
                        .listen('.message.new', (e) => { // Listen for broadcastAs 'message.new'
                            console.log('Seller notification message received:', e.message);
                            // Update the last message in the conversation list for the relevant user
                            const convIndex = this.conversations.findIndex(conv =>
                                conv.user_id === e.message.user_id && conv.shop_id === e.message.shop_id
                            );

                            if (convIndex !== -1) {
                                this.conversations[convIndex].last_message = e.message.message || '(Ảnh)';
                                this.conversations[convIndex].last_message_time = e.message.created_at;
                                // Re-sort conversations
                                this.conversations.sort((a, b) => new Date(b.last_message_time) - new Date(a.last_message_time));
                            }

                            // If this message is for the currently selected conversation, add it to messages
                            if (this.selectedConversation &&
                                e.message.shop_id === this.selectedConversation.shop_id &&
                                e.message.user_id === this.selectedConversation.user_id) {
                                this.addMessage(e.message);
                            }
                        });
                }
            },
            async fetchConversations() {
                try {
                    let url = '';
                    if (this.isCustomer) {
                        url = '/api/shops-to-chat';
                    } else if (this.isSeller) {
                        url = '/api/seller/chat/qa/messages';
                    }

                    if (url) {
                        const res = await fetch(url);
                        if (!res.ok) {
                            throw new Error(`HTTP error! status: ${res.status}`);
                        }
                        this.conversations = await res.json();
                        if (this.conversations.length > 0) {
                            this.selectConversation(this.conversations[0]);
                        }
                    }
                } catch (error) {
                    console.error("Error fetching conversations:", error);
                    this.conversations = [];
                }
            },
            async selectConversation(conversation) {
                // Unsubscribe from previous channel if exists
                if (this.currentChannel) {
                    window.Echo.leave(this.currentChannel);
                    console.log('Left channel:', this.currentChannel);
                }

                this.selectedConversation = conversation;
                this.messages = []; // Clear previous messages

                let channelName = '';
                if (this.isCustomer) {
                    channelName = `chat.${this.selectedConversation.id}.${this.currentUserId}`;
                } else if (this.isSeller) {
                    channelName = `chat.${this.selectedConversation.shop_id}.${this.selectedConversation.user_id}`;
                }

                if (channelName) {
                    console.log('Listening on channel:', channelName);
                    this.currentChannel = channelName; // Store the current channel
                    window.Echo.private(channelName)
                        .listen('.message.new', (e) => { // Listen for broadcastAs 'message.new'
                            console.log('Message received on conversation channel:', e.message);
                            // Add message to the current conversation's messages
                            this.addMessage(e.message);

                            // Update last message in conversation list
                            const convIndex = this.conversations.findIndex(conv => {
                                if (this.isCustomer) return conv.id === e.message.shop_id;
                                if (this.isSeller) return conv.user_id === e.message.user_id && conv.shop_id === e.message.shop_id;
                                return false;
                            });

                            if (convIndex !== -1) {
                                this.conversations[convIndex].last_message = e.message.message || '(Ảnh)';
                                this.conversations[convIndex].last_message_time = e.message.created_at;
                                this.conversations.sort((a, b) => new Date(b.last_message_time) - new Date(a.last_message_time));
                            }
                        });
                }

                try {
                    let url = '';
                    if (this.isCustomer) {
                        url = `/api/chat/messages/${conversation.id}`;
                    } else if (this.isSeller) {
                        url = `/api/seller/chat/messages/${conversation.user_id}`;
                    }

                    if (url) {
                        const res = await fetch(url);
                        if (!res.ok) {
                            throw new Error(`HTTP error! status: ${res.status}`);
                        }
                        this.messages = await res.json();
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                    }
                } catch (error) {
                    console.error("Error fetching messages:", error);
                    this.messages = [];
                }
            },
            async sendMessage() {
                if ((!this.newMessage.trim() && !this.selectedImage) || !this.selectedConversation) {
                    return;
                }

                const formData = new FormData();
                formData.append('shop_id', this.isCustomer ? this.selectedConversation.id : this.selectedConversation.shop_id);
                formData.append('user_id', this.isCustomer ? this.currentUserId : this.selectedConversation.user_id); // This is the customer's ID

                if (this.newMessage.trim()) {
                    formData.append('message', this.newMessage.trim());
                }
                if (this.selectedImage) {
                    formData.append('image', this.selectedImage);
                }

                try {
                    const res = await fetch('/api/chat/send-message', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }

                    const result = await res.json();
                    if (result.status === 'success') {
                        // Add the new message to the messages array
                        // For real-time updates from other users/shops, Laravel Echo would be used.
                        const newMessageData = {
                            id: result.data.id,
                            shop_id: result.data.shop_id,
                            user_id: result.data.user_id,
                            sender_type: result.data.sender_type,
                            message: result.data.message,
                            image_url: result.data.image_url,
                            created_at: result.data.created_at,
                            sender_name: result.data.sender_type === 'user' ? (this.isCustomer ? 'You' : this.selectedConversation.user_name) : (this.isSeller ? 'You' : this.selectedConversation.shop_name),
                            sender_avatar: result.data.sender_type === 'user' ? (this.isCustomer ? null : this.selectedConversation.user_avatar) : (this.isSeller ? null : this.selectedConversation.shop_logo),
                        };
                        this.messages.push(newMessageData);

                        // Update last message in conversation list
                        const convIndex = this.conversations.findIndex(conv => {
                            if (this.isCustomer) return conv.id === newMessageData.shop_id;
                            if (this.isSeller) return conv.user_id === newMessageData.user_id && conv.shop_id === newMessageData.shop_id;
                            return false;
                        });
                        if (convIndex !== -1) {
                            this.conversations[convIndex].last_message = newMessageData.message || '(Ảnh)';
                            this.conversations[convIndex].last_message_time = newMessageData.created_at;
                            this.conversations.sort((a, b) => new Date(b.last_message_time) - new Date(a.last_message_time));
                        }

                        this.newMessage = '';
                        this.selectedImage = null;
                        this.previewImage = null;
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                    }
                }
                catch (error) {
                    console.error("Error sending message:", error);
                }
            },
            handleImageChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.selectedImage = file;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previewImage = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.selectedImage = null;
                    this.previewImage = null;
                }
            },
            removeImage() {
                this.selectedImage = null;
                this.previewImage = null;
                this.$refs.imageInput.value = ''; // Clear file input
            },
            scrollToBottom() {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            },
            startResize(e) {
                let resizing = true;
                const startX = e.clientX;
                const startWidth = this.width;
                const move = (ev) => {
                    if (!resizing) return;
                    let newWidth = startWidth + (ev.clientX - startX);
                    newWidth = Math.max(320, Math.min(newWidth, 700));
                    this.width = newWidth;
                };
                const up = () => {
                    resizing = false;
                    document.removeEventListener('mousemove', move);
                    document.removeEventListener('mouseup', up);
                    document.body.style.userSelect = '';
                };
                document.addEventListener('mousemove', move);
                document.addEventListener('mouseup', up);
                document.body.style.userSelect = 'none';
            },
            expandWidth() {
                this.width = 700;
            },
            toggleWidth() {
                const target = this.width < 650 ? 700 : 420;
                const step = target > this.width ? 16 : -16;
                this.animating = true;
                const animate = () => {
                    if ((step > 0 && this.width < target) || (step < 0 && this.width > target)) {
                        this.width += step;
                        if ((step > 0 && this.width > target) || (step < 0 && this.width < target)) {
                            this.width = target;
                        }
                        requestAnimationFrame(animate);
                    } else {
                        this.width = target;
                        this.animating = false;
                    }
                };
                animate();
            }
        }));
    });
</script> 