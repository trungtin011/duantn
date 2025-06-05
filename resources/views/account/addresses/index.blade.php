@extends('account.layout')

@section('account-content')
<h2 class="text-2xl font-bold mb-4">Danh sách địa chỉ</h2>

@if(session('success'))
<div class="mb-4 text-green-600">{{ session('success') }}</div>
@endif

<a href="{{ route('account.addresses.create') }}" class="mb-4 inline-block bg-black text-white px-4 py-2 rounded">+ Thêm địa chỉ</a>

@foreach($addresses as $address)
<div class="border p-4 mb-3 rounded @if($address->is_default) border-blue-500 @endif">
    <div><strong>Người nhận:</strong> {{ $address->receiver_name }}</div>
    <div><strong>SĐT:</strong> {{ $address->receiver_phone }}</div>
    <div><strong>Địa chỉ:</strong> {{ $address->address }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}</div>
    <div><strong>Mã bưu điện:</strong> {{ $address->zip_code }}</div>
    <div><strong>Loại:</strong> {{ $address->getAddressTypeLabel() }}</div>
    <div><strong>Mặc định:</strong> {{ $address->is_default ? 'Có' : 'Không' }}</div>

    <div class="mt-2 flex gap-2">
        <a href="{{ route('account.addresses.edit', $address) }}" class="text-blue-600 hover:underline">Sửa</a>
        <form action="{{ route('account.addresses.delete', $address) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xoá?')">
            @csrf @method('DELETE')
            <button class="text-red-600 hover:underline">Xoá</button>
        </form>
    </div>
</div>
@endforeach
@endsection
