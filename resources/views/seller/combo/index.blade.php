
@extends('layouts.seller_home')

@section('content')
<div class="container">
    <h1>Quản lý Combo</h1>
    <a href="{{ route('seller.combo.create') }}" class="btn btn-primary mb-3">Tạo Combo Mới</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Combo</th>
                <th>Giá</th>
                <th>Giảm giá</th>
                <th>Trạng thái</th>
                <th>Sản phẩm</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($combos as $combo)
                <tr>
                    <td>{{ $combo->id }}</td>
                    <td>{{ $combo->combo_name }}</td>
                    <td>
                        <?php
                            $originalPrice = 0;
                            foreach ($combo->products as $comboProduct) {
                                $originalPrice += $comboProduct->product->price * $comboProduct->quantity;
                            }
                        ?>
                        <span style="text-decoration: line-through; color: #888;">
                            {{ number_format($originalPrice, 0, ',', '.') }} VNĐ
                        </span>
                        <br>
                        <span style="color: #e44d26; font-weight: bold;">
                            {{ number_format($combo->total_price, 0, ',', '.') }} VNĐ
                        </span>
                    </td>
                    <td>
                        {{ $combo->discount_value }}
                        {{ $combo->discount_type == 'percentage' ? '%' : 'VNĐ' }}
                    </td>
                    <td>{{ $combo->status }}</td>
                    <td>
                        <ul style="list-style: none; padding: 0;">
                            @foreach ($combo->products as $comboProduct)
                                <li style="display: flex; align-items: center; margin-bottom: 10px;">
                                    <img src="{{ $comboProduct->product->image }}" alt="{{ $comboProduct->product->name }}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                    <span>{{ $comboProduct->product->name }} (x{{ $comboProduct->quantity }})</span>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <a href="{{ route('seller.combo.edit', $combo->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('seller.combo.destroy', $combo->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
