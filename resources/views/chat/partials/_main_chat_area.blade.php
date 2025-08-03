<main class="flex flex-col flex-1 bg-gray-100">
    <!-- Chat header -->
    <header id="chat-header" class="flex items-center justify-between h-12 px-4 bg-white border-b border-gray-300 select-none">
        {{-- Content will be dynamically loaded here by JS --}}
    </header>
    <!-- Chat messages -->
    <section id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 relative">
        {{-- Messages will be dynamically loaded here by JS --}}
        {{-- Product Context Area --}}
        @if(isset($productContext))
        <div id="product-context" class="p-2 border-t flex items-center gap-2 bg-gray-50" data-product-id="{{ $productContext->id }}">
            <img src="{{ $productContext->images->first() ? \Illuminate\Support\Facades\Storage::url($productContext->images->first()->image_path) : asset('images/default_product_image.png') }}" class="w-10 h-10 rounded object-cover">
            <div class="flex-1 text-sm truncate">{{ $productContext->name }}</div>
            <button id="remove-product-context" class="text-gray-500 hover:text-red-500">&times;</button>
        </div>
        @endif
    </section>
    <!-- Chat input -->
    <form id="chat-form" class="flex items-center space-x-2 px-4 py-2 bg-white border-t border-gray-300 select-none" style="display:none" enctype="multipart/form-data">
        @csrf
        <button aria-label="Emoji" class="text-gray-500 hover:text-gray-700 text-xl">
            <i class="far fa-smile"></i>
        </button>
        <button aria-label="Like" class="text-gray-500 hover:text-gray-700 text-xl">
            <i class="fas fa-thumbs-up"></i>
        </button>
        <input type="text" id="chat-input" class="flex-1 h-10 px-3 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Nhập tin nhắn..."/>
        <div class="flex space-x-3 ml-3 text-gray-500">
            <button type="button" id="image-upload-btn" aria-label="Insert image" class="hover:text-gray-700 text-xl">
                <i class="far fa-image"></i>
            </button>
            <input type="file" id="image-input" accept="image/*" style="display: none;">
            <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded">Gửi</button>
        </div>
    </form>
    
    <!-- Image preview modal -->
    <div id="image-preview-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-4 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Xem trước ảnh</h3>
                <button id="close-preview" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-4">
                <img id="preview-image" src="" alt="Preview" class="w-full h-64 object-cover rounded">
            </div>
            <div class="flex justify-end space-x-2">
                <button id="cancel-upload" class="px-4 py-2 text-gray-600 border border-gray-300 rounded hover:bg-gray-50">Hủy</button>
                <button id="confirm-upload" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Gửi ảnh</button>
            </div>
        </div>
    </div>
</main> 