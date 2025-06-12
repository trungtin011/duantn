@extends('account.index')

@section('account-content')
<h2 class="text-2xl font-bold mb-4">Chỉnh sửa địa chỉ</h2>

<form method="POST" action="{{ route('account.addresses.update', $address) }}">
    @csrf @method('PUT')
    @include('account.addresses.form')
</form>
@endsection
