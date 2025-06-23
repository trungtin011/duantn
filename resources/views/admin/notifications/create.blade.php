@extends('layouts.admin')

@section('content')
<div class="admin-page-header">
    <h1 class="admin-page-title">Notifications</h1>
    <div class="admin-breadcrumb"><a href="{{ route('admin.dashboard') }}" class="admin-breadcrumb-link">Home</a> / Notifications</div>
</div>


<div class="table-responsive admin-table-container">
    <form action="{{ route('admin.notifications.store') }}" method="post">
        @csrf
        <input type="hidden" name="sender_id" value="1">

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title">
        </div>
        
        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" id="content" name="content"></textarea>
        </div>

        <div class="form-group">
            <label for="receiver_type">Receiver Type</label>
            <select name="receiver_type" id="receiver_type">
                <option value="user">User</option>
                <option value="shop">Shop</option>
                <option value="admin">Admin</option>
                <option value="all">All</option>
            </select>
        </div>
        <div class="form-group">
            <label for="direct_to">Direct to</label>
            <input type="text" class="form-control" id="direct_to" name="direct_to">
        </div>

        <div class="form-group">
            <label for="type">Type</label>
            <select name="type" id="type">
                <option value="promotion">Promotion</option>
                <option value="system">System News</option>
            </select>
        </div>
        <div class="form-group">
            <label for="priority">Priority</label>
            <select class="form-control" id="priority" name="priority">
                <option value="low">Low</option>
                <option value="normal">Normal</option>
                <option value="high">High</option>
            </select>
        </div>
        <button type="submit" class="btn btn-admin-primary">Create</button>
        
    </form>
</div>

@endsection

