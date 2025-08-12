@extends('layouts.seller_home')
@section('content')
<div class="flex h-[850px]" data-shop-id="{{ $shop->id }}">
    {{-- @include('seller.chat.partials._left_sidebar_seller') --}}
    @include('seller.chat.partials._middle_sidebar_seller', ['customers' => $customers, 'shop' => $shop, 'unreadCounts' => $unreadCounts])
    @include('seller.chat.partials._main_chat_area_seller')
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
@vite('resources/js/seller_chat.js')
<script>
  window.__sendImage = function(input){
    const form = document.getElementById('chat-form');
    if(!form || !input || !input.files || input.files.length === 0) return;
    const fd = new FormData(form);
    fd.append('image', input.files[0]);
    fetch(form.action || window.location.href, {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: fd
    }).then(r=>r.json()).then(()=>{
      input.value = '';
    }).catch(()=>{ input.value=''; });
  }
</script>
@endpush