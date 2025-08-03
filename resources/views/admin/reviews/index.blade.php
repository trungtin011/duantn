@extends('layouts.admin')

@section('content')
    <div class="admin-page-header">
        <h1 class="admin-page-title">Quản lý đánh giá</h1>
        <a href="{{ route('admin.dashboard') }}" class="admin-breadcrumb-link">Trang chủ</a> / Danh sách đánh giá

    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="product_name" value="{{ request('product_name') }}" class="form-control"
                placeholder="Tìm theo tên sản phẩm">
        </div>
        <div class="col-md-3">
            <input type="text" name="user" value="{{ request('user') }}" class="form-control"
                placeholder="Tìm theo tên khách hàng">
        </div>
        <div class="col-md-2">
            <select name="rating" class="form-select">
                <option value="">Tất cả sao</option>
                @for ($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }}
                        Sao</option>
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Lọc</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Khách hàng</th>
                    <th>Đánh giá</th>
                    <th>Ngày</th>
                    <th>Phản hồi của seller</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $review->product->image_url ?? 'https://via.placeholder.com/40' }}"
                                    class="me-2" style="width: 40px; height: 40px; object-fit: cover;" />
                                <span>{{ $review->product->name }}</span>
                            </div>
                        </td>
                        <td>
                            {{ $review->user->name }}
                            <br>
                            <small class="text-muted">ID: {{ $review->user->id }}</small>
                        </td>
                        <td>
                            <div class="text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                            <div class="text-muted small mt-1">{{ $review->comment }}</div>
                        </td>
                        <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if ($review->seller_reply)
                                <div class="bg-light border rounded p-2">
                                    <strong>Seller:</strong><br>
                                    {{ $review->seller_reply }}
                                </div>
                            @else
                                <span class="text-muted">Chưa phản hồi</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.reviews.banCustomer', $review->user->id) }}"
                                class="d-inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Xác nhận ban khách hàng này?')"
                                    class="btn btn-sm btn-danger mb-1">Ban Khách</button>
                            </form>

                            @if ($review->product->shop)
                                <form method="POST"
                                    action="{{ route('admin.reviews.warnSeller', $review->product->shop->id) }}"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Cảnh cáo hoặc ban seller?')"
                                        class="btn btn-sm btn-warning">Cảnh Cáo Seller</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Bạn có chắc muốn xoá đánh giá này?')"
                                    class="btn btn-sm btn-outline-danger">
                                    Xoá
                                </button>
                            </form>

                            <!-- Nút Ban seller thủ công -->
                            @if ($review->product->shop && $review->product->shop->owner)
                                <form method="POST"
                                    action="{{ route('admin.reviews.banSeller', $review->product->shop->owner->id) }}"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Xác nhận ban seller này?')"
                                        class="btn btn-sm btn-outline-dark">
                                        Ban Seller
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Không có đánh giá nào phù hợp.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $reviews->withQueryString()->links() }}
    </div>
@endsection
