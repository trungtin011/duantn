<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Ad Click</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-center mb-8">Test Ad Click System</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Test Shop Ad -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Test Shop Advertisement</h2>
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-store text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Test Shop</h3>
                            <p class="text-sm text-gray-600">Campaign: Test Campaign</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('shop.show', 1) }}" 
                       data-shop-ad="1"
                       data-shop-id="1"
                       class="block w-full text-center bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors cursor-pointer">
                        Chi tiết Shop
                    </a>
                </div>
            </div>

            <!-- Test Product Ad -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Test Product Advertisement</h2>
                <div class="space-y-4">
                    <div class="border border-gray-200 rounded-lg p-3">
                        <img src="https://via.placeholder.com/200x150" alt="Test Product" class="w-full h-32 object-cover rounded-lg mb-2">
                        <h4 class="font-medium text-gray-800">Test Product Name</h4>
                        <p class="text-red-500 font-bold">₫100,000</p>
                    </div>
                    
                    <a href="{{ route('product.show', 'test-product') }}" 
                       data-ad-campaign="1"
                       data-shop-id="1"
                       data-product-id="1"
                       class="block w-full text-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors cursor-pointer">
                        Xem sản phẩm
                    </a>
                </div>
            </div>

            <!-- Test Banner Ad -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Test Banner Advertisement</h2>
                <div class="space-y-4">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white text-center">
                        <h3 class="text-xl font-bold mb-2">Banner Quảng Cáo</h3>
                        <p class="text-sm opacity-90">Test banner advertisement</p>
                    </div>
                    
                    <a href="#" 
                       data-banner-ad="1"
                       data-shop-id="0"
                       class="block w-full text-center bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors cursor-pointer">
                        Xem chi tiết
                    </a>
                </div>
            </div>

            <!-- Test Results -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Test Results</h2>
                <div id="test-results" class="space-y-2 text-sm">
                    <p class="text-gray-500">Click vào các quảng cáo để xem kết quả...</p>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">Hướng dẫn test:</h3>
            <ul class="text-blue-700 space-y-2">
                <li>• Click vào các quảng cáo để test hệ thống tracking</li>
                <li>• Kiểm tra console để xem log</li>
                <li>• Kiểm tra database để xem ad_clicks và wallet_transactions</li>
                <li>• Đảm bảo đã đăng nhập để test</li>
            </ul>
        </div>
    </div>

    <!-- Ad Click Handler -->
    <script src="{{ asset('js/ad-click-handler.js') }}"></script>
    
    <script>
        // Test function để hiển thị kết quả
        function showTestResult(message, type = 'info') {
            const resultsDiv = document.getElementById('test-results');
            const resultItem = document.createElement('div');
            resultItem.className = `p-2 rounded ${type === 'success' ? 'bg-green-100 text-green-800' : type === 'error' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'}`;
            resultItem.innerHTML = `<span class="font-medium">[${new Date().toLocaleTimeString()}]</span> ${message}`;
            resultsDiv.appendChild(resultItem);
            
            // Giữ tối đa 10 kết quả
            if (resultsDiv.children.length > 10) {
                resultsDiv.removeChild(resultsDiv.firstChild);
            }
        }

        // Override console.log để hiển thị trong test results
        const originalLog = console.log;
        console.log = function(...args) {
            originalLog.apply(console, args);
            showTestResult(args.join(' '), 'info');
        };

        // Override console.error để hiển thị trong test results
        const originalError = console.error;
        console.error = function(...args) {
            originalError.apply(console, args);
            showTestResult(args.join(' '), 'error');
        };
    </script>
</body>
</html>
