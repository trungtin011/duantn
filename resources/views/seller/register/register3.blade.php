@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/seller-register.css') }}">
@endpush
@section('content')
<div class="container py-5 d-flex flex-column align-items-center" style="min-height:80vh; background:#f7f7f7;">
    <div class="w-100" style="max-width:900px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small" style="--bs-breadcrumb-divider: '>'; color:#bdbdbd;">
                <li class="breadcrumb-item"><a href="/" style="color:#bdbdbd;">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color:#bdbdbd;">Đăng ký trở thành người bán</li>
            </ol>
        </nav>
        <!-- Stepper -->
        <div class="mb-4 px-4">
            <div class="d-flex justify-content-between align-items-center position-relative" style="margin-bottom:30px;">
                <div class="stepper-step text-center flex-fill">
                    <div class="step-dot active"></div>
                    <div class="step-label">Thông tin shop</div>
                </div>
                <div class="stepper-line"></div>
                <div class="stepper-step text-center flex-fill">
                    <div class="step-dot active"></div>
                    <div class="step-label">Cài đặt vận chuyển</div>
                </div>
                <div class="stepper-line"></div>
                <div class="stepper-step text-center flex-fill">
                    <div class="step-dot active"></div>
                    <div class="step-label">Thông tin thuế</div>
                </div>
                <div class="stepper-line"></div>
                <div class="stepper-step text-center flex-fill">
                    <div class="step-dot active"></div>
                    <div class="step-label">Thông tin định danh</div>
                </div>
                <div class="stepper-line"></div>
                <div class="stepper-step text-center flex-fill">
                    <div class="step-dot active"></div>
                    <div class="step-label">Hoàn thành</div>
                </div>
            </div>
        </div>
        <!-- Success Card -->
        <div class="card shadow-sm rounded-4 p-5 d-flex align-items-center justify-content-center" style="min-height:350px;">
            <div class="text-center">
                <div class="mb-4">
                    <span style="display:inline-block; background:#e6f9ed; border-radius:50%; padding:24px;">
                        <i class="fa fa-check" style="font-size:3rem; color:#22c55e;"></i>
                    </span>
                </div>
                <h4 class="fw-bold mb-2">Đăng ký thành công</h4>
                <div class="mb-3" style="color:#4a5568; font-size:1rem;">Hãy đăng bán sản phẩm đầu tiên để khởi động hành trình bán hàng cùng</div>
                <a href="#" class="btn btn-danger px-4">Thêm sản phẩm</a>
            </div>
        </div>
    </div>
</div>
@endsection
