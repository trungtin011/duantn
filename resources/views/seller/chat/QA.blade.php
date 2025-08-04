@extends('layouts.seller_home')
@section('title', 'Trợ Lý Chat - Hỏi Đáp')
@section('content')
<div class="bg-white text-gray-900 font-sans px-4 py-6 max-w-3xl mx-auto relative" id="qa-chat-resizable" style="resize: none; min-width: 320px; width: 100%; max-width: 700px;"
    x-data="qaChatDemo({
        sellerShopId: '{{ optional(optional(Auth::user())->seller)->shop->id ?? null }}',
        csrfToken: '{{ csrf_token() }}',
        pusherKey: '{{ config('broadcasting.connections.pusher.key') }}',
        pusherCluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
        sellerShopLogo: '{{ optional(optional(Auth::user())->seller)->shop->shop_logo ? Storage::url(optional(optional(Auth::user())->seller)->shop->shop_logo) : null }}'
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
        <div class="flex border border-gray-200 rounded-md bg-white" style="height: 500px;">
            <!-- Left Panel: Conversation List -->
            <div class="w-80 border-r border-gray-200 flex flex-col">
                <div class="flex items-center px-3 py-2 border-b border-gray-200 space-x-2">
                    <div class="flex items-center bg-gray-50 rounded-md border border-gray-200 px-2 py-1 flex-grow">
                        <i class="fas fa-search text-gray-400 text-sm"></i>
                        <input aria-label="Search by name" class="bg-transparent placeholder-gray-400 text-xs ml-2 w-full focus:outline-none" placeholder="Tìm theo tên" type="text"/>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto scrollbar-thin">
                    <template x-for="conv in conversations" :key="conv.user_id">
                        <div class="flex items-start px-3 py-3 border-b border-gray-100 cursor-pointer hover:bg-gray-50"
                            :class="{'bg-orange-50': selectedConversation && conv.user_id === selectedConversation.user_id}"
                            @click="selectConversation(conv)">
                            <img :src="conv.user_avatar ? conv.user_avatar : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(conv.user_name)" class="w-6 h-6 flex-shrink-0 rounded-sm object-cover bg-gray-200" />
                            <div class="ml-3 flex-1 min-w-0">
                                <div class="flex justify-between items-center">
                                    <p class="text-xs font-bold text-gray-900 truncate max-w-[130px]" x-text="conv.user_name"></p>
                                </div>
                                <p class="text-xs text-gray-600 truncate max-w-[130px]" x-text="conv.last_message"></p>
                            </div>
                        </div>
                    </template>
                    <template x-if="conversations.length === 0">
                        <div class="text-xs text-gray-400 px-3 py-4">Chưa có cuộc trò chuyện nào.</div>
                    </template>
                </div>
            </div>

            <!-- Right Panel: Message Display -->
            <div x-show="selectedConversation" class="flex-1 flex flex-col bg-gray-50">
                <!-- Chat header -->
                <div class="flex items-center px-4 py-2 border-b border-gray-200">
                    <img :src="selectedConversation.user_avatar ? selectedConversation.user_avatar : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(selectedConversation.user_name)" class="w-8 h-8 rounded-full object-cover" />
                    <p class="text-sm font-semibold ml-3" x-text="selectedConversation.user_name"></p>
                </div>
                <!-- Messages -->
                <div class="flex-1 overflow-y-auto scrollbar-thin p-4 space-y-4" x-ref="messagesContainer">
                        <template x-for="message in messages" :key="message.id">
                            <div class="flex" :class="{'justify-end': message.sender_type === 'shop', 'justify-start': message.sender_type !== 'shop'}">
                                <div class="flex items-end" :class="{'flex-row-reverse': message.sender_type === 'shop'}">
                                    <img :src="message.sender_avatar ? message.sender_avatar : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(message.sender_name)" class="w-6 h-6 rounded-full object-cover flex-shrink-0" />
                                    <div class="rounded-lg p-2 max-w-xs mx-2 text-sm"
                                        :class="{'bg-orange-500 text-white': message.sender_type === 'shop', 'bg-gray-200 text-gray-800': message.sender_type !== 'shop'}">
                                        <p x-text="message.message"></p>
                                        <img x-show="message.image_url" :src="message.image_url" class="max-w-full h-auto mt-2 rounded-md" />
                                        <span class="block text-right text-xs mt-1" :class="{'text-orange-200': message.sender_type === 'shop', 'text-gray-500': message.sender_type !== 'shop'}" x-text="new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <!-- Message input -->
                <div class="border-t border-gray-200 p-4">
                    <form @submit.prevent="replyToCustomer(selectedConversation.user_id)" class="flex items-center space-x-2">
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
                <p class="text-sm font-semibold text-gray-900 mb-1 select-none">Chào mừng bạn đến với ZynoxMall Chat</p>
                <p class="text-xs text-gray-700 select-none">Chọn một cuộc trò chuyện để bắt đầu!</p>
            </div>
        </div>
    </div>
   </div>
   <div class="mt-6 max-w-xl">
    <div class="flex items-center space-x-2">
     <img alt="ZynoxMall logo icon in red square" class="w-5 h-5" height="20" src="https://storage.googleapis.com/a1aa/image/6a99512d-aea7-48c7-172f-0fa94513a0cb.jpg" width="20"/>
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
   <script src="{{ asset('js/seller/qa-chat.js') }}"></script>
@endsection
