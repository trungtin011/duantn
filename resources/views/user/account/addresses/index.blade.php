@extends('user.account.profile')
@section('title', 'Địa chỉ của tôi')
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
            <div
                class="text-green-600 bg-green-100 border border-green-400 p-3 rounded mb-4 relative flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" class="text-green-600 hover:text-green-800 ml-4"
                    onclick="this.parentElement.style.display='none'">×</button>
            </div>
        @elseif (session('error'))
            <div
                class="text-red-600 bg-red-100 border border-red-400 p-3 rounded mb-4 relative flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button type="button" class="text-red-600 hover:text-red-800 ml-4"
                    onclick="this.parentElement.style.display='none'">×</button>
            </div>
        @endif

        <div class="mb-3">
            <h2 class="text-lg">Địa chỉ</h2>
        </div>
        @foreach ($addresses as $address)
            <div class="py-4 mb-3 flex justify-between">
                <div class="">
                    <div class="flex items-center mb-2">
                        <div>
                            {{ $address->receiver_name }}
                            <span class="text-gray-500 mx-1 text-sm">
                                |<span class="mx-1">(+84)</span>
                                {{ $address->receiver_phone }}
                            </span>
                        </div>
                    </div>
                    <div class="text-gray-500 text-sm mb-1">
                        {{ $address->address }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                    </div>
                    <div
                        class="@if ($address->is_default) text-[#EF3248] text-xs border border-[#EF3248] py-0.5 px-1 w-fit @endif">
                        {{ $address->is_default ? 'Mặc định' : '' }}
                    </div>
                </div>
                <div class="mt-2 flex flex-col gap-2">
                    <div class="flex gap-5 justify-end">
                        <a href="{{ route('account.addresses.edit', $address) }}" class="text-blue-600 hover:underline">Cấp
                            nhật</a>
                        @unless ($address->is_default)
                            <form action="{{ route('account.addresses.delete', $address) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn xoá?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Xoá</button>
                            </form>
                        @endunless
                    </div>
                    @unless ($address->is_default)
                        <a href="{{ route('account.addresses.set-default', $address) }}"
                            class="border border-gray-200 px-3 py-1 flex items-center">Đặt làm
                            mặc định</a>
                    @endunless
                </div>
            </div>
        @endforeach
        @if ($addresses->isEmpty())
            <div class="text-gray-500">Bạn chưa có địa chỉ nào.</div>
        @endif
    </div>
@endsection
