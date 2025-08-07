@extends('layouts.app')

@section('title', 'Thông báo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Thông báo của tôi</h1>
            <div class="flex items-center gap-3">
                <button onclick="markAllAsRead()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                    Đánh dấu tất cả đã đọc
                </button>
                <select id="typeFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">Tất cả loại</option>
                    <option value="order">Đơn hàng</option>
                    <option value="promotion">Khuyến mãi</option>
                    <option value="system">Hệ thống</option>
                    <option value="security">Bảo mật</option>
                </select>
            </div>
        </div>
        
        @if($notifications->count() > 0)
            <div class="space-y-6">
                @php
                    $groupedNotifications = $notifications->groupBy('type');
                @endphp
                
                @foreach($groupedNotifications as $type => $typeNotifications)
                    <div class="bg-white rounded-lg shadow-sm border" data-type="{{ $type }}">
                        <!-- Type Header -->
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full 
                                    @switch($type)
                                        @case('order')
                                            bg-blue-500
                                            @break
                                        @case('promotion')
                                            bg-green-500
                                            @break
                                        @case('system')
                                            bg-purple-500
                                            @break
                                        @case('security')
                                            bg-red-500
                                            @break
                                        @default
                                            bg-gray-500
                                    @endswitch">
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900">
                                    @switch($type)
                                        @case('order')
                                            Đơn hàng
                                            @break
                                        @case('promotion')
                                            Khuyến mãi
                                            @break
                                        @case('system')
                                            Hệ thống
                                            @break
                                        @case('security')
                                            Bảo mật
                                            @break
                                        @default
                                            {{ ucfirst($type) }}
                                    @endswitch
                                </h2>
                                <span class="text-sm text-gray-500">({{ $typeNotifications->count() }} thông báo)</span>
                            </div>
                        </div>
                        
                        <!-- Notifications List -->
                        <div class="divide-y divide-gray-200">
                            @foreach($typeNotifications as $notification)
                                @php
                                    $isRead = false;
                                    if ($notification->receiver && $notification->receiver->count() > 0) {
                                        foreach ($notification->receiver as $receiver) {
                                            if ($receiver->receiver_id == auth()->id()) {
                                                $isRead = $receiver->is_read;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                <div class="px-6 py-4 hover:bg-gray-50 transition-colors {{ !$isRead ? 'bg-blue-50 border-l-4 border-blue-500' : 'border-l-4 border-transparent' }}"
                                     data-notification-id="{{ $notification->id }}">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0">
                                            @if($notification->image_path)
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center">
                                                    <img src="{{ asset('images/notifications/' . $notification->image_path) }}" alt="Notification Image" class="w-full h-full rounded-full">
                                                </div>
                                            @else
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center">
                                                    <img src="{{ asset('images/logo.jpg') }}" alt="Notification Image" class="w-full h-full rounded-full">
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-2">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $notification->title }}</h3>
                                                <div class="flex items-center gap-2">
                                                    @if($notification->priority === 'high')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            Quan trọng
                                                        </span>
                                                    @endif
                                                    @if(!$isRead)
                                                        <button onclick="markAsRead({{ $notification->id }})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                            Đánh dấu đã đọc
                                                        </button>
                                                    @endif
                                                    @if($notification->type === 'order' && $notification->order_code)
                                                        <a href="{{ route('user.order.parent-detail', $notification->order_code) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                            Xem đơn hàng
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <p class="text-gray-700 mb-3">{{ $notification->content }}</p>
                                            
                                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                                @if($notification->type === 'order' && $notification->order_code)
                                                    <span class="bg-blue-100 px-2 py-1 rounded text-blue-800 font-medium">#{{ $notification->order_code }}</span>
                                                @endif
                                                <span>{{ $notification->created_at->diffForHumans() }}</span>

                                                @if(!$isRead)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Chưa đọc
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        Đã đọc
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Không có thông báo</h3>
                <p class="text-gray-500">Bạn chưa có thông báo nào. Chúng tôi sẽ thông báo khi có tin mới.</p>
            </div>
        @endif
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

document.getElementById('typeFilter').addEventListener('change', function() {
    const selectedType = this.value;
    const notificationGroups = document.querySelectorAll('[data-type]');
    
    notificationGroups.forEach(group => {
        if (selectedType === '' || group.getAttribute('data-type') === selectedType) {
            group.style.display = 'block';
        } else {
            group.style.display = 'none';
        }
    });
});
</script>
@endsection 