@extends('layouts.admin')

@section('title', 'LaptopHub - Suppliers')
@section('active_nav', 'supplier')
@section('page_title', 'Suppliers')
@section('page_subtitle', 'Manage supplier records with soft delete and recovery')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-index.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.supplier.create') }}" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Supplier</a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
@endsection

@section('admin_content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-card mt-3">
        <div class="card-header">
            <h5>Supplier List</h5>
        </div>
        <div class="table-responsive">
            {!! $dataTable->table(['class' => 'table table-hover mb-0 align-middle w-100', 'id' => 'suppliersTable']) !!}
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
            var statusFilter = $('<select class="form-select form-select-sm ms-2 d-inline-block w-auto"><option value="all">All</option><option value="active">Active</option><option value="inactive">Inactive</option><option value="trashed">Trashed</option></select>');
            
            var currentStatus = '{{ $status }}';
            statusFilter.val(currentStatus);

            setTimeout(function() {
                $('.dataTables_filter').addClass('d-flex justify-content-end align-items-center mb-3');
                $('.dataTables_filter label').addClass('mb-0 me-2');
                $('.dataTables_filter').append(statusFilter);

                var table = window.LaravelDataTables["suppliersTable"];

                statusFilter.on('change', function() {
                    window.location.href = "{{ route('admin.supplier.index') }}?status=" + $(this).val();
                });
            }, 300);
        });
    </script>
@endpush
@endsection
