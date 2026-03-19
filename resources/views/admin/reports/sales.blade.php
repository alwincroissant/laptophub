@extends('layouts.admin')

@section('title', 'LaptopHub - Sales Report')
@section('active_nav', 'sales_report')
@section('page_title', 'Sales Report')
@section('page_subtitle', 'Overview of all delivered orders and calculated revenue')

@section('admin_content')
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body bg-light">
        <form action="{{ route('admin.reports.sales') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="start_date" class="form-label" style="font-weight:600; font-size:.85rem">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate ?? '' }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label" style="font-weight:600; font-size:.85rem">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate ?? '' }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-dark w-100"><i class="bi bi-funnel"></i> Filter Report</button>
            </div>
        </form>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12">
        <div class="card text-white shadow-lg border-0" style="background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%);">
            <div class="card-body p-5 position-relative overflow-hidden">
                <i class="bi bi-cash-coin position-absolute text-white" style="font-size: 8rem; right: -20px; top: -10px; opacity: 0.1; transform: rotate(-15deg);"></i>
                <div class="position-relative z-index-1">
                    <h6 class="text-uppercase mb-2" style="color: rgba(255,255,255,0.8); letter-spacing: 1.5px; font-weight: 600;">Total Period Revenue</h6>
                    <h1 class="display-4 fw-bolder mb-0 text-white shadow-sm">P{{ number_format($totalRevenue, 2) }}</h1>
                    @if($startDate || $endDate)
                    <p class="mt-3 mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.9rem; font-weight: 500;">
                        <i class="bi bi-calendar3 me-1"></i> {{ $startDate ? date('M j, Y', strtotime($startDate)) : 'All Time' }} &mdash; {{ $endDate ? date('M j, Y', strtotime($endDate)) : 'Present' }}
                    </p>
                    @else
                    <p class="mt-3 mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.9rem; font-weight: 500;">
                        <i class="bi bi-globe me-1"></i> Revenue from all completed sales
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-bottom">
        <h5 class="mb-0" style="font-weight:600">Sales History</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-nowrap">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Items</th>
                    <th class="text-end">Order Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                @php
                    $itemsCount = $order->items->sum('quantity');
                    $value = $order->items->sum(function($item) {
                        return $item->unit_price * $item->quantity;
                    });
                @endphp
                <tr>
                    <td style="font-size: .85rem; color: #555;">{{ \Carbon\Carbon::parse($order->placed_at)->format('M j, Y H:i') }}</td>
                    <td><strong>#{{ $order->order_id }}</strong></td>
                    <td>{{ $order->user->full_name ?? 'Unknown Customer' }}</td>
                    <td>{{ number_format($itemsCount) }} Items</td>
                    <td class="text-end" style="color:var(--accent2); font-weight:600">P{{ number_format($value, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">No sales data found for the given criteria.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
