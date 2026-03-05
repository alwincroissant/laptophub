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

    /* ── PRICE SLIDER ── */
    .price-slider-container {
      padding: 1rem 0;
    }

    .price-values {
      display: flex;
      justify-content: space-between;
      margin-bottom: .75rem;
      font-size: .85rem;
      color: var(--ink);
      font-weight: 600;
    }

    .slider-wrapper {
      position: relative;
      height: 6px;
      background: var(--border);
      border-radius: 3px;
      margin-bottom: 1.5rem;
    }

    .slider-track {
      position: absolute;
      height: 6px;
      background: var(--red);
      border-radius: 3px;
    }

    input[type="range"] {
      position: absolute;
      width: 100%;
      height: 6px;
      background: transparent;
      pointer-events: none;
      -webkit-appearance: none;
      top: 0;
    }

    input[type="range"]::-webkit-slider-thumb {
      -webkit-appearance: none;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background: var(--red);
      cursor: pointer;
      pointer-events: auto;
      border: 2px solid #fff;
      box-shadow: 0 2px 6px rgba(0,0,0,.2);
    }

    input[type="range"]::-moz-range-thumb {
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background: var(--red);
      cursor: pointer;
      pointer-events: auto;
      border: 2px solid #fff;
      box-shadow: 0 2px 6px rgba(0,0,0,.2);
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
      padding: 2rem;
      text-align: center;
      font-size: 3rem;
      border-bottom: 1px solid var(--border);
      flex-grow: 1;
      display: flex;
      align-items: center;
      justify-content: center;
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
    <form action="{{ route('logout') }}" method="post" class="m-0">
      @csrf
      <button type="submit" class="nav-pill solid" style="border:none;cursor:pointer">Sign Out</button>
    </form>
  </div>
</nav>

<div class="page-header">
  <div class="container">
    <h1>Shop Products</h1>
    <p>Browse our collection of premium laptops and accessories</p>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row">
      <!-- Sidebar Filters -->
      <div class="col-lg-3">
        <div class="filters-panel">
          <!-- Search Bar -->
          <div class="search-bar">
            <input type="text" id="searchInput" class="search-input" placeholder="Search products...">
            <i class="bi bi-search search-icon"></i>
          </div>

          <div class="filter-group">
            <label class="filter-title">Category</label>
            <div class="filter-option">
              <input type="checkbox" id="cat-laptops" name="category" value="laptops">
              <label for="cat-laptops">Laptops</label>
            </div>
            <div class="filter-option">
              <input type="checkbox" id="cat-gaming" name="category" value="gaming">
              <label for="cat-gaming">Gaming</label>
            </div>
            <div class="filter-option">
              <input type="checkbox" id="cat-components" name="category" value="components">
              <label for="cat-components">Components</label>
            </div>
            <div class="filter-option">
              <input type="checkbox" id="cat-accessories" name="category" value="accessories">
              <label for="cat-accessories">Accessories</label>
            </div>
          </div>

          <div class="filter-group">
            <label class="filter-title">Brand</label>
            @foreach($brands as $brand)
              <div class="filter-option">
                <input type="checkbox" id="brand-{{ $brand->brand_id }}" name="brand" value="{{ $brand->brand_id }}">
                <label for="brand-{{ $brand->brand_id }}">{{ $brand->name }}</label>
              </div>
            @endforeach
          </div>

          <div class="filter-group">
            <label class="filter-title">Price Range</label>
            <div class="price-slider-container">
              <div class="price-values">
                <span id="minPrice">₱0</span>
                <span id="maxPrice">₱200,000</span>
              </div>
              <div class="slider-wrapper">
                <div class="slider-track" id="sliderTrack"></div>
                <input type="range" id="rangeMin" min="0" max="200000" value="0" step="1000">
                <input type="range" id="rangeMax" min="0" max="200000" value="200000" step="1000">
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Products Grid -->
      <div class="col-lg-9">
        <div class="shop-controls">
          <div class="view-count">
            Showing <strong>{{ $products->count() }}</strong> products
          </div>
          <div class="sort-control">
            <select onchange="location.href = this.value">
              <option value="{{ route('customer.shop.index') }}">Sort by: Newest</option>
              <option value="{{ route('customer.shop.index', ['sort' => 'price-asc']) }}">Price: Low to High</option>
              <option value="{{ route('customer.shop.index', ['sort' => 'price-desc']) }}">Price: High to Low</option>
              <option value="{{ route('customer.shop.index', ['sort' => 'popular']) }}">Most Popular</option>
            </select>
          </div>
        </div>

        @if($products->count() > 0)
          <div class="row g-3">
            @foreach($products as $product)
              <div class="col-12 col-sm-6 col-lg-4">
                <div class="product-card">
                  <div class="img-area">💻</div>
                  <div class="card-body">
                    <div class="brand-tag">{{ $product->brand->name ?? 'Unbranded' }}</div>
                    <h5>{{ $product->name }}</h5>
                    <div class="stars mb-1">★★★★★</div>
                    <div class="price">₱{{ number_format($product->price, 2) }}</div>
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

@push('scripts')
<script>
  // Price Range Slider
  const rangeMin = document.getElementById('rangeMin');
  const rangeMax = document.getElementById('rangeMax');
  const minPriceDisplay = document.getElementById('minPrice');
  const maxPriceDisplay = document.getElementById('maxPrice');
  const sliderTrack = document.getElementById('sliderTrack');

  function formatPrice(value) {
    return '₱' + parseInt(value).toLocaleString();
  }

  function updateSlider() {
    let minVal = parseInt(rangeMin.value);
    let maxVal = parseInt(rangeMax.value);

    // Prevent crossing
    if (maxVal - minVal < 5000) {
      if (event.target === rangeMin) {
        rangeMin.value = maxVal - 5000;
        minVal = maxVal - 5000;
      } else {
        rangeMax.value = minVal + 5000;
        maxVal = minVal + 5000;
      }
    }

    // Update display
    minPriceDisplay.textContent = formatPrice(minVal);
    maxPriceDisplay.textContent = formatPrice(maxVal);

    // Update track
    const percentMin = (minVal / rangeMin.max) * 100;
    const percentMax = (maxVal / rangeMax.max) * 100;
    sliderTrack.style.left = percentMin + '%';
    sliderTrack.style.width = (percentMax - percentMin) + '%';
  }

  rangeMin.addEventListener('input', updateSlider);
  rangeMax.addEventListener('input', updateSlider);

  // Initialize
  updateSlider();

  // Search functionality
  const searchInput = document.getElementById('searchInput');
  searchInput.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
      const productName = card.querySelector('h5').textContent.toLowerCase();
      const brandName = card.querySelector('.brand-tag').textContent.toLowerCase();
      
      if (productName.includes(searchTerm) || brandName.includes(searchTerm)) {
        card.closest('.col-12').style.display = '';
      } else {
        card.closest('.col-12').style.display = 'none';
      }
    });
  });
</script>
@endpush

@endsection
