@extends('layouts.admin')

@section('content')
<div style="background: #f4f8fc; margin: -32px -32px 24px -32px; padding: 20px 20px 0 20px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 style="font-weight:700;letter-spacing:-1px;font-size:2rem;">Dashboard</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="font-size: 0.9em; color: #64748b;">
                    <li class="breadcrumb-item"><a href="#" style="color: #64748b; text-decoration: none;">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #232946;">Dashboard</li>
                </ol>
            </nav>
        </div>
        <a href="#" class="btn btn-primary" style="font-weight:500;font-size:1em;padding:8px 20px;border-radius:10px;">Add Product</a>
    </div>
</div>

<div class="row mb-4 g-3">
    <div class="col-md-3">
        <div class="bg-white p-4 shadow-sm rounded-4 d-flex justify-content-between align-items-center">
            <div>
                <p class="mb-0 text-muted" style="font-size:0.9em;">Orders Received</p>
                <h2 style="font-weight:700; font-size: 1.6rem; margin-bottom: 0.25rem;">356</h2>
                <span class="badge" style="background:#d1fae5;color:#059669; padding: 0.35em 0.65em; font-size: 0.8em;">10% <i class="fa-solid fa-arrow-up" style="font-size: 0.6em;"></i></span>
            </div>
            <div style="width: 45px; height: 45px; background:#d1fae5; border-radius:10px;" class="d-flex align-items-center justify-content-center text-success">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                                        <path d="M7,0H4A4,4,0,0,0,0,4V7a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V4A4,4,0,0,0,7,0ZM9,7A2,2,0,0,1,7,9H4A2,2,0,0,1,2,7V4A2,2,0,0,1,4,2H7A2,2,0,0,1,9,4Z"/>
                                        <path d="M20,0H17a4,4,0,0,0-4,4V7a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V4A4,4,0,0,0,20,0Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V4a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z"/>
                                        <path d="M7,13H4a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V17A4,4,0,0,0,7,13Zm2,7a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2H7a2,2,0,0,1,2,2Z"/>
                                        <path d="M20,13H17a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V17A4,4,0,0,0,20,13Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z"/>
                                    </svg>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-white p-4 shadow-sm rounded-4 d-flex justify-content-between align-items-center">
            <div>
                <p class="mb-0 text-muted" style="font-size:0.9em;">Average Daily Sales</p>
                <h2 style="font-weight:700; font-size: 1.6rem; margin-bottom: 0.25rem;">$5680</h2>
                <span class="badge" style="background:#dbeafe;color:#2563eb; padding: 0.35em 0.65em; font-size: 0.8em;">30% <i class="fa-solid fa-arrow-up" style="font-size: 0.6em;"></i></span>
            </div>
            <div style="width: 45px; height: 45px; background:#e0f2f7; border-radius:10px;" class="d-flex align-items-center justify-content-center text-info">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                                        <path d="M7,0H4A4,4,0,0,0,0,4V7a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V4A4,4,0,0,0,7,0ZM9,7A2,2,0,0,1,7,9H4A2,2,0,0,1,2,7V4A2,2,0,0,1,4,2H7A2,2,0,0,1,9,4Z"/>
                                        <path d="M20,0H17a4,4,0,0,0-4,4V7a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V4A4,4,0,0,0,20,0Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V4a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z"/>
                                        <path d="M7,13H4a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V17A4,4,0,0,0,7,13Zm2,7a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2H7a2,2,0,0,1,2,2Z"/>
                                        <path d="M20,13H17a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V17A4,4,0,0,0,20,13Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z"/>
                                    </svg>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-white p-4 shadow-sm rounded-4 d-flex justify-content-between align-items-center">
            <div>
                <p class="mb-0 text-muted" style="font-size:0.9em;">New Customers This Month</p>
                <h2 style="font-weight:700; font-size: 1.6rem; margin-bottom: 0.25rem;">5.8K</h2>
                <span class="badge" style="background:#eef2ff;color:#0284c7; padding: 0.35em 0.65em; font-size: 0.8em;">13% <i class="fa-solid fa-arrow-up" style="font-size: 0.6em;"></i></span>
            </div>
            <div style="width: 45px; height: 45px; background:#eef2ff; border-radius:10px;" class="d-flex align-items-center justify-content-center text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                                        <path d="M7,0H4A4,4,0,0,0,0,4V7a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V4A4,4,0,0,0,7,0ZM9,7A2,2,0,0,1,7,9H4A2,2,0,0,1,2,7V4A2,2,0,0,1,4,2H7A2,2,0,0,1,9,4Z"/>
                                        <path d="M20,0H17a4,4,0,0,0-4,4V7a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V4A4,4,0,0,0,20,0Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V4a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z"/>
                                        <path d="M7,13H4a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V17A4,4,0,0,0,7,13Zm2,7a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2H7a2,2,0,0,1,2,2Z"/>
                                        <path d="M20,13H17a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V17A4,4,0,0,0,20,13Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z"/>
                                    </svg>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-white p-4 shadow-sm rounded-4 d-flex justify-content-between align-items-center">
            <div>
                <p class="mb-0 text-muted" style="font-size:0.9em;">Pending Orders</p>
                <h2 style="font-weight:700; font-size: 1.6rem; margin-bottom: 0.25rem;">580</h2>
                <span class="badge" style="background:#fff7ed;color:#b45309; padding: 0.35em 0.65em; font-size: 0.8em;">10% <i class="fa-solid fa-clock" style="font-size: 0.6em;"></i></span>
            </div>
            <div style="width: 45px; height: 45px; background:#fff7ed; border-radius:10px;" class="d-flex align-items-center justify-content-center text-warning">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                                        <path d="M7,0H4A4,4,0,0,0,0,4V7a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V4A4,4,0,0,0,7,0ZM9,7A2,2,0,0,1,7,9H4A2,2,0,0,1,2,7V4A2,2,0,0,1,4,2H7A2,2,0,0,1,9,4Z"/>
                                        <path d="M20,0H17a4,4,0,0,0-4,4V7a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V4A4,4,0,0,0,20,0Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V4a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z"/>
                                        <path d="M7,13H4a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V17A4,4,0,0,0,7,13Zm2,7a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2H7a2,2,0,0,1,2,2Z"/>
                                        <path d="M20,13H17a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V17A4,4,0,0,0,20,13Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z"/>
                                    </svg>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4 g-3">
    <div class="col-md-8">
        <div class="bg-white p-4 shadow-sm rounded-4">
            <h5 class="mb-3" style="font-weight:600;">Sales Statics</h5>
            <canvas id="salesChart"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="bg-white p-4 shadow-sm rounded-4">
            <h5 class="mb-3" style="font-weight:600;">Most Selling Category</h5>
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<div class="row mb-4 g-3">
    <div class="col-md-4">
        <div class="bg-white p-4 shadow-sm rounded-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0" style="font-weight:600;">Transactions</h5>
                <a href="#" class="text-decoration-none" style="font-size:0.9em;">View All</a>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="https://i.pravatar.cc/40?img=10" alt="avatar" class="rounded-circle">
                    <div>
                        <p class="mb-0" style="font-weight:500;">Konnor Guzman</p>
                        <span class="text-muted" style="font-size:0.8em;">Jan 10, 2023 - 06:02 AM</span>
                    </div>
                    <span class="ms-auto" style="font-weight:500;">$660.22</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="https://i.pravatar.cc/40?img=11" alt="avatar" class="rounded-circle">
                    <div>
                        <p class="mb-0" style="font-weight:500;">Shahnewaz</p>
                        <span class="text-muted" style="font-size:0.8em;">Jan 15, 2023 - 10:30 AM</span>
                    </div>
                    <span class="ms-auto text-danger" style="font-weight:500;">$-80.40</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="https://i.pravatar.cc/40?img=12" alt="avatar" class="rounded-circle">
                    <div>
                        <p class="mb-0" style="font-weight:500;">Steve Smith</p>
                        <span class="text-muted" style="font-size:0.8em;">Feb 01, 2023 - 07:05 PM</span>
                    </div>
                    <span class="ms-auto text-success" style="font-weight:500;">$150.00</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <img src="https://i.pravatar.cc/40?img=13" alt="avatar" class="rounded-circle">
                    <div>
                        <p class="mb-0" style="font-weight:500;">Robert Downy</p>
                        <span class="text-muted" style="font-size:0.8em;">Feb 21, 2023 - 11:22 PM</span>
                    </div>
                    <span class="ms-auto text-success" style="font-weight:500;">$1482.00</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="bg-white p-4 shadow-sm rounded-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0" style="font-weight:600;">Recent Orders</h5>
                <a href="#" class="text-decoration-none" style="font-size:0.9em;">View All</a>
            </div>
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>ITEM</th>
                        <th>PRODUCT ID</th>
                        <th>PRICE</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Apple MacBook Pro 17"</td>
                        <td>#XY-25G</td>
                        <td>$2999.00</td>
                        <td><span class="badge" style="background:#d1fae5;color:#059669;">Active</span></td>
                        <td><button class="btn btn-sm btn-outline-secondary rounded-3" style="font-size:0.8em;">View</button></td>
                    </tr>
                    <tr>
                        <td>Gigabyte Gaming Monitor 4K</td>
                        <td>#JK-10A</td>
                        <td>$599.00</td>
                        <td><span class="badge" style="background:#fee2e2;color:#dc2626;">Disabled</span></td>
                        <td><button class="btn btn-sm btn-outline-secondary rounded-3" style="font-size:0.8em;">View</button></td>
                    </tr>
                    <tr>
                        <td>Logitech G502 Hero Mouse</td>
                        <td>#LG-502</td>
                        <td>$1199.59</td>
                        <td><span class="badge" style="background:#fef9c3;color:#b45309;">Disabled</span></td>
                        <td><button class="btn btn-sm btn-outline-secondary rounded-3" style="font-size:0.8em;">View</button></td>
                    </tr>
                    <tr>
                        <td>Galaxy S22 Ultra Gray</td>
                        <td>#GL-S22</td>
                        <td>$1800.00</td>
                        <td><span class="badge" style="background:#d1fae5;color:#059669;">Active</span></td>
                        <td><button class="btn btn-sm btn-outline-secondary rounded-3" style="font-size:0.8em;">View</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-3">
        <div class="bg-white p-4 shadow-sm rounded-4">
            <h5 class="mb-3" style="font-weight:600;">Traffics Source</h5>
            <div class="mb-2">
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:0.9em;">Facebook</span>
                    <span style="font-size:0.9em;">20%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="mb-2">
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:0.9em;">YouTube</span>
                    <span style="font-size:0.9em;">80%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 80%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="mb-2">
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:0.9em;">WhatsApp</span>
                    <span style="font-size:0.9em;">65%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="mb-2">
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:0.9em;">Instagram</span>
                    <span style="font-size:0.9em;">90%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-secondary" role="progressbar" style="width: 90%;" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted" style="font-size:0.9em;">Others</span>
                    <span style="font-size:0.9em;">10%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 10%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white p-4 shadow-sm rounded-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0" style="font-weight:600;">Product List</h5>
            <span class="text-muted" style="font-size:0.9em;">Avg. 57 orders per day</span>
        </div>
        <div class="d-flex align-items-center gap-2">
             <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" style="border-radius: 10px; font-size: 0.9em;">
                    Category: Show All
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">All</a></li>
                    <li><a class="dropdown-item" href="#">Electronics</a></li>
                    <li><a class="dropdown-item" href="#">Clothing</a></li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" style="border-radius: 10px; font-size: 0.9em;">
                    Status: Show All
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">All</a></li>
                    <li><a class="dropdown-item" href="#">Active</a></li>
                    <li><a class="dropdown-item" href="#">In Active</a></li>
                    <li><a class="dropdown-item" href="#">Scheduled</a></li>
                    <li><a class="dropdown-item" href="#">Low Stock</a></li>
                    <li><a class="dropdown-item" href="#">Out of Stock</a></li>
                </ul>
            </div>
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Search Here..." style="border-radius: 0 10px 10px 0;">
            </div>
        </div>
    </div>

    <table class="table align-middle mb-0">
        <thead style="background:#f4f6fa;">
            <tr>
                <th>ITEM</th>
                <th>PRODUCT ID</th>
                <th>CATEGORY</th>
                <th>PRICE</th>
                <th>STATUS</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Apple MacBook Pro 17"</td>
                <td>#XY-25G</td>
                <td>Computer</td>
                <td>$2999.00</td>
                <td><span class="badge" style="background:#d1fae5;color:#059669;">Active</span></td>
                <td>
                    <button class="btn btn-sm btn-outline-success rounded-3 me-1"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn btn-sm btn-outline-danger rounded-3"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td>Gigabyte Gaming Monitor 4K</td>
                <td>#JK-10A</td>
                 <td>Monitor</td>
                <td>$599.00</td>
                <td><span class="badge" style="background:#fee2e2;color:#dc2626;">Disabled</span></td>
                <td>
                    <button class="btn btn-sm btn-outline-success rounded-3 me-1"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn btn-sm btn-outline-danger rounded-3"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td>Logitech G502 Hero Mouse</td>
                <td>#LG-502</td>
                 <td>Accessories</td>
                <td>$1199.59</td>
                <td><span class="badge" style="background:#fef9c3;color:#b45309;">Pending</span></td>
                <td>
                    <button class="btn btn-sm btn-outline-success rounded-3 me-1"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn btn-sm btn-outline-danger rounded-3"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td>Samsung EVO 500 GB SSD</td>
                <td>#SM-520</td>
                 <td>Hard Disk</td>
                <td>$250.00</td>
                <td><span class="badge" style="background:#bae6fd;color:#0284c7;">Delivered</span></td>
                <td>
                    <button class="btn btn-sm btn-outline-success rounded-3 me-1"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn btn-sm btn-outline-danger rounded-3"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sales Chart
    var ctxSales = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctxSales, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Sales',
                data: [20, 15, 25, 30, 20, 35, 40, 30, 25, 20, 15, 10], // Sample data based on image
                borderColor: 'rgba(59, 130, 246, 1)', // Blue
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                fill: true,
                tension: 0.3
            },
            {
                label: 'Visitors',
                data: [30, 20, 35, 25, 30, 20, 25, 35, 40, 30, 25, 20], // Sample data based on image
                borderColor: 'rgba(163, 230, 53, 1)', // Green
                backgroundColor: 'rgba(163, 230, 53, 0.2)',
                fill: true,
                tension: 0.3
            },
            {
                label: 'Products',
                data: [40, 35, 30, 20, 25, 30, 20, 25, 30, 35, 40, 30], // Sample data based on image
                borderColor: 'rgba(251, 191, 36, 1)', // Yellow/Orange
                backgroundColor: 'rgba(251, 191, 36, 0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Category Chart
    var ctxCategory = document.getElementById('categoryChart').getContext('2d');
    var categoryChart = new Chart(ctxCategory, {
        type: 'pie',
        data: {
            labels: ['Grocery', 'Men', 'Women', 'Kids'], // Sample labels based on image
            datasets: [{
                data: [25, 20, 30, 25], // Sample data based on image proportions
                backgroundColor: [
                    'rgba(59, 130, 246, 1)', // Blue
                    'rgba(239, 68, 68, 1)', // Red
                    'rgba(163, 230, 53, 1)', // Green
                    'rgba(251, 191, 36, 1)', // Yellow/Orange
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
        }
    });
</script>
@endsection