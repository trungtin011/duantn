@extends('layouts.seller_home')

@section('title', 'Thống kê quảng cáo')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Thống kê quảng cáo</h1>

        <!-- Filter Section -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <form id="filterForm" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Từ ngày</label>
                    <input type="date" name="start_date" id="start_date" class="border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Đến ngày</label>
                    <input type="date" name="end_date" id="end_date" class="border border-gray-300 rounded-md px-3 py-2">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Lọc
                </button>
            </form>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800">Tổng số click</h3>
                <p class="text-2xl font-bold text-blue-600" id="totalClicks">0</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-green-800">Click đã tính phí</h3>
                <p class="text-2xl font-bold text-green-600" id="chargedClicks">0</p>
            </div>
            <div class="bg-red-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-red-800">Tổng chi phí</h3>
                <p class="text-2xl font-bold text-red-600" id="totalCost">0 ₫</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-purple-800">Số dư hiện tại</h3>
                <p class="text-2xl font-bold text-purple-600" id="currentBalance">0 ₫</p>
            </div>
        </div>

        <!-- Click Type Breakdown -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-yellow-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-yellow-800">Click chi tiết shop</h3>
                <p class="text-2xl font-bold text-yellow-600" id="shopDetailClicks">0</p>
            </div>
            <div class="bg-indigo-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-indigo-800">Click chi tiết sản phẩm</h3>
                <p class="text-2xl font-bold text-indigo-600" id="productDetailClicks">0</p>
            </div>
            <div class="bg-pink-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-pink-800">Click xem modal</h3>
                <p class="text-2xl font-bold text-pink-600" id="modalViewClicks">0</p>
            </div>
        </div>

        <!-- Click History Table -->
        <div class="bg-white border border-gray-200 rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Lịch sử click quảng cáo</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thời gian
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Loại click
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Chiến dịch
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sản phẩm
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Chi phí
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                        </tr>
                    </thead>
                    <tbody id="clickHistoryTable" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
            <div id="pagination" class="px-6 py-4 border-t border-gray-200">
                <!-- Pagination will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default date range (last 30 days)
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(startDate.getDate() - 30);
    
    document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
    document.getElementById('start_date').value = startDate.toISOString().split('T')[0];

    // Load initial data
    loadAdStats();
    loadClickHistory();

    // Filter form submission
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        loadAdStats();
        loadClickHistory();
    });
});

function loadAdStats() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;

    fetch(`/seller/ad-stats?start_date=${startDate}&end_date=${endDate}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStatsDisplay(data.data);
            }
        })
        .catch(error => {
            console.error('Error loading ad stats:', error);
        });
}

function loadClickHistory(page = 1) {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;

    fetch(`/seller/ad-click-history?page=${page}&start_date=${startDate}&end_date=${endDate}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateClickHistoryTable(data.data);
            }
        })
        .catch(error => {
            console.error('Error loading click history:', error);
        });
}

function updateStatsDisplay(stats) {
    document.getElementById('totalClicks').textContent = stats.total_clicks || 0;
    document.getElementById('chargedClicks').textContent = stats.charged_clicks || 0;
    document.getElementById('totalCost').textContent = formatCurrency(stats.total_cost || 0);
    document.getElementById('shopDetailClicks').textContent = stats.shop_detail_clicks || 0;
    document.getElementById('productDetailClicks').textContent = stats.product_detail_clicks || 0;
    document.getElementById('modalViewClicks').textContent = stats.modal_view_clicks || 0;

    // Load current balance
    fetch('/seller/wallet/balance')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('currentBalance').textContent = formatCurrency(data.balance);
            }
        })
        .catch(error => {
            console.error('Error loading balance:', error);
        });
}

function updateClickHistoryTable(data) {
    const tbody = document.getElementById('clickHistoryTable');
    tbody.innerHTML = '';

    if (data.data && data.data.length > 0) {
        data.data.forEach(click => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${formatDateTime(click.created_at)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${getClickTypeLabel(click.click_type)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${click.ads_campaign ? click.ads_campaign.name : 'N/A'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${click.product ? click.product.name : 'N/A'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${formatCurrency(click.cost_per_click)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${click.is_charged ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${click.is_charged ? 'Đã tính phí' : 'Chưa tính phí'}
                    </span>
                </td>
            `;
            tbody.appendChild(row);
        });
    } else {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Không có dữ liệu</td></tr>';
    }

    // Update pagination
    updatePagination(data);
}

function updatePagination(data) {
    const pagination = document.getElementById('pagination');
    if (data.last_page > 1) {
        let paginationHtml = '<div class="flex items-center justify-between">';
        
        // Previous button
        if (data.current_page > 1) {
            paginationHtml += `<button onclick="loadClickHistory(${data.current_page - 1})" class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Trước</button>`;
        }
        
        // Page numbers
        paginationHtml += '<div class="flex space-x-2">';
        for (let i = 1; i <= data.last_page; i++) {
            if (i === data.current_page) {
                paginationHtml += `<span class="px-3 py-2 border border-blue-500 rounded-md text-sm font-medium text-blue-600 bg-blue-50">${i}</span>`;
            } else {
                paginationHtml += `<button onclick="loadClickHistory(${i})" class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">${i}</button>`;
            }
        }
        paginationHtml += '</div>';
        
        // Next button
        if (data.current_page < data.last_page) {
            paginationHtml += `<button onclick="loadClickHistory(${data.current_page + 1})" class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Sau</button>`;
        }
        
        paginationHtml += '</div>';
        pagination.innerHTML = paginationHtml;
    } else {
        pagination.innerHTML = '';
    }
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

function formatDateTime(dateString) {
    return new Date(dateString).toLocaleString('vi-VN');
}

function getClickTypeLabel(clickType) {
    const labels = {
        'shop_detail': 'Chi tiết shop',
        'product_detail': 'Chi tiết sản phẩm',
        'modal_view': 'Xem modal'
    };
    return labels[clickType] || clickType;
}
</script>
@endsection
