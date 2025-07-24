@extends('layouts.app')

@section('title', 'Thông báo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Thông báo của tôi</h1>
        
        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-3">
                                <div class="w-3 h-3 mt-1.5 rounded-full {{ $notification->is_read === 'unread' ? 'bg-blue-500' : 'bg-gray-300' }}"></div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $notification->title }}</h3>
                                    <p class="text-gray-700 mb-2">{{ $notification->content }}</p>
                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        <span class="bg-gray-100 px-2 py-1 rounded">{{ ucfirst(str_replace('_', ' ', $notification->type)) }}</span>
                                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                                        <span class="capitalize">{{ $notification->priority }}</span>
                                    </div>
                                </div>
                            </div>
                            @if($notification->is_read === 'unread')
                                <button class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                                    Đánh dấu đã đọc
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fa fa-bell-slash"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Không có thông báo</h3>
                <p class="text-gray-500">Bạn chưa có thông báo nào. Chúng tôi sẽ thông báo cho bạn khi có cập nhật mới.</p>
            </div>
        @endif
    </div>
</div>
@endsection 