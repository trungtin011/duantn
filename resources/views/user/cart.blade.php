@extends('user.account.profile')

@section('title', 'Giỏ hàng')

@section('account-content')
    <div class="container mx-auto py-5">
        <!-- Tiêu đề -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Giỏ hàng của bạn</h1>
        </div>

        @if ($cartItems->isEmpty())
            <div class="bg-white p-5 rounded shadow text-center text-gray-500">
                Giỏ hàng của bạn đang trống.
            </div>
        @else
            @php $grouped = $cartItems->groupBy('product.shop.shop_name'); @endphp

            @foreach ($grouped as $shopName => $items)
                <div class="mb-6 border rounded shadow bg-white">
                    <!-- Tên shop -->
                    <div class="relative p-3 border-b flex items-center gap-2"
                        style="background-image: url('{{ asset('storage/' . $items->first()->product->shop->shop_banner) }}');
                           background-size: cover; background-position: center;">
                        <div class="absolute inset-0 bg-black/50"></div>
                        <div class="relative z-10 flex items-center gap-2">
                            <img src="{{ asset('storage/' . $items->first()->product->shop->shop_logo) }}" alt="logo"
                                class="w-10 h-10 object-cover rounded-full">
                            <span class="font-semibold text-white text-lg">
                                {{ $shopName }}
                            </span>
                        </div>
                    </div>

                    <!-- Danh sách sản phẩm -->
                    <div class="divide-y h-fit">
                        @foreach ($items as $item)
                            @php $subtotal = $item->price * $item->quantity; @endphp
                            <div class="p-4 flex flex-col sm:flex-row items-center sm:items-center gap-4 h-fit">
                                <input type="checkbox" name="cart_ids[]" value="{{ $item->id }}" class="cart-checkbox"
                                    data-id="{{ $item->id }}" data-product-id="{{ $item->product->id }}"
                                    data-variant-id="{{ $item->variantID }}" data-quantity="{{ $item->quantity }}">

                                <!-- Hình ảnh -->
                                @php
                                    $defaultImage =
                                        $item->product->images->where('is_default', true)->first() ??
                                        $item->product->images->first();
                                @endphp

                                <div class="w-24 h-24 flex-shrink-0 overflow">
                                    <img src="{{ $defaultImage ? asset('storage/' . $defaultImage->image_path) : asset('images/placeholder.png') }}"
                                        alt="{{ $item->product->name }}" class="w-full h-full object-cover border rounded">
                                </div>

                                <!-- Thông tin sản phẩm -->
                                <div class="flex justify-between w-full h-auto">
                                    <div class="flex flex-col gap-2">
                                        <h2 class="text-gray-800 font-medium">{{ $item->product->name }}</h2>
                                        <div class="flex gap-5">
                                            <div class="text-gray-500 text-sm">
                                                Giá: {{ number_format($item->price, 0, ',', '.') }}đ
                                            </div>
                                            @if ($item->variant)
                                                <div class="text-sm text-gray-600">
                                                    {{ $item->variant->variant_name ?? '' }}
                                                </div>
                                            @endif
                                        </div>

                                        <input type="number" name="quantity" min="1" value="{{ $item->quantity }}"
                                            class="cart-quantity-input w-fit max-w-[50px] border text-center focus:outline-none"
                                            data-id="{{ $item->id }}" data-price="{{ $item->price }}"
                                            data-stock="{{ $item->variant ? $item->variant->stock_total : $item->product->stock_total }}"
                                            max="{{ $item->variant ? $item->variant->stock_total : $item->product->stock_total }}">
                                    </div>

                                    <div class="mt-2 flex flex-col justify-between items-end gap-2">
                                        <button class="remove-cart-item" data-id="{{ $item->id }}">
                                            <i class="fa-solid fa-trash hover:text-red-600" title="Xoá"></i>
                                        </button>

                                        <span class="text-sm">
                                            Tổng: <strong class="subtotal text-red-500"
                                                data-subtotal="{{ $subtotal }}">
                                                {{ number_format($subtotal, 0, ',', '.') }}đ
                                            </strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Tổng thanh toán -->
            <div class="flex justify-between mt-6 bg-white rounded shadow">
                @php $total = $cartItems->sum(fn($i) => $i->price * $i->quantity); @endphp
                <div class="flex items-center gap-4">
                    <div class="flex items-center py-4 ml-4">
                        <label>
                            <input type="checkbox" id="select-all" class="mr-2">
                            Chọn tất cả
                        </label>
                    </div>
                    <div class="border border-dashed border-gray-400 h-full"></div>
                    <div class="flex items-center py-4">
                        <span class="text-gray-700 mr-4">Tổng thanh toán:</span>
                        <span id="cart-total" class="text-2xl font-bold text-red-600">
                            {{ number_format($total, 0, ',', '.') }}đ
                        </span>
                    </div>
                </div>
                <button 
                    class="ml-4 bg-[#EF3248] text-white px-6 py-2 m-4 rounded hover:bg-[#EF3248]/80 transition" id='checkout-button'>
                    Thanh toán
                </button>
            </div>
        @endif
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const buttons = document.querySelectorAll('.remove-cart-item');
                const token = '{{ csrf_token() }}';

                if (!token) {
                    console.error('CSRF token not found!');
                    alert('Không tìm thấy CSRF token! Vui lòng tải lại trang.');
                    return;
                }

                buttons.forEach(button => {
                    button.addEventListener('click', () => {
                        const cartItemId = button.getAttribute('data-id');

                        fetch(`/customer/cart/remove/${cartItemId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json',
                                },
                            })
                            .then(response => {
                                if (!response.ok) throw new Error(
                                    `HTTP error! Status: ${response.status}`);
                                return response.json();
                            })
                            .then(data => {
                                window.location.reload();
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Đã có lỗi xảy ra khi xóa sản phẩm! Vui lòng thử lại.');
                            });
                    });
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                const inputs = document.querySelectorAll('.cart-quantity-input');
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                inputs.forEach(input => {
                    input.addEventListener('change', function() {
                        const cartId = this.dataset.id;
                        const quantity = parseInt(this.value);
                        const maxStock = parseInt(this.dataset.stock);

                        if (quantity > maxStock) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Số lượng vượt quá tồn kho!',
                                text: `Tồn kho hiện tại chỉ còn ${maxStock} sản phẩm.`,
                                confirmButtonText: 'OK'
                            });
                            this.value = maxStock; // reset lại về tối đa
                            return;
                        }

                        fetch(`/customer/cart/update/${cartId}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    quantity: quantity
                                })
                            })
                            .then(res => {
                                if (!res.ok) return res.json().then(err => Promise.reject(err));
                                return res.json();
                            })
                            .then(data => {
                                Swal.fire({
                                    position: 'top-end',
                                    toast: true,
                                    icon: 'success',
                                    title: 'Đã cập nhật',
                                    text: data.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                const price = parseFloat(this.dataset.price);
                                const subtotal = price * quantity;
                                const row = this.closest('tr') || this.closest('.p-4');
                                const subtotalElement = row.querySelector('.subtotal');
                                if (subtotalElement) {
                                    subtotalElement.textContent = subtotal.toLocaleString('vi-VN') +
                                        'đ';
                                }

                                let total = 0;
                                document.querySelectorAll('.cart-quantity-input').forEach(input => {
                                    const q = parseInt(input.value);
                                    const p = parseFloat(input.dataset.price);
                                    total += q * p;
                                });

                                const totalElement = document.getElementById('cart-total');
                                if (totalElement) {
                                    totalElement.textContent = total.toLocaleString('vi-VN') + 'đ';
                                }
                            })
                            .catch(err => {
                                console.error('Lỗi chi tiết:', err);
                                const msg = err?.message || err?.errors?.quantity?.[0] ||
                                    'Không thể cập nhật số lượng!';
                                Swal.fire({
                                    position: 'top-end',
                                    toast: true,
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: msg,
                                });
                            });
                    });
                });
            });
            
            (function() {
                const selectAllCheckbox = document.getElementById('select-all');
                const checkboxes = document.querySelectorAll('.cart-checkbox');
                const totalElement = document.getElementById('cart-total');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';


                // Helper: Lấy danh sách ID sản phẩm và ID cart đã chọn
                function getSelectedProductAndCartIds() {
                    let selected = [];
                    checkboxes.forEach((checkbox) => {
                        if (checkbox.checked) {
                            selected.push({
                                cart_id: checkbox.dataset.id,
                                product_id: checkbox.dataset.productId,
                                variant_id: checkbox.dataset.variantId,
                                quantity: checkbox.closest('.p-4').querySelector('.cart-quantity-input').value
                            });
                        }
                    });
                    return selected;
                }

                function updateSelectedProductsInSession() {
                    const selected = getSelectedProductAndCartIds();
                    return fetch('/customer/cart/selected', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ selected: selected })
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Không thể cập nhật session!');
                        return res.json();
                    })
                    .then(data => {
                        console.log('Sản phẩm được thêm vào session:', selected);
                        return data;
                    })
                    .catch(err => {
                        console.error('Lỗi cập nhật session:', err);
                        throw err;
                    });
                }

                function updateTotal() {
                    let total = 0;
                    checkboxes.forEach((checkbox) => {
                        if (checkbox.checked) {
                            const row = checkbox.closest('.p-4'); // mỗi sản phẩm
                            const subtotal = row.querySelector('.subtotal')?.dataset.subtotal;
                            total += parseInt(subtotal || 0);
                        }
                    });
                    if (totalElement) {
                        totalElement.textContent = total.toLocaleString('vi-VN') + 'đ';
                    }
                }

                checkboxes.forEach(cb => {
                    cb.addEventListener('change', () => {
                        // Nếu có 1 cái chưa chọn, bỏ chọn tất cả
                        if (!cb.checked && selectAllCheckbox) {
                            selectAllCheckbox.checked = false;
                        }

                        // Nếu tất cả đều đang được chọn thì bật "chọn tất cả"
                        const allChecked = [...checkboxes].every(c => c.checked);
                        if (selectAllCheckbox) {
                            selectAllCheckbox.checked = allChecked;
                        }

                        updateTotal();
                        // Khi người dùng tích vào checkbox thì lấy id sản phẩm và id cart gửi lên server
                        updateSelectedProductsInSession();
                    });
                });

                // Sự kiện chọn tất cả
                if (selectAllCheckbox) {
                    selectAllCheckbox.addEventListener('change', () => {
                        const isChecked = selectAllCheckbox.checked;
                        checkboxes.forEach(cb => cb.checked = isChecked);
                        updateTotal();
                        updateSelectedProductsInSession();
                    });
                }

                const checkoutButton = document.getElementById('checkout-button');
                if (checkoutButton) {
                    checkoutButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        updateSelectedProductsInSession()
                            .then(() => {
                                window.location.href = '{{ route('checkout') }}';
                            })
                            .catch(() => {
                                Swal.fire({
                                    position: 'top-end',
                                    toast: true,
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: 'Không thể lưu sản phẩm đã chọn. Vui lòng thử lại!',
                                });
                            });
                    });
                }
            })();
        </script>
    @endpush
@endsection
