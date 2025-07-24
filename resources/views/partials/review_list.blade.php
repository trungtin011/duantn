@foreach ($reviews as $review)
    <div class="border-b py-4">
        <div class="flex items-center mb-2">
            <img src="{{ $review->user->avatar ? Storage::url($review->user->avatar) : asset('storage/user_avatars/default_avatar.png') }}"
                alt="Avatar" class="w-10 h-10 rounded-full object-cover mr-3" loading="lazy">
            <h4 class="mr-2 text-gray-800 text-sm">
                {{ $review->user->fullname ?? 'Người dùng ẩn danh' }}
            </h4>
        </div>
        <div class="ml-[50px]">
            <div class="text-yellow-400 flex">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-sm"></i>
                @endfor
            </div>
            <p class="text-sm text-gray-700 mb-2">{{ $review->comment }}</p>
            <div class="flex items-center gap-2">
                @if ($review->images && $review->images->count())
                    <div class="flex gap-2 mb-2">
                        @foreach ($review->images as $img)
                            <img src="{{ Storage::url($img->image_path) }}" alt="Ảnh đánh giá"
                                class="w-20 h-20 object-cover rounded border" loading="lazy">
                        @endforeach
                    </div>
                @endif
                @if ($review->videos && $review->videos->count())
                    <div class="mb-2">
                        @foreach ($review->videos as $vid)
                            <video controls class="w-28 h-20 rounded">
                                <source src="{{ Storage::url($vid->video_path) }}" type="video/mp4">
                                Trình duyệt của bạn không hỗ trợ video.
                            </video>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</div>
        </div>
    </div>
@endforeach
