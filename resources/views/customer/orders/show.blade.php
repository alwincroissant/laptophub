@extends('layouts.base')

@section('title', 'Order #' . $order->order_id . ' - LaptopHub')

@push('styles')
<style>
    :root {
      --ink:     #0c0c0c;
      --paper:   #f5f1ea;
      --cream:   #ede8df;
      --red:     #c0392b;
      --red-dk:  #962d22;
      --blue:    #1a3a5c;
      --gold:    #b8860b;
      --muted:   #7a756c;
      --border:  #d8d2c8;
      --white:   #ffffff;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Libre Franklin', sans-serif;
      background: var(--paper);
      color: var(--ink);
      overflow-x: hidden;
    }

    /* ── NAVBAR ── */
    .navbar {
      background: var(--ink);
      padding: 1rem 2rem;
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 200;
      border-bottom: 2px solid var(--red);
    }
    .navbar-brand {
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem;
      color: #fff !important;
      letter-spacing: -.5px;
      text-decoration: none;
    }
    .navbar-brand span { color: var(--red); }
    .nav-pill {
      display: inline-block;
      font-size: .8rem;
      font-weight: 500;
      letter-spacing: .04em;
      padding: .45rem 1.2rem;
      border-radius: 3px;
      text-decoration: none;
      transition: background .15s, color .15s;
    }
    .nav-pill.outline {
      border: 1px solid rgba(255,255,255,.3);
      color: rgba(255,255,255,.85);
    }
    .nav-pill.outline:hover { border-color: #fff; color: #fff; }
    .nav-pill.solid { background: var(--red); color: #fff; border: none; cursor: pointer; }
    .nav-pill.solid:hover { background: var(--red-dk); }

    /* ── PAGE HEADER ── */
    .page-header {
      background: var(--blue);
      color: #fff;
      padding: 3rem 0;
      margin-top: 66px;
      border-bottom: 2px solid var(--red);
    }

    .page-header h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.8rem, 4vw, 2.8rem);
      margin-bottom: .5rem;
    }

    /* ── ORDER DETAIL CARD ── */
    .detail-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 2rem;
      margin-bottom: 2rem;
    }

    .detail-title {
      font-size: .9rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-bottom: 1.5rem;
      display: block;
      border-bottom: 1px solid var(--border);
      padding-bottom: .75rem;
    }

    .detail-row {
      display: grid;
      grid-template-columns: auto 1fr;
      gap: 2rem;
      margin-bottom: 2rem;
    }

    .detail-row:last-child {
      margin-bottom: 0;
    }

    .detail-label {
      font-size: .8rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--muted);
      font-weight: 600;
    }

    .detail-value {
      font-size: .95rem;
      color: var(--ink);
    }

    .status-badge {
      display: inline-block;
      padding: .4rem .75rem;
      border-radius: 20px;
      font-size: .7rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .07em;
    }

    .status-pending {
      background: #fff3cd;
      color: #856404;
    }

    .status-confirmed {
      background: #cfe2ff;
      color: #084298;
    }

    .status-processing {
      background: #cfe2ff;
      color: #084298;
    }

    .status-shipped {
      background: #e2e3e5;
      color: #41464b;
    }

    .status-delivered {
      background: #d1e7dd;
      color: #0a3622;
    }

    .status-cancelled {
      background: #f8d7da;
      color: #842029;
    }

    .items-table {
      width: 100%;
      border-collapse: collapse;
    }

    .items-table th {
      background: var(--cream);
      padding: 0.75rem;
      text-align: left;
      font-size: .8rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      font-weight: 600;
      border-bottom: 1px solid var(--border);
    }

    .items-table td {
      padding: 0.75rem;
      border-bottom: 1px solid var(--border);
      font-size: .9rem;
    }

    .items-table th:nth-child(2),
    .items-table th:nth-child(3),
    .items-table th:nth-child(4),
    .items-table td:nth-child(2),
    .items-table td:nth-child(3),
    .items-table td:nth-child(4) {
      text-align: right;
    }

    .items-table tr:last-child td {
      border-bottom: none;
    }

    .item-name {
      font-weight: 600;
    }

    .item-product {
      display: flex;
      align-items: center;
      gap: .75rem;
    }

    .item-thumb {
      width: 44px;
      height: 44px;
      border-radius: 4px;
      border: 1px solid var(--border);
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      flex-shrink: 0;
    }

    .item-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .item-thumb-fallback {
      color: var(--muted);
      font-size: 1rem;
    }

    .item-price {
      color: var(--red);
      font-weight: 600;
    }

    /* ── SUMMARY ── */
    .summary-section {
      background: var(--cream);
      border-radius: 6px;
      padding: 1.5rem;
      margin-top: 0;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1rem;
      font-size: .9rem;
    }

    .summary-row.total {
      border-top: 1px solid var(--border);
      padding-top: 1rem;
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--red);
    }

    /* ── TIMELINE ── */
    .timeline {
      position: relative;
      padding: 0;
    }

    .timeline::before {
      content: '';
      position: absolute;
      left: 15px;
      top: 0;
      bottom: 0;
      width: 2px;
      background: var(--border);
    }

    .timeline-item {
      padding-left: 60px;
      padding-bottom: 2rem;
      position: relative;
    }

    .timeline-item::before {
      content: '';
      position: absolute;
      left: 4px;
      top: 4px;
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background: #fff;
      border: 2px solid var(--red);
    }

    .timeline-item.completed::before {
      background: var(--red);
      border-color: var(--red);
    }

    .timeline-item.completed::before {
      content: '✓';
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .timeline-label {
      font-size: .85rem;
      font-weight: 600;
      margin-bottom: .25rem;
    }

    .timeline-date {
      font-size: .8rem;
      color: var(--muted);
    }

    /* ── BUTTONS ── */
    .btn-action {
      padding: .75rem 1.5rem;
      background: var(--red);
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: .85rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      transition: background .15s;
    }

    .btn-action:hover {
      background: var(--red-dk);
    }

    .btn-action.secondary {
      background: transparent;
      color: var(--blue);
      border: 1px solid var(--blue);
    }

    .btn-action.secondary:hover {
      background: rgba(26, 58, 92, .05);
    }

    .btn-action.danger {
      background: #fff;
      color: #842029;
      border: 1px solid #f1aeb5;
    }

    .btn-action.danger:hover {
      background: #f8d7da;
      border-color: #ea868f;
    }

    .btn-back {
      margin-bottom: 2rem;
      padding: .75rem 1.5rem;
      background: transparent;
      color: var(--blue);
      border: 1px solid var(--blue);
      border-radius: 4px;
      font-size: .85rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      transition: background .15s;
    }

    .btn-back:hover {
      background: rgba(26, 58, 92, .05);
    }
</style>
@endpush

@section('content')
<!-- NAVBAR -->
<nav class="navbar d-flex align-items-center justify-content-between">
  <a href="{{ route('index') }}" class="navbar-brand">Laptop<span>Hub</span></a>
  <div class="d-flex gap-2 align-items-center">
    <a href="{{ route('index') }}" class="nav-pill outline">Home</a>
    <a href="{{ route('customer.shop.index') }}" class="nav-pill outline">Shop</a>
    <a href="{{ route('customer.cart.index') }}" class="nav-pill outline">Cart</a>
    <a href="{{ route('customer.orders.index') }}" class="nav-pill solid">Orders</a>
    @include('customer.partials.account-dropdown')
  </div>
</nav>

<div class="page-header">
  <div class="container">
    <h1>Order #{{ $order->order_id }}</h1>
  </div>
</div>

<section class="py-5">
  <div class="container">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('customer.orders.index') }}" class="btn-back">← Back to Orders</a>

    <div class="row g-4">
      <div class="col-lg-8">
        <!-- Order Status -->
        <div class="detail-card">
          <label class="detail-title">Order Status</label>
          <div style="display: flex; align-items: center; gap: 1rem; justify-content: space-between">
            <div>
              <span class="status-badge status-{{ strtolower($order->status->status_name ?? 'pending') }}">
                {{ $order->status->status_name ?? 'Unknown' }}
              </span>
            </div>
            <p style="font-size: .85rem; color: var(--muted); margin: 0">
              Placed on {{ $order->placed_at->format('F j, Y \a\t g:i A') }}
            </p>
          </div>
        </div>

        <!-- Order Items -->
        <div class="detail-card">
          <label class="detail-title">Items Ordered</label>
          <table class="items-table">
            <thead>
              <tr>
                <th>Product</th>
                <th style="text-align: center">Qty</th>
                <th style="text-align: right">Unit Price</th>
                <th style="text-align: right">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->items as $item)
                <tr>
                  <td>
                    <div class="item-product">
                      <div class="item-thumb">
                        @if($item->product->image_url)
                          <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                        @else
                          <span class="item-thumb-fallback"><i class="bi bi-image"></i></span>
                        @endif
                      </div>
                      <div class="d-flex flex-column">
                        <span class="item-name">{{ $item->product->name }}</span>
                        <a href="{{ route('customer.shop.show', $item->product_id) }}" class="mt-1" style="font-size: 0.78rem; font-weight: 600; text-decoration: none;">
                          <i class="bi bi-bag-check-fill me-1"></i>View Product
                        </a>
                      </div>
                    </div>
                  </td>
                  <td style="text-align: center">{{ $item->quantity }}</td>
                  <td style="text-align: right">₱{{ number_format($item->unit_price, 2) }}</td>
                  <td style="text-align: right" class="item-price">₱{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <div class="summary-section">
            <div class="summary-row">
              <span>Subtotal</span>
              <span>₱{{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="summary-row">
              <span>Shipping</span>
              <span>₱{{ number_format($shipping, 2) }}</span>
            </div>
            <div class="summary-row total">
              <span>Total</span>
              <span>₱{{ number_format($total, 2) }}</span>
            </div>
          </div>
        </div>

        <!-- Timeline -->
        <div class="detail-card">
          <label class="detail-title">Order History</label>
          @php
            $statusName = strtolower((string) ($order->status->status_name ?? 'pending'));
            $isCancelled = $statusName === 'cancelled';
            $isProcessing = $statusName === 'processing';
            $isShipped = $statusName === 'shipped';
            $isDelivered = $statusName === 'delivered';
          @endphp
          <div class="timeline">
            <div class="timeline-item completed">
              <div class="timeline-label">Order Placed</div>
              <div class="timeline-date">{{ $order->placed_at->format('F j, Y') }}</div>
            </div>

            @if($isProcessing || $isShipped || $isDelivered)
              <div class="timeline-item completed">
                <div class="timeline-label">Order Confirmed</div>
                <div class="timeline-date">{{ $order->updated_at->format('F j, Y') }}</div>
              </div>
            @elseif($isCancelled)
              <div class="timeline-item completed">
                <div class="timeline-label">Order Cancelled</div>
                <div class="timeline-date">{{ $order->updated_at->format('F j, Y') }}</div>
              </div>
            @else
              <div class="timeline-item">
                <div class="timeline-label">Order Confirmation Pending</div>
                <div class="timeline-date">We're processing your order</div>
              </div>
            @endif

            @unless($isCancelled)
              @if($isShipped || $isDelivered)
                <div class="timeline-item completed">
                  <div class="timeline-label">Shipped</div>
                  <div class="timeline-date">{{ $order->updated_at->format('F j, Y') }}</div>
                </div>
              @else
                <div class="timeline-item">
                  <div class="timeline-label">Shipping Preparation</div>
                  <div class="timeline-date">We're preparing to ship</div>
                </div>
              @endif

              @if($isDelivered)
                <div class="timeline-item completed">
                  <div class="timeline-label">Delivered</div>
                  <div class="timeline-date">{{ $order->updated_at->format('F j, Y') }}</div>
                </div>
              @else
                <div class="timeline-item">
                  <div class="timeline-label">In Transit</div>
                  <div class="timeline-date">Your order is on its way</div>
                </div>
              @endif
            @endunless
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <!-- Shipping Address -->
        <div class="detail-card">
          <label class="detail-title">Shipping Address</label>
          <div class="detail-value" style="line-height: 1.6">
            {{ $order->shipping_address }}
          </div>
        </div>

        <!-- Payment Method -->
        <div class="detail-card">
          <label class="detail-title">Payment Method</label>
          <div class="detail-value">
            @if($order->paymentMethod)
              {{ $order->paymentMethod->method_name }}
            @else
              Unknown
            @endif
          </div>
        </div>

        <!-- Actions -->
        <div class="detail-card">
          <label class="detail-title">Actions</label>
          @php
            $statusName = strtolower((string) ($order->status->status_name ?? ''));
            $canCancel = in_array($statusName, ['pending', 'processing'], true);
          @endphp
          <div style="display: flex; flex-direction: column; gap: .75rem">
            <a href="{{ route('customer.shop.index') }}" class="btn-action" style="text-align: center; width: 100%">Continue Shopping</a>
            @if($canCancel)
              <form action="{{ route('customer.orders.cancel', $order->order_id) }}" method="post" style="margin:0" onsubmit="return confirm('Cancel this order? This cannot be undone.')">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-action danger" style="text-align: center; width: 100%">Cancel Order</button>
              </form>
            @endif
            <button onclick="window.print()" class="btn-action secondary" style="text-align: center; width: 100%">Print Invoice</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
