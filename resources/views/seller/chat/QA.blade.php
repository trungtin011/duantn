@extends('layouts.seller_home')
@section('title', 'Trợ Lý Chat - Hỏi Đáp')
@section('content')
<div class="bg-white text-gray-900 font-sans px-4 py-6 max-w-3xl mx-auto relative" id="qa-chat-resizable" style="resize: none; min-width: 320px; width: 100%; max-width: 700px;"
    x-data="qaChatDemo({
        sellerShopId: '{{ Auth::user()->shop->id ?? null }}',
        csrfToken: '{{ csrf_token() }}',
        pusherKey: '{{ env('PUSHER_APP_KEY') }}',
        pusherCluster: '{{ env('PUSHER_APP_CLUSTER') }}'
    })">
   <!-- Thanh kéo resize -->
   <div id="qa-chat-dragger" style="position: absolute; top: 0; right: 0; width: 8px; height: 100%; cursor: ew-resize; z-index: 10;"></div>
   <!-- Nội dung chat QA -->
   <h1 class="text-lg font-semibold leading-6">
    Trợ Lý Chat
   </h1>
   <p class="text-sm text-gray-500 mt-1 max-w-[480px]">
    Sử dụng các công cụ khác nhau trong trợ lý chat để làm cho dịch vụ hỗ trợ khách hàng của bạn hiệu quả hơn.
   </p>
   <nav class="flex space-x-6 mt-6 border-b border-gray-200">
    <a href="{{ route('seller.chat.chatautomatically') }}" class="text-sm font-normal text-gray-700 pb-2">Tin nhắn tự động</a>
    <button aria-current="page" class="text-sm font-semibold text-[#D14324] border-b-2 border-[#D14324] pb-2" type="button">
     Hỏi - Đáp
    </button>
    <button class="text-sm font-normal text-gray-700 pb-2" type="button">
     Phím tắt tin nhắn
    </button>
   </nav>
   <div class="mt-10 max-w-xl mx-auto">
    <nav class="flex space-x-2 mb-6">
      <button @click="activeTab = 'shopQuestions'" :class="{'border-orange-600 text-orange-600': activeTab === 'shopQuestions', 'border-gray-300 text-gray-600': activeTab !== 'shopQuestions'}" class="tab-btn text-xs font-semibold px-4 py-2 rounded border bg-white">Câu hỏi của Shop</button>
      <button @click="activeTab = 'overview'" :class="{'border-orange-600 text-orange-600': activeTab === 'overview', 'border-gray-300 text-gray-600': activeTab !== 'overview'}" class="tab-btn text-xs font-semibold px-4 py-2 rounded border bg-white">Bảng tổng quan Hỏi-đáp khách hàng</button>
    </nav>
    <div x-show="activeTab === 'shopQuestions'">
      <h2 class="text-base font-semibold mb-3">Câu hỏi cho khách hàng</h2>
      <div id="qa-shop-list" class="space-y-3">
        <template x-if="shopQuestions.length === 0">
            <div class="text-xs text-gray-500">Chưa có câu hỏi nào của Shop.</div>
        </template>
        <template x-for="msg in shopQuestions" :key="msg.id">
            <div class='border rounded p-3 bg-orange-50 shadow flex flex-col'>
              <div class='flex items-center mb-1'>
                <span class='font-semibold text-orange-600 text-xs mr-2'>Shop</span>
                <span class='text-xs text-gray-400' x-text="new Date(msg.created_at).toLocaleString()"></span>
              </div>
              <div class='text-sm' x-text="msg.message"></div>
            </div>
        </template>
      </div>
      <form @submit.prevent="sendShopQuestion" class="flex items-center space-x-2 mt-4">
        <input x-model="newShopQuestion" type="text" class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-orange-500" placeholder="Nhập câu hỏi cho khách hàng..." autocomplete="off" />
        <button type="submit" class="bg-orange-600 text-white text-xs font-semibold rounded px-4 py-2 hover:bg-orange-700">Gửi câu hỏi</button>
      </form>
    </div>
    <div x-show="activeTab === 'overview'">
        <h2 class="text-base font-semibold mb-3">Bảng tổng quan Hỏi-đáp khách hàng</h2>
        <div id="qa-overview-list" class="space-y-4">
            <template x-if="conversations.length === 0">
                <div class="text-xs text-gray-500">Chưa có tin nhắn nào từ khách hàng.</div>
            </template>
            <template x-for="conv in conversations" :key="conv.user_id">
                <div :id="`conversation-${conv.user_id}`" class='border rounded p-3 bg-white shadow flex flex-col'>
                    <div class='flex items-center mb-1'>
                        <span class='font-semibold text-orange-600 text-xs mr-2' x-text="conv.user_name || 'Khách hàng'"></span>
                        <span class='text-xs text-gray-400' x-text="conv.last_message_time ? new Date(conv.last_message_time).toLocaleString() : ''"></span>
                    </div>
                    <!-- Messages container for this conversation -->
                    <div class='messages-container text-sm mb-2 max-h-48 overflow-y-auto border-b pb-2'
                         x-init="fetchMessagesForConversation(conv.user_id, $el)">
                        <template x-for="msg in conv.messages" :key="msg.id">
                            <div class='flex' :class="{'justify-end': msg.sender_type === 'shop', 'justify-start': msg.sender_type !== 'shop'}">
                                <div class='max-w-xs rounded px-3 py-2 text-sm shadow mb-1'
                                     :class="{'bg-orange-100': msg.sender_type === 'shop', 'bg-white': msg.sender_type !== 'shop'}">
                                    <span class='block font-semibold text-xs mb-1' x-text="msg.sender_type === 'shop' ? 'Shop' : (msg.sender_name || 'Khách')"></span>
                                    <img x-show="msg.image_url" :src="`/storage/${msg.image_url}`" class="max-w-xs max-h-48 rounded mb-1" />
                                    <span x-show="msg.message" x-text="msg.message"></span>
                                    <div class='text-[10px] text-gray-400 mt-1 text-right' x-text="new Date(msg.created_at).toLocaleString()"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <form @submit.prevent="replyToCustomer(conv.user_id)" class='flex items-center space-x-2 qa-reply-form mt-2'>
                        <input type="file" :id="`imageInput-${conv.user_id}`" class="hidden" accept="image/*" @change="handleImageChangeForConv(conv.user_id, $event)" />
                        <button type="button" @click="document.getElementById(`imageInput-${conv.user_id}`).click()" class="text-gray-500 hover:text-orange-500">
                            <i class="fas fa-image text-lg"></i>
                        </button>
                        <input x-model="conv.newMessage" type='text' class='flex-1 border border-gray-300 rounded px-2 py-1 text-xs' placeholder='Trả lời khách...' />
                        <button type='submit' class='bg-orange-600 text-white text-xs rounded px-3 py-1'>Gửi</button>
                    </form>
                    <div x-show="conv.previewImage" class="mt-2 relative">
                        <img :src="conv.previewImage" class="max-w-[100px] h-auto rounded-md" />
                        <button @click="removeImageForConv(conv.user_id)" class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
   </div>
   <div class="mt-6 max-w-xl">
    <div class="flex items-center space-x-2">
     <img alt="Shopee logo icon in red square" class="w-5 h-5" height="20" src="https://storage.googleapis.com/a1aa/image/6a99512d-aea7-48c7-172f-0fa94513a0cb.jpg" width="20"/>
     <span class="text-xs text-gray-700">
      VNshopeeseller
     </span>
    </div>
   </div>
   <div x-show="conversations.length === 0 && activeTab === 'overview'" class="mt-20 max-w-xl mx-auto text-center bg-gradient-to-b from-white to-gray-200 py-20 rounded-sm">
    <img alt="Gray outlined box with a ribbon icon" class="mx-auto mb-4" height="80" src="https://storage.googleapis.com/a1aa/image/377225c4-4c7d-435f-f271-0e61ff484fbf.jpg" width="80"/>
    <p class="text-xs text-gray-500 max-w-xs mx-auto mb-4">
     Không có câu hỏi nào được tìm thấy, bạn có thể dùng tài khoản chính để tạo câu hỏi của Shop
    </p>
    <button class="bg-orange-600 text-white text-xs font-semibold rounded px-4 py-1.5 hover:bg-orange-700 focus:outline-none" type="button" @click="activeTab = 'shopQuestions'; newShopQuestion = 'Tạo câu hỏi cho Shop';">
     Tạo câu hỏi cho Shop
    </button>
   </div>
   <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.0/dist/echo.iife.js"></script>
   <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('qaChatDemo', (initialData) => ({
            sellerShopId: initialData.sellerShopId,
            csrfToken: initialData.csrfToken,
            pusherKey: initialData.pusherKey,
            pusherCluster: initialData.pusherCluster,
            activeTab: 'overview', // 'overview' or 'shopQuestions'
            conversations: [],
            shopQuestions: [],
            newShopQuestion: '',

            init() {
                this.initializeEcho();
                this.fetchConversations();
                this.fetchShopQuestions();
            },

            initializeEcho() {
                if (!this.sellerShopId) {
                    console.warn('Seller Shop ID is not available. Real-time chat for seller might not work.');
                    return;
                }
                window.Pusher = Pusher;
                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: this.pusherKey,
                    cluster: this.pusherCluster,
                    encrypted: true,
                    authEndpoint: '/broadcasting/auth',
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                        },
                    },
                });

                window.Echo.connector.pusher.connection.bind('state_change', (states) => {
                    console.log('Pusher connection state (seller QA):', states.current);
                });

                window.Echo.connector.pusher.connection.bind('error', (err) => {
                    console.error('Pusher connection error (seller QA):', err);
                });

                console.log('Laravel Echo initialized for Seller QA.');

                // Listen for messages directed to this shop
                window.Echo.private(`seller.shop.notifications.${this.sellerShopId}`)
                    .listen('.message.new', (e) => {
                        console.log('New message received for seller:', e.message);
                        // Update the relevant conversation
                        const convIndex = this.conversations.findIndex(c => c.user_id === e.message.user_id);
                        if (convIndex !== -1) {
                            const conv = this.conversations[convIndex];
                            // If the messages are already loaded for this conversation, add it
                            if (conv.messages) {
                                conv.messages.push({
                                    id: e.message.id,
                                    shop_id: e.message.shop_id,
                                    user_id: e.message.user_id,
                                    sender_type: e.message.sender_type,
                                    message: e.message.message,
                                    image_url: e.message.image_url,
                                    created_at: e.message.created_at,
                                    sender_name: e.message.sender_type === 'user' ? (conv.user_name || 'Khách') : 'Shop',
                                    sender_avatar: e.message.sender_type === 'user' ? conv.user_avatar : null,
                                });
                                this.$nextTick(() => {
                                    const container = document.querySelector(`#conversation-${conv.user_id} .messages-container`);
                                    if (container) container.scrollTop = container.scrollHeight;
                                });
                            }
                            // Update last message preview
                            conv.last_message = e.message.message || '(Ảnh)';
                            conv.last_message_time = e.message.created_at;
                            this.conversations.sort((a, b) => new Date(b.last_message_time) - new Date(a.last_message_time));
                        } else {
                            // If it's a new conversation, re-fetch all conversations
                            this.fetchConversations();
                        }
                    })
                    .error((error) => {
                        console.error('Pusher seller notifications channel error:', error);
                    });

                // Listen for private messages if the seller is currently viewing a specific chat
                window.Echo.private(`chat.${this.sellerShopId}.{{ Auth::id() ?? null }}`) // This is not correct. It should be user_id of the customer.
                    .listen('MessageSent', (e) => {
                        console.log('Private chat message received:', e.message);
                        // This specific channel listen is likely not needed if seller.shop.notifications covers all.
                        // However, if implemented, it should be for the specific user they are chatting with.
                        // This part might need further refinement based on how specific seller-to-customer chat is managed.
                    });
            },

            async fetchConversations() {
                try {
                    const res = await fetch('/api/seller/chat/qa/messages');
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    this.conversations = await res.json();
                    this.conversations.forEach(conv => {
                        conv.messages = []; // Initialize messages array for each conversation
                        conv.newMessage = ''; // Initialize message input for each conversation
                        conv.selectedImage = null; // Initialize selected image for each conversation
                        conv.previewImage = null; // Initialize preview image for each conversation
                    });

                    // Pre-fetch messages for all conversations (or just the first few for performance)
                    // For simplicity, we'll fetch only when a conversation is selected via fetchMessagesForConversation
                } catch (error) {
                    console.error("Error fetching conversations:", error);
                    this.conversations = [];
                }
            },

            async fetchMessagesForConversation(userId, containerEl) {
                const conv = this.conversations.find(c => c.user_id === userId);
                if (!conv) return;

                try {
                    const res = await fetch(`/api/seller/chat/messages/${userId}`);
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    conv.messages = await res.json();
                    this.$nextTick(() => {
                        if (containerEl) containerEl.scrollTop = containerEl.scrollHeight;
                    });
                } catch (error) {
                    console.error(`Error fetching messages for user ${userId}:`, error);
                    conv.messages = [];
                }
            },

            async replyToCustomer(userId) {
                const conv = this.conversations.find(c => c.user_id === userId);
                if (!conv || (!conv.newMessage.trim() && !conv.selectedImage)) {
                    return;
                }

                const formData = new FormData();
                formData.append('shop_id', this.sellerShopId);
                formData.append('user_id', userId); // The customer's ID
                formData.append('sender_type', 'shop'); // Seller is sending
                if (conv.newMessage.trim()) {
                    formData.append('message', conv.newMessage.trim());
                }
                if (conv.selectedImage) {
                    formData.append('image', conv.selectedImage);
                }

                try {
                    const res = await fetch('/api/chat/send-message', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }

                    const result = await res.json();
                    if (result.status === 'success') {
                        conv.messages.push({
                            id: result.data.id,
                            shop_id: result.data.shop_id,
                            user_id: result.data.user_id,
                            sender_type: result.data.sender_type,
                            message: result.data.message,
                            image_url: result.data.image_url,
                            created_at: result.data.created_at,
                            sender_name: 'Shop',
                            sender_avatar: null, // Assuming shop avatar is not sent in message data
                        });
                        conv.newMessage = '';
                        conv.selectedImage = null; // Clear image after sending
                        conv.previewImage = null; // Clear image preview
                        document.getElementById(`imageInput-${userId}`).value = ''; // Clear file input
                        this.$nextTick(() => {
                            const container = document.querySelector(`#conversation-${userId} .messages-container`);
                            if (container) container.scrollTop = container.scrollHeight;
                        });
                        // Update last message preview for this conversation
                        conv.last_message = result.data.message || '(Ảnh)';
                        conv.last_message_time = result.data.created_at;
                        this.conversations.sort((a, b) => new Date(b.last_message_time) - new Date(a.last_message_time));
                    }
                } catch (error) {
                    console.error("Error sending message:", error);
                }
            },

            handleImageChangeForConv(userId, event) {
                const conv = this.conversations.find(c => c.user_id === userId);
                if (!conv) return;

                const file = event.target.files[0];
                if (file) {
                    conv.selectedImage = file;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        conv.previewImage = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    conv.selectedImage = null;
                    conv.previewImage = null;
                }
            },

            removeImageForConv(userId) {
                const conv = this.conversations.find(c => c.user_id === userId);
                if (!conv) return;

                conv.selectedImage = null;
                conv.previewImage = null;
                document.getElementById(`imageInput-${userId}`).value = ''; // Clear file input
            },

            async fetchShopQuestions() {
                try {
                    const res = await fetch('/api/seller/chat/qa/messages'); // This API returns all messages
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    const allMessages = await res.json();
                    // Filter for messages sent by the shop itself (as questions/templates)
                    this.shopQuestions = allMessages.filter(msg => msg.sender_type === 'shop');
                } catch (error) {
                    console.error("Error fetching shop questions:", error);
                    this.shopQuestions = [];
                }
            },

            async sendShopQuestion() {
                if (!this.newShopQuestion.trim() || !this.sellerShopId) {
                    return;
                }

                const formData = new FormData();
                formData.append('shop_id', this.sellerShopId);
                formData.append('user_id', '{{ Auth::id() ?? null }}'); // Assuming seller user ID for shop questions
                formData.append('sender_type', 'shop');
                formData.append('message', this.newShopQuestion.trim());

                try {
                    const res = await fetch('/api/chat/send-message', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }

                    const result = await res.json();
                    if (result.status === 'success') {
                        this.newShopQuestion = '';
                        this.fetchShopQuestions(); // Reload shop questions
                    }
                } catch (error) {
                    console.error("Error sending shop question:", error);
                }
            },

            // Kéo ra/kéo vào khung chat QA
            initResizable() {
                const resizable = document.getElementById('qa-chat-resizable');
                const dragger = document.getElementById('qa-chat-dragger');
                let isResizing = false;
                let startX, startWidth;
                dragger.addEventListener('mousedown', function(e) {
                    isResizing = true;
                    startX = e.clientX;
                    startWidth = resizable.offsetWidth;
                    document.body.style.userSelect = 'none';
                });
                document.addEventListener('mousemove', function(e) {
                    if (!isResizing) return;
                    let newWidth = startWidth + (e.clientX - startX);
                    newWidth = Math.max(320, Math.min(newWidth, 700)); // Giới hạn min/max
                    resizable.style.maxWidth = newWidth + 'px';
                    resizable.style.width = newWidth + 'px';
                });
                document.addEventListener('mouseup', function() {
                    isResizing = false;
                    document.body.style.userSelect = '';
                });
            }
        }));
    });
   </script>
  </div>
@endsection
