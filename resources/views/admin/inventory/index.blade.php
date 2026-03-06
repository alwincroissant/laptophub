@extends('layouts.admin')

@section('title', 'LaptopHub - Inventory Dashboard')
@section('active_nav', 'inventory')
@section('page_title', 'Inventory')
@section('page_subtitle', 'Manage stocked items and monitor stock levels')

@section('topbar_actions')
    <a href="{{ route('admin.inventory.create') }}" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Inventory Item</a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
@endsection

@section('admin_content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card red">
                <i class="bi bi-box-seam icon"></i>
                <div class="label">Total Items</div>
                <div class="value">{{ number_format($metrics['total']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card green">
                <i class="bi bi-check2-circle icon"></i>
                <div class="label">In Stock</div>
                <div class="value">{{ number_format($metrics['inStock']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card gold">
                <i class="bi bi-exclamation-circle icon"></i>
                <div class="label">Low Stock</div>
                <div class="value">{{ number_format($metrics['lowStock']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card blue">
                <i class="bi bi-x-octagon icon"></i>
                <div class="label">Out of Stock</div>
                <div class="value">{{ number_format($metrics['outOfStock']) }}</div>
            </div>
        </div>
    </div>

    <div class="filter-card mb-3">
        <form method="GET" action="{{ route('admin.inventory.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-7">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Item, brand, or category">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="low-stock" {{ $status === 'low-stock' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out-of-stock" {{ $status === 'out-of-stock' ? 'selected' : '' }}>Out of Stock</option>
                    <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button class="btn btn-dark w-100" type="submit">Apply</button>
            </div>
        </form>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h5>Inventory Items</h5>
            <span class="text-muted" style="font-size:.78rem">{{ number_format($items->total()) }} total results</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th class="text-end">Price</th>
                    <th class="text-end">Stock</th>
                    <th class="text-end">Threshold</th>
                    <th>Status</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $item)
                    @php
                        $isLowStock = ! $item->is_archived && (int) $item->stock_qty <= (int) $item->low_stock_threshold && (int) $item->stock_qty > 0;
                    @endphp
                    <tr>
                        <td>#{{ $item->product_id }}</td>
                        <td>
                            @if($item->image_url)
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="img-thumbnail" style="width:52px;height:52px;object-fit:cover;">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category_name ?? '-' }}</td>
                        <td>{{ $item->brand_name ?? '-' }}</td>
                        <td class="text-end">P{{ number_format((float) $item->price, 2) }}</td>
                        <td class="text-end">{{ number_format((int) $item->stock_qty) }}</td>
                        <td class="text-end">{{ number_format((int) $item->low_stock_threshold) }}</td>
                        <td>
                            @if($item->is_archived)
                                <span class="status-badge badge-archived">Archived</span>
                            @elseif((int) $item->stock_qty === 0)
                                <span class="status-badge badge-out">Out of Stock</span>
                            @elseif($isLowStock)
                                <span class="status-badge badge-low">Low Stock</span>
                            @else
                                <span class="status-badge badge-active">In Stock</span>
                            @endif
                        </td>
                        <td>{{ optional($item->updated_at)->format('M d, Y h:i A') ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.inventory.edit', $item->product_id) }}" class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.inventory.destroy', $item->product_id) }}" onsubmit="return confirm('Delete this inventory item?')">
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
                        <td colspan="11" class="empty-state">No inventory items found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="pagination-wrap">
                {{ $items->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
