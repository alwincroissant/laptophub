@extends('layouts.admin')

@section('title', 'LaptopHub — Product Dashboard')
@section('active_nav', 'product')
@section('page_title', 'Products')
@section('page_subtitle', 'Catalog overview, stock health, and archive status')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-index.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css"/>
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

        <div class="filter-card mb-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="mb-0">Product Import</h5>
                <small class="text-muted">Upload an Excel file to bulk import products</small>
            </div>
            <form method="POST" enctype="multipart/form-data" action="{{ route('admin.product.import') }}" class="d-flex gap-2">
                @csrf
                <input type="file" name="item_upload" class="form-control" accept=".xlsx,.xls,.csv" required>
                <button type="submit" class="btn btn-info text-white text-nowrap"><i class="bi bi-file-earmark-excel me-1"></i>Import Excel File</button>
            </form>
        </div>

    <div class="table-card">
        <div class="card-header">
            <h5>Product List</h5>
            <span class="text-muted" style="font-size:.78rem">{{ number_format($products->count()) }} total results</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" id="productsTable">
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

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#productsTable').DataTable({
                "pageLength": 12,
                "order": [] 
            });
        });
    </script>
@endpush
@endsection
