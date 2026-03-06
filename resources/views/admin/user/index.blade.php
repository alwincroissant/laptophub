@extends('layouts.admin')

@section('title', 'LaptopHub - User Management')
@section('active_nav', 'user')
@section('page_title', 'Users')
@section('page_subtitle', 'Manage user accounts, roles, and access status')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-index.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.user.create') }}" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg me-1"></i>Add User</a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
@endsection

@section('admin_content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="filter-card mb-3">
        <form method="GET" action="{{ route('admin.user.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Name, email, or contact number">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label">Role</label>
                <select name="role_id" class="form-select">
                    <option value="0">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->role_id }}" {{ $roleId === (int) $role->role_id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button class="btn btn-dark w-100" type="submit">Apply</button>
            </div>
        </form>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h5>User List</h5>
            <span class="text-muted" style="font-size:.78rem">{{ number_format($users->total()) }} total results</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>#{{ $user->user_id }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->role_name ?? 'N/A' }}</td>
                        <td>{{ $user->contact_number ?: '-' }}</td>
                        <td>
                            @if ($user->is_active)
                                <span class="status-badge badge-active">Active</span>
                            @else
                                <span class="status-badge badge-archived">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.user.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                @if ($user->is_active)
                                    <form method="POST" action="{{ route('admin.user.deactivate', $user) }}" onsubmit="return confirm('Deactivate this user account?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Deactivate" aria-label="Deactivate">
                                            <i class="bi bi-person-dash"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.user.activate', $user) }}" onsubmit="return confirm('Activate this user account?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Activate" aria-label="Activate">
                                            <i class="bi bi-person-check"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">No users found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="pagination-wrap">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
