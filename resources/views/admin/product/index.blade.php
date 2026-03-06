@extends('layouts.admin')

@section('title', 'LaptopHub — Product Dashboard')
@section('active_nav', 'product')
@section('page_title', 'Products')
@section('page_subtitle', 'Catalog overview, stock health, and archive status')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-index.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.product.create') }}" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Product</a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
@endsection

@section('admin_content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card red">
                    <i class="bi bi-box-seam icon"></i>
                    <div class="label">Total Products</div>
                    <div class="value">{{ number_format($metrics['total']) }}</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card blue">
                    <i class="bi bi-check2-circle icon"></i>
                    <div class="label">Active Products</div>
                    <div class="value">{{ number_format($metrics['active']) }}</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card gold">
                    <i class="bi bi-exclamation-circle icon"></i>
                    <div class="label">Low Stock</div>
                    <div class="value">{{ number_format($metrics['lowStock']) }}</div>
                    <div class="change" style="color:var(--accent)">Needs restock attention</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card green">
                    <i class="bi bi-x-octagon icon"></i>
                    <div class="label">Out of Stock</div>
                    <div class="value">{{ number_format($metrics['outOfStock']) }}</div>
                </div>
            </div>
        </div>

        <div class="filter-card mb-3">
            <form method="GET" action="{{ route('admin.product.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-7">
                    <label class="form-label">Search</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="form-control"
                        placeholder="Product, brand, or category"
                    >
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>Archived</option>
                        <option value="trashed" {{ $status === 'trashed' ? 'selected' : '' }}>Trashed</option>
                        <option value="low-stock" {{ $status === 'low-stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out-of-stock" {{ $status === 'out-of-stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button class="btn btn-dark w-100" type="submit">Apply</button>
                </div>
            </form>
        </div>

    <div class="table-card">
        <div class="card-header">
            <h5>Product List</h5>
            <span class="text-muted" style="font-size:.78rem">{{ number_format($products->total()) }} total results</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th class="text-end">Price</th>
                    <th class="text-end">Stock</th>
                    <th class="text-end">Threshold</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($products as $product)
                    @php
                        $isLowStock = ! $product->is_archived && $product->stock_qty <= $product->low_stock_threshold;
                    @endphp
                    <tr>
                        <td>#{{ $product->product_id }}</td>
                        <td>
                            @if ($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-thumbnail" style="width:52px;height:52px;object-fit:cover;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category_name ?? '—' }}</td>
                        <td>{{ $product->brand_name ?? '—' }}</td>
                        <td class="text-end">₱{{ number_format((float) $product->price, 2) }}</td>
                        <td class="text-end">{{ number_format($product->stock_qty) }}</td>
                        <td class="text-end">{{ number_format($product->low_stock_threshold) }}</td>
                        <td>
                            @if ($product->deleted_at)
                                <span class="status-badge badge-archived">Trashed</span>
                            @elseif ($product->is_archived)
                                <span class="status-badge badge-archived">Archived</span>
                            @elseif ($product->stock_qty == 0)
                                <span class="status-badge badge-out">Out of Stock</span>
                            @elseif ($isLowStock)
                                <span class="status-badge badge-low">Low Stock</span>
                            @else
                                <span class="status-badge badge-active">Active</span>
                            @endif
                        </td>
                        <td>{{ optional($product->updated_at)->format('M d, Y h:i A') ?? '—' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.product.show', $product->product_id) }}" class="btn btn-sm btn-outline-secondary" title="View" aria-label="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if ($product->deleted_at)
                                    <form method="POST" action="{{ route('admin.product.restore', $product->product_id) }}" onsubmit="return confirm('Recover this product?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Recover" aria-label="Recover">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.product.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.product.destroy', $product) }}" onsubmit="return confirm('Soft delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Soft Delete" aria-label="Soft Delete">
                                            <i class="bi bi-archive"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="empty-state">No products found for this filter.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="pagination-wrap">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @endif
@endsection
