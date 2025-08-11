@extends('layouts.app')

@section('title', 'Test Ad Click - PHP Thuần')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-center">Test Hệ Thống Ad Click (PHP Thuần)</h1>
        
        <!-- Thông báo -->
        @if(session('ad_click_success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('ad_click_success') }}
            </div>
        @endif
        
        @if(session('ad_click_error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('ad_click_error') }}
            </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Test Shop Detail Click -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Test Click Shop Detail</h2>
                <p class="text-gray-600 mb-4">Click vào link bên dưới để test trừ tiền từ ví shop khi click quảng cáo shop</p>
                
                <a href="{{ route('simple.ad.click') }}?ad_click_type=shop_detail&shop_id=1&campaign_id=1" 
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
                
                <a href="{{ route('simple.ad.click') }}?ad_click_type=product_detail&shop_id=1&campaign_id=1&product_id=1" 
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
            <p class="text-gray-600 mb-4">Click vào nút bên dưới để test API (chỉ trừ tiền lần đầu)</p>
            
            <div class="flex gap-4">
                <button onclick="testApi()" class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600 transition-colors">
                    Test API Click
                </button>
                <button onclick="resetData()" class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-colors">
                    Reset Test Data
                </button>
                <button onclick="debugData()" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors">
                    Debug Data
                </button>
            </div>
            
            <div id="api-result" class="mt-4 p-4 bg-gray-100 rounded hidden">
                <h3 class="font-semibold mb-2">Kết quả:</h3>
                <pre id="api-result-content" class="text-sm"></pre>
            </div>
        </div>
        
        <!-- Xem thống kê -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Xem Thống Kê</h2>
            <p class="text-gray-600 mb-4">Click vào link bên dưới để xem thống kê click quảng cáo</p>
            
            <a href="{{ route('simple.ad.stats') }}?shop_id=1" 
               class="inline-block bg-purple-500 text-white px-6 py-3 rounded-lg hover:bg-purple-600 transition-colors">
                Xem Thống Kê
            </a>
        </div>
        
        <!-- Hướng dẫn -->
        <div class="mt-8 bg-blue-50 p-6 rounded-lg">
            <h2 class="text-xl font-semibold mb-4 text-blue-800">Hướng Dẫn Test</h2>
            <ol class="list-decimal list-inside space-y-2 text-blue-700">
                <li>Click vào các link test ở trên để ghi nhận click quảng cáo</li>
                <li>Mỗi lần click sẽ trừ 1000đ từ ví shop</li>
                <li>Hệ thống sẽ hiển thị thông báo thành công hoặc lỗi</li>
                <li>Kiểm tra bảng <code>shop_wallets</code> để xem số dư thay đổi</li>
                <li>Kiểm tra bảng <code>ad_clicks</code> để xem lịch sử click</li>
                <li>Kiểm tra bảng <code>wallet_transactions</code> để xem giao dịch trừ tiền</li>
                <li>Mỗi user chỉ được click 1 lần trong 24h để tránh spam</li>
            </ol>
        </div>
    </div>
</div>

<script>
function testApi() {
    fetch('/simple-ad/test?shop_id=1&campaign_id=1')
        .then(response => response.json())
        .then(data => {
            showApiResult(data);
        })
        .catch(error => {
            showApiResult({error: error.message});
        });
}

function resetData() {
    if (confirm('Bạn có chắc muốn reset dữ liệu test?')) {
        fetch('/simple-ad/reset?shop_id=1')
            .then(response => response.json())
            .then(data => {
                showApiResult(data);
            })
            .catch(error => {
                showApiResult({error: error.message});
            });
    }
}

function debugData() {
    fetch('/simple-ad/debug?shop_id=1')
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
