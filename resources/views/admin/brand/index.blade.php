@extends('layouts.admin')

@section('title', 'LaptopHub — Brands')
@section('active_nav', 'brand')
@section('page_title', 'Brands')
@section('page_subtitle', 'Manage brand list with soft delete and recovery.')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-index.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.brand.create') }}" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Brand</a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
@endsection

@section('admin_content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

        <div class="filter-card mb-3">
            <form method="GET" action="{{ route('admin.brand.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-7">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Brand name">
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
                <h5>Brand List</h5>
                <span class="text-muted" style="font-size:.78rem">{{ number_format($brands->total()) }} total results</span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($brands as $brand)
                        <tr>
                            <td>#{{ $brand->brand_id }}</td>
                            <td>{{ $brand->name }}</td>
                            <td>{{ $brand->description ?: '—' }}</td>
                            <td>
                                @if ($brand->deleted_at)
                                    <span class="status-badge badge-archived">Trashed</span>
                                @elseif ($brand->is_active)
                                    <span class="status-badge badge-active">Active</span>
                                @else
                                    <span class="status-badge badge-low">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    @if ($brand->deleted_at)
                                        <form method="POST" action="{{ route('admin.brand.restore', $brand->brand_id) }}" onsubmit="return confirm('Recover this brand?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Recover" aria-label="Recover">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.brand.edit', $brand) }}" class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.brand.destroy', $brand) }}" onsubmit="return confirm('Soft delete this brand?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Soft Delete" aria-label="Soft Delete">
                                                <i class="bi bi-archive"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.brand.force-destroy', $brand->brand_id) }}" onsubmit="return confirm('Delete permanently?')">
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
                            <td colspan="5" class="empty-state">No brands found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if ($brands->hasPages())
                <div class="pagination-wrap">
                    {{ $brands->links() }}
                </div>
            @endif
        </div>
@endsection
