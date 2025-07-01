@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10">
    <h2 class="text-xl font-bold mb-4">Đánh giá sản phẩm</h2>
    <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="product_id" value="{{ $productId }}">

     <label class="block mb-2 font-semibold">Đánh giá sao:</label>
<div class="flex mb-4" id="starRating">
    @for ($i = 1; $i <= 5; $i++)
        <i class="bi bi-star-fill text-3xl text-gray-400 cursor-pointer mx-1 transition-colors duration-150" data-value="{{ $i }}"></i>
    @endfor
    <input type="hidden" name="rating" id="ratingInput" value="0">
</div>

        </select>

        <label class="block mb-2 font-semibold">Bình luận:</label>
        <textarea name="comment" class="w-full border p-2 mb-4" rows="4"></textarea>

        <label class="block mb-2 font-semibold">Tải lên hình ảnh:</label>
        <input type="file" name="images[]" accept="image/*" multiple class="mb-4">

        <label class="block mb-2 font-semibold">Tải lên video:</label>
        <input type="file" name="videos[]" accept="video/*" multiple class="mb-4">

        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Gửi đánh giá</button>
    </form>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stars = document.querySelectorAll('#starRating i');
        const ratingInput = document.getElementById('ratingInput');

        stars.forEach((star, index) => {
            star.addEventListener('mouseover', () => {
                for (let i = 0; i < stars.length; i++) {
                    stars[i].classList.remove('text-yellow-500');
                    stars[i].classList.add('text-gray-400');
                }
                for (let i = 0; i <= index; i++) {
                    stars[i].classList.remove('text-gray-400');
                    stars[i].classList.add('text-yellow-500');
                }
            });

            star.addEventListener('mouseout', () => {
                updateSelectedStars();
            });

            star.addEventListener('click', () => {
                ratingInput.value = star.dataset.value;
                updateSelectedStars();
            });
        });

        function updateSelectedStars() {
            const selected = parseInt(ratingInput.value);
            for (let i = 0; i < stars.length; i++) {
                if (i < selected) {
                    stars[i].classList.remove('text-gray-400');
                    stars[i].classList.add('text-yellow-500');
                } else {
                    stars[i].classList.remove('text-yellow-500');
                    stars[i].classList.add('text-gray-400');
                }
            }
        }
    });
</script>
@endpush
