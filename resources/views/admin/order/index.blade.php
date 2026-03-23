@extends('layouts.admin')

@section('title', 'LaptopHub - Orders Dashboard')
@section('active_nav', 'order')
@section('page_title', 'Orders')
@section('page_subtitle', 'Track customer purchases and order progress')

@section('admin_styles')
    <link href="{{ asset('css/admin-order.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
@endsection

@section('admin_content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-2">
            <div class="stat-card red">
                <i class="bi bi-receipt icon"></i>
                <div class="label">Total Orders</div>
                <div class="value">{{ number_format($metrics['total']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-2">
            <div class="stat-card gold">
                <i class="bi bi-hourglass-split icon"></i>
                <div class="label">Pending</div>
                <div class="value">{{ number_format($metrics['pending']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-2">
            <div class="stat-card blue">
                <i class="bi bi-gear-wide-connected icon"></i>
                <div class="label">Processing</div>
                <div class="value">{{ number_format($metrics['processing']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-2">
            <div class="stat-card blue">
                <i class="bi bi-truck icon"></i>
                <div class="label">Shipped</div>
                <div class="value">{{ number_format($metrics['shipped']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-2">
            <div class="stat-card green">
                <i class="bi bi-check2-circle icon"></i>
                <div class="label">Delivered</div>
                <div class="value">{{ number_format($metrics['delivered']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-2">
            <div class="stat-card red">
                <i class="bi bi-x-circle icon"></i>
                <div class="label">Cancelled</div>
                <div class="value">{{ number_format($metrics['cancelled']) }}</div>
            </div>
        </div>
    </div>

    <div class="table-card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Order List</h5>
            <div id="table-buttons"></div>
        </div>
        <div class="table-responsive">
            {!! $dataTable->table(['class' => 'table table-hover mb-0 align-middle w-100', 'id' => 'ordersTable']) !!}
        </div>
    </div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function() {
            var statusFilter = $('<select class="form-select form-select-sm ms-2 d-inline-block w-auto"><option value="0">All Statuses</option>@foreach($statuses as $status)<option value="{{ $status->status_id }}">{{ $status->status_name }}</option>@endforeach</select>');
            
            var currentStatus = '{{ $statusId }}';
            statusFilter.val(currentStatus);

            setTimeout(function() {
                $('.dataTables_filter').addClass('d-flex justify-content-end align-items-center mb-3');
                $('.dataTables_filter label').addClass('mb-0 me-2');
                $('.dataTables_filter').append(statusFilter);

                var table = window.LaravelDataTables["ordersTable"];
                table.buttons().container().appendTo('#table-buttons');

                statusFilter.on('change', function() {
                    window.location.href = "{{ route('admin.order.index') }}?status_id=" + $(this).val();
                });
            }, 300);
        });
    </script>
@endpush
@endsection
