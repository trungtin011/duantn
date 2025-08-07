@php
    use Carbon\Carbon;
@endphp
@extends('layouts.seller_home')
@push('css')
    <style>
        .section-title { font-size: 1.25rem; font-weight: 600; }
        .stat-card { @apply border rounded p-3 text-center; }
        .stat-value { @apply text-blue-600 font-semibold text-xl; }
        .stat-label { @apply text-gray-600 text-xs; }
        .form-group { @apply mb-2; }
        .error-message { @apply text-red-500 text-xs mt-2; }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
@endpush
@section('title', 'Trang chủ Seller')
@section('content')
    <div class="flex min-h-[calc(100vh-40px)]">
        <main class="flex-1 p-4 space-y-6 overflow-y-auto">
            <!-- Error Messages -->
            @if (session('error') || $error)
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') ?? $error }}</span>
                </div>
            @endif

            <!-- Danh sách cần làm -->
            <section class="bg-white rounded-lg p-4 shadow-sm flex flex-col sm:flex-row justify-between text-center sm:text-left space-y-4 sm:space-y-0 sm:space-x-10">
                <h2 class="section-title w-full sm:w-auto sm:flex-shrink-0 sm:self-center">Danh sách cần làm</h2>
                <div class="flex justify-around sm:justify-start flex-1 space-x-10 text-gray-600 text-xs">
                    <div>
                        <div class="stat-value">{{ $statistics['order_statistics']['pending'] }}</div>
                        <div class="stat-label">Chờ Xác Nhận</div>
                    </div>
                    <div>
                        <div class="stat-value">{{ $statistics['order_statistics']['completed'] }}</div>
                        <div class="stat-label">Đã Hoàn Thành</div>
                    </div>
                    <div>
                        <div class="stat-value">{{ $statistics['order_statistics']['cancelled'] + $statistics['order_statistics']['returned'] }}</div>
                        <div class="stat-label">Đơn Trả Hàng/Hủy</div>
                    </div>
                    <div>
                        <div class="stat-value">{{ $lowStockCount }}</div>
                        <div class="stat-label">Sản Phẩm Sắp Hết Hàng</div>
                    </div>
                </div>
            </section>

            <!-- Phân Tích Bán Hàng -->
            <section class="bg-white rounded-lg p-4 shadow-sm space-y-3">
                <div class="flex justify-between items-center">
                    <h2 class="section-title">Phân Tích Bán Hàng</h2>
                    <div class="flex flex-col items-end space-y-2">
                        <form method="GET" action="{{ route('seller.home') }}" id="dateFilterForm">
                            <div class="flex flex-col sm:flex-row sm:space-x-4">
                                <div class="flex-1">
                                    <div class="form-group">
                                        <label for="filter_type" class="text-xs text-gray-600">Loại bộ lọc:</label>
                                        <select name="filter_type" id="filter_type" class="text-xs text-gray-600 border rounded px-2 py-1 w-full">
                                            <option value="date" {{ $filterType == 'date' ? 'selected' : '' }}>Theo ngày</option>
                                            <option value="month" {{ $filterType == 'month' ? 'selected' : '' }}>Theo tháng</option>
                                            <option value="year" {{ $filterType == 'year' ? 'selected' : '' }}>Theo năm</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex-1" id="year_group">
                                    <div class="form-group">
                                        <label for="year" class="text-xs text-gray-600">Năm:</label>
                                        <select name="year" id="year" class="text-xs text-gray-600 border rounded px-2 py-1 w-full">
                                            @for ($y = Carbon::today()->year; $y >= Carbon::today()->year - 5; $y--)
                                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="flex-1" id="month_group">
                                    <div class="form-group">
                                        <label for="month" class="text-xs text-gray-600">Tháng:</label>
                                        <select name="month" id="month" class="text-xs text-gray-600 border rounded px-2 py-1 w-full">
                                            @for ($m = 1; $m <= ($year == Carbon::today()->year ? Carbon::today()->month : 12); $m++)
                                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $m }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="flex-1" id="start_date_group">
                                    <div class="form-group">
                                        <label for="start_date" class="text-xs text-gray-600">Từ ngày:</label>
                                        <input type="date" name="start_date" id="start_date" value="{{ $filterType == 'date' ? Carbon::parse($startDate)->format('Y-m-d') : '' }}" max="{{ Carbon::today()->format('Y-m-d') }}" class="text-xs text-gray-600 border rounded px-2 py-1 w-full">
                                    </div>
                                </div>
                                <div class="flex-1" id="end_date_group">
                                    <div class="form-group">
                                        <label for="end_date" class="text-xs text-gray-600">Đến ngày:</label>
                                        <input type="date" name="end_date" id="end_date" value="{{ $filterType == 'date' ? Carbon::parse($endDate)->format('Y-m-d') : '' }}" max="{{ Carbon::today()->format('Y-m-d') }}" class="text-xs text-gray-600 border rounded px-2 py-1 w-full">
                                    </div>
                                </div>
                                <div class="flex items-end">
                                    <button type="submit" class="text-xs text-white bg-blue-600 rounded px-3 py-1.5 hover:bg-blue-700 transition-colors">Lọc</button>
                                </div>
                            </div>
                            <div id="dateError" class="error-message hidden"></div>
                        </form>
                        <div class="text-xs text-gray-400 whitespace-nowrap">
                            {{ now()->format('d/m/Y H:i') }} GMT+7
                            <span class="text-gray-300">(Dữ liệu thay đổi so với hôm qua)</span>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <canvas id="salesChart" height="200"></canvas>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-center text-gray-600 text-xs font-normal border-t border-b border-gray-200 py-3">
                    <div>
                        <div class="flex justify-center items-center space-x-1"><span>Doanh thu</span><i class="fas fa-question-circle text-[10px]"></i></div>
                        <div class="font-semibold text-base mt-1 sales">₫{{ number_format($statistics['total_revenue'], 2) }}</div>
                        <div class="text-gray-400 text-[10px] mt-0.5">{{ $statistics['revenue_change'] ?? '-0.00%' }}</div>
                    </div>
                    <div>
                        <div class="flex justify-center items-center space-x-1"><span>Lợi nhuận</span><i class="fas fa-question-circle text-[10px]"></i></div>
                        <div class="font-semibold text-base mt-1 profit">₫{{ number_format($statistics['profit'], 2) }}</div>
                        <div class="text-gray-400 text-[10px] mt-0.5">{{ $statistics['profit_change'] ?? '-0.00%' }}</div>
                    </div>
                    <div>
                        <div class="flex justify-center items-center space-x-1"><span>Lượt theo dõi</span><i class="fas fa-question-circle text-[10px]"></i></div>
                        <div class="font-semibold text-base mt-1 visits">{{ $shop->total_followers ?? 0 }}</div>
                        <div class="text-gray-400 text-[10px] mt-0.5">{{ $statistics['visits_change'] ?? '-0.00%' }}</div>
                    </div>
                    <div>
                        <div class="flex justify-center items-center space-x-1"><span>Đơn hàng</span><i class="fas fa-question-circle text-[10px]"></i></div>
                        <div class="font-semibold text-base mt-1 orders">{{ $statistics['order_statistics']['completed'] }}</div>
                        <div class="text-gray-400 text-[10px] mt-0.5">{{ $statistics['orders_change'] ?? '-0.00%' }}</div>
                    </div>
                </div>
            </section>

            <!-- Đánh giá khách hàng -->
            <section class="bg-white rounded-lg p-4 shadow-sm space-y-3">
                <div class="flex justify-between items-center">
                    <h2 class="section-title">Đánh giá khách hàng</h2>
                    <a href="#" class="text-blue-600 text-xs font-normal hover:underline whitespace-nowrap">Xem thêm <i class="fas fa-chevron-right text-[10px]"></i></a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="stat-card">
                        <div class="stat-value">{{ $statistics['review_statistics']['total_reviews'] }}</div>
                        <div class="stat-label">Số lượng đánh giá</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($statistics['review_statistics']['average_rating'], 1) }}/5</div>
                        <div class="stat-label">Điểm đánh giá trung bình</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $statistics['inventory_statistics']['total_stock'] }}</div>
                        <div class="stat-label">Tổng Tồn Kho</div>
                    </div>
                </div>
                <div class="mb-4">
                    <canvas id="reviewChart" height="100"></canvas>
                </div>
            </section>

            <!-- Quảng cáo ZynoxMall -->
            <section class="bg-white rounded-lg p-4 shadow-sm space-y-3">
                <div class="flex justify-between items-center">
                    <h2 class="section-title">Quảng cáo ZynoxMall</h2>
                    <a href="#" class="text-blue-600 text-xs font-normal hover:underline whitespace-nowrap">Xem thêm <i class="fas fa-chevron-right text-[10px]"></i></a>
                </div>
                <div class="border border-gray-200 rounded-md p-3 text-gray-600 text-xs relative" style="background-image: url('https://placehold.co/100x100/feeaea/feeaea?text='); background-repeat: no-repeat; background-position: right bottom; background-size: 100px 100px;">
                    <div class="flex items-center space-x-2 mb-1">
                        <img src="https://storage.googleapis.com/a1aa/image/edf41f8c-c956-422e-817c-1cfaae696afc.jpg" alt="Ad icon" class="w-4 h-4">
                        <span class="font-semibold text-gray-700">Tối đa hóa doanh số bán hàng với Quảng cáo ZynoxMall!</span>
                    </div>
                    <p class="text-gray-400 leading-tight">Tìm hiểu thêm về Quảng cáo ZynoxMall để tạo quảng cáo hiệu quả và tối ưu chi phí.</p>
                    <button class="absolute bottom-3 right-3 text-xs text-[#ff4d4f] border border-[#ff4d4f] rounded px-2 py-0.5 hover:bg-[#ff4d4f] hover:text-white transition-colors">Tìm hiểu thêm</button>
                </div>
            </section>

            <!-- Sản Phẩm Bán Chạy -->
            <section class="bg-white rounded-lg p-4 shadow-sm space-y-3">
                <div class="flex justify-between items-center">
                    <h2 class="section-title">Sản Phẩm Bán Chạy</h2>
                    <a href="#" class="text-blue-600 text-xs font-normal hover:underline whitespace-nowrap">Xem thêm <i class="fas fa-chevron-right text-[10px]"></i></a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @forelse ($statistics['top_selling_products'] as $product)
                        <div class="border rounded p-3 flex items-center space-x-3">
                            <img src="{{ $product['image_path'] ?? 'https://placehold.co/50x50' }}" alt="Product image" class="w-12 h-12 rounded">
                            <div>
                                <p class="text-sm font-semibold">{{ $product['name'] }}</p>
                                <p class="text-xs text-gray-500">Đã bán: {{ $product['total_sold'] }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500">Chưa có sản phẩm bán chạy.</div>
                    @endforelse
                </div>
            </section>

            <!-- Tồn kho -->
            <section class="bg-white rounded-lg p-4 shadow-sm space-y-3">
                <div class="flex justify-between items-center">
                    <h2 class="section-title">Tồn kho thấp</h2>
                    <a href="#" class="text-blue-600 text-xs font-normal hover:underline whitespace-nowrap">
                        Xem thêm <i class="fas fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @forelse($statistics['low_stock_products'] ?? [] as $product)
                        <div class="border rounded p-3 flex items-center space-x-3">
                            <img src="{{ $product['image_path'] ?? asset('images/no-image.png') }}" alt="Product image" class="w-12 h-12 rounded object-cover">
                            <div>
                                <p class="text-sm font-semibold">{{ $product['name'] }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ $product['sku'] }}</p>
                                <p class="text-xs text-gray-500">Tồn kho: {{ $product['stock_total'] }}</p>
                                @if($product['stock_total'] <= 10)
                                    <p class="text-xs text-red-500 font-semibold">Sắp hết hàng!</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 col-span-full">Chưa có sản phẩm tồn kho thấp.</div>
                    @endforelse
                </div>
            </section>

            <!-- Nhiệm vụ -->
            <section class="bg-white rounded-lg p-4 shadow-sm space-y-3">
                <div class="flex justify-between items-center">
                    <h2 class="section-title">Nhiệm vụ Người Bán</h2>
                    <a href="#" class="text-blue-600 text-xs font-normal hover:underline whitespace-nowrap">Xem thêm <i class="fas fa-chevron-right text-[10px]"></i></a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse ($tasks ?? [] as $task)
                        <div class="border rounded p-3">
                            <p class="text-sm font-semibold">{{ $task['title'] }}</p>
                            <p class="text-xs text-gray-500">{{ $task['reward'] }}</p>
                            <button class="text-xs text-[#ff4d4f] border border-[#ff4d4f] rounded px-2 py-0.5 mt-2 hover:bg-[#ff4d4f] hover:text-white transition-colors">Bắt đầu</button>
                        </div>
                    @empty
                        <div class="text-center text-gray-500">Chưa có nhiệm vụ nào.</div>
                    @endforelse
                </div>
            </section>
        </main>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Initialize sales chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            let salesChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: @json($statistics['revenue_data']['labels']),
                    datasets: [{
                        label: 'Doanh thu',
                        data: @json($statistics['revenue_data']['values']),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.2)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) { return '₫' + value.toLocaleString('vi-VN'); }
                            },
                            title: {
                                display: true,
                                text: 'Doanh thu (VND)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ $filterType == "year" ? "Tháng" : "Ngày" }}'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Doanh Thu ({{ $filterType == "year" ? $year : Carbon::parse($startDate)->format('d/m/Y') . " - " . Carbon::parse($endDate)->format('d/m/Y') }})'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Doanh thu: ₫${context.parsed.y.toLocaleString('vi-VN')}`;
                                }
                            }
                        }
                    }
                }
            });

            // Initialize review chart
            const reviewCtx = document.getElementById('reviewChart').getContext('2d');
            new Chart(reviewCtx, {
                type: 'doughnut',
                data: {
                    labels: ['5 sao', '4 sao', '3 sao', '2 sao', '1 sao'],
                    datasets: [{
                        data: [
                            {{ $statistics['review_statistics']['rating_distribution']['5'] }},
                            {{ $statistics['review_statistics']['rating_distribution']['4'] }},
                            {{ $statistics['review_statistics']['rating_distribution']['3'] }},
                            {{ $statistics['review_statistics']['rating_distribution']['2'] }},
                            {{ $statistics['review_statistics']['rating_distribution']['1'] }}
                        ],
                        backgroundColor: ['#2ecc71', '#3498db', '#f1c40f', '#e67e22', '#e74c3c']
                    }]
                },
                options: {
                    plugins: { legend: { position: 'right' } }
                }
            });

            // Client-side form handling
            document.getElementById('filter_type').addEventListener('change', function() {
                const filterType = this.value;
                const yearGroup = document.getElementById('year_group');
                const monthGroup = document.getElementById('month_group');
                const startDateGroup = document.getElementById('start_date_group');
                const endDateGroup = document.getElementById('end_date_group');

                yearGroup.style.display = filterType === 'year' || filterType === 'month' ? 'block' : 'none';
                monthGroup.style.display = filterType === 'month' ? 'block' : 'none';
                startDateGroup.style.display = filterType === 'date' ? 'block' : 'none';
                endDateGroup.style.display = filterType === 'date' ? 'block' : 'none';

                // Clear date inputs when switching to year or month
                if (filterType !== 'date') {
                    document.getElementById('start_date').value = '';
                    document.getElementById('end_date').value = '';
                }

                // Update month dropdown based on selected year
                updateMonthOptions();
            });

            // Update month dropdown based on year selection
            document.getElementById('year').addEventListener('change', function() {
                updateMonthOptions();
            });

            function updateMonthOptions() {
                const year = parseInt(document.getElementById('year').value);
                const currentYear = {{ Carbon::today()->year }};
                const currentMonth = {{ Carbon::today()->month }};
                const monthSelect = document.getElementById('month');
                const selectedMonth = monthSelect.value;

                // Clear current options
                monthSelect.innerHTML = '';

                // Set max month based on year
                const maxMonth = (year === currentYear) ? currentMonth : 12;

                // Populate month options
                for (let m = 1; m <= maxMonth; m++) {
                    const option = document.createElement('option');
                    option.value = m;
                    option.textContent = m;
                    if (m == selectedMonth) {
                        option.selected = true;
                    }
                    monthSelect.appendChild(option);
                }
            }

            // Client-side date validation
            document.getElementById('dateFilterForm').addEventListener('submit', function(e) {
                const filterType = document.getElementById('filter_type').value;
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');
                const yearInput = document.getElementById('year');
                const monthInput = document.getElementById('month');
                const errorDiv = document.getElementById('dateError');
                const today = new Date('{{ Carbon::today()->format('Y-m-d') }}');

                // Reset error message
                errorDiv.classList.add('hidden');
                errorDiv.textContent = '';

                if (filterType === 'date') {
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);

                    // Check for end date before start date
                    if (endDate < startDate) {
                        e.preventDefault();
                        errorDiv.textContent = 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.';
                        errorDiv.classList.remove('hidden');
                        return;
                    }

                    // Check for date range exceeding 31 days
                    const diffDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
                    if (diffDays > 31) {
                        e.preventDefault();
                        errorDiv.textContent = 'Khoảng thời gian được chọn không được vượt quá 31 ngày.';
                        errorDiv.classList.remove('hidden');
                        return;
                    }
                } else if (filterType === 'month') {
                    const year = parseInt(yearInput.value);
                    const month = parseInt(monthInput.value);
                    const currentYear = {{ Carbon::today()->year }};
                    const currentMonth = {{ Carbon::today()->month }};

                    // Prevent selecting future months
                    if (year > currentYear || (year === currentYear && month > currentMonth)) {
                        e.preventDefault();
                        errorDiv.textContent = 'Không thể chọn tháng trong tương lai.';
                        errorDiv.classList.remove('hidden');
                        monthInput.value = '';
                        return;
                    }
                } else if (filterType === 'year') {
                    const year = parseInt(yearInput.value);
                    const currentYear = {{ Carbon::today()->year }};

                    // Prevent selecting future years
                    if (year > currentYear) {
                        e.preventDefault();
                        errorDiv.textContent = 'Không thể chọn năm trong tương lai.';
                        errorDiv.classList.remove('hidden');
                        yearInput.value = '';
                        return;
                    }
                }
            });

            // Trigger change event on page load to set initial visibility and month options
            document.getElementById('filter_type').dispatchEvent(new Event('change'));
            updateMonthOptions();
        </script>
    @endpush
@endsection