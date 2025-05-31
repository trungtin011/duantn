@extends('layouts.admin')

@section('head')
<style>
    /* Custom styles to match the image */
    .form-select.form-select-custom {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
        background-position: right 0.75rem center !important;
        background-size: 16px 12px !important;
    }
    .table th, .table td {
        padding: 12px 15px;
    }
    .table thead th {
        background-color: #f4f6fa;
        border-bottom: none;
    }
    .table tbody tr {
        background-color: #fff;
        border-bottom: 8px solid #f4f6fa;
    }
     .table tbody tr:last-child {
        border-bottom: none;
    }
    .table tbody td:first-child {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }
    .table tbody td:last-child {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }
    .badge-active {
        background-color: #d1fae5;
        color: #059669;
    }
    .badge-inactive {
        background-color: #fee2e2;
        color: #dc2626;
    }
    .badge-scheduled {
        background-color: #dbeafe;
        color: #2563eb;
    }
     .badge-low-stock {
        background-color: #fef9c3;
        color: #b45309;
    }
     .badge-out-of-stock {
        background-color: #fee2e2;
        color: #dc2626;
    }
    .btn-action {
        border-radius: 6px;
        border-color: #e0e0e0;
        color: #64748b;
    }
    .btn-action i {
        font-size: 0.9em;
    }
    .pagination .page-link {
        border-radius: 8px !important;
        margin: 0 4px;
        border: 1px solid #e0e0e0;
        color: #64748b;
    }
    .pagination .page-item.active .page-link {
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: #fff;
    }
    .pagination .page-item.disabled .page-link {
         color: #acb4be;
    }
</style>
@endsection

@section('content')
<div style="background: #f4f8fc; margin: -32px -32px 24px -32px; padding: 20px 32px; border-bottom: 1px solid #e0e0e0;">
    <h1 style="font-weight:700;letter-spacing:-0.5px;font-size:1.8rem;margin-bottom: 0.3rem;">Products</h1>
    <div class="text-muted" style="font-size:0.9em;"><a href="#" style="color:inherit;text-decoration:none;">Home</a> / Product List</div>
</div>

<div class="bg-white p-4 shadow-sm rounded-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center" style="gap:16px;">
            <div class="input-group" style="width: 280px;">
                <span class="input-group-text bg-white border-end-0" style="border-radius: 8px 0 0 8px;"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Search by product name" style="border-radius: 0 8px 8px 0; padding: 10px 12px;">
            </div>
            <select class="form-select form-select-custom" style="width: 150px; border-radius: 8px; padding: 10px 12px;">
                <option selected>Status: Active</option>
                <option>All</option>
                <option>Pending</option>
                <option>Disabled</option>
                 <option>In Active</option>
                 <option>Scheduled</option>
                 <option>Low Stock</option>
                 <option>Out of Stock</option>
            </select>
        </div>
        <a href="#" class="btn btn-primary" style="font-weight:500;font-size:1em;padding: 10px 24px; border-radius: 8px;">Add Product</a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0" style="border-collapse: separate; border-spacing: 0 8px;">
            <thead style="background:#f4f6fa;">
                <tr>
                    <th style="width: 40px; padding-left: 16px;"><input type="checkbox"></th>
                    <th style="padding-left: 8px;">PRODUCT</th>
                    <th>SKU</th>
                    <th>QTY</th>
                    <th>PRICE</th>
                    <th>RATING</th>
                    <th>STATUS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                {{-- Dữ liệu sản phẩm mẫu --}}
                <tr style="background:#fff;">
                    <td style="padding-left: 16px; "><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;" class="me-3">
                            <span>Whitetails Women's Open Sky</span>
                        </div>
                    </td>
                    <td>#479063DR</td>
                    <td>37</td>
                    <td>$171.00</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 5</td>
                    <td><span class="badge rounded-pill badge-active" style="padding:5px 12px;">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr style="background:#fff;">
                    <td style="padding-left: 16px; "><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;" class="me-3">
                            <span>Simple Modern School Boys</span>
                        </div>
                    </td>
                    <td>#DF7B2B64</td>
                    <td>124</td>
                    <td>$98.00</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 4.5</td>
                    <td><span class="badge rounded-pill badge-inactive" style="padding:5px 12px;">In Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr style="background:#fff;">
                    <td style="padding-left: 16px; "><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;" class="me-3">
                            <span>Women's Essentials Convertible</span>
                        </div>
                    </td>
                    <td>#373F9567</td>
                    <td>05</td>
                    <td>$56.99</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 4</td>
                    <td>
                         <span class="badge rounded-pill badge-scheduled" style="padding:5px 12px;">Scheduled</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr style="background:#fff;">
                    <td style="padding-left: 16px; "><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;" class="me-3">
                            <span>Calvin Klein Gabrianna Novelty</span>
                        </div>
                    </td>
                    <td>#C174E363</td>
                    <td>00</td>
                    <td>$340.18</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 4.8</td>
                    <td><span class="badge rounded-pill badge-out-of-stock" style="padding:5px 12px;">Out Of Stock</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr style="background:#fff;">
                    <td style="padding-left: 16px; "><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;" class="me-3">
                            <span>Tommy Hilfiger Women's Jaden</span>
                        </div>
                    </td>
                    <td>#26BC663E</td>
                    <td>57</td>
                    <td>$224.00</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 4.2</td>
                    <td><span class="badge rounded-pill badge-active" style="padding:5px 12px;">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr style="background:#fff;">
                    <td style="padding-left: 16px; "><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;" class="me-3">
                            <span>Govicta Men's Shoes Leather</span>
                        </div>
                    </td>
                    <td>#AD6ACDB9</td>
                    <td>45</td>
                    <td>$47.00</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 4.7</td>
                    <td><span class="badge rounded-pill badge-active" style="padding:5px 12px;">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr style="background:#fff;">
                    <td style="padding-left: 16px; "><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;" class="me-3">
                            <span>Legendary Whitetails Men's.</span>
                        </div>
                    </td>
                    <td>#3F6CBB65</td>
                    <td>78</td>
                    <td>$29.99</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 4.1</td>
                    <td><span class="badge rounded-pill badge-scheduled" style="padding:5px 12px;">Scheduled</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                 <tr style="background:#fff;">
                    <td style="padding-left: 16px; "><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;" class="me-3">
                            <span>Backpack, School Bag.</span>
                        </div>
                    </td>
                    <td>#8054F82C</td>
                    <td>104</td>
                    <td>$99.99</td>
                    <td><i class="fa-solid fa-star" style="color:#f59e42;"></i> 4.9</td>
                    <td><span class="badge rounded-pill badge-active" style="padding:5px 12px;">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary me-1 btn-action"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Phân trang mẫu --}}
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted" style="font-size:0.9em;">Showing 10 Product of 120</div>
        <nav aria-label="Page navigation example">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#">&lt;</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item"><a class="page-link" href="#">&gt;</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection 