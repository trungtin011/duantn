document.addEventListener('alpine:init', () => {
    Alpine.data('qaChatDemo', (initialData) => ({
        sellerShopId: initialData.sellerShopId,
        csrfToken: initialData.csrfToken,
        pusherKey: initialData.pusherKey,
        pusherCluster: initialData.pusherCluster,
        sellerShopLogo: initialData.sellerShopLogo,
        activeTab: 'overview', // 'overview' or 'shopQuestions'
        conversations: [],
        selectedConversation: null, // Add selectedConversation
        messages: [], // Add messages array for selected conversation
        newMessage: '', // Message input for selected conversation
        selectedImage: null, // Selected image for message input
        previewImage: null, // Preview image for message input
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
                key: 'efad735d30eec92530b6',
                cluster: 'ap1',
                forceTLS: true,
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
                        let conv = this.conversations[convIndex];
                        console.log('Found existing conversation for user:', conv.user_id);
                        // Update last message preview and time
                        conv.last_message = e.message.message || '(Ảnh)';
                        conv.last_message_time = e.message.created_at;

                        // To ensure reactivity for the conversation object itself, reassign it in the array
                        this.conversations[convIndex] = { ...conv };

                        // Sort conversations by last message time
                        this.conversations.sort((a, b) => new Date(b.last_message_time) - new Date(a.last_message_time));
                        console.log('Conversation updated and sorted.');

                        // If this message is for the currently selected conversation, add it to messages
                        if (this.selectedConversation &&
                            e.message.shop_id === this.selectedConversation.shop_id &&
                            e.message.user_id === this.selectedConversation.user_id) {
                            this.addMessage(e.message);
                        }

                    } else {
                        console.log('New conversation. Re-fetching all conversations.');
                        // If it's a new conversation, re-fetch all conversations to add it to the list
                        this.fetchConversations();
                    }
                })
                .error((error) => {
                    console.error('Pusher seller notifications channel error:', error);
                });

            // This specific channel listener is not correct for receiving customer messages.
            // The 'seller.shop.notifications' channel should handle overall new message alerts.
            // window.Echo.private(`chat.${this.sellerShopId}.{{ Auth::id() ?? null }}`)
            //     .listen('MessageSent', (e) => {
            //         console.log('Private chat message received:', e.message);
            //     });
        },

        async fetchConversations() {
            try {
                const res = await fetch('/api/seller/chat/qa/messages');
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                this.conversations = await res.json();
                if (this.conversations.length > 0) {
                    this.selectConversation(this.conversations[0]); // Select first conversation by default
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

            let channelName = `chat.${this.selectedConversation.shop_id}.${this.selectedConversation.user_id}`;

            if (channelName) {
                console.log('Listening on channel:', channelName);
                this.currentChannel = channelName; // Store the current channel
                window.Echo.private(channelName)
                    .listen('.message.new', (e) => { // Listen for broadcastAs 'message.new'
                        console.log('Message received on conversation channel:', e.message);
                        this.addMessage(e.message);

                        // Update last message in conversation list
                        const convIndex = this.conversations.findIndex(conv =>
                            conv.user_id === e.message.user_id && conv.shop_id === e.message.shop_id
                        );

                        if (convIndex !== -1) {
                            this.conversations[convIndex].last_message = e.message.message || '(Ảnh)';
                            this.conversations[convIndex].last_message_time = e.message.created_at;
                            this.conversations.sort((a, b) => new Date(b.last_message_time) - new Date(a.last_message_time));
                        }
                    })
                    .error((error) => {
                        console.error('Pusher conversation channel error:', error);
                    });
            }

            try {
                // Fetch messages for the selected conversation
                const res = await fetch(`/seller/chat/messages/${conversation.user_id}`);
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                this.messages = await res.json();
                this.$nextTick(() => {
                    const container = this.$refs.messagesContainer; // Use ref for messages container
                    if (container) container.scrollTop = container.scrollHeight;
                });
            } catch (error) {
                console.error(`Error fetching messages for user ${conversation.user_id}:`, error);
                this.messages = [];
            }
        },

        addMessage(message) {
            const senderName = message.sender_type === 'user' ? (this.selectedConversation.user_name || 'Khách') : 'Shop';
            const senderAvatar = message.sender_type === 'user' ? (this.selectedConversation.user_avatar || null) : this.sellerShopLogo; // Use sellerShopLogo for shop messages

            this.messages = [...this.messages, {
                id: message.id,
                shop_id: message.shop_id,
                user_id: message.user_id,
                sender_type: message.sender_type,
                message: message.message,
                image_url: message.image_url,
                created_at: message.created_at,
                sender_name: senderName,
                sender_avatar: senderAvatar,
            }];

            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) container.scrollTop = container.scrollHeight;
            });
        },

        async fetchShopQuestions() {
            try {
                const res = await fetch('/api/seller/chat/qa/messages?type=shop_questions');
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                const data = await res.json();
                this.shopQuestions = data.map(msg => ({
                    id: msg.id,
                    message: msg.message,
                    created_at: msg.created_at,
                    // Add other fields as needed for display, e.g., image_url, sender_name etc.
                }));
            } catch (error) {
                console.error("Error fetching shop questions:", error);
                this.shopQuestions = [];
            }
        },

        async sendShopQuestion() {
            if (!this.newShopQuestion.trim()) return;

            const formData = new FormData();
            formData.append('shop_id', this.sellerShopId);
            formData.append('user_id', 0); // Placeholder, adjust as per backend logic for general shop questions
            formData.append('message', this.newShopQuestion.trim());
            formData.append('sender_type', 'shop');

            try {
                const res = await fetch('/api/chat/send-message', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: formData,
                });

                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }

                const data = await res.json();
                console.log('Shop question sent:', data);

                this.shopQuestions.push({
                    id: data.data.id,
                    message: data.data.message,
                    created_at: data.data.created_at,
                });
                this.newShopQuestion = ''; // Clear input

            } catch (error) {
                console.error("Error sending shop question:", error);
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
            if (this.$refs.imageInput) {
                this.$refs.imageInput.value = '';
            }
        },

        async replyToCustomer(userId) {
            if (!this.newMessage.trim() && !this.selectedImage) {
                return;
            }

            const formData = new FormData();
            formData.append('shop_id', this.sellerShopId);
            formData.append('user_id', userId);
            formData.append('message', this.newMessage.trim());
            formData.append('sender_type', 'shop');

            if (this.selectedImage) {
                formData.append('image', this.selectedImage);
            }

            try {
                const res = await fetch('/api/chat/send-message', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: formData,
                });

                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }

                const data = await res.json();
                console.log('Message sent:', data);

                this.addMessage(data.data); // Add the newly sent message to the chat
                this.newMessage = ''; // Clear text input
                this.removeImage(); // Clear image input and preview

            } catch (error) {
                console.error("Error sending message:", error);
            }
        }
    }));
}); 