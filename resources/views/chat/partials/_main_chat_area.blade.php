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
    <form id="chat-form" class="flex items-center space-x-2 px-4 py-2 bg-white border-t border-gray-300 select-none" style="display:none">
        @csrf
        <button aria-label="Emoji" class="text-gray-500 hover:text-gray-700 text-xl">
            <i class="far fa-smile"></i>
        </button>
        <button aria-label="Like" class="text-gray-500 hover:text-gray-700 text-xl">
            <i class="fas fa-thumbs-up"></i>
        </button>
        <input type="text" id="chat-input" class="flex-1 h-10 px-3 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Nhập tin nhắn..."/>
        <div class="flex space-x-3 ml-3 text-gray-500">
            <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded">Gửi</button>
            {{-- <button aria-label="Insert image" class="hover:text-gray-700">
                <i class="far fa-image"></i>
            </button>
            <button aria-label="Attach file" class="hover:text-gray-700">
                <i class="fas fa-paperclip"></i>
            </button>
            <button aria-label="Insert sticker" class="hover:text-gray-700">
                <i class="far fa-sticky-note"></i>
            </button>
            <button aria-label="More options" class="hover:text-gray-700">
                <i class="fas fa-ellipsis-h"></i>
            </button> --}}
        </div>
    </form>
</main> 