@extends('layouts.admin')

@section('head')
<style>
    /* Add custom styles specific to the category page here */
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
    <h1 style="font-weight:700;letter-spacing:-0.5px;font-size:1.8rem;margin-bottom: 0.3rem;">Category</h1>
    <div class="text-muted" style="font-size:0.9em;"><a href="#" style="color:inherit;text-decoration:none;">Home</a> / Category List</div>
</div>

<div class="row g-3">
    {{-- Left Column: Add/Edit Category Form --}}
    <div class="col-md-4">
        <div class="bg-white p-4 shadow-sm rounded-4">
            <h5 class="mb-3" style="font-weight:600;">Upload Image</h5>
            <div class="border rounded-3 d-flex justify-content-center align-items-center mb-3" style="height: 150px; background-color: #f4f6fa;">
                <div class="text-center text-muted">
                    <img src="https://via.placeholder.com/60" alt="Image Placeholder" class="mb-2">
                    <p class="mb-0" style="font-size:0.9em;">Image size must be less than 5Mb</p>
                </div>
            </div>
            <div class="mb-3">
                <label for="uploadImage" class="form-label">Upload Image</label>
                <input class="form-control" type="file" id="uploadImage">
            </div>
            <div class="mb-3">
                <label for="categoryName" class="form-label">Name</label>
                <input type="text" class="form-control" id="categoryName" placeholder="Name">
            </div>
            <div class="mb-3">
                <label for="categorySlug" class="form-label">Slug</label>
                <input type="text" class="form-control" id="categorySlug" placeholder="Slug">
            </div>
            <div class="mb-3">
                <label for="parentCategory" class="form-label">Parent</label>
                <select class="form-select form-select-custom" id="parentCategory">
                    <option selected>Electronics</option>
                    <option>Option 1</option>
                    <option>Option 2</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="categoryDescription" class="form-label">Description</label>
                <textarea class="form-control" id="categoryDescription" rows="3" placeholder="Description Here"></textarea>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="createAsParent">
                <label class="form-check-label" for="createAsParent">Create As Parent Category</label>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
        </div>
    </div>

    {{-- Right Column: Category List Table --}}
    <div class="col-md-8">
        <div class="bg-white p-4 shadow-sm rounded-4 mb-4">
             <div class="table-responsive">
                <table class="table align-middle mb-0" style="border-collapse: separate; border-spacing: 0 8px;">
                    <thead style="background:#f4f6fa;">
                        <tr>
                            <th style="width: 40px; padding-left: 16px;"><input type="checkbox"></th>
                            <th>ID</th>
                            <th>NAME</th>
                            <th>DESCRIPTION</th>
                            <th>SLUG</th>
                            <th>ITEMS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Sample Category Data --}}
                        <tr style="background:#fff;">
                            <td style="padding-left: 16px;"><input type="checkbox"></td>
                            <td>#479063DR</td>
                             <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/30" alt="" style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;" class="me-2">
                                    <span>Fashion</span>
                                </div>
                            </td>
                            <td>Lorem ipsum</td>
                            <td>/fashion</td>
                            <td>78</td>
                            <td>
                                <button class="btn btn-sm btn-outline-success me-1 btn-action"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-sm btn-outline-danger btn-action"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                         <tr style="background:#fff;">
                            <td style="padding-left: 16px;"><input type="checkbox"></td>
                            <td>#479063DR</td>
                             <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/30" alt="" style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;" class="me-2">
                                    <span>Bags</span>
                                </div>
                            </td>
                            <td>Lorem ipsum</td>
                            <td>/bags</td>
                            <td>105</td>
                            <td>
                                <button class="btn btn-sm btn-outline-success me-1 btn-action"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-sm btn-outline-danger btn-action"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                         <tr style="background:#fff;">
                            <td style="padding-left: 16px;"><input type="checkbox"></td>
                            <td>#479063DR</td>
                             <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/30" alt="" style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;" class="me-2">
                                    <span>Shoes</span>
                                </div>
                            </td>
                            <td>Lorem ipsum</td>
                            <td>/shoes</td>
                            <td>25</td>
                            <td>
                                <button class="btn btn-sm btn-outline-success me-1 btn-action"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-sm btn-outline-danger btn-action"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                         <tr style="background:#fff;">
                            <td style="padding-left: 16px;"><input type="checkbox"></td>
                            <td>#479063DR</td>
                             <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/30" alt="" style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;" class="me-2">
                                    <span>Jackes</span>
                                </div>
                            </td>
                            <td>Lorem ipsum</td>
                            <td>/jackes</td>
                            <td>62</td>
                            <td>
                                <button class="btn btn-sm btn-outline-success me-1 btn-action"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-sm btn-outline-danger btn-action"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                         <tr style="background:#fff;">
                            <td style="padding-left: 16px;"><input type="checkbox"></td>
                            <td>#479063DR</td>
                             <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/30" alt="" style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;" class="me-2">
                                    <span>Shirts</span>
                                </div>
                            </td>
                            <td>Lorem ipsum</td>
                            <td>/shirts</td>
                            <td>45</td>
                            <td>
                                <button class="btn btn-sm btn-outline-success me-1 btn-action"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-sm btn-outline-danger btn-action"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted" style="font-size:0.9em;">Showing 10 Product of 120</div>
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
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection 