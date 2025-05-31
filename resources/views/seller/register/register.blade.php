@extends('layouts.app')

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
                    <div class="step-label">Thực tính danh</div>
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
                    <label class="col-sm-3 col-form-label fw-semibold">Tên người dùng:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-lg" name="username" placeholder="">
                    </div>
                </div>
                <div class="mb-3 row align-items-center">
                    <label class="col-sm-3 col-form-label fw-semibold">Tên Shop:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-lg" name="shop_name" placeholder="">
                    </div>
                </div>
                <div class="mb-3 row align-items-start">
                    <label class="col-sm-3 col-form-label fw-semibold">Địa chỉ lấy hàng:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-lg" name="address" placeholder="">
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3 mt-2">+ Thêm địa chỉ</button>
                    </div>
                </div>
                <div class="mb-3 row align-items-center">
                    <label class="col-sm-3 col-form-label fw-semibold">Email:</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control form-control-lg" name="email" placeholder="">
                    </div>
                </div>
                <div class="mb-4 row align-items-center">
                    <label class="col-sm-3 col-form-label fw-semibold">Số điện thoại:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-lg" name="phone" placeholder="">
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