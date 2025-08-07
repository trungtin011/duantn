@extends('user.account.profile')

@section('title', 'Giỏ hàng')

@section('account-content')
    <div class="container mx-auto py-5">
        <!-- Tiêu đề -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Giỏ hàng của bạn</h1>
        </div>

        <!-- Hiển thị thông báo flash -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if ($cartItems->isEmpty())
            <div class="bg-white p-5 rounded shadow text-center text-gray-500">
                Giỏ hàng của bạn đang trống.
            </div>
        @else
            @php
                // Nhóm các mục giỏ hàng theo shop_name và combo_id
                $groupedByShop = $cartItems->groupBy('product.shop.shop_name');
            @endphp

            @foreach ($groupedByShop as $shopName => $shopItems)
                <!-- Sản phẩm không thuộc combo -->
                @php
                    $nonComboItems = $shopItems->where('combo_id', null);
                @endphp
                @if ($nonComboItems->isNotEmpty())
                    <div class="mb-6 border rounded shadow bg-white">
                        <!-- Tên shop -->
                        <div class="relative p-3 border-b flex items-center gap-2"
                            style="background-image: url('{{ asset('storage/' . $nonComboItems->first()->product->shop->shop_banner) }}');
                                    background-size: cover; background-position: center;">
                            <div class="absolute inset-0 bg-black/50"></div>
                            <div class="relative z-10 flex items-center gap-2">
                                <img src="{{ asset('storage/' . $nonComboItems->first()->product->shop->shop_logo) }}"
                                    alt="logo" class="w-10 h-10 object-cover rounded-full">
                                <span class="font-semibold text-white text-lg">
                                    {{ $shopName }}
                                </span>
                            </div>
                        </div>

                        <div class="divide-y h-fit">
                            @foreach ($nonComboItems as $item)
                                @php $subtotal = $item->price * $item->quantity; @endphp
                                <div class="p-4 flex flex-col sm:flex-row items-center sm:items-center gap-4 h-fit">
                                    <input type="checkbox" name="cart_ids[]" value="{{ $item->id }}"
                                        class="cart-checkbox" data-id="{{ $item->id }}"
                                        data-product-id="{{ $item->product->id }}" data-variant-id="{{ $item->variantID }}"
                                        data-quantity="{{ $item->quantity }}"
                                        {{ in_array($item->id, session('selected_products', [])) ? 'checked' : '' }}>

                                    <!-- Hình ảnh -->
                                    @php
                                        $defaultImage =
                                            $item->product->images->where('is_default', true)->first() ??
                                            $item->product->images->first();
                                    @endphp
                                    <div class="w-24 h-24 flex-shrink-0 overflow">
                                        <img src="{{ $defaultImage ? asset('storage/' . $defaultImage->image_path) : asset('images/placeholder.png') }}"
                                            alt="{{ $item->product->name }}"
                                            class="w-full h-full object-cover border rounded">
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
                                            <input type="number" name="quantity" min="1"
                                                value="{{ $item->quantity }}"
                                                class="cart-quantity-input w-fit max-w-[50px] border text-center focus:outline-none"
                                                data-id="{{ $item->id }}" data-price="{{ $item->price }}"
                                                data-stock="{{ $item->variant ? $item->variant->stock : $item->product->stock_total }}"
                                                max="{{ $item->variant ? $item->variant->stock : $item->product->stock_total }}">
                                        </div>

                                        <div class="mt-2 flex flex-col justify-between items-end gap-2">
                                            <button class="remove-cart-item" data-id="{{ $item->id }}">
                                                <i class="fa-solid fa-trash hover:text-red-600" title="Xóa"></i>
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
                @endif

                <!-- Combo -->
                @php
                    $comboItems = $shopItems->where('combo_id', '!=', null)->groupBy('combo_id');
                @endphp
                @foreach ($comboItems as $comboId => $items)
                    <div class="mb-6 border rounded shadow bg-white">
                        <div class="p-4">
                            <div class="flex flex-col gap-4">
                                <!-- Checkbox và nút xóa chung cho combo -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="cart_ids[]" value="{{ $items->first()->id }}"
                                            class="cart-checkbox combo-checkbox" data-combo-id="{{ $comboId }}"
                                            data-cart-id="{{ $items->first()->id }}"
                                            {{ in_array($items->first()->id, session('selected_products', [])) ? 'checked' : '' }}>
                                        <span class="ml-2 text-lg font-semibold">Combo {{ $comboId }}</span>
                                    </div>
                                    <button class="remove-cart-item" data-id="{{ $items->first()->id }}">
                                        <i class="fa-solid fa-trash hover:text-red-600" title="Xóa combo"></i>
                                    </button>
                                </div>

                                <!-- Danh sách sản phẩm trong combo -->
                                @foreach ($items as $item)
                                    @php
                                        $subtotal = $item->price * $item->quantity;
                                        $defaultImage =
                                            $item->product->images->where('is_default', true)->first() ??
                                            $item->product->images->first();
                                    @endphp
                                    <div class="p-4 flex flex-col sm:flex-row items-center sm:items-center gap-4 h-fit">
                                        <!-- Hình ảnh -->
                                        <div class="w-24 h-24 flex-shrink-0 overflow">
                                            <img src="{{ $defaultImage ? asset('storage/' . $defaultImage->image_path) : asset('images/placeholder.png') }}"
                                                alt="{{ $item->product->name }}"
                                                class="w-full h-full object-cover border rounded">
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
                                                <p class="text-gray-500 text-sm">Số lượng trong combo:
                                                    {{ $item->combo->products->firstWhere('productID', $item->productID)->quantity ?? 1 }}
                                                </p>
                                            </div>

                                            <div class="mt-2 flex flex-col justify-between items-end gap-2">
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

                                <!-- Ô nhập số lượng chung cho combo -->
                                @php
                                    // Tính số lượng tối đa dựa trên tồn kho của các sản phẩm trong combo
                                    $maxQuantity = $items
                                        ->map(function ($item) {
                                            $stock = $item->variant
                                                ? $item->variant->stock
                                                : $item->product->stock_total;
                                            $comboProduct = $item->combo->products->firstWhere(
                                                'productID',
                                                $item->productID,
                                            );
                                            return floor($stock / ($comboProduct ? $comboProduct->quantity : 1));
                                        })
                                        ->min();
                                @endphp
                                <div class="mt-4">
                                    <label class="text-gray-700">Số lượng combo:</label>
                                    <input type="number"
                                        class="combo-quantity-input w-20 border border-gray-300 rounded-md p-2 text-sm"
                                        data-combo-id="{{ $comboId }}" data-cart-id="{{ $items->first()->id }}"
                                        value="{{ floor($items->first()->quantity / ($items->first()->combo->products->firstWhere('productID', $items->first()->productID)->quantity ?? 1)) }}"
                                        min="1" max="{{ $maxQuantity }}">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach

            <!-- Tổng thanh toán -->
            <div class="flex justify-between mt-6 bg-white rounded shadow">
                @php $total = $cartItems->whereIn('id', session('selected_products', []))->sum(fn($i) => $i->price * $i->quantity); @endphp
                <div class="flex items-center gap-4">
                    <div class="flex items-center py-4 ml-4">
                        <label>
                            <input type="checkbox" id="select-all" class="mr-2" Chọn tất cả </label>
                    </div>
                    <div class="border border-dashed border-gray-400 h-full"></div>
                    <div class="flex items-center py-4">
                        <span class="text-gray-700 mr-4">Tổng thanh toán:</span>
                        <span id="cart-total" class="text-2xl font-bold text-red-600">
                            {{ number_format($total, 0, ',', '.') }}đ
                        </span>
                    </div>
                </div>
                <a href="{{ route('checkout') }}"
                    class="ml-4 bg-[#EF3248] text-white px-6 py-2 m-4 rounded hover:bg-[#EF3248]/80 transition"
                    id="checkout-button">
                    Thanh toán
                </a>
            </div>
        @endif
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                    '{{ csrf_token() }}';
                if (!token) {
                    console.error('CSRF token not found!');
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Không tìm thấy CSRF token! Vui lòng tải lại trang.',
                    });
                    return;
                }

                document.querySelectorAll('.remove-cart-item').forEach(button => {
                    button.addEventListener('click', () => {
                        const cartItemId = button.getAttribute('data-id');
                        const confirmMessage = button.closest('.p-4').querySelector('.combo-checkbox') ?
                            'Bạn có chắc muốn xóa combo này?' :
                            'Bạn có chắc muốn xóa sản phẩm này?';

                        Swal.fire({
                            icon: 'warning',
                            title: 'Xác nhận xóa',
                            text: confirmMessage,
                            showCancelButton: true,
                            confirmButtonText: 'Xóa',
                            cancelButtonText: 'Hủy',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch(`/customer/cart/remove/${cartItemId}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': token,
                                            'Accept': 'application/json',
                                        },
                                    })
                                    .then(response => {
                                        if (!response.ok) throw new Error(
                                            `HTTP error! Status: ${response.status}`
                                        );
                                        return response.json();
                                    })
                                    .then(data => {
                                        Swal.fire({
                                            position: 'top-end',
                                            toast: true,
                                            icon: 'success',
                                            title: 'Đã xóa',
                                            text: data.message,
                                            timer: 1500,
                                            showConfirmButton: false,
                                        });
                                        window.location.reload();
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Lỗi',
                                            text: 'Đã có lỗi xảy ra khi xóa! Vui lòng thử lại.',
                                        });
                                    });
                            }
                        });
                    });
                });

                document.querySelectorAll('.cart-quantity-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const cartId = this.dataset.id;
                        const quantity = parseInt(this.value);
                        const maxStock = parseInt(this.dataset.stock);

                        if (quantity < 1) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Số lượng không hợp lệ!',
                                text: 'Số lượng phải lớn hơn hoặc bằng 1.',
                                confirmButtonText: 'OK',
                            });
                            this.value = 1;
                            return;
                        }

                        if (quantity > maxStock) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Số lượng vượt quá tồn kho!',
                                text: `Tồn kho hiện tại chỉ còn ${maxStock} sản phẩm.`,
                                confirmButtonText: 'OK',
                            });
                            this.value = maxStock;
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
                                }),
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
                                    showConfirmButton: false,
                                });

                                const price = parseFloat(this.dataset.price);
                                const subtotal = price * quantity;
                                const row = this.closest('.p-4');
                                const subtotalElement = row.querySelector('.subtotal');
                                if (subtotalElement) {
                                    subtotalElement.textContent = subtotal.toLocaleString('vi-VN') +
                                        'đ';
                                    subtotalElement.dataset.subtotal = subtotal;
                                }

                                updateTotal();
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

                document.querySelectorAll('.combo-quantity-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const cartId = this.dataset.cartId;
                        const comboId = this.dataset.comboId;
                        const quantity = parseInt(this.value);
                        const maxStock = parseInt(this.getAttribute('max'));

                        if (quantity < 1) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Số lượng không hợp lệ!',
                                text: 'Số lượng combo phải lớn hơn hoặc bằng 1.',
                                confirmButtonText: 'OK',
                            });
                            this.value = 1;
                            return;
                        }

                        if (quantity > maxStock) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Số lượng vượt quá tồn kho!',
                                text: `Số lượng combo tối đa là ${maxStock}.`,
                                confirmButtonText: 'OK',
                            });
                            this.value = maxStock;
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
                                }),
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
                                    showConfirmButton: false,
                                });
                                window.location.reload();
                            })
                            .catch(err => {
                                console.error('Lỗi chi tiết:', err);
                                const msg = err?.message || err?.errors?.quantity?.[0] ||
                                    'Không thể cập nhật số lượng combo!';
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

                // Xử lý checkbox và tổng thanh toán
                const selectAllCheckbox = document.getElementById('select-all');
                const checkoutButton = document.getElementById('checkout-button');
                const checkboxes = document.querySelectorAll('.cart-checkbox, .combo-checkbox');
                const totalElement = document.getElementById('cart-total');

                function getSelectedProductAndCartIds() {
                    let selected = [];
                    checkboxes.forEach((checkbox) => {
                        if (checkbox.checked) {
                            selected.push({
                                cart_id: checkbox.dataset.cartId || checkbox.dataset.id,
                                product_id: checkbox.dataset.productId,
                                variant_id: checkbox.dataset.variantId,
                                combo_id: checkbox.dataset.comboId,
                                quantity: checkbox.closest('.p-4')?.querySelector(
                                        '.cart-quantity-input, .combo-quantity-input')?.value ||
                                    checkbox.dataset.quantity,
                                subtotal: checkbox.closest('.p-4')?.querySelector('.subtotal')?.dataset
                                    .subtotal,
                            });
                        }
                    });
                    return selected;
                }

                function updateSelectedProductsInSession() {
                    const selected = getSelectedProductAndCartIds();
                    fetch('/customer/cart/selected', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                selected: selected
                            }),
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Không thể cập nhật session!');
                            return res.json();
                        })
                        .then(data => {
                            console.log('Sản phẩm được thêm vào session:', selected);
                        })
                        .catch(err => {
                            console.error('Lỗi cập nhật session:', err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Không thể cập nhật session!',
                            });
                        });
                }

                function updateTotal() {
                    let total = 0;
                    checkboxes.forEach((checkbox) => {
                        if (checkbox.checked) {
                            const row = checkbox.closest('.p-4');
                            if (checkbox.classList.contains('combo-checkbox')) {
                                // Tổng cho combo
                                const comboItems = row.querySelectorAll('.subtotal');
                                const quantity = parseInt(row.querySelector('.combo-quantity-input')?.value) ||
                                    1;
                                comboItems.forEach(item => {
                                    const subtotal = parseFloat(item.dataset.subtotal);
                                    total += subtotal;
                                });
                            } else {
                                // Tổng cho sản phẩm không thuộc combo
                                const quantity = parseInt(row.querySelector('.cart-quantity-input')?.value) ||
                                    1;
                                const subtotal = parseFloat(row.querySelector('.subtotal')?.dataset.subtotal);
                                total += subtotal;
                            }
                        }
                    });
                    if (totalElement) {
                        totalElement.textContent = total.toLocaleString('vi-VN') + 'đ';
                    }
                }

                function saveCheckboxState() {
                    const checkedIds = [];
                    checkboxes.forEach(cb => {
                        if (cb.checked) {
                            checkedIds.push(cb.dataset.comboId || cb.dataset.id);
                        }
                    });
                    localStorage.setItem('cart_checked_ids', JSON.stringify(checkedIds));
                }

                function restoreCheckboxState() {
                    const checkedIds = JSON.parse(localStorage.getItem('cart_checked_ids') || '[]');
                    checkboxes.forEach(cb => {
                        const id = cb.dataset.comboId || cb.dataset.id;
                        cb.checked = checkedIds.includes(id);
                    });
                }

                restoreCheckboxState();
                updateTotal();

                if (selectAllCheckbox) {
                    selectAllCheckbox.addEventListener('change', function() {
                        checkboxes.forEach(cb => {
                            cb.checked = selectAllCheckbox.checked;
                        });
                        updateTotal();
                        saveCheckboxState();
                    });
                }

                checkboxes.forEach(cb => {
                    cb.addEventListener('change', () => {
                        // Cập nhật trạng thái chọn tất cả
                        const allChecked = [...checkboxes].every(c => c.checked);
                        if (selectAllCheckbox) {
                            selectAllCheckbox.checked = allChecked;
                        }

                        updateTotal();
                        // Không gọi updateSelectedProductsInSession() ở đây nữa
                        saveCheckboxState();
                    });
                });

                if (selectAllCheckbox && checkoutButton) {
                    checkoutButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        const selected = getSelectedProductAndCartIds();
                        if (selected.length === 0) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Chưa chọn sản phẩm',
                                text: 'Vui lòng chọn ít nhất một sản phẩm để thanh toán!'
                            });
                            return;
                        }
                        fetch('/customer/cart/selected', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    selected
                                }),
                            })
                            .then(res => {
                                if (!res.ok) throw new Error('Không thể cập nhật session!');
                                return res.json();
                            })
                            .then(data => {
                                // Sau khi backend xác nhận, chuyển trang
                                window.location.href = checkoutButton.href;
                            })
                            .catch(err => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: 'Không thể cập nhật session! Vui lòng thử lại.'
                                });
                            });
                    });
                }
            });
        </script>
    @endpush
@endsection
