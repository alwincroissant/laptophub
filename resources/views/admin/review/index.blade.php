@extends('layouts.admin')

@section('title', 'LaptopHub - Review Moderation')
@section('active_nav', 'review')
@section('page_title', 'Reviews')
@section('page_subtitle', 'Moderate customer reviews and visibility')

@section('admin_content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card red">
                <i class="bi bi-chat-square-text icon"></i>
                <div class="label">Total Reviews</div>
                <div class="value">{{ number_format($metrics['total']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card green">
                <i class="bi bi-eye icon"></i>
                <div class="label">Visible</div>
                <div class="value">{{ number_format($metrics['shown']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card blue">
                <i class="bi bi-eye-slash icon"></i>
                <div class="label">Hidden</div>
                <div class="value">{{ number_format($metrics['hidden']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card gold">
                <i class="bi bi-star-fill icon"></i>
                <div class="label">Avg Rating</div>
                <div class="value">{{ number_format($metrics['avg_rating'], 1) }}</div>
            </div>
        </div>
    </div>



    <div class="table-card">
        <div class="card-header">
            <h5>Review List</h5>
        </div>
        <div class="table-responsive">
            {!! $dataTable->table(['class' => 'table mb-0 table-hover align-middle w-100', 'id' => 'reviewsTable']) !!}
        </div>


    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<style>
    /* Styling for the custom DataTables wrapper */
    .dataTables_wrapper .row {
        align-items: center;
        margin-bottom: 0.5rem;
        padding: 0 1rem;
    }
    .dataTables_wrapper .dataTables_paginate {
        padding: 0.5rem 1rem;
    }
    .dataTables_wrapper .dataTables_info {
        padding: 1rem;
    }
    /* Ensure action icons align properly */
    .table td { vertical-align: middle; }
    
    .status-badge {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        letter-spacing: 0.5px;
    }
    .badge-delivered { background-color: #d1fae5; color: #065f46; border: 1px solid #10b981; }
    .badge-cancelled { background-color: #fce7f3; color: #9d174d; border: 1px solid #ec4899; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function() {
            var visibilityFilter = $('<select class="form-select form-select-sm ms-2 d-inline-block w-auto"><option value="">All Visibilities</option><option value="Visible">Visible</option><option value="Hidden">Hidden</option></select>');
            var ratingFilter = $('<select class="form-select form-select-sm ms-2 d-inline-block w-auto"><option value="">All Ratings</option><option value="★★★★★">5 Stars</option><option value="★★★★☆">4 Stars</option><option value="★★★☆☆">3 Stars</option><option value="★★☆☆☆">2 Stars</option><option value="★☆☆☆☆">1 Star</option></select>');

            setTimeout(function() {
                $('.dataTables_filter').addClass('d-flex justify-content-end align-items-center mb-3');
                $('.dataTables_filter label').addClass('mb-0 me-2');
                $('.dataTables_filter').append(ratingFilter).append(visibilityFilter);

                var table = window.LaravelDataTables["reviewsTable"];

                visibilityFilter.on('change', function() {
                    table.column(5).search($(this).val()).draw();
                });

                ratingFilter.on('change', function() {
                    table.column(4).search($(this).val()).draw();
                });
            }, 300);
        });
    </script>
@endpush
