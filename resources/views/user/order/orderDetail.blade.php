@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="orderDetail.css">
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3 bg-light">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 fw-bold text-dark">Chi tiết đơn hàng</h2>
                            <p class="text-muted mb-0 fs-6">Đơn hàng #19893 | Order Created: Jan 26, 2023 10:30 AM</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <select class="form-select form-select-sm shadow-sm" style="width: 200px;">
                                <option value="delivered">Change Status: Delivered</option>
                                <option value="processing">Change Status: Processing</option>
                                <option value="cancelled">Change Status: Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm save-btn">Lưu</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold text-dark mb-3"><i class="bi bi-person-fill me-2 text-primary"></i>Khách hàng</h5>
                        <p class="card-text text-muted">
                            <strong>Tên:</strong> Nguyễn Văn A<br>
                            <strong>Email:</strong> thanhlinh1923@gmail.com<br>
                            <strong>Số điện thoại:</strong> 0123456789
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold text-dark mb-3"><i class="bi bi-credit-card-fill me-2 text-primary"></i>Thông tin thanh toán</h5>
                        <p class="card-text text-muted">
                            <strong>Phương thức thanh toán:</strong> VNPay<br>
                            <strong>Tên chủ tài khoản:</strong> Nguyễn Văn A<br>
                            <strong>Số tài khoản:</strong> 1234 **** ****
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold text-dark mb-3"><i class="bi bi-truck me-2 text-primary"></i>Thông tin giao hàng</h5>
                        <p class="card-text text-muted">
                            <strong>Phương thức vận chuyển:</strong> GHN<br>
                            <strong>Địa chỉ:</strong> 11 Hàng Điều, Tân Lộc, BMT
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold text-dark mb-4">Danh sách sản phẩm</h5>
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Sản phẩm</th>
                                        <th scope="col">Đơn giá</th>
                                        <th scope="col">Số lượng</th>
                                        <th scope="col">Thành giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <img src="https://down-vn.img.susercontent.com/file/sg-11134201-7rdvw-m0483czm1rcq5a.webp" alt="Puma" class="me-2 rounded" style="width: 40px; height: auto; vertical-align: middle;">
                                            Puma Sneakers
                                        </td>
                                        <td>99,999 VND</td>
                                        <td>3</td>
                                        <td>299,997 VND</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="https://down-vn.img.susercontent.com/file/sg-11134201-7rdvw-m0483czm1rcq5a.webp" alt="Puma" class="me-2 rounded" style="width: 40px; height: auto; vertical-align: middle;">
                                            Puma Sneakers
                                        </td>
                                        <td>99,999 VND</td>
                                        <td>3</td>
                                        <td>299,997 VND</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="https://down-vn.img.susercontent.com/file/sg-11134201-7rdvw-m0483czm1rcq5a.webp" alt="Puma" class="me-2 rounded" style="width: 40px; height: auto; vertical-align: middle;">
                                            Puma Sneakers
                                        </td>
                                        <td>99,999 VND</td>
                                        <td>3</td>
                                        <td>299,997 VND</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold text-dark mb-4">Tổng quan đơn hàng</h5>
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Tổng tiền hàng</td>
                                    <td class="text-end fw-medium">299,997 VND</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Phí ship</td>
                                    <td class="text-end fw-medium">0 VND</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Giảm giá</td>
                                    <td class="text-end fw-medium text-success">0 VND</td>
                                </tr>
                                <tr class="border-top pt-2">
                                    <td class="fw-bold text-dark">Tổng cộng</td>
                                    <td class="text-end fw-bold text-primary">299,997 VND</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection