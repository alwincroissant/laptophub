@extends('layouts.admin')

@section('title', 'LaptopHub - Orders Dashboard')
@section('active_nav', 'order')
@section('page_title', 'Orders')
@section('page_subtitle', 'Track customer purchases and order progress')

@section('admin_styles')
    <link href="{{ asset('css/admin-order.css') }}" rel="stylesheet"/>
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

    <div class="filter-card mb-3">
        <form method="GET" action="{{ route('admin.order.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-7">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Order ID, customer name, or email">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">Status</label>
                <select name="status_id" class="form-select">
                    <option value="0">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->status_id }}" {{ $statusId === (int) $status->status_id ? 'selected' : '' }}>{{ $status->status_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button class="btn btn-dark w-100" type="submit">Apply</button>
            </div>
        </form>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h5>Order List</h5>
            <span class="text-muted" style="font-size:.78rem">{{ number_format($orders->total()) }} total results</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th class="text-end">Items</th>
                    <th class="text-end">Subtotal</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Placed</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($orders as $order)
                    @php
                        $statusClass = match (strtolower((string) $order->status_name)) {
                            'pending' => 'badge-pending',
                            'processing' => 'badge-processing',
                            'shipped' => 'badge-shipped',
                            'delivered' => 'badge-delivered',
                            'cancelled' => 'badge-cancelled',
                            default => 'badge-default',
                        };
                    @endphp
                    <tr>
                        <td class="mono-id">#{{ $order->order_id }}</td>
                        <td>
                            <div>{{ $order->customer_name ?? 'Unknown Customer' }}</div>
                            <div class="text-muted" style="font-size:.76rem">{{ $order->customer_email ?? 'No email' }}</div>
                        </td>
                        <td class="text-end">{{ number_format((int) $order->item_count) }}</td>
                        <td class="text-end">₱{{ number_format((float) $order->subtotal, 2) }}</td>
                        <td>{{ $order->payment_method ?? 'N/A' }}</td>
                        <td><span class="status-badge {{ $statusClass }}">{{ $order->status_name ?? 'Unknown' }}</span></td>
                        <td>{{ optional($order->placed_at)->format('M d, Y h:i A') ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('admin.order.show', $order->order_id) }}" class="btn btn-sm btn-outline-secondary" title="View order" aria-label="View order">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No orders found for this filter.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="p-3 border-top">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
