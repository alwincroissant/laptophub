@extends('layouts.admin')

@section('title', 'LaptopHub - Role Management')
@section('active_nav', 'role')
@section('page_title', 'Roles')
@section('page_subtitle', 'Manage system roles and access groups')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-index.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.role.create') }}" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Role</a>
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
        <form method="GET" action="{{ route('admin.role.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-10">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Role name">
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button class="btn btn-dark w-100" type="submit">Apply</button>
            </div>
        </form>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h5>Role List</h5>
            <span class="text-muted" style="font-size:.78rem">{{ number_format($roles->total()) }} total results</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Role Name</th>
                    <th class="text-end">Users Assigned</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($roles as $role)
                    @php
                        $isProtected = in_array($role->role_name, $protectedRoles, true);
                    @endphp
                    <tr>
                        <td>#{{ $role->role_id }}</td>
                        <td>{{ $role->role_name }}</td>
                        <td class="text-end">{{ number_format((int) $role->users_count) }}</td>
                        <td>
                            @if ($isProtected)
                                <span class="status-badge badge-active">System</span>
                            @else
                                <span class="status-badge badge-archived">Custom</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.role.edit', $role) }}" class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.role.destroy', $role) }}" onsubmit="return confirm('Delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" aria-label="Delete" {{ $isProtected || $role->users_count > 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">No roles found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($roles->hasPages())
            <div class="pagination-wrap">
                {{ $roles->links() }}
            </div>
        @endif
    </div>
@endsection
