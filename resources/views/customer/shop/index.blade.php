@extends('layouts.base')

@section('title', 'Shop - LaptopHub')

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

    .page-header p {
      color: rgba(255,255,255,.75);
      font-size: .95rem;
    }

    /* ── SEARCH BAR ── */
    .search-bar {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .search-input {
      width: 100%;
      padding: .75rem 2.5rem .75rem 1rem;
      border: 1.5px solid var(--border);
      border-radius: 4px;
      font-size: .85rem;
      font-family: 'Libre Franklin', sans-serif;
      transition: border-color .15s;
    }

    .search-input:focus {
      border-color: var(--blue);
      outline: none;
    }

    .search-icon {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
      pointer-events: none;
    }

    /* ── FILTERS SIDEBAR ── */
    .filters-panel {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    .filter-group {
      margin-bottom: 1.5rem;
    }

    .filter-group:last-child {
      margin-bottom: 0;
    }

    .filter-title {
      font-size: .85rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: var(--ink);
      margin-bottom: .75rem;
      display: block;
      border-bottom: 1px solid var(--border);
      padding-bottom: .5rem;
    }

    .filter-option {
      display: flex;
      align-items: center;
      margin-bottom: .5rem;
    }

    .filter-option input[type="checkbox"] {
      cursor: pointer;
      margin-right: .5rem;
    }

    .filter-option label {
      cursor: pointer;
      font-size: .85rem;
      flex: 1;
    }

    .filter-actions {
      display: flex;
      gap: .5rem;
      margin-top: 1rem;
    }

    .btn-filter {
      width: 100%;
      border: none;
      border-radius: 4px;
      background: var(--red);
      color: #fff;
      padding: .65rem .8rem;
      font-size: .82rem;
      font-weight: 600;
      cursor: pointer;
    }

    .btn-clear {
      width: 100%;
      border: 1px solid var(--border);
      border-radius: 4px;
      background: #fff;
      color: var(--ink);
      padding: .65rem .8rem;
      font-size: .82rem;
      font-weight: 600;
      text-align: center;
      text-decoration: none;
    }

    /* ── PRODUCTS GRID ── */
    .product-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 6px;
      overflow: hidden;
      transition: box-shadow .2s, transform .2s;
      text-decoration: none;
      color: var(--ink);
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .product-card:hover {
      box-shadow: 0 8px 28px rgba(0,0,0,.1);
      transform: translateY(-4px);
    }

    .product-card .img-area {
      background: var(--cream);
      height: 220px;
      text-align: center;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .product-card .img-area img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .product-card .img-fallback {
      font-size: 2rem;
      color: var(--muted);
    }

    .product-card .card-body {
      padding: 1.25rem;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .product-card .brand-tag {
      font-size: .65rem;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--muted);
      font-weight: 600;
      margin-bottom: .35rem;
    }

    .product-card h5 {
      font-size: .9rem;
      font-weight: 600;
      margin-bottom: .5rem;
      flex-grow: 1;
    }

    .product-card .price {
      font-family: 'Playfair Display', serif;
      font-size: 1.25rem;
      color: var(--red);
      margin-bottom: .75rem;
    }

    .product-card .stars {
      color: var(--gold);
      font-size: .8rem;
      margin-bottom: .75rem;
    }

    .badge-new {
      font-size: .6rem;
      background: var(--red);
      color: #fff;
      padding: .2em .55em;
      border-radius: 3px;
      text-transform: uppercase;
      letter-spacing: .07em;
      vertical-align: middle;
    }

    .btn-add-cart {
      width: 100%;
      background: var(--red);
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: .75rem;
      font-size: .85rem;
      font-weight: 600;
      cursor: pointer;
      transition: background .15s;
    }

    .btn-add-cart:hover {
      background: var(--red-dk);
    }

    .btn-view {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      border: 1px solid var(--blue);
      color: var(--blue);
      background: #fff;
      border-radius: 4px;
      padding: .72rem;
      font-size: .85rem;
      font-weight: 600;
      text-decoration: none;
      transition: background .15s;
    }

    .btn-view:hover {
      background: rgba(26, 58, 92, .05);
    }

    /* ── SORT & VIEW ── */
    .shop-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .sort-control select {
      padding: .6rem 1rem;
      border: 1px solid var(--border);
      border-radius: 4px;
      font-size: .85rem;
      background: #fff;
      color: var(--ink);
      cursor: pointer;
    }

    .btn-sort {
      border: 1px solid var(--border);
      border-radius: 4px;
      background: #fff;
      color: var(--ink);
      padding: .58rem .85rem;
      font-size: .82rem;
      font-weight: 600;
      margin-left: .4rem;
    }

    .view-count {
      font-size: .85rem;
      color: var(--muted);
    }
</style>
@endpush

@section('content')
<!-- NAVBAR -->
<nav class="navbar d-flex align-items-center justify-content-between">
  <a href="{{ route('index') }}" class="navbar-brand">Laptop<span>Hub</span></a>
  <div class="d-flex gap-2 align-items-center">
    <a href="{{ route('index') }}" class="nav-pill outline">Home</a>
    <a href="{{ route('customer.shop.index') }}" class="nav-pill solid">Shop</a>
    <a href="{{ route('customer.cart.index') }}" class="nav-pill outline">Cart</a>
    <a href="{{ route('customer.orders.index') }}" class="nav-pill outline">Orders</a>
    @include('customer.partials.account-dropdown')
  </div>
</nav>

<div class="page-header">
  <div class="container">
    <h1>Shop Products</h1>
    <p>Browse our collection of premium laptops and accessories</p>
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
    <div class="row">
      <!-- Sidebar Filters -->
      <div class="col-lg-3">
        <form method="GET" action="{{ route('customer.shop.index') }}" class="filters-panel">
          <input type="hidden" name="sort" value="{{ $sort }}">

          <div class="search-bar">
            <input type="text" name="search" class="search-input" placeholder="Search products..." value="{{ $search }}">
            <i class="bi bi-search search-icon"></i>
          </div>

          <div class="filter-group">
            <label class="filter-title">Category</label>
            @foreach($categories as $category)
              <div class="filter-option">
                <input type="checkbox" id="cat-{{ $category->category_id }}" name="category[]" value="{{ $category->category_id }}" {{ in_array($category->category_id, $selectedCategories, true) ? 'checked' : '' }}>
                <label for="cat-{{ $category->category_id }}">{{ $category->name }}</label>
              </div>
            @endforeach
          </div>

          <div class="filter-group">
            <label class="filter-title">Brand</label>
            @foreach($brands as $brand)
              <div class="filter-option">
                <input type="checkbox" id="brand-{{ $brand->brand_id }}" name="brand[]" value="{{ $brand->brand_id }}" {{ in_array($brand->brand_id, $selectedBrands, true) ? 'checked' : '' }}>
                <label for="brand-{{ $brand->brand_id }}">{{ $brand->name }}</label>
              </div>
            @endforeach
          </div>

          <div class="filter-group">
            <label class="filter-title">Price Range</label>
            <div class="filter-option">
              <input type="checkbox" id="price-budget" name="price[]" value="budget" {{ in_array('budget', $selectedPrices, true) ? 'checked' : '' }}>
              <label for="price-budget">Budget (₱0 - ₱30,000)</label>
            </div>
            <div class="filter-option">
              <input type="checkbox" id="price-mid" name="price[]" value="mid" {{ in_array('mid', $selectedPrices, true) ? 'checked' : '' }}>
              <label for="price-mid">Mid Range (₱30,000 - ₱70,000)</label>
            </div>
            <div class="filter-option">
              <input type="checkbox" id="price-premium" name="price[]" value="premium" {{ in_array('premium', $selectedPrices, true) ? 'checked' : '' }}>
              <label for="price-premium">Premium (₱70,000 - ₱150,000)</label>
            </div>
            <div class="filter-option">
              <input type="checkbox" id="price-luxury" name="price[]" value="luxury" {{ in_array('luxury', $selectedPrices, true) ? 'checked' : '' }}>
              <label for="price-luxury">Luxury (₱150,000+)</label>
            </div>
          </div>

          <div class="filter-actions">
            <button type="submit" class="btn-filter">Apply</button>
            <a href="{{ route('customer.shop.index') }}" class="btn-clear">Clear</a>
          </div>
        </form>
      </div>

      <!-- Products Grid -->
      <div class="col-lg-9">
        <div class="shop-controls">
          <div class="view-count">
            Showing <strong>{{ $products->count() }}</strong> products
          </div>
          <div class="sort-control">
            <form method="GET" action="{{ route('customer.shop.index') }}" class="d-flex align-items-center">
              <input type="hidden" name="search" value="{{ $search }}">
              @foreach($selectedCategories as $categoryId)
                <input type="hidden" name="category[]" value="{{ $categoryId }}">
              @endforeach
              @foreach($selectedBrands as $brandId)
                <input type="hidden" name="brand[]" value="{{ $brandId }}">
              @endforeach
              @foreach($selectedPrices as $priceRange)
                <input type="hidden" name="price[]" value="{{ $priceRange }}">
              @endforeach

              <select name="sort">
                <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Sort by: Newest</option>
                <option value="price-asc" {{ $sort === 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price-desc" {{ $sort === 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Most Popular</option>
              </select>
              <button type="submit" class="btn-sort">Sort</button>
            </form>
          </div>
        </div>

        @if($products->count() > 0)
          <div class="row g-3">
            @foreach($products as $product)
              <div class="col-12 col-sm-6 col-lg-4">
                <div class="product-card">
                  <div class="img-area">
                    @if($product->image_url)
                      <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    @else
                      <span class="img-fallback"><i class="bi bi-image"></i></span>
                    @endif
                  </div>
                  <div class="card-body">
                    <div class="brand-tag">{{ $product->brand->name ?? 'Unbranded' }}</div>
                    <h5>{{ $product->name }}</h5>
                    <div class="stars mb-1">★★★★★</div>
                    <div class="price">₱{{ number_format($product->price, 2) }}</div>
                    <a href="{{ route('customer.shop.show', $product->product_id) }}" class="btn-view mb-2">
                      <i class="bi bi-eye me-1"></i> View
                    </a>
                    <form action="{{ route('customer.cart.add') }}" method="post">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                      <div style="display: flex; gap: .5rem; margin-bottom: .75rem">
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_qty }}" style="width: 60px; padding: .5rem; border: 1px solid var(--border); border-radius: 4px; font-size: .85rem">
                        <button type="submit" class="btn-add-cart" style="flex-grow: 1">
                          <i class="bi bi-cart-plus"></i> Add to Cart
                        </button>
                      </div>
                    </form>
                    @if($product->stock_qty < 10 && $product->stock_qty > 0)
                      <p style="font-size: .75rem; color: var(--red); margin: 0">Only {{ $product->stock_qty }} left</p>
                    @elseif($product->stock_qty <= 0)
                      <p style="font-size: .75rem; color: var(--muted); margin: 0">Out of stock</p>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <!-- Pagination -->
          <div class="mt-5 text-center">
            {{ $products->links('pagination::bootstrap-5') }}
          </div>
        @else
          <div style="text-align: center; padding: 4rem 2rem; background: var(--cream); border-radius: 6px">
            <p style="font-size: 1.1rem; color: var(--muted); margin-bottom: 1rem">No products found</p>
            <a href="{{ route('customer.shop.index') }}" class="btn-submit" style="display: inline-block; text-decoration: none">Clear Filters</a>
          </div>
        @endif
      </div>
    </div>
  </div>
</section>

@endsection
