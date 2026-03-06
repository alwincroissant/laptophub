@extends('layouts.admin')

@section('title', 'LaptopHub - Order Details')
@section('active_nav', 'order')
@section('page_title', 'Order #'.$order->order_id)
@section('page_subtitle', 'Inspect items, shipping details, and update fulfillment status')

@section('admin_styles')
    <link href="{{ asset('css/admin-order.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.order.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Orders</a>
@endsection

@section('admin_content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-4">
            <div class="detail-card h-100">
                <h6 class="mb-3">Order Summary</h6>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Order ID</span><strong class="mono-id">#{{ $order->order_id }}</strong></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Placed</span><span>{{ optional($order->placed_at)->format('M d, Y h:i A') ?? 'N/A' }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Payment</span><span>{{ $order->paymentMethod->method_name ?? 'N/A' }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal</span><strong>₱{{ number_format($subtotal, 2) }}</strong></div>
                <div class="d-flex justify-content-between mb-2"><span class="text-muted">Shipping</span><strong>₱{{ number_format($shipping, 2) }}</strong></div>
                <hr>
                <div class="d-flex justify-content-between"><span>Total</span><strong>₱{{ number_format($total, 2) }}</strong></div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="detail-card h-100">
                <h6 class="mb-3">Customer</h6>
                <div><strong>{{ $order->user->full_name ?? 'Unknown Customer' }}</strong></div>
                <div class="text-muted">{{ $order->user->email ?? 'No email' }}</div>
                <div class="text-muted">{{ $order->user->contact_number ?? 'No contact number' }}</div>
                <hr>
                <h6 class="mb-2">Shipping Address</h6>
                <div class="small">{{ $order->shipping_address }}</div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="detail-card h-100">
                <h6 class="mb-3">Update Status</h6>
                <form method="POST" action="{{ route('admin.order.update-status', $order->order_id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-2">
                        <label class="form-label">Current Status</label>
                        <input type="text" class="form-control" value="{{ $order->status->status_name ?? 'Unknown' }}" disabled>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">New Status</label>
                        <select class="form-select" name="status_id" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status->status_id }}" {{ (int) $order->status_id === (int) $status->status_id ? 'selected' : '' }}>{{ $status->status_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Note (optional)</label>
                        <textarea class="form-control" name="note" rows="3" maxlength="255" placeholder="Reason or context for status update..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-dark w-100">Save Status</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="table-card">
                <div class="card-header">
                    <h5>Order Items</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Line Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                                <td class="text-end">₱{{ number_format((float) $item->unit_price, 2) }}</td>
                                <td class="text-end">{{ number_format((int) $item->quantity) }}</td>
                                <td class="text-end">₱{{ number_format((float) $item->unit_price * (int) $item->quantity, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No order items found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="detail-card">
                <h6 class="mb-3">Status History</h6>
                @if($statusLogs->isEmpty())
                    <p class="text-muted mb-0">No status history logs yet.</p>
                @else
                    <div class="d-grid gap-2">
                        @foreach($statusLogs as $log)
                            <div class="border rounded p-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>{{ $log->status->status_name ?? 'Unknown' }}</strong>
                                    <span class="note-text">{{ optional($log->changed_at)->format('M d, Y h:i A') ?? 'N/A' }}</span>
                                </div>
                                <div class="note-text">By: {{ $log->changedBy->full_name ?? 'System' }}</div>
                                @if($log->note)
                                    <div class="small mt-1">{{ $log->note }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
