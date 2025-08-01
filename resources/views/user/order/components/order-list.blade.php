@forelse ($orders as $order)
    @include('user.order.components.order-block', [
        'order' => $order,
        'reviewedProductIds' => $reviewedProductIds,
        'statuses' => $statuses,
    ])
@empty
    <div class="bg-white shadow-sm rounded-lg text-center py-6">
        <div class="p-4 flex flex-col gap-2 items-center">
            <h5 class="text-gray-500 text-base sm:text-lg">
                Bạn không có đơn hàng nào {{ strtolower($statuses[request()->route('status')] ?? '') }}.
            </h5>
        </div>
    </div>
@endforelse 