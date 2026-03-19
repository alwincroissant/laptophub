@extends('layouts.admin')

@section('title', 'LaptopHub — Edit Product')
@section('active_nav', 'product')
@section('page_title', 'Edit Product')
@section('page_subtitle', 'Update product details in catalog.')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-form.css') }}" rel="stylesheet"/>
    <style>
        .img-delete-wrapper {
            position: relative;
            display: inline-block;
        }
        .img-delete-wrapper input[type="checkbox"] {
            display: none;
        }
        .img-delete-wrapper label.delete-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            background: #dc3545;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.25);
            font-size: 16px;
            transition: all 0.2s;
            z-index: 10;
        }
        .img-delete-wrapper label.delete-btn:hover {
            background: #bb2d3b;
            transform: scale(1.1);
        }
        .img-delete-wrapper input[type="checkbox"]:checked ~ label.delete-btn {
            background: #212529;
        }
        .img-delete-wrapper input[type="checkbox"]:checked ~ label.delete-btn i::before {
            content: "\F12A"; /* bi-arrow-counterclockwise icon to undo */
        }
        .img-delete-wrapper input[type="checkbox"]:checked ~ img {
            opacity: 0.35;
            filter: grayscale(100%);
            border-color: #dc3545;
        }
        .img-delete-wrapper img {
            transition: all 0.3s ease;
        }
    </style>
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
                        <label class="form-label" for="image">Main Product Image</label>
                        <input id="image" name="image" type="file" class="form-control" accept="image/png,image/jpeg,image/webp">
                        <small class="text-muted d-block">Optional. Upload a file only if you want to replace the current main image.</small>
                        @if ($product->image_url)
                            <div class="mt-3">
                                <div class="img-delete-wrapper">
                                    <input type="checkbox" id="delete_main_image" name="delete_main_image" value="1">
                                    <label class="delete-btn" for="delete_main_image" title="Mark for deletion"><i class="bi bi-x"></i></label>
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-height:120px;">
                                </div>
                                <div class="text-muted small mt-1">Click the red X to mark image for removal.</div>
                            </div>
                        @endif
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="gallery_images">Additional Gallery Images</label>
                        <input id="gallery_images" name="gallery_images[]" type="file" multiple class="form-control" accept="image/png,image/jpeg,image/webp">
                        <small class="text-muted d-block">Optional. Select multiple files to add to the existing product image gallery.</small>
                        @if ($product->images->count() > 0)
                            <div class="mt-3 d-flex gap-3 flex-wrap">
                                @foreach($product->images as $img)
                                    <div class="img-delete-wrapper">
                                        <input type="checkbox" id="del_img_{{ $img->image_id }}" name="delete_gallery_images[]" value="{{ $img->image_id }}">
                                        <label class="delete-btn" for="del_img_{{ $img->image_id }}" title="Mark for deletion"><i class="bi bi-x"></i></label>
                                        <img src="{{ $img->image_url }}" class="img-thumbnail" style="height:80px;width:80px;object-fit:cover;">
                                    </div>
                                @endforeach
                            </div>
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
