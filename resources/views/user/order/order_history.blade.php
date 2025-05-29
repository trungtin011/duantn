
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/order_history.css') }}">
{{-- Link for Bootstrap Icons (needed for chat and shop icons) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('content')
<div class="container py-5" style="min-height:80vh; background:#fff;">
    <div class="w-100" style="max-width:1000px; margin:auto;">
        {{-- Breadcrumbs --}}
        <div class="d-flex align-items-center mb-4 text-secondary">
            <a href="#" class="text-secondary text-decoration-none">Trang chủ</a>
            <span class="mx-2">/</span>
            <span>Lịch sử đơn hàng</span>
        </div>

        {{-- Tabs for Order Status --}}
        <ul class="nav nav-tabs mb-4 border-0" id="orderStatusTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold text-dark border-0 border-bottom border-dark border-3 rounded-0 pb-3" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">Tất cả</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-muted border-0 rounded-0 pb-3" id="processing-tab" data-bs-toggle="tab" data-bs-target="#processing" type="button" role="tab" aria-controls="processing" aria-selected="false">Đang xử lý</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-muted border-0 rounded-0 pb-3" id="awaiting-pickup-tab" data-bs-toggle="tab" data-bs-target="#awaiting-pickup" type="button" role="tab" aria-controls="awaiting-pickup" aria-selected="false">Chờ lấy hàng</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-muted border-0 rounded-0 pb-3" id="in-delivery-tab" data-bs-toggle="tab" data-bs-target="#in-delivery" type="button" role="tab" aria-controls="in-delivery" aria-selected="false">Đang giao hàng</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-muted border-0 rounded-0 pb-3" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">Hoàn thành</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-muted border-0 rounded-0 pb-3" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled" aria-selected="false">Đã hủy</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-muted border-0 rounded-0 pb-3" id="returns-tab" data-bs-toggle="tab" data-bs-target="#returns" type="button" role="tab" aria-controls="returns" aria-selected="false">Trả hàng/Hoàn tiền</button>
            </li>
        </ul>

        <div class="tab-content" id="orderStatusTabsContent">
            {{-- Tab Pane: Tất cả (All) --}}
            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">

                {{-- Example Order Block 1 (from Shop A) --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0 fw-bold me-3">Shop Quần Áo XYZ</h6>
                            <button class="btn btn-sm btn-outline-secondary d-flex align-items-center me-2 py-1 px-3">
                                <i class="bi bi-chat-dots me-1"></i> Chat
                            </button>
                            <button class="btn btn-sm btn-outline-secondary d-flex align-items-center py-1 px-3">
                                <i class="bi bi-shop me-1"></i> Xem Shop
                            </button>
                        </div>
                    </div>
                    <div class="card-body px-4 py-2">
                        {{-- Product 1 for Shop A --}}
                        <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="me-3" style="width: 80px; height: 80px; background-color: #f0f0f0; border-radius: 4px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ asset('images/sample_tee.png') }}" alt="Áo thun cơ bản" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-normal">Áo thun cơ bản màu trắng (size M)</h6>
                                    <p class="text-muted mb-0">Số lượng: 2</p>
                                </div>
                            </div>
                            <span class="fw-bold text-dark">Giá: 99.000đ</span>
                        </div>
                        {{-- Product 2 for Shop A --}}
                        <div class="d-flex align-items-center justify-content-between py-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3" style="width: 80px; height: 80px; background-color: #f0f0f0; border-radius: 4px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ asset('images/sample_pants.png') }}" alt="Quần jean slimfit" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-normal">Quần jean slimfit (size 30)</h6>
                                    <p class="text-muted mb-0">Số lượng: 1</p>
                                </div>
                            </div>
                            <span class="fw-bold text-dark">Giá: 250.000đ</span>
                        </div>
                    </div>
                    <div class="card-footer bg-light d-flex justify-content-end align-items-center py-3">
                        <span class="fw-bold me-4">Thành tiền: <span class="text-danger">448.000đ</span></span>
                        <button class="btn btn-sm btn-success px-4 py-2 me-2" style="background-color: #00a854; border-color: #00a854;">Mua Lại</button>
                        <button class="btn btn-sm btn-outline-secondary px-4 py-2 me-2">Liên hệ người bán</button>
                        <button class="btn btn-sm btn-outline-secondary px-4 py-2">Xem chi tiết</button>
                    </div>
                </div>

                {{-- Example Order Block 2 (from Shop B - showing different buttons) --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0 fw-bold me-3">Cửa hàng Điện tử VNK</h6>
                            <button class="btn btn-sm btn-outline-secondary d-flex align-items-center me-2 py-1 px-3">
                                <i class="bi bi-chat-dots me-1"></i> Chat
                            </button>
                            <button class="btn btn-sm btn-outline-secondary d-flex align-items-center py-1 px-3">
                                <i class="bi bi-shop me-1"></i> Xem Shop
                            </button>
                        </div>
                    </div>
                    <div class="card-body px-4 py-2">
                        {{-- Product 1 for Shop B --}}
                        <div class="d-flex align-items-center justify-content-between py-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3" style="width: 80px; height: 80px; background-color: #f0f0f0; border-radius: 4px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ asset('images/sample_headphone.png') }}" alt="Tai nghe không dây" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-normal">Tai nghe Bluetooth X500</h6>
                                    <p class="text-muted mb-0">Số lượng: 1</p>
                                </div>
                            </div>
                            <span class="fw-bold text-dark">Giá: 799.000đ</span>
                        </div>
                    </div>
                    <div class="card-footer bg-light d-flex justify-content-end align-items-center py-3">
                        <span class="fw-bold me-4">Thành tiền: <span class="text-danger">799.000đ</span></span>
                        {{-- Example: No "Mua Lại" button for specific order statuses or product types --}}
                        <button class="btn btn-sm btn-outline-secondary px-4 py-2 me-2">Liên hệ người bán</button>
                        <button class="btn btn-sm btn-outline-secondary px-4 py-2">Xem chi tiết</button>
                    </div>
                </div>

                {{-- Example Order Block 3 (from Shop C - a cancelled order example) --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0 fw-bold me-3">Hiệu sách Trí Thức</h6>
                            <button class="btn btn-sm btn-outline-secondary d-flex align-items-center me-2 py-1 px-3">
                                <i class="bi bi-chat-dots me-1"></i> Chat
                            </button>
                            <button class="btn btn-sm btn-outline-secondary d-flex align-items-center py-1 px-3">
                                <i class="bi bi-shop me-1"></i> Xem Shop
                            </button>
                        </div>
                    </div>
                    <div class="card-body px-4 py-2">
                        <div class="d-flex align-items-center justify-content-between py-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3" style="width: 80px; height: 80px; background-color: #f0f0f0; border-radius: 4px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ asset('images/sample_book.png') }}" alt="Sách Lập trình Laravel" class="img-fluid" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-normal">Sách: Lập trình Laravel từ A-Z</h6>
                                    <p class="text-muted mb-0">Số lượng: 1</p>
                                </div>
                            </div>
                            <span class="fw-bold text-dark">Giá: 320.000đ</span>
                        </div>
                    </div>
                    <div class="card-footer bg-light d-flex justify-content-end align-items-center py-3">
                        <span class="fw-bold me-4">Thành tiền: <span class="text-danger">320.000đ</span></span>
                        {{-- For cancelled orders, "Mua Lại" and "Liên hệ người bán" might not be relevant --}}
                        <button class="btn btn-sm btn-outline-secondary px-4 py-2">Xem chi tiết</button>
                    </div>
                </div>

                {{-- Message for no orders in this tab (uncomment to see this state) --}}
                {{--
                <div class="card shadow-sm border-0 text-center py-5">
                    <div class="card-body">
                        <h5 class="text-muted">Bạn chưa có đơn hàng nào trong trạng thái này.</h5>
                        <a href="#" class="btn btn-dark mt-3">Quay lại mua sắm</a>
                    </div>
                </div>
                --}}

            </div>

            {{-- Other Tab Panes (empty for this sample) --}}
            <div class="tab-pane fade" id="processing" role="tabpanel" aria-labelledby="processing-tab">
                <div class="card shadow-sm border-0 text-center py-5">
                    <div class="card-body">
                        <h5 class="text-muted">Bạn không có đơn hàng nào đang xử lý.</h5>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="awaiting-pickup" role="tabpanel" aria-labelledby="awaiting-pickup-tab">
                <div class="card shadow-sm border-0 text-center py-5">
                    <div class="card-body">
                        <h5 class="text-muted">Bạn không có đơn hàng nào chờ lấy hàng.</h5>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="in-delivery" role="tabpanel" aria-labelledby="in-delivery-tab">
                <div class="card shadow-sm border-0 text-center py-5">
                    <div class="card-body">
                        <h5 class="text-muted">Bạn không có đơn hàng nào đang giao hàng.</h5>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                <div class="card shadow-sm border-0 text-center py-5">
                    <div class="card-body">
                        <h5 class="text-muted">Bạn không có đơn hàng nào đã hoàn thành.</h5>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                <div class="card shadow-sm border-0 text-center py-5">
                    <div class="card-body">
                        <h5 class="text-muted">Bạn không có đơn hàng nào đã hủy.</h5>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="returns" role="tabpanel" aria-labelledby="returns-tab">
                <div class="card shadow-sm border-0 text-center py-5">
                    <div class="card-body">
                        <h5 class="text-muted">Bạn không có đơn hàng nào đang yêu cầu trả hàng/hoàn tiền.</h5>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection