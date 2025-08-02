@php
    $shopList = $shops;
    if (isset($shopProduct) && $shopProduct && !$shops->contains('id', $shopProduct->id)) {
        $shopList = $shops->push($shopProduct);
    }
@endphp
@extends('layouts.app')
@section('content')
<div class="flex flex-1 h-full overflow-hidden border border-gray-300 max-h-[80vh]">
    @include('chat.partials._left_sidebar')
    @include('chat.partials._middle_sidebar', ['shopList' => $shopList])
    @include('chat.partials._main_chat_area', ['productContext' => $productContext ?? null])
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
@vite('resources/js/chat.js')
@endpush 