@extends('layouts.admin')

@section('title', 'LaptopHub - Suppliers')
@section('active_nav', 'supplier')
@section('page_title', 'Suppliers')
@section('page_subtitle', 'Manage supplier records with soft delete and recovery')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-index.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.supplier.create') }}" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Supplier</a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
@endsection

@section('admin_content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="filter-card mb-3">
        <form method="GET" action="{{ route('admin.supplier.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-7">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Supplier name, contact, email, phone">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="trashed" {{ $status === 'trashed' ? 'selected' : '' }}>Trashed</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button class="btn btn-dark w-100" type="submit">Apply</button>
            </div>
        </form>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h5>Supplier List</h5>
            <span class="text-muted" style="font-size:.78rem">{{ number_format($suppliers->total()) }} total results</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th class="text-end">Products</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($suppliers as $supplier)
                    <tr>
                        <td>#{{ $supplier->supplier_id }}</td>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->contact_name ?: '-' }}</td>
                        <td>{{ $supplier->contact_email ?: '-' }}</td>
                        <td>{{ $supplier->contact_phone ?: '-' }}</td>
                        <td class="text-end">{{ number_format((int) $supplier->products_count) }}</td>
                        <td>
                            @if ($supplier->deleted_at)
                                <span class="status-badge badge-archived">Trashed</span>
                            @elseif ($supplier->is_active)
                                <span class="status-badge badge-active">Active</span>
                            @else
                                <span class="status-badge badge-low">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @if ($supplier->deleted_at)
                                    <form method="POST" action="{{ route('admin.supplier.restore', $supplier->supplier_id) }}" onsubmit="return confirm('Recover this supplier?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Recover" aria-label="Recover">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.supplier.edit', $supplier) }}" class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.supplier.destroy', $supplier) }}" onsubmit="return confirm('Soft delete this supplier?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Soft Delete" aria-label="Soft Delete">
                                            <i class="bi bi-archive"></i>
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.supplier.force-destroy', $supplier->supplier_id) }}" onsubmit="return confirm('Delete permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" aria-label="Delete">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-state">No suppliers found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($suppliers->hasPages())
            <div class="pagination-wrap">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
@endsection
