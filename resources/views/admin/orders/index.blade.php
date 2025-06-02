@extends('layouts.admin')

@section('head')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/order.css') }}">
@endpush
@endsection

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Order List</h1>
    <div class="admin-breadcrumb"><a href="#" class="admin-breadcrumb-link">Home</a> / Order List</div>
</div>

<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="input-group search-input-group" style="width: 280px;">
                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Search by order id">
            </div>
        </div>
        <select class="form-select form-select-admin" style="width: 150px;">
            <option selected>Status: Delivered</option>
            <option>All</option>
            <option>Delivered</option>
            <option>Denied</option>
            <option>Refunded</option>
            <option>Pending</option>
        </select>
    </div>

    <div class="table-responsive admin-table-container">
        <table class="table align-middle mb-0 admin-table">
            <thead class="admin-table-thead">
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
                <tr>
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
                    <td><span class="badge rounded-pill badge-admin badge-delivered">Delivered</span></td>
                    <td>16 Jan, 2023</td>
                    <td><button class="btn btn-sm btn-success btn-admin-secondary">View Details</button></td>
                    <td>
                         <button class="btn btn-sm btn-action-icon me-1"><i class="fa-solid fa-print"></i></button>
                         <button class="btn btn-sm btn-action-icon"><i class="fa-solid fa-eye"></i></button>
                    </td>
                </tr>
                 <tr>
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
                    <td><button class="btn btn-sm btn-danger btn-admin-secondary">View Details</button></td>
                    <td>
                         <button class="btn btn-sm btn-action-icon me-1"><i class="fa-solid fa-print"></i></button>
                         <button class="btn btn-sm btn-action-icon"><i class="fa-solid fa-eye"></i></button>
                    </td>
                </tr>
                 <tr>
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
                    <td><button class="btn btn-sm btn-info btn-admin-secondary">View Details</button></td>
                    <td>
                         <button class="btn btn-sm btn-action-icon me-1"><i class="fa-solid fa-print"></i></button>
                         <button class="btn btn-sm btn-action-icon"><i class="fa-solid fa-eye"></i></button>
                    </td>
                </tr>
                 <tr>
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
                    <td><button class="btn btn-sm btn-warning btn-admin-secondary">View Details</button></td>
                    <td>
                         <button class="btn btn-sm btn-action-icon me-1"><i class="fa-solid fa-print"></i></button>
                         <button class="btn btn-sm btn-action-icon"><i class="fa-solid fa-eye"></i></button>
                    </td>
                </tr>
                 <tr>
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
                    <td><button class="btn btn-sm btn-success btn-admin-secondary">View Details</button></td>
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
        <div class="text-muted text-sm">Showing 10 items of 120</div>
        <nav aria-label="Pagination navigation">
            <ul class="pagination pagination-sm mb-0 pagination-admin">
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