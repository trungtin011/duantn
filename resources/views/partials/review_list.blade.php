@forelse ($reviews as $review)
    <div class="py-4 border-t">
        <div class="flex items-center gap-2 mb-2">
            <img class="w-6 h-6 rounded-full"
                src="{{ $review->user->avatar ? Storage::url($review->user->avatar) : asset('images/avatar.png') }}"
                alt="avatar" loading="lazy">
            <span class="text-sm text-gray-600">{{ $review->user->fullname ?? 'Ẩn danh' }}</span>
        </div>
        <div class="flex gap-1 mb-1">
            @for ($i = 1; $i <= 5; $i++)
                <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-yellow-400"></i>
            @endfor
        </div>
        <p class="text-sm text-gray-600 mb-2">{{ $review->created_at->format('Y-m-d H:i') }} |
            {{ $review->variant_info ?? 'N/A' }}</p>
        @if ($review->comment)
            <p class="text-sm text-gray-800">{{ $review->comment }}</p>
        @endif
        @if ($review->images)
            <div class="flex gap-2 mt-2">
                @foreach ($review->images as $img)
                    <img src="{{ asset('storage/' . $img->image_path) }}"
                        class="w-16 h-16 object-cover rounded cursor-pointer review-media"
                        data-full="{{ asset('storage/' . $img->image_path) }}">
                @endforeach
            </div>
        @endif
        @if ($review->video_path)
            <video class="w-16 h-16 mt-2 rounded cursor-pointer review-media" muted
                data-full="{{ asset('storage/' . $review->video_path) }}">
                <source src="{{ asset('storage/' . $review->video_path) }}" type="video/mp4">
            </video>
        @endif
        <div class="text-sm text-blue-600 flex items-center gap-1 mt-2 cursor-pointer like-review"
            data-id="{{ $review->id }}">
            <i class="fas fa-thumbs-up"></i>
            <span>{{ $review->likes->count() }} Hữu Ích</span>
        </div>
    </div>
@empty
    <p class="text-sm text-gray-600">Không có đánh giá nào.</p>
@endforelse
