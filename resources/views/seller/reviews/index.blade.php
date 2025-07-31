@extends('layouts.seller_home')

@section('content')
    <div class="mt-[32px] mb-[24px]">
        <h1 class="font-semibold text-[28px]">Đánh giá từ khách hàng</h1>
    </div>

    <div class="bg-white shadow rounded p-4">
        @forelse($reviews as $review)
            <div class="border-b py-4">
                <div class="font-semibold text-lg">{{ $review->product->name }}</div>
                <div class="text-gray-600 text-sm">Khách: {{ $review->user->name }} |
                    {{ $review->created_at->format('d/m/Y') }}</div>
                <div class="text-yellow-500">
                    ⭐ {{ $review->rating }} / 5
                </div>
                <p class="mt-2">{{ $review->comment }}</p>

                {{-- Hiển thị ảnh nếu có --}}
                @if ($review->images->count())
                    <div class="flex gap-2 mt-2">
                        @foreach ($review->images as $img)
                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                class="w-[100px] h-[100px] object-cover rounded border" />
                        @endforeach
                    </div>
                @endif

                {{-- Hiển thị video nếu có --}}
                @if ($review->videos->count())
                    <div class="mt-2">
                        @foreach ($review->videos as $vid)
                            <video controls class="w-[300px] mt-2 rounded">
                                <source src="{{ asset('storage/' . $vid->video_path) }}" type="video/mp4">
                                Trình duyệt không hỗ trợ video.
                            </video>
                        @endforeach
                    </div>
                @endif
                {{-- Hiển thị phản hồi nếu có --}}
                @if ($review->seller_reply)
                    <div class="mt-2 p-3 bg-gray-100 rounded border-l-4 border-blue-500">
                        <strong>Phản hồi của bạn:</strong>
                        <p>{{ $review->seller_reply }}</p>
                    </div>
                @else
                    {{-- Hiển thị form phản hồi --}}
                    <form action="{{ route('seller.reviews.reply', $review->id) }}" method="POST" class="mt-4">
                        @csrf
                        <label class="block mb-1 font-medium">Phản hồi:</label>
                        <textarea name="seller_reply" rows="3" class="w-full border rounded px-3 py-2" required></textarea>
                        <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Gửi
                            phản hồi</button>
                    </form>
                @endif

            </div>
        @empty
            <p>Chưa có đánh giá nào cho sản phẩm của bạn.</p>
        @endforelse

        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
    </div>
@endsection
