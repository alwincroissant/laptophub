<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaptopHub — Create Product</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <link href="{{ asset('css/admin-product-form.css') }}" rel="stylesheet"/>
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
            <h1>Create Product</h1>
            <p class="sub">Add a new product to the catalog.</p>
        </div>
        <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Products
        </a>
    </div>

    <div class="content">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">
            <div class="card-header">
                <h5>Product Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.product.store') }}" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label class="form-label" for="name">Product Name</label>
                        <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="category_id">Category</label>
                        <select id="category_id" name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->category_id }}" @selected(old('category_id') == $category->category_id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="brand_id">Brand</label>
                        <select id="brand_id" name="brand_id" class="form-select" required>
                            <option value="">Select Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->brand_id }}" @selected(old('brand_id') == $brand->brand_id)>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="price">Price</label>
                        <input id="price" name="price" type="number" step="0.01" min="0" class="form-control" value="{{ old('price') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="stock_qty">Stock Quantity</label>
                        <input id="stock_qty" name="stock_qty" type="number" min="0" class="form-control" value="{{ old('stock_qty', 0) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="low_stock_threshold">Low Stock Threshold</label>
                        <input id="low_stock_threshold" name="low_stock_threshold" type="number" min="0" class="form-control" value="{{ old('low_stock_threshold', 5) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="compatibility">Compatibility</label>
                        <textarea id="compatibility" name="compatibility" class="form-control" rows="2">{{ old('compatibility') }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_archived" name="is_archived" value="1" @checked(old('is_archived'))>
                            <label class="form-check-label" for="is_archived">Archive this product</label>
                        </div>
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-dark">Save Product</button>
                        <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
