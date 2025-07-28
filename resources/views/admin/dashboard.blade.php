@extends('layouts.admin')

@section('content')
    <div class="mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="admin-page-title">Thống kê</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb admin-breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="admin-breadcrumb-link">Trang chủ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thống kê</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="admin-card d-flex justify-content-between align-items-start">
                <div>
                    <h2 class="card-number font-semibold">{{ $deliveredOrders }}</h2>
                    <p class="mb-0 text-muted text-xs mb-3">Đơn hàng đã nhận</p>
                    <span class="text-xs bg-[#EDFAF3] text-[#50CD89] px-2 py-1 rounded flex items-center gap-1 w-fit">
                        {{ $deliveredGrowth }}%
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 512.001 512.001" width="12"
                            height="12">
                            <path fill="currentColor"
                                d="M506.35,80.699c-7.57-7.589-19.834-7.609-27.43-0.052L331.662,227.31l-42.557-42.557c-7.577-7.57-19.846-7.577-27.423,0 L89.076,357.36c-7.577,7.57-7.577,19.853,0,27.423c3.782,3.788,8.747,5.682,13.712,5.682c4.958,0,9.93-1.894,13.711-5.682 l158.895-158.888l42.531,42.524c7.57,7.57,19.808,7.577,27.397,0.032l160.97-160.323 C513.881,100.571,513.907,88.288,506.35,80.699z">
                            </path>
                            <path fill="currentColor"
                                d="M491.96,449.94H38.788V42.667c0-10.712-8.682-19.394-19.394-19.394S0,31.955,0,42.667v426.667 c0,10.712,8.682,19.394,19.394,19.394H491.96c10.712,0,19.394-8.682,19.394-19.394C511.354,458.622,502.672,449.94,491.96,449.94z">
                            </path>
                            <path fill="currentColor"
                                d="M492.606,74.344H347.152c-10.712,0-19.394,8.682-19.394,19.394s8.682,19.394,19.394,19.394h126.061v126.067 c0,10.705,8.682,19.394,19.394,19.394S512,249.904,512,239.192V93.738C512,83.026,503.318,74.344,492.606,74.344z">
                            </path>
                        </svg>
                    </span>
                </div>
                <div
                    class="icon-box icon-box-active d-flex align-items-center justify-content-center text-white bg-[#50CD89] rounded-full p-[10px]">
                    <svg width="20" height="20" viewBox="0 0 22 22" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.37 7.87988H16.62" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path d="M5.38 7.87988L6.13 8.62988L8.38 6.37988" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M11.37 14.8799H16.62" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path d="M5.38 14.8799L6.13 15.6299L8.38 13.3799" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M8 21H14C19 21 21 19 21 14V8C21 3 19 1 14 1H8C3 1 1 3 1 8V14C1 19 3 21 8 21Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card d-flex justify-content-between align-items-start">
                <div>
                    <h2 class="card-number font-semibold">{{ number_format($avgDailyRevenue) }} VNĐ</h2>
                    <p class="mb-0 text-muted text-xs mb-3">Doanh thu trung bình hàng ngày</p>
                    <span class="text-xs bg-[#F1EBFD] text-[#7239EA] px-2 py-1 rounded flex items-center gap-1 w-fit">
                        {{ $revenueGrowth }}%
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 512.001 512.001" width="12"
                            height="12">
                            <path fill="currentColor"
                                d="M506.35,80.699c-7.57-7.589-19.834-7.609-27.43-0.052L331.662,227.31l-42.557-42.557c-7.577-7.57-19.846-7.577-27.423,0 L89.076,357.36c-7.577,7.57-7.577,19.853,0,27.423c3.782,3.788,8.747,5.682,13.712,5.682c4.958,0,9.93-1.894,13.711-5.682 l158.895-158.888l42.531,42.524c7.57,7.57,19.808,7.577,27.397,0.032l160.97-160.323 C513.881,100.571,513.907,88.288,506.35,80.699z">
                            </path>
                            <path fill="currentColor"
                                d="M491.96,449.94H38.788V42.667c0-10.712-8.682-19.394-19.394-19.394S0,31.955,0,42.667v426.667 c0,10.712,8.682,19.394,19.394,19.394H491.96c10.712,0,19.394-8.682,19.394-19.394C511.354,458.622,502.672,449.94,491.96,449.94z">
                            </path>
                            <path fill="currentColor"
                                d="M492.606,74.344H347.152c-10.712,0-19.394,8.682-19.394,19.394s8.682,19.394,19.394,19.394h126.061v126.067 c0,10.705,8.682,19.394,19.394,19.394S512,249.904,512,239.192V93.738C512,83.026,503.318,74.344,492.606,74.344z">
                            </path>
                        </svg>
                    </span>
                </div>
                <div
                    class="icon-box icon-box-active d-flex align-items-center justify-content-center text-white bg-[#7239EA] rounded-full p-[10px]">
                    <svg width="20" height="22" viewBox="0 0 20 22" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 21H19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path
                            d="M3.59998 7.37988H2C1.45 7.37988 1 7.82988 1 8.37988V16.9999C1 17.5499 1.45 17.9999 2 17.9999H3.59998C4.14998 17.9999 4.59998 17.5499 4.59998 16.9999V8.37988C4.59998 7.82988 4.14998 7.37988 3.59998 7.37988Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path
                            d="M10.7999 4.18994H9.19995C8.64995 4.18994 8.19995 4.63994 8.19995 5.18994V16.9999C8.19995 17.5499 8.64995 17.9999 9.19995 17.9999H10.7999C11.3499 17.9999 11.7999 17.5499 11.7999 16.9999V5.18994C11.7999 4.63994 11.3499 4.18994 10.7999 4.18994Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path
                            d="M17.9999 1H16.3999C15.8499 1 15.3999 1.45 15.3999 2V17C15.3999 17.55 15.8499 18 16.3999 18H17.9999C18.5499 18 18.9999 17.55 18.9999 17V2C18.9999 1.45 18.5499 1 17.9999 1Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card d-flex justify-content-between align-items-start">
                <div>
                    <h2 class="card-number font-semibold">{{ number_format($newCustomers) }}</h2>
                    <p class="mb-0 text-muted text-xs mb-3">Khách hàng mới trong tháng này</p>
                    <span class="text-xs bg-[#EBF4FF] text-[#3E97FF] px-2 py-1 rounded flex items-center gap-1 w-fit">
                        {{ $customerGrowth }}%
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 512.001 512.001" width="12"
                            height="12">
                            <path fill="currentColor"
                                d="M506.35,80.699c-7.57-7.589-19.834-7.609-27.43-0.052L331.662,227.31l-42.557-42.557c-7.577-7.57-19.846-7.577-27.423,0 L89.076,357.36c-7.577,7.57-7.577,19.853,0,27.423c3.782,3.788,8.747,5.682,13.712,5.682c4.958,0,9.93-1.894,13.711-5.682 l158.895-158.888l42.531,42.524c7.57,7.57,19.808,7.577,27.397,0.032l160.97-160.323 C513.881,100.571,513.907,88.288,506.35,80.699z">
                            </path>
                            <path fill="currentColor"
                                d="M491.96,449.94H38.788V42.667c0-10.712-8.682-19.394-19.394-19.394S0,31.955,0,42.667v426.667 c0,10.712,8.682,19.394,19.394,19.394H491.96c10.712,0,19.394-8.682,19.394-19.394C511.354,458.622,502.672,449.94,491.96,449.94z">
                            </path>
                            <path fill="currentColor"
                                d="M492.606,74.344H347.152c-10.712,0-19.394,8.682-19.394,19.394s8.682,19.394,19.394,19.394h126.061v126.067 c0,10.705,8.682,19.394,19.394,19.394S512,249.904,512,239.192V93.738C512,83.026,503.318,74.344,492.606,74.344z">
                            </path>
                        </svg>
                    </span>
                </div>
                <div
                    class="icon-box icon-box-active d-flex align-items-center justify-content-center text-white bg-[#3E97FF] rounded-full p-[10px]">
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17 6.16C16.94 6.15 16.87 6.15 16.81 6.16C15.43 6.11 14.33 4.98 14.33 3.58C14.33 2.15 15.48 1 16.91 1C18.34 1 19.49 2.16 19.49 3.58C19.48 4.98 18.38 6.11 17 6.16Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                        <path
                            d="M15.9699 13.44C17.3399 13.67 18.8499 13.43 19.9099 12.72C21.3199 11.78 21.3199 10.24 19.9099 9.30004C18.8399 8.59004 17.3099 8.35003 15.9399 8.59003"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                        <path
                            d="M4.96998 6.16C5.02998 6.15 5.09998 6.15 5.15998 6.16C6.53998 6.11 7.63998 4.98 7.63998 3.58C7.63998 2.15 6.48998 1 5.05998 1C3.62998 1 2.47998 2.16 2.47998 3.58C2.48998 4.98 3.58998 6.11 4.96998 6.16Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                        <path
                            d="M5.99994 13.44C4.62994 13.67 3.11994 13.43 2.05994 12.72C0.649941 11.78 0.649941 10.24 2.05994 9.30004C3.12994 8.59004 4.65994 8.35003 6.02994 8.59003"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                        <path
                            d="M11 13.63C10.94 13.62 10.87 13.62 10.81 13.63C9.42996 13.58 8.32996 12.45 8.32996 11.05C8.32996 9.61997 9.47995 8.46997 10.91 8.46997C12.34 8.46997 13.49 9.62997 13.49 11.05C13.48 12.45 12.38 13.59 11 13.63Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                        <path
                            d="M8.08997 16.78C6.67997 17.72 6.67997 19.26 8.08997 20.2C9.68997 21.27 12.31 21.27 13.91 20.2C15.32 19.26 15.32 17.72 13.91 16.78C12.32 15.72 9.68997 15.72 8.08997 16.78Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card d-flex justify-content-between align-items-start">
                <div>
                    <h2 class="card-number font-semibold">{{ $platfrom_revenue }}</h2>
                    <p class="mb-0 text-muted text-xs mb-3">Hoa hồng từ các đơn hàng</p>
                    <span class="text-xs bg-[#FFF4E5] text-[#FF9800] px-2 py-1 rounded flex items-center gap-1 w-fit">
                        {{ $platfromGrowth }}%
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 512.001 512.001"
                            width="12" height="12">
                            <path fill="currentColor"
                                d="M506.35,80.699c-7.57-7.589-19.834-7.609-27.43-0.052L331.662,227.31l-42.557-42.557c-7.577-7.57-19.846-7.577-27.423,0 L89.076,357.36c-7.577,7.57-7.577,19.853,0,27.423c3.782,3.788,8.747,5.682,13.712,5.682c4.958,0,9.93-1.894,13.711-5.682 l158.895-158.888l42.531,42.524c7.57,7.57,19.808,7.577,27.397,0.032l160.97-160.323 C513.881,100.571,513.907,88.288,506.35,80.699z">
                            </path>
                            <path fill="currentColor"
                                d="M491.96,449.94H38.788V42.667c0-10.712-8.682-19.394-19.394-19.394S0,31.955,0,42.667v426.667 c0,10.712,8.682,19.394,19.394,19.394H491.96c10.712,0,19.394-8.682,19.394-19.394C511.354,458.622,502.672,449.94,491.96,449.94z">
                            </path>
                            <path fill="currentColor"
                                d="M492.606,74.344H347.152c-10.712,0-19.394,8.682-19.394,19.394s8.682,19.394,19.394,19.394h126.061v126.067 c0,10.705,8.682,19.394,19.394,19.394S512,249.904,512,239.192V93.738C512,83.026,503.318,74.344,492.606,74.344z">
                            </path>
                        </svg>
                    </span>
                </div>
                <div
                    class="icon-box icon-box-active d-flex align-items-center justify-content-center text-white bg-[#FF9800] rounded-full p-[10px]">
                    <svg width="23" height="22" viewBox="0 0 23 22" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.17004 6.43994L11 11.5499L19.77 6.46991" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M11 20.6099V11.5399" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path
                            d="M20.61 8.17V13.83C20.61 13.88 20.61 13.92 20.6 13.97C19.9 13.36 19 13 18 13C17.06 13 16.19 13.33 15.5 13.88C14.58 14.61 14 15.74 14 17C14 17.75 14.21 18.46 14.58 19.06C14.67 19.22 14.78 19.37 14.9 19.51L13.07 20.52C11.93 21.16 10.07 21.16 8.92999 20.52L3.59 17.56C2.38 16.89 1.39001 15.21 1.39001 13.83V8.17C1.39001 6.79 2.38 5.11002 3.59 4.44002L8.92999 1.48C10.07 0.84 11.93 0.84 13.07 1.48L18.41 4.44002C19.62 5.11002 20.61 6.79 20.61 8.17Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                        <path
                            d="M22 17C22 18.2 21.47 19.27 20.64 20C19.93 20.62 19.01 21 18 21C15.79 21 14 19.21 14 17C14 15.74 14.58 14.61 15.5 13.88C16.19 13.33 17.06 13 18 13C20.21 13 22 14.79 22 17Z"
                            stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path d="M18.25 15.75V17.25L17 18" stroke="currentColor" stroke-width="1.5"
                            stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4 g-3">
        <div class="col-md-8">
            <div class="bg-white rounded-md">
                <h5 class="px-4 pt-4 font-semibold">Thống kê bán hàng</h5>
                <canvas id="salesChart" class="p-4"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-white rounded-md p-0 h-[555px]">
                <h5 class="px-4 pt-4 font-semibold">Danh mục bán chạy nhất</h5>
                <canvas id="categoryChart" class="p-5"></canvas>
            </div>
        </div>
    </div>

    <div class="row mb-4 g-3">
        <div class="col-md-12">
            <div class="bg-white rounded-md p-0 h-[555px]">
                <h5 class="px-4 pt-4 font-semibold">Thương hiệu bán chạy nhất</h5>
                <canvas id="brandChart" class="p-5"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6 mb-6">
        <div class="bg-white p-8 col-span-12 xl:col-span-4 2xl:col-span-3 rounded-md">
            <div class="flex items-center justify-between mb-8">
                <h2 class="">Giao dịch</h2>
                <a href="#"
                    class="text-decoration-none text-[#3e97ff] hover:text-info/60 hover:border-info/60 text-sm border-bottom dashed">Xem
                    tất cả</a>
            </div>
            <div class="space-y-5">
                @foreach ($recentOrders as $order)
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="m-2 mb:sm-0 flex items-center space-x-3">
                            <div class="avatar">
                                <img class="rounded-full w-8 h-8" src="{{ asset('images/avatar.png') }}" alt="avatar">
                            </div>
                            <div>
                                <h4 class="text-sm text-base text-slate-700 mb-[6px] leading-none">
                                    {{ $order->customer_name }}
                                </h4>
                                <p class="text-xs text-slate-400 line-clamp-1 m-0 leading-none">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y - h:i A') }}
                                </p>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-success mb-0">{{ number_format($order->amount) }} VNĐ</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white p-8 col-span-12 xl:col-span-8 2xl:col-span-6 rounded-md">
            <div class="">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 ">Đơn đặt hàng gần đây</h5>
                    <a href="#"
                        class="text-decoration-none text-[#3e97ff] hover:text-info/60 hover:border-info/60 text-sm border-bottom dashed">Xem
                        tất cả</a>
                </div>
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-400 uppercase">
                        <tr>
                            <th scope="col" class="py-3">Sản phẩm</th>
                            <th scope="col" class="py-3">ID sản phẩm</th>
                            <th scope="col" class="py-3">Giá</th>
                            <th scope="col" class="py-3">Trạng thái</th>
                            <th scope="col" class="py-3">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                        @foreach ($recentOrders as $order)
                            <tr>
                                <th>{{ $order->product_name }}</th>
                                <td>{{ $order->order_id }}</td>
                                <td>{{ number_format($order->price) }} VNĐ</td>
                                <td>
                                    @if ($order->status == 'pending')
                                        <span class="text-xs text-[#FF9800] bg-[#FFF4E5] px-2 py-1 rounded">Chờ xử
                                            lý</span>
                                    @elseif ($order->status == 'processing')
                                        <span class="text-xs text-[#3E97FF] bg-[#EBF4FF] px-2 py-1 rounded">Đang xử
                                            lý</span>
                                    @elseif ($order->status == 'delivered')
                                        <span class="text-xs text-[#50CD89] bg-[#EDFAF3] px-2 py-1 rounded">Đã giao</span>
                                    @elseif ($order->status == 'shipped')
                                        <span class="text-xs text-[#4CAF50] bg-[#E8F5E9] px-2 py-1 rounded">Đang
                                            giao</span>
                                    @elseif ($order->status == 'cancelled')
                                        <span class="text-xs text-[#F44336] bg-[#FFEDEE] px-2 py-1 rounded">Đã hủy</span>
                                    @elseif ($order->status == 'refunded')
                                        <span class="text-xs text-[#9E9E9E] bg-[#F5F5F5] px-2 py-1 rounded">Đã hoàn
                                            tiền</span>
                                    @else
                                        <span class="text-xs text-[#757575] bg-[#EEEEEE] px-2 py-1 rounded">Không xác
                                            định</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="text-xs text-[#3e97ff] bg-[#EBF4FF] px-3 py-2 rounded">
                                        Chi tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white p-8 col-span-12 xl:col-span-12 2xl:col-span-3 rounded-md">
            <h3 class="mb-4">Nguồn lưu lượng truy cập</h3>
            <div class="space-y-4">
                <div class="bar">
                    <div class="flex justify-between items-center">
                        <h5 class="text-xs font-semibold text-slate-700 mb-1">Facebook</h5>
                        <span class="text-xs text-slate-700 mb-0">20%</span>
                    </div>
                    <div class="relative h-2 w-full bg-[#3b5998]/10 rounded">
                        <div data-width="20%" class="data-width absolute top-0 h-full rounded bg-[#3b5998] progress-bar "
                            style="width: 20%;"></div>
                    </div>
                </div>
                <div class="bar">
                    <div class="flex justify-between items-center">
                        <h5 class="text-xs font-semibold text-slate-700 mb-1">YouTube</h5>
                        <span class="text-xs text-slate-700 mb-0">80%</span>
                    </div>
                    <div class="relative h-2 w-full bg-[#FF0000]/10 rounded">
                        <div data-width="80%" class="data-width absolute top-0 h-full rounded bg-[#FF0000] progress-bar "
                            style="width: 80%;"></div>
                    </div>
                </div>
                <div class="bar">
                    <div class="flex justify-between items-center">
                        <h5 class="text-xs font-semibold text-slate-700 mb-1">WhatsApp</h5>
                        <span class="text-xs text-slate-700 mb-0">65%</span>
                    </div>
                    <div class="relative h-2 w-full bg-[#25D366]/10 rounded">
                        <div data-width="65%" class="data-width absolute top-0 h-full rounded bg-[#25D366] progress-bar "
                            style="width: 65%;"></div>
                    </div>
                </div>
                <div class="bar">
                    <div class="flex justify-between items-center">
                        <h5 class="text-xs font-semibold text-slate-700 mb-1">Instagram</h5>
                        <span class="text-xs text-slate-700 mb-0">90%</span>
                    </div>
                    <div class="relative h-2 w-full bg-[#C13584]/10 rounded">
                        <div data-width="65%" class="data-width absolute top-0 h-full rounded bg-[#C13584] progress-bar "
                            style="width: 65%;"></div>
                    </div>
                </div>
                <div class="bar">
                    <div class="flex justify-between items-center">
                        <h5 class="text-xs font-semibold text-slate-700 mb-1">Others</h5>
                        <span class="text-xs text-slate-700 mb-0">10%</span>
                    </div>
                    <div class="relative h-2 w-full bg-[#737373]/10 rounded">
                        <div data-width="10%" class="data-width absolute top-0 h-full rounded bg-[#737373] progress-bar "
                            style="width: 10%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="">
        <div class="w-[1400px] 2xl:w-full">
            <div class="grid grid-cols-12 border-b border-[#f2f2f6] rounded-t-md bg-white px-[35px] py-[17px] pb-[25px]">
                <div class="table-information col-span-4">
                    <h3 class="font-medium tracking-wide text-slate-800 text-lg mb-2 leading-none">Danh sách sản phẩm</h3>
                    <p class="text-slate-500 mb-0 text-xs">Trung bình {{ number_format($avgDailyRevenue) }} đơn hàng mỗi
                        ngày</p>
                </div>
                <div class="table-actions space-x-9 flex justify-end items-center col-span-8">
                    <div class="table-action-item">
                        <div class="show-category flex items-center category-select">
                            <span class="text-xs font-normal text-slate-400 mr-2">Danh mục</span>
                            <div class="choices">
                                <div class="choices__inner">
                                    <select class="text-xs" name="category">
                                        <option value="">Tất cả</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-action-item">
                        <div class="show-category flex items-center status-select">
                            <span class="text-xs font-normal text-slate-400 mr-2">Trạng thái</span>
                            <div class="">
                                <div class="">
                                    <select class="text-xs" name="status">
                                        <option value="">Tất cả</option>
                                        <option value="active">Hoạt động</option>
                                        <option value="out_of_stock">Hết hàng</option>
                                        <option value="deleted">Xoá</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-[250px]">
                        <form action="#">
                            <div class="w-[250px] relative">
                                <input
                                    class="input h-9 w-full pr-12 pl-4 py-2 text-xs placeholder:text-slate-400 focus:outline-none border border-[#F2F2F6] rounded-[4px]"
                                    type="text" placeholder="Search Here...">
                                <button class="absolute top-1/2 right-6 translate-y-[-50%] hover:text-theme">
                                    <svg class="-translate-y-px" width="13" height="13" viewBox="0 0 20 20"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9 17C13.4183 17 17 13.4183 17 9C17 4.58172 13.4183 1 9 1C4.58172 1 1 4.58172 1 9C1 13.4183 4.58172 17 9 17Z"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                        <path d="M18.9999 19L14.6499 14.65" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="">
                <div class="relative rounded-b-md bg-white px-10 py-7 mb-[30px]">
                    <!-- table -->
                    <table class="w-full text-base text-left text-gray-400">
                        <thead class="bg-white">
                            <tr class="border-b border-[#f2f2f6] text-xs">
                                <th scope="col" class="pr-8 py-3 text-xs uppercase font-semibold">
                                    Sản phẩm
                                </th>
                                <th scope="col" class="px-3 py-3 text-xs uppercase font-semibold">
                                    ID Sản phẩm
                                </th>
                                <th scope="col" class="px-3 py-3 text-xs uppercase font-semibold">
                                    Danh mục
                                </th>
                                <th scope="col" class="px-3 py-3 text-xs uppercase font-semibold">
                                    Giá
                                </th>
                                <th scope="col" class="px-3 py-3 text-xs uppercase font-semibold">
                                    Trạng thái
                                </th>
                                <th scope="col" class="px-3 py-3 text-xs uppercase font-semibold w-[14%] 2xl:w-[12%]">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-900 font-normal">
                            @foreach ($products as $product)
                                <tr class="bg-white border-b border-gray-6 last:border-0 text-start">
                                    <td class="py-3">
                                        <a href="#" class="text-sm text-heading text-hover-primary">
                                            {{ $product->name }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-[16px]">{{ $product->product_id }}</td>
                                    <td class="py-3 px-[16px]">{{ $product->category_name ?? 'Không có danh mục' }}</td>
                                    <td class="py-3 px-[16px]">{{ number_format($product->price) }} VNĐ</td>
                                    <td class="py-3 px-[16px]">
                                        <span
                                            class="text-[11px] text-[#50cd89] px-3 py-1 rounded-md bg-[#EDFAF3]">{{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-[16px]">
                                        <div class="flex items-center space-x-2">
                                            <button
                                                class="flex items-center gap-1 bg-[#50cd89] text-white text-sm pt-2 pb-[6px] px-[10px] rounded-md">
                                                <span class="text-[9px] inline-block -translate-y-[1px] mr-[1px]">
                                                    <svg class="translate-y-px" height="10"
                                                        viewBox="0 0 492.49284 492" width="10"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill="currentColor"
                                                            d="m304.140625 82.472656-270.976563 270.996094c-1.363281 1.367188-2.347656 3.09375-2.816406 4.949219l-30.035156 120.554687c-.898438 3.628906.167969 7.488282 2.816406 10.136719 2.003906 2.003906 4.734375 3.113281 7.527344 3.113281.855469 0 1.730469-.105468 2.582031-.320312l120.554688-30.039063c1.878906-.46875 3.585937-1.449219 4.949219-2.8125l271-270.976562zm0 0">
                                                        </path>
                                                        <path fill="currentColor"
                                                            d="m476.875 45.523438-30.164062-30.164063c-20.160157-20.160156-55.296876-20.140625-75.433594 0l-36.949219 36.949219 105.597656 105.597656 36.949219-36.949219c10.070312-10.066406 15.617188-23.464843 15.617188-37.714843s-5.546876-27.648438-15.617188-37.71875zm0 0">
                                                        </path>
                                                    </svg>
                                                </span>
                                                Sửa
                                            </button>
                                            <button
                                                class="flex items-center gap-1 bg-transparent text-black text-sm pt-2 pb-[6px] px-[10px] rounded-md border border-[#50cd89]">
                                                <span class="text-[9px] inline-block -translate-y-[1px] mr-[1px]">
                                                    <svg class="translate-y-px" width="10" height="10"
                                                        viewBox="0 0 20 22" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M19.0697 4.23C17.4597 4.07 15.8497 3.95 14.2297 3.86V3.85L14.0097 2.55C13.8597 1.63 13.6397 0.25 11.2997 0.25H8.67967C6.34967 0.25 6.12967 1.57 5.96967 2.54L5.75967 3.82C4.82967 3.88 3.89967 3.94 2.96967 4.03L0.929669 4.23C0.509669 4.27 0.209669 4.64 0.249669 5.05C0.289669 5.46 0.649669 5.76 1.06967 5.72L3.10967 5.52C8.34967 5 13.6297 5.2 18.9297 5.73C18.9597 5.73 18.9797 5.73 19.0097 5.73C19.3897 5.73 19.7197 5.44 19.7597 5.05C19.7897 4.64 19.4897 4.27 19.0697 4.23Z"
                                                            fill="currentColor"></path>
                                                        <path
                                                            d="M17.2297 7.14C16.9897 6.89 16.6597 6.75 16.3197 6.75H3.67975C3.33975 6.75 2.99975 6.89 2.76975 7.14C2.53975 7.39 2.40975 7.73 2.42975 8.08L3.04975 18.34C3.15975 19.86 3.29975 21.76 6.78975 21.76H13.2097C16.6997 21.76 16.8398 19.87 16.9497 18.34L17.5697 8.09C17.5897 7.73 17.4597 7.39 17.2297 7.14ZM11.6597 16.75H8.32975C7.91975 16.75 7.57975 16.41 7.57975 16C7.57975 15.59 7.91975 15.25 8.32975 15.25H11.6597C12.0697 15.25 12.4097 15.59 12.4097 16C12.4097 16.41 12.0697 16.75 11.6597 16.75ZM12.4997 12.75H7.49975C7.08975 12.75 6.74975 12.41 6.74975 12C6.74975 11.59 7.08975 11.25 7.49975 11.25H12.4997C12.9097 11.25 13.2497 11.59 13.2497 12C13.2497 12.41 12.9097 12.75 12.4997 12.75Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                </span>
                                                Xoá
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
                labels: @json($labels),
                datasets: [{
                        label: 'Doanh thu',
                        data: @json($sales),
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Lượt truy cập',
                        data: @json($visitors),
                        borderColor: 'rgba(163, 230, 53, 1)',
                        backgroundColor: 'rgba(163, 230, 53, 0.2)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Sản phẩm bán ra',
                        data: @json($products),
                        borderColor: 'rgba(251, 191, 36, 1)',
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
                        position: 'top'
                    }
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
                labels: @json($categoryLabels),
                datasets: [{
                    data: @json($categoryValues),
                    backgroundColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(163, 230, 53, 1)',
                        'rgba(251, 191, 36, 1)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });

        // Brand Chart
        var ctxBrand = document.getElementById('brandChart').getContext('2d');
        var brandChart = new Chart(ctxBrand, {
            type: 'pie',
            data: {
                labels: @json($brandLabels),
                datasets: [{
                    data: @json($brandValues),
                    backgroundColor: [
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>
@endsection
