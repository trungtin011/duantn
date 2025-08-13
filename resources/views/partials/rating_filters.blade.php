<div class="mb-6">
    <h3 class="font-semibold text-sm mb-3 text-gray-700">Đánh giá</h3>
    @php
        $computedRatingCounts = [];
        if (isset($ratingCounts) && is_array($ratingCounts) && !empty($ratingCounts)) {
            // Ưu tiên sử dụng dữ liệu đã tính sẵn nếu được truyền vào
            for ($i = 5; $i >= 1; $i--) {
                $computedRatingCounts[$i] = (int) ($ratingCounts[$i] ?? 0);
            }
        } elseif (isset($products)) {
            // Tính số lượng đánh giá theo từng mức sao cụ thể từ danh sách sản phẩm hiện có
            for ($i = 5; $i >= 1; $i--) {
                $count = 0;
                foreach ($products as $p) {
                    if (isset($p->orderReviews) && $p->orderReviews->isNotEmpty()) {
                        // Tính trung bình rating của sản phẩm
                        $avgRating = $p->orderReviews->avg('rating');
                        if ($avgRating !== null) {
                            $avgRounded = round($avgRating);
                            // Chỉ đếm sản phẩm có rating trung bình đúng mức sao này
                            if ($avgRounded == $i) {
                                $count++;
                            }
                        }
                    }
                }
                $computedRatingCounts[$i] = $count;
            }
        } else {
            // Mặc định 0 nếu không có dữ liệu
            for ($i = 5; $i >= 1; $i--) {
                $computedRatingCounts[$i] = 0;
            }
        }
    @endphp
    
    <div class="space-y-2">
        @for ($i = 5; $i >= 1; $i--)
            <div class="rating-group">
                <label class="flex items-center w-full cursor-pointer p-2 rounded-md hover:bg-gray-50 transition-colors duration-200 {{ request('rating') == $i ? 'bg-gray-100 border border-gray-200' : '' }}">
                    <input type="radio" class="filter-radio mr-3" name="rating" value="{{ $i }}"
                        {{ request('rating') == $i ? 'checked' : '' }}>
                    
                    <div class="flex items-center space-x-2 flex-1">
                        <!-- Stars Display -->
                        <div class="flex items-center space-x-1">
                            @for ($star = 1; $star <= 5; $star++)
                                @if ($star <= $i)
                                    <svg class="w-3 h-3 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @else
                                    <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        
                        <!-- Rating Text -->
                        <span class="text-sm text-gray-700">
                            @if($i == 5)
                                Tuyệt vời
                            @elseif($i == 4)
                                Rất tốt
                            @elseif($i == 3)
                                Tốt
                            @elseif($i == 2)
                                Trung bình
                            @else
                                Kém
                            @endif
                        </span>
                        
                        <!-- Count Badge -->
                        <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600 ml-auto">
                            {{ $computedRatingCounts[$i] ?? 0 }}
                        </span>
                    </div>
                </label>
            </div>
        @endfor

        <!-- Clear rating filter -->
        <div class="rating-group mt-4">
            <label class="flex items-center w-full cursor-pointer p-2 rounded-md hover:bg-gray-50 transition-colors duration-200 {{ !request('rating') ? 'bg-gray-100 border border-gray-200' : '' }}">
                <input type="radio" class="filter-radio mr-3" name="rating" value=""
                    {{ !request('rating') ? 'checked' : '' }}>
                
                <span class="text-sm text-gray-700">Tất cả đánh giá</span>
            </label>
        </div>
    </div>
</div>
