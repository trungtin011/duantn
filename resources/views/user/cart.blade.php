
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endpush

@section('content')
<div class="container py-5" style="min-height:80vh;">
    <div class="w-100" style="max-width:900px; margin:auto;">
        <div class="d-flex align-items-center mb-4 text-secondary">
            <a href="#" class="text-secondary text-decoration-none">Home</a>
            <span class="mx-2">/</span>
            <span>Cart</span>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th scope="col" class="py-3 px-4">Sản phẩm</th>
                            <th scope="col" class="py-3">Giá</th>
                            <th scope="col" class="py-3">Số lượng</th>
                            <th scope="col" class="py-3">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Example Product 1 --}}
                        <tr>
                            <td class="align-middle py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('images/lcd_monitor.png') }}" alt="LCD Monitor" style="width: 60px; height: 60px; object-fit: contain; margin-right: 15px;">
                                    <span>LCD Monitor</span>
                                </div>
                            </td>
                            <td class="align-middle py-3">$650</td>
                            <td class="align-middle py-3">
                                <div class="input-group input-group-sm" style="width: 100px;">
                                    <input type="number" class="form-control text-center" value="1" min="1" max="99">
                                </div>
                            </td>
                            <td class="align-middle py-3">$650</td>
                        </tr>
                        {{-- Example Product 2 --}}
                        <tr>
                            <td class="align-middle py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('images/h1_gamepad.png') }}" alt="H1 Gamepad" style="width: 60px; height: 60px; object-fit: contain; margin-right: 15px;">
                                    <span>H1 Gamepad</span>
                                </div>
                            </td>
                            <td class="align-middle py-3">$550</td>
                            <td class="align-middle py-3">
                                <div class="input-group input-group-sm" style="width: 100px;">
                                    <input type="number" class="form-control text-center" value="2" min="1" max="99">
                                </div>
                            </td>
                            <td class="align-middle py-3">$1100</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="#" class="btn btn-light border px-4 py-2">Quay lại cửa hàng</a>
            <button class="btn btn-light border px-4 py-2">Cập nhật giỏ hàng</button>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="mb-3">Mã giảm giá</h6>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Nhập mã giảm giá">
                            <button class="btn btn-dark">Áp dụng mã</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Tổng số giỏ hàng</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tổng phụ:</span>
                            <span>$1750</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Vận chuyển:</span>
                            <span>Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Tổng tiền:</span>
                            <span>$1750</span>
                        </div>
                        <div class="mt-4 text-end">
                            <a href="#" class="btn btn-dark px-5 py-2">Tiến hành thanh toán</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection