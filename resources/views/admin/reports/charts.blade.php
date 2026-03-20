@extends('layouts.admin')

@section('title', 'LaptopHub — Analytics Charts')
@section('active_nav', 'reports.charts')
@section('page_title', 'Analytics Charts')
@section('page_subtitle', 'Visual sales and product data.')

@section('admin_content')

<div class="row mb-4">
    <div class="col-12">
        <div class="form-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reports.charts') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label text-muted small text-uppercase">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small text-uppercase">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-dark w-100">Apply Date Range</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MP7 Requirement: sales bar chart with date range (date picker) -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="mb-0">Sales Revenue ({{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }})</h5>
                <p class="text-muted small mb-0 mt-1">Total Period Revenue: ₱{{ number_format($totalRangeRevenue, 2) }}</p>
            </div>
            <div class="card-body" style="height: 350px;">
                {!! $barChart->container() !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- MP7 Requirement: A pie chart showing the percentage of total sales contributed by each product -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="mb-0">Product Sales Contribution</h5>
                <p class="text-muted small mb-0 mt-1">Percentage of total sales by product</p>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center" style="height: 350px;">
                {!! $pieChart->container() !!}
            </div>
        </div>
    </div>

    <!-- MP7 Requirement: charts yearly sales -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Yearly Sales ({{ $selectedYear }})</h5>
                    <p class="text-muted small mb-0 mt-1">Monthly revenue breakdown</p>
                </div>
                <!-- Independent Yearly Form -->
                <form method="GET" action="{{ route('admin.reports.charts') }}" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <input type="number" name="year" min="2020" max="2050" step="1" class="form-control form-control-sm text-center" style="max-width: 90px;" value="{{ $selectedYear }}" required>
                    <button type="submit" class="btn btn-outline-primary btn-sm">Set Year</button>
                </form>
            </div>
            <div class="card-body" style="height: 350px;">
                {!! $yearlyChart->container() !!}
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Load Chart.js natively required by ConsoleTVs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>

{!! $barChart->script() !!}
{!! $pieChart->script() !!}
{!! $yearlyChart->script() !!}
@endpush
