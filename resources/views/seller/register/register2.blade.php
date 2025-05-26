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
                <div class="mb-3 row align-items-center">
                    <label class="col-sm-3 col-form-label fw-semibold">Loại hình kinh doanh</label>
                    <div class="col-sm-9 d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="business_type" id="type1" value="personal" checked>
                            <label class="form-check-label" for="type1">Cá nhân</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="business_type" id="type2" value="household">
                            <label class="form-check-label" for="type2">Hộ kinh doanh</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="business_type" id="type3" value="company">
                            <label class="form-check-label" for="type3">Công ty</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 row align-items-start">
                    <label class="col-sm-3 col-form-label fw-semibold">Địa chỉ đăng ký kinh doanh</label>
                    <div class="col-sm-9">
                        <select class="form-select mb-2" name="business_address_select">
                            <option selected>Chọn địa chỉ</option>
                            <option value="1">Địa chỉ 1</option>
                            <option value="2">Địa chỉ 2</option>
                        </select>
                        <textarea class="form-control form-control-lg" name="business_address" rows="2" placeholder=""></textarea>
                        <div class="form-text small mt-1">Đối với kinh doanh cá nhân điền theo giấy đăng ký kinh doanh đối với dạng tự. Hộ kinh doanh: hoặc theo giấy tờ định danh (trên CCCD/CMND) cá nhân, đối với công ty.</div>
                    </div>
                </div>
                <div class="mb-3 row align-items-center">
                    <label class="col-sm-3 col-form-label fw-semibold">Email nhận hóa đơn điện tử</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control form-control-lg" name="invoice_email" placeholder="">
                        <div class="form-text small mt-1">Hóa đơn điện tử của bạn sẽ được gửi đến địa chỉ email này</div>
                    </div>
                </div>
                <div class="mb-4 row align-items-center">
                    <label class="col-sm-3 col-form-label fw-semibold">Mã số thuế</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-lg" name="tax_code" placeholder="">
                        <div class="form-text small mt-1">Mã số thuế là mã số thuế kinh doanh. <a href="#">Tìm hiểu thêm.</a></div>
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