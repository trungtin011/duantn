@extends('layouts.admin')

@section('content')
<h2>Danh sách Logo</h2>
<a href="{{ route('logo.create') }}" class="btn btn-primary mb-3">Thêm logo</a>
<table class="table">
    <thead>
        <tr>
            <th>Tên</th>
            <th>Ảnh</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logos as $logo)
        <tr>
            <td>{{ $logo->name }}</td>
            <td><img src="{{ asset('storage/' . $logo->image_path) }}" height="60"></td>
            <td><span class="badge bg-{{ $logo->status == 'active' ? 'success' : 'secondary' }}">{{ $logo->status }}</span></td>
            <td>
                <form action="{{ route('logo.destroy', $logo->id) }}" method="POST" onsubmit="return confirm('Xoá logo?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">Xoá</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
