<div class="mb-4">
    <h3 class="font-semibold text-sm mb-2 text-gray-700">Đánh giá</h3>
    @php
        $computedRatingCounts = [];
        if (isset($ratingCounts) && is_array($ratingCounts) && !empty($ratingCounts)) {
            // Ưu tiên sử dụng dữ liệu đã tính sẵn nếu được truyền vào
            for ($i = 5; $i >= 1; $i--) {
                $computedRatingCounts[$i] = (int) ($ratingCounts[$i] ?? 0);
            }
        } elseif (isset($products)) {
            // Tính số lượng đánh giá theo ngưỡng từ danh sách sản phẩm hiện có
            for ($i = 5; $i >= 1; $i--) {
                $count = 0;
                foreach ($products as $p) {
                    if (isset($p->orderReviews)) {
                        $count += $p->orderReviews->where('rating', '>=', $i)->count();
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
        @for($i = 5; $i >= 1; $i--)
            <div class="rating-group">
                <div class="flex items-center bg-white rounded-md px-2 py-1">
                    <label class="flex items-center space-x-2 w-full cursor-pointer">
                        <input type="radio" class="filter-radio" name="rating" 
                            value="{{ $i }}"
                            {{ request('rating') == $i ? 'checked' : '' }}>
                        <div class="flex items-center space-x-1">
                            @for($star = 1; $star <= 5; $star++)
                                @if($star <= $i)
                                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endif
                            @endfor
                            <span class="text-sm text-gray-800 ml-1">({{ $computedRatingCounts[$i] ?? 0 }})</span>
                        </div>
                    </label>
                </div>
            </div>
        @endfor
        
        <!-- Clear rating filter -->
        <div class="rating-group">
            <div class="flex items-center bg-white rounded-md px-2 py-1">
                <label class="flex items-center space-x-2 w-full cursor-pointer">
                    <input type="radio" class="filter-radio" name="rating" 
                        value="" 
                        {{ !request('rating') ? 'checked' : '' }}>
                    <span class="text-sm text-gray-800">Tất cả đánh giá</span>
                </label>
            </div>
        </div>
    </div>
</div>
