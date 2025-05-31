@extends('layouts.admin')

@section('head')
<style>
    /* Add custom styles specific to the order list page here */
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
    .badge-delivered {
        background-color: #d1fae5;
        color: #059669;
    }
    .badge-denied {
        background-color: #fee2e2;
        color: #dc2626;
    }
    .badge-refunded {
        background-color: #dbeafe;
        color: #2563eb;
    }
     .badge-pending {
        background-color: #fef9c3;
        color: #b45309;
    }
    .btn-view-details {
        background-color: #10b981;
        color: #fff;
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 0.9em;
    }
     .btn-action-icon {
        border-radius: 6px;
        border: 1px solid #e0e0e0;
        color: #64748b;
         padding: 5px 8px;
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
    <h1 style="font-weight:700;letter-spacing:-0.5px;font-size:1.8rem;margin-bottom: 0.3rem;">Order List</h1>
    <div class="text-muted" style="font-size:0.9em;"><a href="#" style="color:inherit;text-decoration:none;">Home</a> / Order List</div>
</div>

<div class="bg-white p-4 shadow-sm rounded-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center" style="gap:16px;">
            <div class="input-group" style="width: 280px;">
                <span class="input-group-text bg-white border-end-0" style="border-radius: 8px 0 0 8px;"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Search by order id" style="border-radius: 0 8px 8px 0; padding: 10px 12px;">
            </div>
        </div>
        <select class="form-select form-select-custom" style="width: 150px; border-radius: 8px; padding: 10px 12px;">
            <option selected>Status: Delivered</option>
            <option>All</option>
            <option>Delivered</option>
            <option>Denied</option>
            <option>Refunded</option>
            <option>Pending</option>
        </select>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0" style="border-collapse: separate; border-spacing: 0 8px;">
            <thead style="background:#f4f6fa;">
                <tr>
                    <th style="width: 40px; padding-left: 16px;"><input type="checkbox"></th>
                    <th>ORDER ID</th>
                    <th>CUSTOMER</th>
                    <th>QTY</th>
                    <th>TOTAL</th>
                    <th>STATUS</th>
                    <th>DATE</th>
                    <th>ACTION</th>
                    <th>INVOICE</th>
                </tr>
            </thead>
            <tbody>
                {{-- Sample Order Data --}}
                <tr style="background:#fff;">
                    <td style="padding-left: 16px;"><input type="checkbox"></td>
                    <td>#479063DR</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="https://i.pravatar.cc/40?img=10" alt="avatar" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            <span>William Watson</span>
                        </div>
                    </td>
                    <td>2</td>
                    <td>$171.00</td>
                    <td><span class="badge rounded-pill badge-delivered" style="padding:5px 12px;">Delivered</span></td>
                    <td>16 Jan, 2023</td>
                    <td><button class="btn btn-sm btn-view-details">View Details</button></td>
                    <td>
                         <button class="btn btn-sm btn-action-icon me-1"><i class="fa-solid fa-print"></i></button>
                         <button class="btn btn-sm btn-action-icon"><i class="fa-solid fa-eye"></i></button>
                    </td>
                </tr>
                 <tr style="background:#fff;">
                    <td style="padding-left: 16px;"><input type="checkbox"></td>
                    <td>#1893507</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="https://i.pravatar.cc/40?img=11" alt="avatar" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            <span>Shahnewaz Sakil</span>
                        </div>
                    </td>
                    <td>5</td>
                    <td>$1044.00</td>
                    <td><span class="badge rounded-pill badge-denied" style="padding:5px 12px;">Denied</span></td>
                    <td>18 Feb, 2023</td>
                    <td><button class="btn btn-sm btn-view-details">View Details</button></td>
                    <td>
                         <button class="btn btn-sm btn-action-icon me-1"><i class="fa-solid fa-print"></i></button>
                         <button class="btn btn-sm btn-action-icon"><i class="fa-solid fa-eye"></i></button>
                    </td>
                </tr>
                 <tr style="background:#fff;">
                    <td style="padding-left: 16px;"><input type="checkbox"></td>
                    <td>#26BC663E</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="https://i.pravatar.cc/40?img=12" alt="avatar" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            <span>Bootstrap Turner</span>
                        </div>
                    </td>
                    <td>7</td>
                    <td>$542.00</td>
                    <td><span class="badge rounded-pill badge-refunded" style="padding:5px 12px;">Refunded</span></td>
                    <td>25 Jan, 2023</td>
                    <td><button class="btn btn-sm btn-view-details">View Details</button></td>
                    <td>
                         <button class="btn btn-sm btn-action-icon me-1"><i class="fa-solid fa-print"></i></button>
                         <button class="btn btn-sm btn-action-icon"><i class="fa-solid fa-eye"></i></button>
                    </td>
                </tr>
                 <tr style="background:#fff;">
                    <td style="padding-left: 16px;"><input type="checkbox"></td>
                    <td>#373F9567</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="https://i.pravatar.cc/40?img=13" alt="avatar" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            <span>Robert Downy</span>
                        </div>
                    </td>
                    <td>15</td>
                    <td>$1450.00</td>
                    <td><span class="badge rounded-pill badge-pending" style="padding:5px 12px;">Pending</span></td>
                    <td>10 Feb, 2023</td>
                    <td><button class="btn btn-sm btn-view-details">View Details</button></td>
                    <td>
                         <button class="btn btn-sm btn-action-icon me-1"><i class="fa-solid fa-print"></i></button>
                         <button class="btn btn-sm btn-action-icon"><i class="fa-solid fa-eye"></i></button>
                    </td>
                </tr>
                 <tr style="background:#fff;">
                    <td style="padding-left: 16px;"><input type="checkbox"></td>
                    <td>#AD6ACDB9</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="https://i.pravatar.cc/40?img=14" alt="avatar" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            <span>Dr. Stephene</span>
                        </div>
                    </td>
                    <td>1</td>
                    <td>$540.00</td>
                    <td><span class="badge rounded-pill badge-delivered" style="padding:5px 12px;">Delivered</span></td>
                    <td>24 Jan, 2023</td>
                    <td><button class="btn btn-sm btn-view-details">View Details</button></td>
                    <td>
                         <button class="btn btn-sm btn-action-icon me-1"><i class="fa-solid fa-print"></i></button>
                         <button class="btn btn-sm btn-action-icon"><i class="fa-solid fa-eye"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted" style="font-size:0.9em;">Showing 10 items of 120</div>
        <nav aria-label="Page navigation example">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#">&lt;</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item active"><a class="page-link" href="#">3</a></li>
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