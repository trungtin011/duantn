
@extends('layouts.seller_home')

@section('content')
<div class="container">
    <h1>Sửa Combo</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('seller.combo.update', $combo->id) }}" method="POST" id="combo-form">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label for="combo_name">Tên Combo</label>
            <input type="text" name="combo_name" id="combo_name" class="form-control" value="{{ old('combo_name', $combo->combo_name) }}" required>
        </div>
        <div class="form-group">
            <label for="combo_description">Mô tả</label>
            <textarea name="combo_description" id="combo_description" class="form-control">{{ old('combo_description', $combo->combo_description) }}</textarea>
        </div>
        <div class="form-group">
            <label for="total_price">Tổng giá (VNĐ)</label>
            <input type="number" name="total_price" id="total_price" class="form-control" value="{{ old('total_price', $combo->total_price) }}" readonly>
        </div>
        <div class="form-group">
            <label for="discount_value">Giá trị giảm giá</label>
            <input type="number" name="discount_value" id="discount_value" class="form-control" value="{{ old('discount_value', $combo->discount_value) }}" min="0">
        </div>
        <div class="form-group">
            <label for="discount_type">Loại giảm giá</label>
            <select name="discount_type" id="discount_type" class="form-control">
                <option value="">Không giảm giá</option>
                <option value="percentage" {{ old('discount_type', $combo->discount_type) == 'percentage' ? 'selected' : '' }}>%</option>
                <option value="fixed" {{ old('discount_type', $combo->discount_type) == 'fixed' ? 'selected' : '' }}>VNĐ</option>
            </select>
        </div>
      

        <h3>Chọn sản phẩm (ít nhất 2 sản phẩm)</h3>
        <div id="product-list">
            @foreach ($combo->products as $index => $comboProduct)
                <div class="product-item mb-3">
                    <div class="row">
                        <div class="col-md-7">
                            <label>Sản phẩm</label>
                            <select name="products[{{ $index }}][productID]" class="form-control product-select" required onchange="calculateTotal()">
                                <option value="">Chọn sản phẩm</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ $comboProduct->productID == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Số lượng</label>
                            <input type="number" name="products[{{ $index }}][quantity]" class="form-control quantity-input" value="{{ $comboProduct->quantity }}" min="1" required oninput="calculateTotal()">
                        </div>
                        <div class="col-md-2">
                            <label> </label>
                            <button type="button" class="btn btn-danger btn-sm form-control" onclick="removeProduct(this)">Xóa</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-secondary mb-3" onclick="addProduct()">Thêm sản phẩm</button>

        <button type="submit" class="btn btn-primary">Cập nhật Combo</button>
    </form>
</div>

<script>
    let productIndex = {{ count($combo->products) }};

    // Product prices from backend
    const productPrices = {
        @foreach ($products as $product)
            "{{ $product->id }}": {{ $product->price }},
        @endforeach
    };

    function addProduct() {
        const productList = document.getElementById('product-list');
        const newProduct = `
            <div class="product-item mb-3">
                <div class="row">
                    <div class="col-md-7">
                        <label>Sản phẩm</label>
                        <select name="products[${productIndex}][productID]" class="form-control product-select" required onchange="calculateTotal()">
                            <option value="">Chọn sản phẩm</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Số lượng</label>
                        <input type="number" name="products[${productIndex}][quantity]" class="form-control quantity-input" value="1" min="1" required oninput="calculateTotal()">
                    </div>
                    <div class="col-md-2">
                        <label> </label>
                        <button type="button" class="btn btn-danger btn-sm form-control" onclick="removeProduct(this)">Xóa</button>
                    </div>
                </div>
            </div>`;
        productList.insertAdjacentHTML('beforeend', newProduct);
        productIndex++;
        calculateTotal();
    }

    function removeProduct(button) {
        const productList = document.getElementById('product-list');
        if (productList.querySelectorAll('.product-item').length <= 2) {
            alert('Combo phải có ít nhất 2 sản phẩm!');
            return;
        }
        button.closest('.product-item').remove();
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        const productItems = document.querySelectorAll('.product-item');

        productItems.forEach(item => {
            const select = item.querySelector('.product-select');
            const quantityInput = item.querySelector('.quantity-input');
            const productId = select.value;
            const quantity = parseInt(quantityInput.value) || 1;

            if (productId && productPrices[productId]) {
                total += productPrices[productId] * quantity;
            }
        });

        // Apply discount
        const discountValue = parseFloat(document.getElementById('discount_value').value) || 0;
        const discountType = document.getElementById('discount_type').value;

        if (discountType === 'percentage' && discountValue > 0) {
            total -= (total * discountValue) / 100;
        } else if (discountType === 'fixed' && discountValue > 0) {
            total -= discountValue;
        }

        // Ensure total is not negative
        total = Math.max(0, total);

        // Update total price field
        document.getElementById('total_price').value = Math.round(total);
    }

    // Add event listeners for discount inputs
    document.getElementById('discount_value').addEventListener('input', calculateTotal);
    document.getElementById('discount_type').addEventListener('change', calculateTotal);

    // Prevent form submission if fewer than 2 products
    document.getElementById('combo-form').addEventListener('submit', function (event) {
        const productItems = document.querySelectorAll('.product-item');
        if (productItems.length < 2) {
            event.preventDefault();
            alert('Combo phải có ít nhất 2 sản phẩm!');
        }
    });

    // Initial calculation
    calculateTotal();
</script>
@endsection
