@extends('user.account.profile')

@section('account-content')
    <div class="bg-white px-6 py-4 rounded shadow">

        <div class="mb-3 border-b border-gray-200 pb-4 mb-4 flex items-center justify-between">
            <h2 class="text-lg">Địa chỉ của tôi</h2>
            <a href="{{ route('account.addresses.create') }}"
                class="inline-block bg-[#ef3248] hover:bg-red-600 text-white px-4 py-2">
                + Thêm địa chỉ
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <div class="">
            <h2 class="text-lg">Địa chỉ</h2>
        </div>
        @foreach ($addresses as $address)
            <div class="border p-4 mb-3 rounded @if ($address->is_default) border-blue-500 @endif">
                <div><strong>Người nhận:</strong> {{ $address->receiver_name }}</div>
                <div><strong>SĐT:</strong> {{ $address->receiver_phone }}</div>
                <div><strong>Địa chỉ:</strong> {{ $address->address }}, {{ $address->ward }}, {{ $address->district }},
                    {{ $address->province }}</div>
                <div><strong>Mã bưu điện:</strong> {{ $address->zip_code }}</div>
                <div><strong>Loại:</strong> {{ $address->getAddressTypeLabel() }}</div>
                <div><strong>Mặc định:</strong> {{ $address->is_default ? 'Có' : 'Không' }}</div>

                <div class="mt-2 flex gap-2">
                    <a href="{{ route('account.addresses.edit', $address) }}" class="text-blue-600 hover:underline">Sửa</a>
                    <form action="{{ route('account.addresses.delete', $address) }}" method="POST"
                        onsubmit="return confirm('Bạn có chắc muốn xoá?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline">Xoá</button>
                    </form>
                </div>
            </div>
        @endforeach
        @if ($addresses->isEmpty())
            <div class="text-gray-500">Bạn chưa có địa chỉ nào.</div>
        @endif
    </div>
@endsection
