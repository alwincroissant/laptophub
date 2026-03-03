@extends('layouts.admin')

@section('title', 'LaptopHub — Product Info')
@section('active_nav', 'product')
@section('page_title', 'Product Information')
@section('page_subtitle', 'View complete details of this product.')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-form.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Products
    </a>
@endsection

@section('admin_content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="form-card mb-3">
        <div class="card-header">
            <h5>{{ $product->name }}</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    @if ($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-thumbnail" style="width:100%;max-height:280px;object-fit:cover;">
                    @else
                        <div class="border rounded d-flex align-items-center justify-content-center text-muted" style="height:220px;">No image</div>
                    @endif
                </div>

                <div class="col-md-8">
                    <div class="row g-2">
                        <div class="col-6"><strong>ID:</strong> #{{ $product->product_id }}</div>
                        <div class="col-6"><strong>Brand:</strong> {{ $product->brand_name ?? '—' }}</div>
                        <div class="col-6"><strong>Category:</strong> {{ $product->category_name ?? '—' }}</div>
                        <div class="col-6"><strong>Price:</strong> ₱{{ number_format((float) $product->price, 2) }}</div>
                        <div class="col-6"><strong>Stock:</strong> {{ number_format($product->stock_qty) }}</div>
                        <div class="col-6"><strong>Low Stock Threshold:</strong> {{ number_format($product->low_stock_threshold) }}</div>
                        <div class="col-6">
                            <strong>Status:</strong>
                            @if ($product->deleted_at)
                                Trashed
                            @elseif ($product->is_archived)
                                Archived
                            @else
                                Active
                            @endif
                        </div>
                        <div class="col-6"><strong>Last Updated:</strong> {{ optional($product->updated_at)->format('M d, Y h:i A') ?? '—' }}</div>
                    </div>

                    <hr>

                    <div class="mb-2">
                        <strong>Description</strong>
                        <div class="text-muted">{{ $product->description ?: '—' }}</div>
                    </div>

                    <div>
                        <strong>Compatibility</strong>
                        <div class="text-muted">{{ $product->compatibility ?: '—' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        @if ($product->deleted_at)
            <form method="POST" action="{{ route('admin.product.restore', $product->product_id) }}" onsubmit="return confirm('Recover this product?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-success">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Recover
                </button>
            </form>
            <form method="POST" action="{{ route('admin.product.force-destroy', $product->product_id) }}" onsubmit="return confirm('Delete this product permanently? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash3 me-1"></i> Delete
                </button>
            </form>
        @else
            <a href="{{ route('admin.product.edit', $product->product_id) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </a>
            <form method="POST" action="{{ route('admin.product.destroy', $product->product_id) }}" onsubmit="return confirm('Soft delete this product?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-warning">
                    <i class="bi bi-archive me-1"></i> Soft Delete
                </button>
            </form>
            <form method="POST" action="{{ route('admin.product.force-destroy', $product->product_id) }}" onsubmit="return confirm('Delete this product permanently? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash3 me-1"></i> Delete
                </button>
            </form>
        @endif
    </div>
@endsection
