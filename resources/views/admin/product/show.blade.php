@extends('layouts.admin')

@section('title', 'LaptopHub — Product Info')
@section('active_nav', 'product')
@section('page_title', 'Product Information')
@section('page_subtitle', 'View complete details of this product.')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-form.css') }}" rel="stylesheet"/>
    <style>
        /* ── CSS GALLERY ── */
        .css-gallery { width: 100%; margin-bottom: 1rem; }
        .gallery-main {
            position: relative; width: 100%; padding-bottom: 100%;
            background: #f8f9fa; border-radius: 8px; overflow: hidden;
            border: 1px solid #dee2e6;
        }
        .gallery-main img.main-img {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            object-fit: contain; opacity: 0; transition: opacity 0.3s ease; z-index: 1;
            background: #fff;
        }
        .gallery-main .fallback {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            font-size: 3.5rem; color: #6c757d;
        }
        .gallery-radio { display: none; }

        @for ($i = 0; $i < 20; $i++)
            #img-{{ $i }}:checked ~ .gallery-main #main-img-{{ $i }} { opacity: 1; z-index: 2; }
            #img-{{ $i }}:checked ~ .gallery-thumbs [for="img-{{ $i }}"] {
                opacity: 1; border-color: #0d6efd; box-shadow: 0 0 0 1.5px #0d6efd;
            }
        @endfor

        .gallery-thumbs {
            display: flex; gap: .75rem; overflow-x: auto; padding-bottom: .5rem; margin-top: 1rem;
        }
        .gallery-thumbs::-webkit-scrollbar { display: none; }
        .gallery-thumbs { -ms-overflow-style: none; scrollbar-width: none; }
        .gallery-thumbs .thumb-label {
            cursor: pointer; display: block; width: 80px; height: 80px;
            flex-shrink: 0; border: 1px solid #dee2e6; border-radius: 6px;
            overflow: hidden; opacity: 0.6; transition: opacity 0.2s, border-color 0.2s;
            background: #fff;
        }
        .gallery-thumbs .thumb-label img { width: 100%; height: 100%; object-fit: cover; }
        .gallery-thumbs .thumb-label:hover { opacity: .9; }
    </style>
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
                    @php
                        $allImages = collect();
                        if ($product->image_url) $allImages->push($product->image_url);
                        if ($product->images) {
                            foreach($product->images as $img) $allImages->push($img->image_url);
                        }
                    @endphp

                    <div class="css-gallery mb-0">
                        @if($allImages->isNotEmpty())
                            @foreach($allImages as $index => $imgUrl)
                                <input type="radio" name="gallery" id="img-{{ $index }}" class="gallery-radio" {{ $index === 0 ? 'checked' : '' }}>
                            @endforeach

                            <div class="gallery-main">
                                @foreach($allImages as $index => $imgUrl)
                                    <img src="{{ $imgUrl }}" class="main-img" id="main-img-{{ $index }}" alt="{{ $product->name }}">
                                @endforeach
                            </div>

                            @if($allImages->count() > 1)
                                <div class="gallery-thumbs">
                                    @foreach($allImages as $index => $imgUrl)
                                        <label for="img-{{ $index }}" class="thumb-label">
                                            <img src="{{ $imgUrl }}" alt="Thumbnail {{ $index }}">
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="gallery-main" style="margin-bottom:0">
                                <div class="fallback"><i class="bi bi-image"></i></div>
                            </div>
                        @endif
                    </div>
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
        @endif
    </div>
@endsection
