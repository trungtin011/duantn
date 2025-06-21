@extends('layouts.admin')

@section('title', 'Duyệt Cửa Hàng')

@section('content')
    <div class="container mx-auto py-5 px-4">
        <h2 class="text-2xl font-bold mb-6">Danh sách cửa hàng chờ duyệt</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 border border-red-300 p-3 rounded text-sm mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($shops->isEmpty())
            <p class="text-gray-600">Không có cửa hàng nào đang chờ duyệt.</p>
        @else
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tên Shop
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Số điện thoại
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Hành động
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shops as $shop)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $shop->shop_name }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $shop->shop_email }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $shop->shop_phone }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                @php
                                    $statusClass = '';
                                    $statusText = '';
                                    if ($shop->shop_status == 'inactive' || $shop->shop_status == 'pending') {
                                        $statusClass = 'bg-yellow-200';
                                        $statusText = 'Chờ duyệt';
                                    } elseif ($shop->shop_status == 'active') {
                                        $statusClass = 'bg-green-200';
                                        $statusText = 'Hoạt động';
                                    } else {
                                        $statusClass = 'bg-red-200';
                                        $statusText = 'Bị từ chối';
                                    }
                                @endphp
                                <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                                    <span aria-hidden="true" class="absolute inset-0 {{ $statusClass }} opacity-50 rounded-full"></span>
                                    <span class="relative">{{ $statusText }}</span>
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <form action="{{ route('admin.shops.approve', $shop) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-xs">Duyệt</button>
                                </form>
                                <button type="button" onclick="showRejectModal({{ $shop->id }}, {{ Js::from($shop->shop_name) }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2 text-xs">Từ chối</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Từ chối cửa hàng <span id="shopNameReject" class="font-bold"></span></h3>
                <div class="mt-2 px-7 py-3">
                    <form id="rejectForm" method="POST" action="">
                        @csrf
                        <textarea name="rejection_reason" rows="4" class="mt-2 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Lý do từ chối (bắt buộc)" required></textarea>
                        <div class="items-center px-4 py-3">
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Gửi từ chối</button>
                            <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="mt-3 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function showRejectModal(shopId, shopName) {
        const modal = document.getElementById('rejectModal');
        const shopNameSpan = document.getElementById('shopNameReject');
        const rejectForm = document.getElementById('rejectForm');
        
        shopNameSpan.textContent = shopName;
        rejectForm.action = '/admin/shops/' + shopId + '/reject'; // Update this to your actual route
        modal.classList.remove('hidden');
    }
</script>
@endsection 