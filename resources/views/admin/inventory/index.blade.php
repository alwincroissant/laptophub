@extends('layouts.admin')

@section('title', 'LaptopHub - Inventory Dashboard')
@section('active_nav', 'inventory')
@section('page_title', 'Inventory')
@section('page_subtitle', 'Manage stocked items and monitor stock levels')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-index.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.inventory.create') }}" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Inventory Item</a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
@endsection

@section('admin_content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card red">
                <i class="bi bi-box-seam icon"></i>
                <div class="label">Total Items</div>
                <div class="value">{{ number_format($metrics['total']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card green">
                <i class="bi bi-check2-circle icon"></i>
                <div class="label">In Stock</div>
                <div class="value">{{ number_format($metrics['inStock']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card gold">
                <i class="bi bi-exclamation-circle icon"></i>
                <div class="label">Low Stock</div>
                <div class="value">{{ number_format($metrics['lowStock']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card blue">
                <i class="bi bi-x-octagon icon"></i>
                <div class="label">Out of Stock</div>
                <div class="value">{{ number_format($metrics['outOfStock']) }}</div>
            </div>
        </div>
    </div>

    <div class="table-card mt-3">
        <div class="card-header">
            <h5>Inventory Items</h5>
        </div>
        <div class="table-responsive">
            {!! $dataTable->table(['class' => 'table table-hover mb-0 align-middle w-100', 'id' => 'inventoryTable']) !!}
        </div>
    </div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function() {
            var statusFilter = $('<select class="form-select form-select-sm ms-2 d-inline-block w-auto"><option value="all">All</option><option value="active">Active</option><option value="low-stock">Low Stock</option><option value="out-of-stock">Out of Stock</option><option value="archived">Archived</option></select>');
            
            var currentStatus = '{{ $status }}';
            statusFilter.val(currentStatus);

            setTimeout(function() {
                $('.dataTables_filter').addClass('d-flex justify-content-end align-items-center mb-3');
                $('.dataTables_filter label').addClass('mb-0 me-2');
                $('.dataTables_filter').append(statusFilter);

                var table = window.LaravelDataTables["inventoryTable"];

                statusFilter.on('change', function() {
                    window.location.href = "{{ route('admin.inventory.index') }}?status=" + $(this).val();
                });
            }, 300);
        });
    </script>
@endpush
@endsection
