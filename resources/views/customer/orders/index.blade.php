@extends('layouts.base')

@section('title', 'My Orders - LaptopHub')

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

    /* ── FILTERS ── */
    .filters-bar {
      display: flex;
      gap: 1rem;
      margin-bottom: 2rem;
      flex-wrap: wrap;
    }

    .filter-btn {
      padding: .6rem 1.2rem;
      border: 1px solid var(--border);
      background: #fff;
      border-radius: 4px;
      font-size: .85rem;
      font-weight: 500;
      cursor: pointer;
      transition: background .15s, border-color .15s;
      text-decoration: none;
      color: var(--ink);
    }

    .filter-btn:hover,
    .filter-btn.active {
      border-color: var(--red);
      background: var(--red);
      color: #fff;
    }

    /* ── ORDER CARD ── */
    .order-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      transition: box-shadow .2s;
    }

    .order-card:hover {
      box-shadow: 0 4px 16px rgba(0,0,0,.08);
    }

    .order-header {
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 1rem;
      margin-bottom: 1rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--border);
    }

    .order-id {
      font-size: .8rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: .25rem;
    }

    .order-date {
      font-size: .85rem;
      color: var(--muted);
    }

    .order-total {
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem;
      color: var(--red);
      font-weight: 600;
      line-height: 1;
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

    .order-items {
      display: grid;
      gap: .75rem;
      margin-bottom: 1rem;
    }

    .order-item-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: .85rem;
      padding: .75rem;
      background: var(--cream);
      border-radius: 4px;
    }

    .order-item-main {
      display: flex;
      align-items: center;
      gap: .75rem;
      flex: 1;
      min-width: 0;
    }

    .order-item-thumb {
      width: 44px;
      height: 44px;
      border-radius: 4px;
      background: #fff;
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      flex-shrink: 0;
    }

    .order-item-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .order-item-thumb-fallback {
      color: var(--muted);
      font-size: 1rem;
    }

    .order-item-name {
      font-weight: 500;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .order-item-qty {
      color: var(--muted);
      margin: 0 1rem;
    }

    .order-item-price {
      color: var(--red);
      font-weight: 600;
      min-width: 80px;
      text-align: right;
    }

    .order-item-review {
      margin-left: .75rem;
      min-width: 110px;
      text-align: right;
    }

    .btn-review-item {
      display: inline-block;
      font-size: .72rem;
      font-weight: 700;
      letter-spacing: .05em;
      text-transform: uppercase;
      padding: .38rem .58rem;
      border-radius: 4px;
      border: 1px solid var(--blue);
      color: var(--blue);
      text-decoration: none;
      background: #fff;
      transition: background .15s;
    }

    .btn-review-item:hover {
      background: rgba(26, 58, 92, .08);
    }

    .reviewed-pill {
      display: inline-block;
      font-size: .68rem;
      font-weight: 700;
      letter-spacing: .05em;
      text-transform: uppercase;
      padding: .35rem .5rem;
      border-radius: 999px;
      background: #d1e7dd;
      color: #0a3622;
    }

    .order-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .order-actions {
      display: flex;
      gap: .75rem;
    }

    .btn-action {
      padding: .6rem 1rem;
      border: 1px solid var(--border);
      background: #fff;
      border-radius: 4px;
      font-size: .8rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      color: var(--ink);
      transition: background .15s, border-color .15s;
    }

    .btn-action:hover {
      border-color: var(--blue);
      background: rgba(26, 58, 92, .05);
    }

    .btn-action.primary {
      background: var(--red);
      color: #fff;
      border-color: var(--red);
    }

    .btn-action.primary:hover {
      background: var(--red-dk);
      border-color: var(--red-dk);
    }

    .btn-action.danger {
      background: #fff;
      color: #842029;
      border-color: #f1aeb5;
    }

    .btn-action.danger:hover {
      background: #f8d7da;
      border-color: #ea868f;
    }

    /* ── EMPTY STATE ── */
    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      background: var(--cream);
      border-radius: 8px;
    }

    .empty-state-icon {
      font-size: 3rem;
      margin-bottom: 1rem;
    }

    .empty-state h3 {
      font-size: 1.3rem;
      margin-bottom: .5rem;
    }

    .empty-state p {
      color: var(--muted);
      margin-bottom: 2rem;
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
    <h1>My Orders</h1>
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

    <!-- Filter Buttons -->
    <div class="filters-bar">
      <a href="{{ route('customer.orders.index') }}" class="filter-btn {{ !request('status') ? 'active' : '' }}">All Orders</a>
      <a href="{{ route('customer.orders.index', ['status' => 'pending']) }}" class="filter-btn {{ request('status') === 'pending' ? 'active' : '' }}">Pending</a>
      <a href="{{ route('customer.orders.index', ['status' => 'processing']) }}" class="filter-btn {{ request('status') === 'processing' ? 'active' : '' }}">Processing</a>
      <a href="{{ route('customer.orders.index', ['status' => 'shipped']) }}" class="filter-btn {{ request('status') === 'shipped' ? 'active' : '' }}">Shipped</a>
      <a href="{{ route('customer.orders.index', ['status' => 'delivered']) }}" class="filter-btn {{ request('status') === 'delivered' ? 'active' : '' }}">Delivered</a>
      <a href="{{ route('customer.orders.index', ['status' => 'cancelled']) }}" class="filter-btn {{ request('status') === 'cancelled' ? 'active' : '' }}">Cancelled</a>
    </div>

    @if($orders && $orders->count() > 0)
      @foreach($orders as $order)
        <div class="order-card">
          <div class="order-header">
            <div>
              <div class="order-id">Order #{{ $order->order_id }}</div>
              <div class="order-date">{{ $order->placed_at->format('F j, Y • g:i A') }}</div>
              <div style="margin-top: .5rem">
                <span class="status-badge status-{{ strtolower($order->status->status_name ?? 'pending') }}">
                  {{ $order->status->status_name ?? 'Unknown' }}
                </span>
              </div>
            </div>
            <div style="text-align: right">
              <div class="order-total">₱{{ number_format($order->items->sum(function($item) { return $item->unit_price * $item->quantity; }), 2) }}</div>
            </div>
          </div>

          <div class="order-items">
            @foreach($order->items as $item)
              <div class="order-item-row">
                <div class="order-item-main">
                  <div class="order-item-thumb">
                    @if($item->product->image_url)
                      <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                    @else
                      <span class="order-item-thumb-fallback"><i class="bi bi-image"></i></span>
                    @endif
                  </div>
                  <div class="order-item-name">{{ $item->product->name }}</div>
                </div>
                <div class="order-item-qty">× {{ $item->quantity }}</div>
                <div class="order-item-price">₱{{ number_format($item->unit_price * $item->quantity, 2) }}</div>
                <div class="order-item-review">
                  @php
                    $isDelivered = strtolower((string) ($order->status->status_name ?? '')) === 'delivered';
                    $hasReview = (bool) $item->review;
                  @endphp

                  @if($isDelivered && ! $hasReview)
                    <a
                      href="{{ route('customer.shop.show', $item->product_id) . '?order_item_id=' . $item->order_item_id . '#reviews' }}"
                      class="btn-review-item"
                    >
                      Write Review
                    </a>
                  @elseif($hasReview)
                    <span class="reviewed-pill">Reviewed</span>
                  @endif
                </div>
              </div>
            @endforeach
          </div>

          <div class="order-footer">
            <div style="font-size: .85rem; color: var(--muted)">
              Shipping to: <strong>{{ $order->shipping_address }}</strong>
            </div>
            <div class="order-actions">
              @php
                $statusName = strtolower((string) ($order->status->status_name ?? ''));
                $canCancel = in_array($statusName, ['pending', 'processing'], true);
              @endphp
              <a href="{{ route('customer.orders.show', $order->order_id) }}" class="btn-action">View Details</a>
              @if($canCancel)
                <form action="{{ route('customer.orders.cancel', $order->order_id) }}" method="post" style="margin:0" onsubmit="return confirm('Cancel this order? This cannot be undone.')">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn-action danger">Cancel Order</button>
                </form>
              @endif
              <a href="{{ route('customer.shop.index') }}" class="btn-action primary">Buy Again</a>
            </div>
          </div>
        </div>
      @endforeach

      <!-- Pagination -->
      <div class="mt-4">
        {{ $orders->links('pagination::bootstrap-5') }}
      </div>
    @else
      <div class="empty-state">
        <div class="empty-state-icon">📦</div>
        <h3>No orders yet</h3>
        <p>You haven't placed any orders yet. Start shopping to see your orders here.</p>
        <a href="{{ route('customer.shop.index') }}" class="btn-submit" style="display: inline-block; text-decoration: none">Start Shopping</a>
      </div>
    @endif
  </div>
</section>

@endsection
