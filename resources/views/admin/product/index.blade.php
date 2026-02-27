<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaptopHub — Product Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <link href="{{ asset('css/admin-product-index.css') }}" rel="stylesheet"/>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="wordmark">LaptopHub <span class="badge-admin">Admin</span></div>
        <div class="mt-1" style="font-size:.75rem;color:rgba(255,255,255,.4)">Management Console</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>

        <div class="nav-section-label">Catalog</div>
        <a href="{{ route('admin.product.index') }}" class="nav-link active"><i class="bi bi-laptop"></i> Products</a>
        <a href="#" class="nav-link"><i class="bi bi-tags"></i> Categories</a>
        <a href="#" class="nav-link"><i class="bi bi-award"></i> Brands</a>

        <div class="nav-section-label">Commerce</div>
        <a href="#" class="nav-link"><i class="bi bi-bag-check"></i> Orders</a>
        <a href="#" class="nav-link"><i class="bi bi-cart3"></i> Carts</a>
        <a href="#" class="nav-link"><i class="bi bi-star-half"></i> Reviews</a>

        <div class="nav-section-label">Operations</div>
        <a href="#" class="nav-link"><i class="bi bi-box-seam"></i> Inventory</a>
        <a href="#" class="nav-link"><i class="bi bi-truck"></i> Suppliers</a>
        <a href="#" class="nav-link"><i class="bi bi-arrow-repeat"></i> Restock Log</a>

        <div class="nav-section-label">Users</div>
        <a href="#" class="nav-link"><i class="bi bi-people"></i> All Users</a>
        <a href="#" class="nav-link"><i class="bi bi-shield-lock"></i> Roles</a>

        <div class="nav-section-label">System</div>
        <a href="#" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
    </nav>

    <div class="sidebar-footer d-flex align-items-center gap-2">
        <div class="avatar">AD</div>
        <div>
            <div style="color:#fff;font-weight:500;font-size:.8rem">Admin User</div>
            <div>admin@laptophub.ph</div>
        </div>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div>
            <h1>Products</h1>
            <p class="sub">Catalog overview, stock health, and archive status</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.product.create') }}" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Product</a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
        </div>
    </div>

    <div class="content">
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
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category_name ?? '—' }}</td>
                        <td>{{ $product->brand_name ?? '—' }}</td>
                        <td class="text-end">₱{{ number_format((float) $product->price, 2) }}</td>
                        <td class="text-end">{{ number_format($product->stock_qty) }}</td>
                        <td class="text-end">{{ number_format($product->low_stock_threshold) }}</td>
                        <td>
                            @if ($product->is_archived)
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
                                <a href="{{ route('admin.product.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form method="POST" action="{{ route('admin.product.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="empty-state">No products found for this filter.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="pagination-wrap">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
</div>
</body>
</html>
