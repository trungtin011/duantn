@extends('account.layout')

@section('account-content')
<h2 class="text-2xl font-bold mb-4">Thêm địa chỉ mới</h2>

<form method="POST" action="{{ route('account.addresses.store') }}">
    @csrf
    @include('account.addresses.form')
    
</form>
@endsection
