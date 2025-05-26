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
                <li class="breadcrumb-item active" aria-current="page" style="color:#bdbdbd;">Đăng ký từ thành người bán</li>
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
                    <div class="step-dot"></div>
                    <div class="step-label">Cài đặt vận chuyển</div>
                </div>
                <div class="stepper-line"></div>
                <div class="stepper-step text-center flex-fill">
                    <div class="step-dot"></div>
                    <div class="step-label">Thông tin thuế</div>
                </div>
                <div class="stepper-line"></div>
                <div class="stepper-step text-center flex-fill">
                    <div class="step-dot"></div>
                    <div class="step-label">Thông tin định danh</div>
                </div>
                <div class="stepper-line"></div>
                <div class="stepper-step text-center flex-fill">
                    <div class="step-dot"></div>
                    <div class="step-label">Hoàn thành</div>
                </div>
            </div>
        </div>
        <!-- Card Form -->
        <div class="card shadow-sm rounded-4 p-4">
            <form>
                <!-- Hỏa Tốc -->
                <div class="mb-3">
                    <label class="fw-semibold mb-2">Hỏa Tốc</label>
                    <div class="d-flex align-items-center border rounded-3 px-3 py-2 position-relative">
                        <input type="text" class="form-control border-0 p-0 bg-transparent" value="Hỏa Tốc" readonly>
                        <span class="text-danger small ms-2">[COD đã được kích hoạt]</span>
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input" type="checkbox" checked>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm ms-2">Thu gọn</button>
                    </div>
                </div>
                <!-- Tiết Kiệm -->
                <div class="mb-3">
                    <label class="fw-semibold mb-2">Tiết Kiệm</label>
                    <div class="d-flex align-items-center border rounded-3 px-3 py-2 position-relative">
                        <input type="text" class="form-control border-0 p-0 bg-transparent" value="Tiết Kiệm" readonly>
                        <span class="text-danger small ms-2">[COD đã được kích hoạt]</span>
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input" type="checkbox" checked>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm ms-2">Thu gọn</button>
                    </div>
                </div>
                <!-- Tự Nhận Hàng -->
                <div class="mb-3">
                    <label class="fw-semibold mb-2">Tự Nhận Hàng</label>
                    <div class="d-flex align-items-center border rounded-3 px-3 py-2 position-relative">
                        <input type="text" class="form-control border-0 p-0 bg-transparent" value="Tự Nhận Hàng" readonly>
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input" type="checkbox" checked>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm ms-2">Thu gọn</button>
                    </div>
                </div>
                <!-- Hỏa Tốc (2) -->
                <div class="mb-3">
                    <label class="fw-semibold mb-2">Hỏa Tốc</label>
                    <div class="d-flex align-items-center border rounded-3 px-3 py-2 position-relative">
                        <input type="text" class="form-control border-0 p-0 bg-transparent" value="Hỏa Tốc" readonly>
                        <span class="text-danger small ms-2">[COD đã được kích hoạt]</span>
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input" type="checkbox" checked>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm ms-2">Thu gọn</button>
                    </div>
                </div>
                <!-- Hỏa Tốc (3) -->
                <div class="mb-4">
                    <label class="fw-semibold mb-2">Hỏa Tốc</label>
                    <div class="d-flex align-items-center border rounded-3 px-3 py-2 position-relative">
                        <input type="text" class="form-control border-0 p-0 bg-transparent" value="Hỏa Tốc" readonly>
                        <span class="text-danger small ms-2">[COD đã được kích hoạt]</span>
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input" type="checkbox" checked>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm ms-2">Thu gọn</button>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-light border">Lưu</button>
                    <button type="button" class="btn btn-danger px-4">Tiếp theo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
