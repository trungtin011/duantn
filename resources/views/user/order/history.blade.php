@extends('user.account.layout')

@section('account-content')
    <div class="container mx-auto bg-white">
        <!-- Tabs for Order Status -->
        {{-- <ul class="flex items-center justify-between border border-gray-200 px-4 py-4 mb-8 overflow-x-auto"
            id="orderStatusTabs" role="tablist">
            <li class="mr-4" role="presentation">
                <button class="px-2 font-bold text-gray-500 hover:text-black focus:outline-none" id="all-tab"
                    data-target="#all" type="button" role="tab" aria-controls="all">Tất cả</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="pending-tab"
                    data-target="#pending" type="button" role="tab" aria-controls="pending">Đang chờ xử lý</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="processing-tab"
                    data-target="#processing" type="button" role="tab" aria-controls="processing">Đang xử lý</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="shipped-tab"
                    data-target="#shipped" type="button" role="tab" aria-controls="shipped">Đang giao hàng</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="delivered-tab"
                    data-target="#delivered" type="button" role="tab" aria-controls="delivered">Hoàn thành</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="cancelled-tab"
                    data-target="#cancelled" type="button" role="tab" aria-controls="cancelled">Đã hủy</button>
            </li>
            <li class="mr-4" role="presentation">
                <button class="font-bold text-gray-500 hover:text-black focus:outline-none" id="refunded-tab"
                    data-target="#refunded" type="button" role="tab" aria-controls="refunded">Trả hàng/Hoàn
                    tiền</button>
            </li>
        </ul> --}}

        <div class="tab-content h-full" id="orderStatusTabsContent">
            <!-- Tab Pane: Tất cả -->
            <div class="tab-pane" id="all" role="tabpanel" aria-labelledby="all-tab">
                @forelse ($allOrders as $order)
                   @include('user.order.components.order-block', [
                    'order' => $order,
                    'reviewedProductIds' => $reviewedProductIds
                ])

                @empty
                    <div class="bg-white shadow-sm rounded-lg text-center py-6">
                        <div class="p-4 flex flex-col gap-2 items-center">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn chưa có đơn hàng nào.</h5>
                            <a href="{{ route('home') }}" class="btn btn-dark">Quay lại mua sắm</a>
                        </div>
                    </div>
                @endforelse
                {{ $allOrders->links() }}
            </div>

            <!-- Tab Pane: Đang chờ xử lý -->
            <div class="tab-pane hidden" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                @forelse ($pendingOrders as $order)
                   @include('user.order.components.order-block', [
                    'order' => $order,
                    'reviewedProductIds' => $reviewedProductIds
                ])

                @empty
                    <div class="bg-white shadow-sm rounded-lg text-center py-6">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đang chờ xử lý.</h5>
                        </div>
                    </div>
                @endforelse
                {{ $pendingOrders->links() }}
            </div>

            <!-- Tab Pane: Đang xử lý -->
            <div class="tab-pane hidden" id="processing" role="tabpanel" aria-labelledby="processing-tab">
                @forelse ($processingOrders as $order)
                    @include('user.order.components.order-block', [
                    'order' => $order,
                    'reviewedProductIds' => $reviewedProductIds
                ])

                @empty
                    <div class="bg-white shadow-sm rounded-lg text-center py-6">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đang xử lý.</h5>
                        </div>
                    </div>
                @endforelse
                {{ $processingOrders->links() }}
            </div>

            <!-- Tab Pane: Đang giao hàng -->
            <div class="tab-pane hidden" id="shipped" role="tabpanel" aria-labelledby="shipped-tab">
                @forelse ($shippedOrders as $order)
                    @include('user.order.components.order-block', [
                    'order' => $order,
                    'reviewedProductIds' => $reviewedProductIds
                ])

                @empty
                    <div class="bg-white shadow-sm rounded-lg text-center py-6">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đang giao hàng.</h5>
                        </div>
                    </div>
                @endforelse
                {{ $shippedOrders->links() }}
            </div>

            <!-- Tab Pane: Hoàn thành -->
            <div class="tab-pane hidden" id="delivered" role="tabpanel" aria-labelledby="delivered-tab">
                @forelse ($deliveredOrders as $order)
                    @include('user.order.components.order-block', [
                    'order' => $order,
                    'reviewedProductIds' => $reviewedProductIds
                ])

                @empty
                    <div class="bg-white shadow-sm rounded-lg text-center py-6">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đã hoàn thành.</h5>
                        </div>
                    </div>
                @endforelse
                {{ $deliveredOrders->links() }}
            </div>

            <!-- Tab Pane: Đã hủy -->
            <div class="tab-pane hidden" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                @forelse ($cancelledOrders as $order)
                    @include('user.order.components.order-block', [
                        'order' => $order,
                        'reviewedProductIds' => $reviewedProductIds
                    ])

                @empty
                    <div class="bg-white shadow-sm rounded-lg text-center py-6">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đã hủy.</h5>
                        </div>
                    </div>
                @endforelse
                {{ $cancelledOrders->links() }}
            </div>

            <!-- Tab Pane: Trả hàng/Hoàn tiền -->
            <div class="tab-pane hidden" id="refunded" role="tabpanel" aria-labelledby="refunded-tab">
                @forelse ($refundedOrders as $order)
                    @include('user.order.components.order-block', [
                            'order' => $order,
                            'reviewedProductIds' => $reviewedProductIds
                        ])

                @empty
                    <div class="bg-white shadow-sm rounded-lg text-center py-6">
                        <div class="p-4">
                            <h5 class="text-gray-500 text-base sm:text-lg">Bạn không có đơn hàng nào đang yêu cầu trả
                                hàng/hoàn tiền.</h5>
                        </div>
                    </div>
                @endforelse
                {{ $refundedOrders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tabList = document.querySelector('#orderStatusTabs');
                const tabContents = document.querySelectorAll('.tab-content .tab-pane');

                tabList.addEventListener('click', function(e) {
                    const tab = e.target.closest('button');
                    if (!tab) return;

                    e.preventDefault();
                    tabList.querySelectorAll('button').forEach(t => t.classList.remove('text-black',
                        'border-b-2', 'border-black'));
                    tab.classList.add('text-black', 'border-b-2', 'border-black');
                    tabContents.forEach(content => content.classList.add('hidden'));
                    const target = document.querySelector(tab.getAttribute('data-target'));
                    if (target) {
                        target.classList.remove('hidden');
                    }
                });

                // Kích hoạt tab mặc định (Tất cả)
                const defaultTab = tabList.querySelector('#all-tab');
                if (defaultTab) {
                    defaultTab.classList.add('text-black', 'border-b-2', 'border-black');
                    const defaultContent = document.querySelector(defaultTab.getAttribute('data-target'));
                    if (defaultContent) {
                        defaultContent.classList.remove('hidden');
                    }
                }
            });
        </script>
    @endpush
@endsection
