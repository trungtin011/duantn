@extends('layouts.seller_home')
@section('content')
<div class="flex flex-1 h-full overflow-hidden border border-gray-300 max-h-[80vh]" data-shop-id="{{ $shop->id }}">
    @include('seller.chat.partials._left_sidebar_seller')
    @include('seller.chat.partials._middle_sidebar_seller', ['customers' => $customers, 'shop' => $shop, 'unreadCounts' => $unreadCounts])
    @include('seller.chat.partials._main_chat_area_seller')
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
@vite('resources/js/seller_chat.js')
@endpush