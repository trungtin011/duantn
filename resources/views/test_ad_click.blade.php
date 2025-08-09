@extends('layouts.app')

@section('title', 'Test Ad Click System')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-center">Test Hệ Thống Ad Click</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Test Shop Detail Click -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Test Click Shop Detail</h2>
                <p class="text-gray-600 mb-4">Click vào link bên dưới để test trừ tiền từ ví shop khi click quảng cáo shop</p>
                
                <a href="{{ route('ad.click') }}?ad_click_type=shop_detail&shop_id=1&campaign_id=1" 
                   class="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors">
                    Click Quảng Cáo Shop
                </a>
                
                <div class="mt-4 p-4 bg-gray-100 rounded">
                    <h3 class="font-semibold mb-2">Thông tin test:</h3>
                    <ul class="text-sm text-gray-600">
                        <li>• Shop ID: 1</li>
                        <li>• Campaign ID: 1</li>
                        <li>• Click Type: shop_detail</li>
                        <li>• Phí: 1000đ/click</li>
                    </ul>
                </div>
            </div>
            
            <!-- Test Product Detail Click -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Test Click Product Detail</h2>
                <p class="text-gray-600 mb-4">Click vào link bên dưới để test trừ tiền từ ví shop khi click quảng cáo sản phẩm</p>
                
                <a href="{{ route('ad.click') }}?ad_click_type=product_detail&shop_id=1&campaign_id=1&product_id=1" 
                   class="inline-block bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-colors">
                    Click Quảng Cáo Sản Phẩm
                </a>
                
                <div class="mt-4 p-4 bg-gray-100 rounded">
                    <h3 class="font-semibold mb-2">Thông tin test:</h3>
                    <ul class="text-sm text-gray-600">
                        <li>• Shop ID: 1</li>
                        <li>• Campaign ID: 1</li>
                        <li>• Product ID: 1</li>
                        <li>• Click Type: product_detail</li>
                        <li>• Phí: 1000đ/click</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Test API -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Test API</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <h3 class="font-semibold mb-2">Check Click Status</h3>
                    <button onclick="checkClickStatus()" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        Kiểm Tra Trạng Thái
                    </button>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-2">Get Shop Stats</h3>
                    <button onclick="getShopStats()" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                        Lấy Thống Kê
                    </button>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-2">Get Shop History</h3>
                    <button onclick="getShopHistory()" class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600">
                        Lấy Lịch Sử
                    </button>
                </div>
            </div>
            
            <div id="api-result" class="mt-4 p-4 bg-gray-100 rounded hidden">
                <h3 class="font-semibold mb-2">Kết quả:</h3>
                <pre id="api-result-content" class="text-sm"></pre>
            </div>
        </div>
        
        <!-- Instructions -->
        <div class="mt-8 bg-blue-50 p-6 rounded-lg">
            <h2 class="text-xl font-semibold mb-4 text-blue-800">Hướng Dẫn Test</h2>
            <ol class="list-decimal list-inside space-y-2 text-blue-700">
                <li>Click vào các link test ở trên để ghi nhận click quảng cáo</li>
                <li>Mỗi lần click sẽ trừ 1000đ từ ví shop</li>
                <li>Hệ thống sẽ hiển thị thông báo thành công hoặc lỗi</li>
                <li>Sử dụng các API để kiểm tra thống kê và lịch sử</li>
                <li>Kiểm tra bảng <code>shop_wallets</code> để xem số dư thay đổi</li>
                <li>Kiểm tra bảng <code>ad_clicks</code> để xem lịch sử click</li>
                <li>Kiểm tra bảng <code>wallet_transactions</code> để xem giao dịch trừ tiền</li>
            </ol>
        </div>
    </div>
</div>

<script>
function checkClickStatus() {
    fetch('/ad/status?campaign_id=1&shop_id=1')
        .then(response => response.json())
        .then(data => {
            showApiResult(data);
        })
        .catch(error => {
            showApiResult({error: error.message});
        });
}

function getShopStats() {
    fetch('/ad/api/stats?shop_id=1')
        .then(response => response.json())
        .then(data => {
            showApiResult(data);
        })
        .catch(error => {
            showApiResult({error: error.message});
        });
}

function getShopHistory() {
    fetch('/ad/api/history?shop_id=1&limit=5')
        .then(response => response.json())
        .then(data => {
            showApiResult(data);
        })
        .catch(error => {
            showApiResult({error: error.message});
        });
}

function showApiResult(data) {
    const resultDiv = document.getElementById('api-result');
    const resultContent = document.getElementById('api-result-content');
    
    resultContent.textContent = JSON.stringify(data, null, 2);
    resultDiv.classList.remove('hidden');
}
</script>
@endsection
