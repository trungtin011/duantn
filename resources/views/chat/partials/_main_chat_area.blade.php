<main class="flex flex-col flex-1 bg-gray-50">
    <!-- Chat header -->
    <header id="chat-header" class="flex items-center justify-between h-12 px-4 bg-white border-b border-gray-200 shadow-sm">
        <div class="flex items-center space-x-2">
            <div class="relative">
                <img id="current-shop-avatar" class="w-8 h-8 rounded-full border border-gray-200" src="{{ asset('images/default_shop_logo.png') }}" alt="Shop Avatar"/>
                <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-green-500 border border-white rounded-full"></div>
            </div>
            <div>
                <h3 id="current-shop-name" class="text-sm font-semibold text-gray-800">Chọn shop để bắt đầu chat</h3>
                <p id="current-shop-status" class="text-xs text-gray-500">Trực tuyến</p>
            </div>
        </div>
    </header>
    
    <!-- Chat messages -->
    <section id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3 relative bg-gradient-to-b from-gray-50 to-white">
        <!-- Welcome message -->
        <div id="welcome-message" class="text-center py-6">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-comments text-[#ef3248] text-lg"></i>
            </div>
            <h3 class="text-sm font-semibold text-gray-800 mb-1">Chào mừng đến với ZynoxMall Chat</h3>
            <p class="text-xs text-gray-500">Chọn một shop từ danh sách bên trái để bắt đầu trò chuyện</p>
        </div>
        
        <!-- Product Context Area -->
        @if(isset($productContext))
        <div id="product-context" class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm" data-product-id="{{ $productContext->id }}">
            <div class="flex items-center space-x-2">
                <img src="{{ $productContext->images->first() ? \Illuminate\Support\Facades\Storage::url($productContext->images->first()->image_path) : asset('images/default_product_image.png') }}" 
                     class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-800 text-xs">{{ $productContext->name }}</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Đang thảo luận về sản phẩm này</p>
                </div>
                <button id="remove-product-context" class="w-6 h-6 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded transition-colors">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </div>
        @endif
        
        <!-- Initial messages display -->
        @if(isset($messages) && $messages->count() > 0)
            @foreach($messages as $msg)
                <div class="mb-3 {{ $msg->sender_type === 'user' ? 'text-right' : 'text-left' }}">
                    <div class="inline-block max-w-xs lg:max-w-md">
                        <div class="{{ $msg->sender_type === 'user' ? 'bg-[#ef3248] text-white' : 'bg-white border border-gray-200' }} rounded-lg px-3 py-2 shadow-sm">
                            @if($msg->image_url)
                                <img src="{{ $msg->image_url }}" alt="Image" class="max-w-full h-auto rounded-lg mb-2" style="max-height: 150px;">
                            @endif
                            @if($msg->message && trim($msg->message) !== '')
                                <p class="text-sm">{{ $msg->message }}</p>
                            @endif
                        </div>
                        <div class="text-xs text-gray-400 mt-1">
                            {{ \Carbon\Carbon::parse($msg->created_at)->format('H:i') }}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        
        <!-- Messages will be dynamically loaded here by JS -->
    </section>
    
    <!-- Typing indicator -->
    <div id="typing-indicator" class="hidden px-4 py-2 bg-white border-t border-gray-200">
        <div class="flex items-center space-x-2">
            <div class="typing-indicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
            <span class="text-xs text-gray-500">Đang nhập...</span>
        </div>
    </div>
    
    <!-- Chat input -->
    <form id="chat-form" class="bg-white border-t border-gray-200 p-3 shadow-lg" style="display:none" enctype="multipart/form-data">
        @csrf
        
        <!-- Image preview area -->
        <div id="image-preview-area" class="hidden mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Hình ảnh đã chọn:</span>
                <button type="button" id="remove-image" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="flex items-center space-x-3">
                <img id="preview-image" src="" alt="Preview" class="w-16 h-16 object-cover rounded border border-gray-300">
                <div class="flex-1">
                    <p id="image-name" class="text-sm text-gray-600"></p>
                    <p class="text-xs text-gray-500">Bạn có thể gửi ảnh kèm tin nhắn hoặc chỉ gửi ảnh</p>
                </div>
            </div>
        </div>
        
        <!-- Message input area -->
        <div class="flex items-center space-x-2">
            <!-- Text input -->
            <div class="flex-1 relative h-[38px]">
                <textarea id="chat-input" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#ef3248] focus:border-transparent resize-none message-input" 
                          placeholder="Nhập tin nhắn... (không bắt buộc khi gửi ảnh)"
                          rows="1"></textarea>
                
                <!-- File upload button -->
                <button type="button" id="image-upload-btn" aria-label="Insert image" 
                        class="absolute right-1.5 bottom-2 w-6 h-6 flex items-center justify-center text-gray-500 hover:text-[#ef3248] hover:bg-red-50 rounded transition-colors">
                    <i class="far fa-image text-sm"></i>
                </button>
            </div>
            
            <!-- Send button -->
            <button type="submit" class="w-8 h-[38px] bg-[#ef3248] text-white rounded-lg hover:bg-[#d91f35] focus:outline-none focus:ring-2 focus:ring-[#ef3248] focus:ring-offset-2 transition-all duration-200 send-button flex items-center justify-center">
                <i class="fas fa-paper-plane text-xs"></i>
            </button>
        </div>
        
        <!-- Hidden file input -->
        <input type="file" id="image-input" accept="image/*" style="display: none;">
    </form>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatInput = document.getElementById('chat-input');
    
    // Auto-resize textarea
    if (chatInput) {
        chatInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 80) + 'px';
        });
        
        // Handle Enter key
        chatInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('chat-form').dispatchEvent(new Event('submit'));
            }
        });
    }
});
</script> 