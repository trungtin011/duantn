@php
    $shopList = $shops;
    if (isset($shopProduct) && $shopProduct && !$shops->contains('id', $shopProduct->id)) {
        $shopList = $shops->push($shopProduct);
    }
@endphp
@extends('layouts.app')
@section('content')
<!-- Chat Interface - Direct Display -->
<div class="container mx-auto py-6">
    <div class="bg-white rounded-lg border border-gray-200 w-full h-[600px] flex flex-col overflow-hidden">
        <!-- Chat Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-pink-50">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-[#ef3248] rounded-lg flex items-center justify-center">
                    <i class="fas fa-comments text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">ZynoxMall Chat</h2>
                    <p class="text-xs text-gray-500">Hỗ trợ khách hàng</p>
                </div>
            </div>
        </div>
        
        <!-- Chat Content -->
        <div class="flex flex-1 overflow-hidden">
            @include('chat.partials._middle_sidebar', ['shopList' => $shopList])
            @include('chat.partials._main_chat_area', ['productContext' => $productContext ?? null])
        </div>
    </div>
</div>

<!-- Loading overlay -->
<div id="chat-loading" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#ef3248]"></div>
        <span class="text-gray-700">Đang tải...</span>
    </div>
</div>
@endsection

@push('styles')
<style>
    .chat-container {
        background: linear-gradient(135deg, #ef3248 0%, #d91f35 100%);
    }
    
    .message-bubble {
        animation: fadeInUp 0.3s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .typing-indicator {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .typing-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #9ca3af;
        animation: typing 1.4s infinite ease-in-out;
    }
    
    .typing-dot:nth-child(1) {
        animation-delay: -0.32s;
    }

    .typing-dot:nth-child(2) {
        animation-delay: -0.16s;
    }
    
    @keyframes typing {

        0%,
        80%,
        100% {
            transform: scale(0.8);
            opacity: 0.5;
        }
        40% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .shop-item:hover {
        background: linear-gradient(90deg, #fef2f2 0%, #fee2e2 100%);
        transform: translateX(2px);
        transition: all 0.2s ease;
    }
    
    .shop-item.active {
        background: linear-gradient(90deg, #fef2f2 0%, #fecaca 100%);
        border-right: 3px solid #ef3248;
    }
    
    .message-input:focus {
        box-shadow: 0 0 0 3px rgba(239, 50, 72, 0.1);
    }
    
    .send-button {
        background: linear-gradient(135deg, #ef3248 0%, #d91f35 100%);
        transition: all 0.2s ease;
    }
    
    .send-button:hover {
        background: linear-gradient(135deg, #d91f35 0%, #c41e3a 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 50, 72, 0.3);
    }
    
    .message-time {
        font-size: 0.75rem;
        color: #9ca3af;
        opacity: 0.8;
    }
    
    .unread-badge {
        background: linear-gradient(135deg, #ef3248 0%, #d91f35 100%);
        animation: pulse 2s infinite;
    }
    
    .unread-count-badge {
        background: linear-gradient(135deg, #ef3248 0%, #d91f35 100%);
        animation: pulse 2s infinite;
        z-index: 10;
    }
    
    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
        
        .bg-white {
            height: calc(100vh - 2rem);
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
@vite('resources/js/chat.js')
@endpush
