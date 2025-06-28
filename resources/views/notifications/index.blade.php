@extends('layouts.app')

@section('title', 'Thông báo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Thông báo</h1>
            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="inline">
                @csrf
                @method('POST')
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Đánh dấu tất cả đã đọc
                </button>
            </form>
        </div>

        @if($notifications->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-500">Không có thông báo nào</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="border-b border-gray-200 pb-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $notification->is_read === 'unread' ? 'bg-blue-500' : 'bg-gray-300' }}"></div>
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $notification->title }}</h3>
                                </div>
                                <p class="text-gray-600 mt-2">{{ $notification->content }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $notification->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="flex gap-2">
                                @if($notification->is_read === 'unread')
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="text-blue-500 hover:text-blue-600">
                                            Đánh dấu đã đọc
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600">
                                        Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 