@foreach ($reviews as $review)
    <div class="border-b py-4">
        <div class="flex">
            @include('partials.user-avatar', ['user' => $review->user, 'size' => 'lg'])
            <div class="flex flex-col gap-1">
                <h4 class="mr-2 text-gray-800 text-sm">
                    {{ $review->user->fullname ?? 'Người dùng ẩn danh' }}
                    <span class="text-xs text-gray-500 ml-2">
                        {{ $review->created_at->diffForHumans() }}
                    </span>
                </h4>
                <div class="text-yellow-400 flex">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-sm"></i>
                    @endfor
                </div>
            </div>
        </div>
        <div class="ml-[52px] mt-1">
            <!-- Hiển thị kiểu sản phẩm mua -->
            @if ($review->product && $review->product->variants->count() > 0)
                <div class="text-xs text-gray-500 mb-3 bg-gray-100 p-2 rounded-md w-fit">
                    <span class="font-bold">Loại:</span>
                    {{ $review->product->variants->first()->variant_name }}
                </div>
            @else
                <div class="text-xs text-gray-500 mb-3 bg-gray-100 rounded-md p-2 w-fit">
                    <span class="font-bold">Loại:</span>
                    {{ $review->product->name }}
                </div>
            @endif

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
            <div class="text-xs text-gray-500 flex items-center gap-3 mt-2">
                @auth
                    <button
                        class="like-review-btn flex items-center gap-1 text-red-500 hover:text-red-700 focus:outline-none transition-colors duration-200"
                        data-review-id="{{ $review->id }}" data-liked="{{ $review->liked_by_auth ? 'true' : 'false' }}">
                        <i class="fa{{ $review->liked_by_auth ? 's' : 'r' }} fa-heart"></i>
                        <span class="like-count">{{ $review->likes_count ?? 0 }}</span>
                    </button>
                @else
                    <span class="flex items-center gap-1 text-gray-400 cursor-pointer" title="Đăng nhập để thích đánh giá">
                        <i class="far fa-heart"></i>
                        <span>{{ $review->likes_count ?? 0 }}</span>
                    </span>
                @endauth
            </div>
            
            <!-- Phản hồi từ seller -->
            @if ($review->seller_reply)
                <div class="mt-3 p-3 bg-gray-100 border-l-4 border-blue-500 rounded">
                    <strong class="text-gray-700 block mb-1">Phản hồi từ người bán:</strong>
                    <p class="text-gray-800 text-sm">{{ $review->seller_reply }}</p>
                </div>
            @endif
        </div>
    </div>
@endforeach
