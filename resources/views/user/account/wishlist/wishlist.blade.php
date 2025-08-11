@extends('layouts.app')

@section('title', 'Danh sách yêu thích')

@section('content')
    <style>
        /* Custom scrollbar cho danh sách yêu thích */
        .wishlist-scroll::-webkit-scrollbar {
            height: 8px;
        }

        .wishlist-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .wishlist-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .wishlist-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Ẩn thanh cuộn mặc định trên mobile */
        @media (max-width: 640px) {
            .wishlist-scroll::-webkit-scrollbar {
                height: 6px;
            }
        }
    </style>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Danh sách yêu thích</h1>
                <p class="text-gray-600">Quản lý các sản phẩm bạn đã thêm vào danh sách yêu thích</p>
            </div>

            <!-- Wishlist Content -->
            <div class="">
                @if ($wishlistItems->isEmpty())
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fa-regular fa-heart text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-sm text-gray-600">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
                        <a href="{{ route('home') }}"
                            class="inline-block mt-4 px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Khám phá sản phẩm
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto wishlist-scroll pb-2">
                        <div class="flex gap-5 sm:gap-4 lg:gap-6 p-4 sm:p-0 min-w-max">
                            @foreach ($wishlistItems as $item)
                                @php
                                    $product = $item->product;
                                    $imageUrl = $product->images->isNotEmpty()
                                        ? asset('storage/' . $product->images->first()->image_path)
                                        : asset('images/placeholder.png');
                                    $displayPrice = $product->display_price;
                                    $displayOriginal = $product->display_original_price;
                                    $hasDiscount = $displayOriginal && $displayPrice < $displayOriginal;
                                    $discountPercent =
                                        $hasDiscount && $displayOriginal > 0
                                            ? round((($displayOriginal - $displayPrice) / $displayOriginal) * 100)
                                            : 0;
                                    $stats = $product
                                        ->orderReviews()
                                        ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as reviews_count')
                                        ->first();
                                    $avgRating = round($stats->avg_rating ?? 0, 1);
                                    $reviewsCount = (int) ($stats->reviews_count ?? 0);
                                    $hasVariants = $product->is_variant && $product->variants->isNotEmpty();

                                    // Tạo JSON data cho variants
                                    $variantsJson = '[]';
                                    if ($hasVariants) {
                                        $variantsData = [];
                                        foreach ($product->variants as $variant) {
                                            $variantsData[] = [
                                                'id' => $variant->id,
                                                'variant_name' => $variant->variant_name ?? 'Biến thể',
                                                'price' => (float) $variant->price,
                                                'sale_price' =>
                                                    $variant->sale_price !== null ? (float) $variant->sale_price : null,
                                                'stock' => $variant->stock,
                                                'status' => $variant->status,
                                            ];
                                        }
                                        $variantsJson = json_encode($variantsData);
                                    }
                                @endphp

                                <div class="group relative bg-white rounded-xl overflow-hidden border border-gray-200 hover:border-gray-300 shadow-sm hover:shadow-lg transition-all duration-300 w-64 sm:w-[272px] flex-shrink-0"
                                    data-has-variants="{{ $hasVariants ? '1' : '0' }}"
                                    data-product-json='{!! $variantsJson !!}'>

                                    <!-- Product Image -->
                                    <div class="relative bg-gray-50 aspect-[4/3] overflow-hidden">
                                        @if ($hasDiscount)
                                            <span
                                                class="absolute top-2 left-2 z-10 inline-flex items-center rounded-full bg-red-500/90 text-white text-[10px] font-semibold px-2 py-0.5 shadow-sm">
                                                -{{ $discountPercent }}%
                                            </span>
                                        @endif

                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                            class="h-full w-full object-contain transition-transform duration-500 group-hover:scale-105" />

                                        <!-- Add to Cart Button -->
                                        <form class="add-to-cart-form" data-product-id="{{ $product->id }}">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <input type="hidden" name="variant_id" value="">
                                            <button type="submit"
                                                class="absolute inset-x-2 bottom-2 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300 bg-black text-white rounded-md px-3 py-2 text-xs sm:text-sm flex items-center justify-center gap-2 shadow">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" class="shrink-0">
                                                    <path d="M8.25 20.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                    </path>
                                                    <path d="M18.75 20.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                    </path>
                                                    <path d="M2.25 3.75H5.25L7.5 16.5H19.5" stroke="white"
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    </path>
                                                    <path
                                                        d="M7.5 12.5H19.1925c.0866.0001.1707-.0299.2378-.0849.0671-.055.113-.1315.1301-.2165l1.35-6.75a.374.374 0 0 0-.277-.413H6"
                                                        stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg>
                                                <span>Thêm vào giỏ hàng</span>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="p-3 sm:p-4">
                                        <h3
                                            class="text-sm sm:text-base font-semibold text-gray-900 line-clamp-2 min-h-[2.5rem]">
                                            {{ $product->name }}</h3>

                                        <!-- Price -->
                                        <div class="mt-2 flex items-center gap-2">
                                            <span
                                                class="text-sm sm:text-base font-bold text-gray-900">${{ number_format($displayPrice, 0, '.', ',') }}</span>
                                            @if ($hasDiscount)
                                                <span
                                                    class="text-xs text-gray-500 line-through truncate">${{ number_format($displayOriginal, 0, '.', ',') }}</span>
                                            @endif
                                        </div>

                                        <!-- Rating -->
                                        <div class="mt-2 flex items-center text-yellow-400">
                                            @php
                                                $fullStars = (int) floor($avgRating);
                                                $hasHalf = $avgRating - $fullStars >= 0.5;
                                                $emptyStars = 5 - $fullStars - ($hasHalf ? 1 : 0);
                                            @endphp
                                            @for ($i = 0; $i < $fullStars; $i++)
                                                <i class="fa-solid fa-star text-[10px] sm:text-xs"></i>
                                            @endfor
                                            @if ($hasHalf)
                                                <i class="fa-solid fa-star-half-stroke text-[10px] sm:text-xs"></i>
                                            @endif
                                            @for ($i = 0; $i < $emptyStars; $i++)
                                                <i class="fa-regular fa-star text-[10px] sm:text-xs"></i>
                                            @endfor
                                            <span class="ml-1.5 text-gray-500 text-xs">({{ $reviewsCount }})</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                @endif
            </div>

            <!-- Recommended Products -->
            @if (isset($recommendedProducts) && $recommendedProducts->isNotEmpty())
                <div class="mt-12">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6">Sản phẩm gợi ý</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-4 lg:gap-6">
                        @foreach ($recommendedProducts as $product)
                            <div
                                class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                                <div class="aspect-[4/3] overflow-hidden rounded-t-lg">
                                    <img src="{{ $product->images->isNotEmpty() ? asset('storage/' . $product->images->first()->image_path) : asset('images/placeholder.png') }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </div>
                                <div class="p-4">
                                    <h3 class="font-medium text-gray-900 line-clamp-2 mb-2">{{ $product->name }}</h3>
                                    <p class="text-lg font-bold text-red-600">
                                        ${{ number_format($product->display_price, 0, '.', ',') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const forms = document.querySelectorAll('.add-to-cart-form');

                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const productId = this.dataset.productId;
                        const quantity = this.querySelector('input[name="quantity"]').value;
                        const variantInput = this.querySelector('input[name="variant_id"]');
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content');
                        const btn = this.querySelector('button');

                        if (btn) {
                            btn.disabled = true;
                            btn.classList.add('opacity-70', 'cursor-not-allowed');
                        }

                        // Check if product has variants and no variant selected
                        const hasVariants = this.closest('.group').getAttribute('data-has-variants') ===
                            '1';
                        const variantsData = this.closest('.group').variantData || null;

                        if (hasVariants && !variantInput.value) {
                            if (variantsData && Array.isArray(variantsData) && variantsData.length >
                                0) {
                                openVariantModal({
                                    productId,
                                    variants: variantsData,
                                    onConfirm: (variantId, qty) => {
                                        variantInput.value = variantId;
                                        this.querySelector('input[name="quantity"]').value =
                                            qty;
                                        // Re-submit after choosing variant
                                        this.dispatchEvent(new Event('submit'));
                                    },
                                    onClose: () => {
                                        if (btn) {
                                            btn.disabled = false;
                                            btn.classList.remove('opacity-70',
                                                'cursor-not-allowed');
                                        }
                                    }
                                });
                                return;
                            } else {
                                if (window.Swal) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Chọn biến thể',
                                        text: 'Vui lòng chọn biến thể trước khi thêm vào giỏ hàng.'
                                    });
                                }
                                if (btn) {
                                    btn.disabled = false;
                                    btn.classList.remove('opacity-70', 'cursor-not-allowed');
                                }
                                return;
                            }
                        }

                        // Add to cart
                        fetch('{{ route('cart.add') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    product_id: productId,
                                    quantity: quantity,
                                    variant_id: variantInput.value || null
                                })
                            })
                            .then(async (response) => {
                                const data = await response.json().catch(() => ({
                                    message: 'Lỗi không xác định'
                                }));

                                if (!response.ok) {
                                    throw new Error(data.message || 'Có lỗi xảy ra');
                                }

                                return data;
                            })
                            .then(data => {
                                if (window.Swal) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Thành công!',
                                        text: data.message,
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 1500,
                                        timerProgressBar: true,
                                    });
                                }
                                document.dispatchEvent(new CustomEvent('cartUpdated'));
                            })
                            .catch(error => {
                                console.error('Add to cart error:', error);
                                if (window.Swal) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi!',
                                        text: error.message ||
                                            'Có lỗi xảy ra khi thêm vào giỏ hàng.',
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                    });
                                }
                            })
                            .finally(() => {
                                if (btn) {
                                    btn.disabled = false;
                                    btn.classList.remove('opacity-70', 'cursor-not-allowed');
                                }
                            });
                    });
                });

                // Attach product data to card wrappers for quick access
                document.querySelectorAll('.group[data-product-json]').forEach(card => {
                    try {
                        card.variantData = JSON.parse(card.getAttribute('data-product-json'));
                    } catch (e) {
                        card.variantData = null;
                    }
                });

                function openVariantModal({
                    productId,
                    variants,
                    onConfirm,
                    onClose
                }) {
                    const modalId = `variant-modal-${productId}`;
                    let modal = document.getElementById(modalId);

                    if (!modal) {
                        modal = document.createElement('div');
                        modal.id = modalId;
                        modal.className =
                            'fixed inset-0 bg-gray-600/60 hidden z-50 flex items-center justify-center p-4';
                        modal.innerHTML = `
                        <div class="bg-white rounded-lg p-4 sm:p-6 w-full max-w-lg shadow-xl max-h-[90vh] overflow-y-auto">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-800">Chọn biến thể</h3>
                                <button type="button" class="text-gray-400 hover:text-gray-600" data-close>
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                            <div class="space-y-3" data-variants></div>
                            <div class="mt-4 flex items-center gap-3">
                                <span class="text-sm text-gray-700">Số lượng:</span>
                                <div class="inline-flex border rounded-lg overflow-hidden">
                                    <button type="button" class="px-3 py-1 border-r" data-dec>-</button>
                                    <input type="text" class="w-14 text-center" value="1" data-qty>
                                    <button type="button" class="px-3 py-1 border-l" data-inc>+</button>
                                </div>
                            </div>
                            <div class="mt-5 flex justify-end gap-3">
                                <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm" data-cancel>Hủy</button>
                                <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm disabled:opacity-50 disabled:cursor-not-allowed" data-confirm disabled>Xác nhận</button>
                            </div>
                        </div>`;
                        document.body.appendChild(modal);
                    }

                    const listEl = modal.querySelector('[data-variants]');
                    listEl.innerHTML = '';
                    let selectedId = null;

                    variants.forEach(v => {
                        const price = v.sale_price ?? v.price;
                        const isOut = (v.stock ?? 0) <= 0 || v.status === 'out_of_stock';
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className =
                            `w-full text-left px-3 py-2 rounded border ${isOut ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'} flex items-center justify-between`;
                        btn.disabled = isOut;
                        btn.dataset.id = v.id;
                        btn.innerHTML =
                            `<span class="text-sm">${v.variant_name || 'Biến thể'}</span><span class="text-sm font-semibold">${new Intl.NumberFormat('vi-VN').format(price)} đ</span>`;

                        btn.addEventListener('click', () => {
                            selectedId = v.id;
                            modal.querySelectorAll('[data-variants] button').forEach(b => b.classList
                                .remove('ring-2', 'ring-red-500'));
                            btn.classList.add('ring-2', 'ring-red-500');
                            modal.querySelector('[data-confirm]').disabled = false;
                        });

                        listEl.appendChild(btn);
                    });

                    const qtyInput = modal.querySelector('[data-qty]');
                    modal.querySelector('[data-dec]').onclick = () => {
                        const n = Math.max(1, (parseInt(qtyInput.value) || 1) - 1);
                        qtyInput.value = n;
                    };
                    modal.querySelector('[data-inc]').onclick = () => {
                        const n = (parseInt(qtyInput.value) || 1) + 1;
                        qtyInput.value = n;
                    };

                    const close = () => {
                        modal.classList.add('hidden');
                        if (onClose) onClose();
                    };

                    modal.querySelector('[data-close]').onclick = close;
                    modal.querySelector('[data-cancel]').onclick = close;
                    modal.onclick = (e) => {
                        if (e.target === modal) close();
                    };

                    modal.querySelector('[data-confirm]').onclick = () => {
                        if (!selectedId) {
                            if (window.Swal) {
                                Swal.fire({
                                    position: 'top-end',
                                    toast: true,
                                    icon: 'warning',
                                    title: 'Vui lòng chọn biến thể!',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                            return;
                        }
                        modal.classList.add('hidden');
                        if (onConfirm) onConfirm(selectedId, parseInt(qtyInput.value) || 1);
                    };

                    modal.classList.remove('hidden');
                }
            });
        </script>
    @endpush
@endsection
