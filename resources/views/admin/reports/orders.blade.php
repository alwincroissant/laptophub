@extends('layouts.admin')

@section('title', 'LaptopHub - Customer Order Summary')
@section('active_nav', 'order_summary')
@section('page_title', 'Order Summary Report')
@section('page_subtitle', 'System-wide categorization of fulfillment statuses')

@section('admin_content')
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body bg-light">
        <form action="{{ route('admin.reports.orders') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="start_date" class="form-label" style="font-weight:600; font-size:.85rem">From Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate ?? '' }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label" style="font-weight:600; font-size:.85rem">To Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate ?? '' }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100"><i class="bi bi-funnel me-1"></i> Check Summary</button>
                <a href="{{ route('admin.reports.orders') }}" class="btn btn-outline-secondary px-3" title="Clear Filters"><i class="bi bi-x"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    @php
        $totalSystemOrders = $summary->sum('total_orders');
    @endphp

    <div class="col-12">
        <div class="card shadow-sm border-0 bg-white">
            <div class="card-body p-4 text-center">
                <i class="bi bi-box-seam display-4 mb-3 d-block" style="color:var(--accent2)"></i>
                <h1 class="display-3 fw-bold mb-1">{{ number_format($totalSystemOrders) }}</h1>
                <p class="text-uppercase fw-semibold text-muted tracking-wide mb-0">Total Lifetime Orders</p>
            </div>
        </div>
    </div>

    @foreach($summary as $stat)
        @php
            $percentage = $totalSystemOrders > 0 ? ($stat->total_orders / $totalSystemOrders) * 100 : 0;
            $statusName = strtolower($stat->status_name);
            $barColor = match ($statusName) {
                'delivered' => '#2f9c5a',
                'shipped' => 'var(--accent2)',
                'processing' => '#c89a2f',
                'pending' => '#c89a2f',
                'cancelled' => 'var(--accent)',
                default => '#7a7670',
            };
        @endphp
        <div class="col-12 col-md-4 col-xl">
            <div class="card shadow-sm border-0 h-100" style="border-bottom: 4px solid {{ $barColor }} !important;">
                <div class="card-body">
                    <h6 class="text-uppercase mb-3" style="font-weight: 700; color:#555">{{ $stat->status_name }}</h6>
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <h2 class="mb-0 fw-bold">{{ number_format($stat->total_orders) }}</h2>
                        <span style="font-size: .9rem; color: #888;">{{ number_format($percentage, 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h5 class="mb-0" style="font-weight:600">Recent Customer Orders</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-nowrap">
            <thead class="table-light">
                <tr>
                    <th>Order #</th>
                    <th>Date Placed</th>
                    <th>Customer Name</th>
                    <th>Current Status</th>
                    <th class="text-end">Units</th>
                    <th class="text-end">Total Base Cart</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allOrders as $order)
                @php
                    $statusName = strtolower($order->status->status_name ?? 'unknown');
                    $badgeColor = match ($statusName) {
                        'delivered' => 'bg-success',
                        'shipped' => 'bg-info text-dark',
                        'processing' => 'bg-warning text-dark',
                        'cancelled' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                    $itemsCount = $order->items->sum('quantity');
                    $subtotal = $order->items->sum(function($item) {
                        return $item->unit_price * $item->quantity;
                    });
                    $shipping = $order->shipping_fee ?? 0;
                    $taxRate = $order->tax_rate ?? 0;
                    $taxAmount = $subtotal * ($taxRate / 100);
                    $value = $subtotal + $shipping + $taxAmount;
                @endphp
                <tr>
                    <td><strong>{{ $order->order_id }}</strong></td>
                    <td style="font-size: .85rem; color: #555;">{{ \Carbon\Carbon::parse($order->placed_at)->format('M j, Y H:i') }}</td>
                    <td>{{ $order->user->full_name ?? 'System Guest' }}</td>
                    <td><span class="badge {{ $badgeColor }}">{{ $order->status->status_name ?? 'N/A' }}</span></td>
                    <td class="text-end fw-bold">{{ number_format($itemsCount) }}</td>
                    <td class="text-end" style="color:var(--accent2); font-weight:600">P{{ number_format($value, 2) }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.order.show', $order->order_id) }}" class="btn btn-sm btn-outline-dark" style="font-size:.75rem">View File</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">Awaiting customer payloads.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($allOrders->hasPages())
    <div class="card-footer bg-white pt-4 pb-2">
        {{ $allOrders->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
