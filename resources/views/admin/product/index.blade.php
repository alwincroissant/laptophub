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
                <button type="submit" class="btn btn-info text-white text-nowrap"><i class="bi bi-file-earmark-excel me-1"></i>Import Products</button>
            </form>
        </div>

        <div class="filter-card mb-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="mb-0">Product Images Import</h5>
                <small class="text-muted">Upload an Excel file to bulk import product images</small>
            </div>
            <form method="POST" enctype="multipart/form-data" action="{{ route('admin.product.images.import') }}" class="d-flex gap-2">
                @csrf
                <input type="file" name="images_upload" class="form-control" accept=".xlsx,.xls,.csv" required>
                <button type="submit" class="btn btn-primary text-white text-nowrap"><i class="bi bi-images me-1"></i>Import Images</button>
            </form>
        </div>

    <div class="table-card">
        <div class="card-header">
            <h5>Product List</h5>
        </div>
        <div class="table-responsive">
            {!! $dataTable->table(['class' => 'table table-hover mb-0 align-middle w-100', 'id' => 'productsTable']) !!}
        </div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            var categoryFilter = $('<select class="form-select form-select-sm ms-2 d-inline-block w-auto"><option value="">All Categories</option>@foreach($categories as $category)<option value="{{ $category->name }}">{{ $category->name }}</option>@endforeach</select>');
            
            var brandFilter = $('<select class="form-select form-select-sm ms-2 d-inline-block w-auto"><option value="">All Brands</option>@foreach($brands as $brand)<option value="{{ $brand->name }}">{{ $brand->name }}</option>@endforeach</select>');

            setTimeout(function() {
                $('.dataTables_filter').addClass('d-flex justify-content-end align-items-center mb-3');
                $('.dataTables_filter label').addClass('mb-0 me-2');
                $('.dataTables_filter').append(categoryFilter).append(brandFilter);

                var table = window.LaravelDataTables["productsTable"];

                categoryFilter.on('change', function() {
                    table.column(3).search($(this).val()).draw();
                });

                brandFilter.on('change', function() {
                    table.column(4).search($(this).val()).draw();
                });
            }, 300);
        });
    </script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}
@endpush
@endsection
