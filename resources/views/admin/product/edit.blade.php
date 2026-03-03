@extends('layouts.admin')

@section('title', 'LaptopHub — Edit Product')
@section('active_nav', 'product')
@section('page_title', 'Edit Product')
@section('page_subtitle', 'Update product details in catalog.')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-form.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Products
    </a>
@endsection

@section('admin_content')
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
                <form method="POST" action="{{ route('admin.product.update', $product) }}" class="row g-3" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label class="form-label" for="name">Product Name</label>
                        <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="category_id">Category</label>
                        <select id="category_id" name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->category_id }}" @selected(old('category_id', $product->category_id) == $category->category_id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="brand_id">Brand</label>
                        <select id="brand_id" name="brand_id" class="form-select" required>
                            <option value="">Select Brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->brand_id }}" @selected(old('brand_id', $product->brand_id) == $brand->brand_id)>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="price">Price</label>
                        <input id="price" name="price" type="number" step="0.01" min="0" class="form-control" value="{{ old('price', $product->price) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="stock_qty">Stock Quantity</label>
                        <input id="stock_qty" name="stock_qty" type="number" min="0" class="form-control" value="{{ old('stock_qty', $product->stock_qty) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="low_stock_threshold">Low Stock Threshold</label>
                        <input id="low_stock_threshold" name="low_stock_threshold" type="number" min="0" class="form-control" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="image">Product Image</label>
                        <input id="image" name="image" type="file" class="form-control" accept="image/png,image/jpeg,image/webp">
                        <small class="text-muted d-block">Optional. Upload a file only if you want to replace the current image.</small>
                        @if ($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-thumbnail mt-2" style="max-height:120px;">
                        @endif
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="compatibility">Compatibility</label>
                        <textarea id="compatibility" name="compatibility" class="form-control" rows="2">{{ old('compatibility', $product->compatibility) }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_archived" name="is_archived" value="1" @checked(old('is_archived', $product->is_archived))>
                            <label class="form-check-label" for="is_archived">Archive this product</label>
                        </div>
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-dark">Update Product</button>
                        <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
@endsection
