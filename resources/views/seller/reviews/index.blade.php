@extends('layouts.seller_home')

@section('content')
    <div class="mt-6 mb-4 px-4 sm:px-0">
        <h1 class="font-semibold text-xl sm:text-2xl">Đánh giá từ khách hàng</h1>
    </div>

    <div class="bg-white shadow rounded p-4 sm:p-6 mx-2 sm:mx-0">
        @forelse($reviews as $review)
            <div class="border-b py-4">
                <!-- Thông tin sản phẩm & người dùng -->
                <div class="text-base font-semibold text-gray-800 mb-1">
                    {{ $review->product->name }}
                </div>
                <div class="text-sm text-gray-500 mb-1">
                    Khách: {{ $review->user->name }} |
                    {{ $review->created_at->format('d/m/Y') }}
                </div>

                <!-- Rating -->
                <div class="text-yellow-500 text-sm">⭐ {{ $review->rating }} / 5</div>

                <!-- Nội dung bình luận -->
                <p class="mt-2 text-gray-700 text-sm whitespace-pre-line">{{ $review->comment }}</p>

                <!-- Hình ảnh -->
                @if ($review->images->count())
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach ($review->images as $img)
                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                class="w-24 h-24 object-cover rounded border" alt="Ảnh đánh giá" />
                        @endforeach
                    </div>
                @endif

                <!-- Video -->
                @if ($review->videos->count())
                    <div class="mt-3 flex flex-col gap-3">
                        @foreach ($review->videos as $vid)
                            <video controls class="w-full max-w-xs sm:max-w-sm rounded">
                                <source src="{{ asset('storage/' . $vid->video_path) }}" type="video/mp4">
                                Trình duyệt không hỗ trợ video.
                            </video>
                        @endforeach
                    </div>
                @endif

                <!-- Phản hồi của seller -->
                @if ($review->seller_reply)
                    <div class="mt-3 p-3 bg-gray-100 rounded border-l-4 border-blue-500">
                        <strong class="block mb-1 text-sm text-blue-700">Phản hồi của bạn:</strong>
                        <p class="text-sm text-gray-700">{{ $review->seller_reply }}</p>
                    </div>
                @else
                    <!-- Form phản hồi -->
                    <form action="{{ route('seller.reviews.reply', $review->id) }}" method="POST" class="mt-4">
                        @csrf
                        <label class="block mb-1 text-sm font-medium text-gray-700">Phản hồi:</label>
                        <textarea name="seller_reply" rows="3"
                            class="w-full border rounded px-3 py-2 text-sm focus:outline-blue-500" required></textarea>
                        <button type="submit"
                            class="mt-2 px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                            Gửi phản hồi
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-gray-600 text-sm">Chưa có đánh giá nào cho sản phẩm của bạn.</p>
        @endforelse

        <!-- Phân trang -->
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    </div>
@endsection
