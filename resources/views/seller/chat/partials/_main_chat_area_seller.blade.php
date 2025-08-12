<main class="flex flex-col flex-1 bg-gray-100">
    <!-- Chat header -->
    <header id="chat-header" class="flex items-center justify-between px-4 bg-white border-b border-gray-300 select-none">
        {{-- Content will be dynamically loaded here by JS --}}
    </header>
    <!-- Chat messages -->
    <section id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 relative bg-white">
        {{-- Messages will be dynamically loaded here by JS --}}
    </section>
    <!-- Chat input -->
    <form id="chat-form" class="flex flex-col px-4 py-2 bg-white border-t border-gray-300 select-none" style="display:none" enctype="multipart/form-data">
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
        
        <!-- Input controls -->
        <div class="flex items-center space-x-2">
            <input type="text" id="chat-input" class="flex-1 h-10 px-3 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Nhập tin nhắn... (không bắt buộc khi gửi ảnh)"/>
            <div class="flex space-x-3 ml-3 text-gray-500">
                <button type="button" id="image-upload-btn" aria-label="Insert image" class="hover:text-gray-700 text-xl">
                    <i class="far fa-image"></i>
                </button>
                <input type="file" id="image-input" accept="image/*" style="display: none;">
                <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Gửi</button>
            </div>
        </div>
    </form>
</main>

<style>
/* Highlight search results */
.highlight-search {
    background-color: #fef3c7 !important;
    color: #92400e !important;
    padding: 2px 4px;
    border-radius: 4px;
    font-weight: 600;
}

/* Search input focus styles */
#customer-search-input:focus {
    background-color: white;
    border: 1px solid #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Smooth transitions */
.customer-btn {
    transition: all 0.2s ease-in-out;
}

.customer-btn:hover {
    background-color: #f3f4f6;
    transform: translateX(2px);
}

/* Search results animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.customer-btn {
    animation: fadeIn 0.3s ease-out;
}
</style> 