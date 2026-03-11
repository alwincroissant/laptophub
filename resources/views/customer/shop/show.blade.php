@extends('layouts.base')

@section('title', $product->name . ' - LaptopHub')

@push('styles')
<style>
    :root {
      --ink:     #0c0c0c;
      --paper:   #f5f1ea;
      --cream:   #ede8df;
      --red:     #c0392b;
      --red-dk:  #962d22;
      --blue:    #1a3a5c;
      --muted:   #7a756c;
      --border:  #d8d2c8;
      --white:   #ffffff;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Libre Franklin', sans-serif;
      background: var(--paper);
      color: var(--ink);
      overflow-x: hidden;
    }

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

    .detail-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 8px;
      overflow: hidden;
    }

    .detail-image {
      width: 100%;
      max-height: 480px;
      object-fit: cover;
      display: block;
      background: var(--cream);
    }

    .detail-image-fallback {
      height: 320px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--cream);
      color: var(--muted);
      font-size: 2.5rem;
    }

    .detail-body {
      padding: 1.5rem;
    }

    .meta {
      font-size: .78rem;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: var(--muted);
      margin-bottom: .5rem;
    }

    .title {
      font-size: 1.6rem;
      font-weight: 700;
      margin-bottom: .75rem;
    }

    .price {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      color: var(--red);
      margin-bottom: 1rem;
    }

    .desc {
      font-size: .92rem;
      color: #2c2c2c;
      line-height: 1.65;
      margin-bottom: 1rem;
      white-space: pre-line;
    }

    .info-item {
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: .75rem;
      background: #fff;
    }

    .info-label {
      font-size: .72rem;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: var(--muted);
      margin-bottom: .35rem;
    }

    .info-value {
      font-size: .9rem;
      font-weight: 600;
    }

    .availability {
      display: inline-block;
      font-size: .78rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      padding: .35rem .6rem;
      border-radius: 3px;
      margin-bottom: 1rem;
      font-weight: 600;
    }

    .availability.in {
      background: #d1e7dd;
      color: #0a3622;
    }

    .availability.out {
      background: #f8d7da;
      color: #842029;
    }

    .actions {
      display: flex;
      gap: .75rem;
      flex-wrap: wrap;
    }

    .btn-back {
      background: transparent;
      color: var(--blue);
      border: 1px solid var(--blue);
      border-radius: 4px;
      padding: .75rem 1rem;
      text-decoration: none;
      font-size: .85rem;
      font-weight: 600;
    }

    .btn-add-cart {
      background: var(--red);
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: .75rem 1rem;
      font-size: .85rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: .4rem;
    }

    .btn-auth-required {
      background: linear-gradient(180deg, #d24536 0%, #bf3629 100%);
      color: #fff;
      border: 1px solid #b63226;
      border-radius: 4px;
      padding: .75rem 1rem;
      font-size: .84rem;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: transform .12s ease, box-shadow .12s ease, filter .12s ease;
    }

    .btn-auth-required:hover {
      color: #fff;
      filter: brightness(.98);
      transform: translateY(-1px);
      box-shadow: 0 6px 14px rgba(192, 57, 43, .22);
    }

    .auth-cart-note {
      margin: 0;
      font-size: .76rem;
      color: var(--muted);
      line-height: 1.35;
    }

    .qty-input {
      width: 78px;
      border: 1px solid var(--border);
      border-radius: 4px;
      padding: .72rem;
      font-size: .85rem;
    }

</style>
@endpush

@section('content')
<nav class="navbar d-flex align-items-center justify-content-between">
  <a href="{{ route('index') }}" class="navbar-brand">Laptop<span>Hub</span></a>
  <div class="d-flex gap-2 align-items-center">
    <a href="{{ route('index') }}" class="nav-pill outline">Home</a>
    <a href="{{ route('customer.shop.index') }}" class="nav-pill solid">Shop</a>
    @auth
      <a href="{{ route('customer.cart.index') }}" class="nav-pill outline">Cart</a>
      <a href="{{ route('customer.orders.index') }}" class="nav-pill outline">Orders</a>
      @include('customer.partials.account-dropdown')
    @else
      <a href="{{ route('index') }}#login" class="nav-pill outline">Log In</a>
      <a href="{{ route('index') }}#register" class="nav-pill solid">Register</a>
    @endauth
  </div>
</nav>

<div class="page-header">
  <div class="container">
    <h1>Product Information</h1>
    <p>View complete details before adding to cart</p>
  </div>
</div>

<div class="container mt-4">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger mb-0">{{ $errors->first() }}</div>
  @endif
</div>

<section class="py-5">
  <div class="container">
    <div class="detail-card">
      @if($product->image_url)
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="detail-image">
      @else
        <div class="detail-image-fallback"><i class="bi bi-image"></i></div>
      @endif

      <div class="detail-body">
        <div class="meta">{{ $product->brand->name ?? 'Unbranded' }} • {{ $product->category->name ?? 'Uncategorized' }}</div>
        <h2 class="title">{{ $product->name }}</h2>
        <div class="price">₱{{ number_format($product->price, 2) }}</div>

        <div class="availability {{ $product->stock_qty > 0 ? 'in' : 'out' }}">
          {{ $product->stock_qty > 0 ? 'In Stock' : 'Out of Stock' }}
        </div>

        <div class="desc">{{ $product->description ?  : 'No description available.' }}</div>

        @if($product->compatibility)
          <div class="info-item" style="margin-bottom:1.25rem">
            <div class="info-label">Compatibility</div>
            <div class="info-value" style="font-weight:500">{{ $product->compatibility }}</div>
          </div>
        @endif

        <div class="actions">
          <a href="{{ route('customer.shop.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Back to Shop</a>

          @if($product->stock_qty > 0)
            @auth
              <form action="{{ route('customer.cart.add') }}" method="post" class="d-flex gap-2 align-items-center">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_qty }}" class="qty-input">
                <button type="submit" class="btn-add-cart"><i class="bi bi-cart-plus"></i> Add to Cart</button>
              </form>
            @else
              <a href="{{ route('index') }}#login" class="btn-auth-required">Log In to Add to Cart</a>
              <p class="auth-cart-note">Guest browsing is enabled. Sign in to continue to cart and checkout.</p>
            @endauth
          @else
            <span style="font-size:.85rem;color:var(--muted)">Out of stock</span>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
