@extends('layouts.admin')

@section('title', 'LaptopHub - Create Inventory Item')
@section('active_nav', 'inventory')
@section('page_title', 'Create Inventory Item')
@section('page_subtitle', 'Add a new stocked item')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-form.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Inventory
    </a>
@endsection

@section('admin_content')
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <div class="card-header">
            <h5>Inventory Item Information</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.inventory.store') }}" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label class="form-label" for="name">Item Name</label>
                    <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="category_id">Category</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" @selected(old('category_id') == $category->category_id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="brand_id">Brand</label>
                    <select id="brand_id" name="brand_id" class="form-select" required>
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
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
                    <label class="form-label" for="image_url">Image URL</label>
                    <input id="image_url" name="image_url" type="url" class="form-control" value="{{ old('image_url') }}" placeholder="https://example.com/item.jpg">
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
                        <label class="form-check-label" for="is_archived">Archive this item</label>
                    </div>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark">Save Item</button>
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
