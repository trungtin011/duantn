@extends('layouts.admin')

@section('content')
    <div class="admin-page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="admin-page-title">Dashboard</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb admin-breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="admin-breadcrumb-link">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
            {{-- The "Add Product" button is not in the Dashboard image, removing it --}}
            {{-- <a href="#" class="btn btn-primary btn-admin-primary">Add Product</a> --}}
        </div>
    </div>

    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="admin-card d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-0 text-muted text-sm">Orders Received</p>
                    <h2 class="card-number">356</h2>
                    <span class="badge badge-admin badge-active">10% <i class="fa-solid fa-arrow-up text-xs"></i></span>
                </div>
                <div class="icon-box icon-box-active d-flex align-items-center justify-content-center text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28"
                        fill="currentColor">
                        <path
                            d="M7,0H4A4,4,0,0,0,0,4V7a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V4A4,4,0,0,0,7,0ZM9,7A2,2,0,0,1,7,9H4A2,2,0,0,1,2,7V4A2,2,0,0,1,4,2H7A2,2,0,0,1,9,4Z" />
                        <path
                            d="M20,0H17a4,4,0,0,0-4,4V7a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V4A4,4,0,0,0,20,0Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V4a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z" />
                        <path
                            d="M7,13H4a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V17A4,4,0,0,0,7,13Zm2,7a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2H7a2,2,0,0,1,2,2Z" />
                        <path
                            d="M20,13H17a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V17A4,4,0,0,0,20,13Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-0 text-muted text-sm">Average Daily Sales</p>
                    <h2 class="card-number">$5680</h2>
                    <span class="badge badge-admin badge-active">30% <i class="fa-solid fa-arrow-up text-xs"></i></span>
                </div>
                <div class="icon-box icon-box-active d-flex align-items-center justify-content-center text-info">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28"
                        fill="currentColor">
                        <path
                            d="M7,0H4A4,4,0,0,0,0,4V7a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V4A4,4,0,0,0,7,0ZM9,7A2,2,0,0,1,7,9H4A2,2,0,0,1,2,7V4A2,2,0,0,1,4,2H7A2,2,0,0,1,9,4Z" />
                        <path
                            d="M20,0H17a4,4,0,0,0-4,4V7a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V4A4,4,0,0,0,20,0Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V4a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z" />
                        <path
                            d="M7,13H4a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V17A4,4,0,0,0,7,13Zm2,7a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2H7a2,2,0,0,1,2,2Z" />
                        <path
                            d="M20,13H17a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V17A4,4,0,0,0,20,13Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-0 text-muted text-sm">New Customers This Month</p>
                    <h2 class="card-number">5.8K</h2>
                    <span class="badge badge-admin badge-active">13% <i class="fa-solid fa-arrow-up text-xs"></i></span>
                </div>
                <div class="icon-box icon-box-active d-flex align-items-center justify-content-center text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28"
                        fill="currentColor">
                        <path
                            d="M7,0H4A4,4,0,0,0,0,4V7a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V4A4,4,0,0,0,7,0ZM9,7A2,2,0,0,1,7,9H4A2,2,0,0,1,2,7V4A2,2,0,0,1,4,2H7A2,2,0,0,1,9,4Z" />
                        <path
                            d="M20,0H17a4,4,0,0,0-4,4V7a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V4A4,4,0,0,0,20,0Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V4a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z" />
                        <path
                            d="M7,13H4a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V17A4,4,0,0,0,7,13Zm2,7a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2H7a2,2,0,0,1,2,2Z" />
                        <path
                            d="M20,13H17a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V17A4,4,0,0,0,20,13Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-0 text-muted text-sm">Pending Orders</p>
                    <h2 class="card-number">580</h2>
                    <span class="badge badge-admin badge-active">10% <i class="fa-solid fa-clock text-xs"></i></span>
                </div>
                <div class="icon-box icon-box-active d-flex align-items-center justify-content-center text-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28"
                        fill="currentColor">
                        <path
                            d="M7,0H4A4,4,0,0,0,0,4V7a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V4A4,4,0,0,0,7,0ZM9,7A2,2,0,0,1,7,9H4A2,2,0,0,1,2,7V4A2,2,0,0,1,4,2H7A2,2,0,0,1,9,4Z" />
                        <path
                            d="M20,0H17a4,4,0,0,0-4,4V7a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V4A4,4,0,0,0,20,0Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V4a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z" />
                        <path
                            d="M7,13H4a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V17A4,4,0,0,0,7,13Zm2,7a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2H7a2,2,0,0,1,2,2Z" />
                        <path
                            d="M20,13H17a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V17A4,4,0,0,0,20,13Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4 g-3">
        <div class="col-md-8">
            <div class="admin-card">
                <h5 class="mb-3 admin-card-title">Sales Statics</h5>
                <canvas id="salesChart"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-card">
                <h5 class="mb-3 admin-card-title">Most Selling Category</h5>
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 admin-card-title">Transactions</h5>
                    <a href="#" class="text-decoration-none text-sm">View All</a>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="https://i.pravatar.cc/40?img=10" alt="avatar" class="rounded-circle">
                        <div>
                            <p class="mb-0 fw-medium">Konnor Guzman</p>
                            <span class="text-muted text-sm">Jan 10, 2023 - 06:02 AM</span>
                        </div>
                        <span class="ms-auto fw-medium">$660.22</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="https://i.pravatar.cc/40?img=11" alt="avatar" class="rounded-circle">
                        <div>
                            <p class="mb-0 fw-medium">Shahnewaz</p>
                            <span class="text-muted text-sm">Jan 15, 2023 - 10:30 AM</span>
                        </div>
                        <span class="ms-auto text-danger fw-medium">$-80.40</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="https://i.pravatar.cc/40?img=12" alt="avatar" class="rounded-circle">
                        <div>
                            <p class="mb-0 fw-medium">Steve Smith</p>
                            <span class="text-muted text-sm">Feb 01, 2023 - 07:05 PM</span>
                        </div>
                        <span class="ms-auto fw-medium">$150.00</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <img src="https://i.pravatar.cc/40?img=13" alt="avatar" class="rounded-circle">
                        <div>
                            <p class="mb-0 fw-medium">Robert Downy</p>
                            <span class="text-muted text-sm">Feb 21, 2023 - 11:22 PM</span>
                        </div>
                        <span class="ms-auto fw-medium">$1482.00</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 admin-card-title">Recent Orders</h5>
                    <a href="#" class="text-decoration-none text-sm">View All</a>
                </div>
                <table class="table align-middle mb-0 admin-table admin-table-container">
                    <thead class="admin-table-thead">
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
                            <td><span class="badge badge-admin badge-active">Active</span></td>
                            <td><button class="btn btn-sm btn-admin-secondary">View</button></td>
                        </tr>
                        <tr>
                            <td>Gigabyte Gaming Monitor 4K</td>
                            <td>#JK-10A</td>
                            <td>$599.00</td>
                            <td><span class="badge badge-admin badge-inactive">Disabled</span></td>
                            <td><button class="btn btn-sm btn-admin-secondary">View</button></td>
                        </tr>
                        <tr>
                            <td>Logitech G502 Hero Mouse</td>
                            <td>#LG-502</td>
                            <td>$1199.59</td>
                            <td><span class="badge badge-admin badge-low-stock">Disabled</span></td>
                            <td><button class="btn btn-sm btn-admin-secondary">View</button></td>
                        </tr>
                        <tr>
                            <td>Galaxy S22 Ultra Gray</td>
                            <td>#GL-S22</td>
                            <td>$1800.00</td>
                            <td><span class="badge badge-admin badge-active">Active</span></td>
                            <td><button class="btn btn-sm btn-admin-secondary">View</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card">
                <h5 class="mb-3 admin-card-title">Traffics Source</h5>
                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted text-sm">Facebook</span>
                        <span class="text-sm">20%</span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 20%;" aria-valuenow="20"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted text-sm">YouTube</span>
                        <span class="text-sm">80%</span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 80%;" aria-valuenow="80"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted text-sm">WhatsApp</span>
                        <span class="text-sm">65%</span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%;" aria-valuenow="65"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted text-sm">Instagram</span>
                        <span class="text-sm">90%</span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 90%;" aria-valuenow="90"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted text-sm">Others</span>
                        <span class="text-sm">10%</span>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 10%;" aria-valuenow="10"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-0 admin-card-title">Product List</h5>
                <span class="text-muted text-sm">Avg. 57 orders per day</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle btn-admin-outline" type="button"
                        data-bs-toggle="dropdown">
                        Category: Show All
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">All</a></li>
                        <li><a class="dropdown-item" href="#">Electronics</a></li>
                        <li><a class="dropdown-item" href="#">Clothing</a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle btn-admin-outline" type="button"
                        data-bs-toggle="dropdown">
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
                <div class="input-group search-input-group" style="width: 250px;">
                    <span class="input-group-text bg-white border-end-0"><i
                            class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" placeholder="Search Here...">
                </div>
            </div>
        </div>

        <table class="table align-middle mb-0 admin-table admin-table-container">
            <thead class="admin-table-thead">
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
                    <td><span class="badge badge-admin badge-active">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-success me-1 btn-action-icon"><i
                                class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i
                                class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Gigabyte Gaming Monitor 4K</td>
                    <td>#JK-10A</td>
                    <td>Monitor</td>
                    <td>$599.00</td>
                    <td><span class="badge badge-admin badge-inactive">Disabled</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-success me-1 btn-action-icon"><i
                                class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i
                                class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Logitech G502 Hero Mouse</td>
                    <td>#LG-502</td>
                    <td>Accessories</td>
                    <td>$1199.59</td>
                    <td><span class="badge badge-admin badge-low-stock">Disabled</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-success me-1 btn-action-icon"><i
                                class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i
                                class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Samsung EVO 500 GB SSD</td>
                    <td>#SM-520</td>
                    <td>Hard Disk</td>
                    <td>$250.00</td>
                    <td><span class="badge badge-admin badge-active">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-success me-1 btn-action-icon"><i
                                class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-action-icon"><i
                                class="fa-solid fa-trash"></i></button>
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
                    }
                ]
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
