@extends('layouts.admin')

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Notifications</h1>
    <div class="admin-breadcrumb"><a href="{{ route('admin.dashboard') }}" class="admin-breadcrumb-link">Home</a> / Notifications</div>
</div>
<div class="admin-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <form method="GET" action="{{ route('admin.notifications.index') }}" class="d-flex align-items-center gap-2">
                <div class="input-group search-input-group" style="width: 280px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fa-solid fa-magnifying-glass text-muted"></i>
                    </span>
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control border-start-0" 
                        placeholder="Search by notification name"
                        value="{{ request('search') }}" 
                    >
                </div>

                <label class="mb-0">Receiver Type:</label>
                <select name="receiver_type" class="form-select" style="width: 150px;">
                    <option value="all" {{ request('receiver_type') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="user" {{ request('receiver_type') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="shop" {{ request('receiver_type') == 'shop' ? 'selected' : '' }}>Shop</option>
                    <option value="admin" {{ request('receiver_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="employee" {{ request('receiver_type') == 'employee' ? 'selected' : '' }}>Employee</option>
                </select>

                <select name="status" class="form-select" style="width: 150px;">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        <a href="{{ route('admin.notifications.create') }}" class="btn btn-admin-primary">Add Notification</a>
    </div>
</div>

<div class="table-responsive admin-table-container">
    <table class="table align-middle mb-0 admin-table">
        <thead class="admin-table-thead">
            <tr>
                <th style="width: 40px; padding-left: 16px;"><input type="checkbox"></th>
                <th style="padding-left: 8px;">TITLE</th>
                <th>CONTENT</th>
                <th>SENDER</th>
                <th>RECEIVER TYPE</th>
                <th>PRIORITY</th>
                <th>TYPE</th>
                <th>STATUS</th>
                <th>CREATED AT</th>
                <th>UPDATED AT</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notifications as $notification)
                <tr>
                    <td style="padding-left: 16px;"><input type="checkbox"></td>
                    <td style="padding-left: 8px;">
                        <div class="d-flex align-items-center">
                            <span>{{ $notification['title'] }}</span>
                        </div>
                    </td>
                    <td>{{ $notification['content'] }}</td>
                    <td>{{ $notification['sender']?->username ?? 'N/A' }}</td>
                    <td>{{ $notification['receiver_type'] }}</td>
                    <td><span class="badge rounded-pill badge-admin badge-active">{{ $notification['priority'] }}</span></td>
                    <td>{{ $notification['type'] }}</td>
                    <td><span class="badge rounded-pill badge-admin badge-active">{{ $notification['status'] }}</span></td>
                    <td>{{ $notification['created_at'] }}</td>
                    <td>{{ $notification['updated_at'] }}</td>
                    <td>
                        <a href="{{ route('admin.notifications.edit', $notification['id']) }}" class="btn btn-admin-primary">Edit</a>
                        <a href="{ route('admin.notifications.destroy', $notification['id']) }}" class="btn btn-admin-danger" onclick="return confirm('Are you sure you want to delete this notification?')">Delete</a>
                    </td>
                </tr>
                <tr>
                    
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Link phân trang -->
<div class="mt-4">
    {{ $notifications->links() }}
</div>

@push('scripts')
    <script>
        document.querySelectorAll('.receiver-ids').forEach(function(element) {
            element.addEventListener('click', function() {
                if (this.style.whiteSpace === 'normal') {
                    this.style.whiteSpace = 'nowrap';
                    this.style.textOverflow = 'ellipsis';
                } else {
                    this.style.whiteSpace = 'normal';
                    this.style.textOverflow = 'inherit';
                }
            });
        });
    </script>
@endpush
@endsection