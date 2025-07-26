<main class="flex flex-col flex-1 bg-gray-100">
    <!-- Chat header -->
    <header id="chat-header" class="flex items-center justify-between h-12 px-4 bg-white border-b border-gray-300 select-none">
        {{-- Content will be dynamically loaded here by JS --}}
    </header>
    <!-- Chat messages -->
    <section id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 relative">
        {{-- Messages will be dynamically loaded here by JS --}}
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
        </div>
    </form>
</main> 