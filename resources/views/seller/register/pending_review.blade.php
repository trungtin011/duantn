@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header bg-warning text-white text-center">
                    <h3 class="font-weight-light my-4">Đăng Ký Shop Đang Chờ Duyệt</h3>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-clock fa-5x text-warning"></i>
                    </div>
                    <p class="lead">Cảm ơn bạn đã đăng ký mở shop tại {{ config('app.name') }}.</p>
                    <p>Đơn đăng ký của bạn đã được gửi thành công và hiện đang trong quá trình xem xét.</p>
                    <p>Chúng tôi sẽ thông báo cho bạn qua email khi quá trình xét duyệt hoàn tất, thường là trong vòng <strong>3-4 ngày làm việc</strong>.</p>
                    <p>Vui lòng kiên nhẫn chờ đợi.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Quay về Trang Chủ</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
