@extends('layouts.base')

@section('title', 'Cart - LaptopHub')

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

    /* ── CART ITEM ── */
    .cart-item {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 1.5rem;
      margin-bottom: 1rem;
      display: grid;
      grid-template-columns: auto 80px 1fr auto;
      gap: 1.5rem;
      align-items: center;
    }

    .cart-item-check {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .cart-item-check input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: var(--red);
    }

    .cart-item-img {
      background: var(--cream);
      border-radius: 4px;
      width: 80px;
      height: 80px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .cart-item-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .cart-item-img-fallback {
      font-size: 1.5rem;
      color: var(--muted);
    }

    .cart-item-details h5 {
      font-size: .95rem;
      font-weight: 600;
      margin-bottom: .25rem;
    }

    .cart-item-details p {
      font-size: .8rem;
      color: var(--muted);
      margin: 0;
    }

    .cart-item-price {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--red);
      font-weight: 600;
      margin: 0;
    }

    .item-meta-row {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: .75rem;
      margin-bottom: .5rem;
    }

    .quantity-control {
      display: flex;
      align-items: center;
      gap: .4rem;
      margin-top: 0;
    }

    .qty-btn {
      width: 28px;
      height: 28px;
      border: 1px solid var(--border);
      background: #fff;
      border-radius: 3px;
      cursor: pointer;
      font-size: .8rem;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background .15s;
    }

    .qty-btn:hover {
      background: var(--cream);
    }

    .qty-display {
      width: 45px;
      text-align: center;
      font-size: .85rem;
      font-weight: 600;
    }

    .remove-btn {
      background: transparent;
      color: var(--red);
      border: 1px solid var(--red);
      border-radius: 4px;
      width: 32px;
      height: 32px;
      padding: 0;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 0.85rem;
      transition: background .15s, color .15s;
    }

    .remove-btn:hover {
      background: var(--red);
      color: #fff;
    }

    .remove-btn:active {
      transform: scale(0.95);
    }

    /* ── SUMMARY SIDEBAR ── */
    .order-summary {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 2rem;
      position: sticky;
      top: 80px;
    }

    .summary-title {
      font-size: .9rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-bottom: 1.5rem;
      display: block;
      border-bottom: 1px solid var(--border);
      padding-bottom: .75rem;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: .9rem;
      margin-bottom: 1rem;
    }

    .summary-row.total {
      border-top: 1px solid var(--border);
      padding-top: 1rem;
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--red);
    }

    .btn-checkout {
      width: 100%;
      background: var(--red);
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 1rem;
      font-size: .9rem;
      font-weight: 600;
      cursor: pointer;
      margin-top: 1.5rem;
      transition: background .15s;
      display: block;
      text-decoration: none;
      text-align: center;
      line-height: 1.2;
    }

    .btn-checkout:hover {
      background: var(--red-dk);
    }

    .btn-continue-shopping {
      width: 100%;
      background: transparent;
      color: var(--blue);
      border: 1px solid var(--blue);
      border-radius: 4px;
      padding: .75rem;
      font-size: .85rem;
      font-weight: 600;
      cursor: pointer;
      margin-top: .75rem;
      transition: background .15s;
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }

    .btn-continue-shopping:hover {
      background: rgba(26, 58, 92, .05);
    }

    .btn-checkout:disabled {
      opacity: .6;
      cursor: not-allowed;
    }

    @media (max-width: 768px) {
      .cart-item {
        grid-template-columns: auto 64px 1fr;
      }

      .cart-item > div:last-child {
        grid-column: 1 / -1;
        margin-top: .5rem;
      }

      .item-meta-row {
        justify-content: flex-start;
      }
    }

    /* ── EMPTY CART ── */
    .empty-cart {
      text-align: center;
      padding: 4rem 2rem;
      background: var(--cream);
      border-radius: 8px;
    }

    .empty-cart-icon {
      font-size: 3rem;
      margin-bottom: 1rem;
    }

    .empty-cart h3 {
      font-size: 1.3rem;
      margin-bottom: .5rem;
    }

    .empty-cart p {
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
    <a href="{{ route('customer.cart.index') }}" class="nav-pill solid">Cart</a>
    <a href="{{ route('customer.orders.index') }}" class="nav-pill outline">Orders</a>
    @include('customer.partials.account-dropdown')
  </div>
</nav>

<div class="page-header">
  <div class="container">
    <h1>Shopping Cart</h1>
  </div>
</div>

<section class="py-5">
  <div class="container">
    @if($cartItems && $cartItems->count() > 0)
      <div class="row g-4">
        <div class="col-lg-8">
          <h5 style="margin-bottom: 1.5rem; font-size: .95rem; font-weight: 600; text-transform: uppercase; letter-spacing: .08em; color: var(--muted)">Items in Cart ({{ $cartItems->count() }})</h5>

          @foreach($cartItems as $item)
            <div class="cart-item">
              <div class="cart-item-check">
                <input
                  type="checkbox"
                  class="cart-item-select"
                  value="{{ $item->cart_item_id }}"
                  data-line-total="{{ $item->product->price * $item->quantity }}"
                  checked
                >
              </div>
              <div class="cart-item-img">
                @if($item->product->image_url)
                  <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                @else
                  <span class="cart-item-img-fallback"><i class="bi bi-image"></i></span>
                @endif
              </div>
              <div class="cart-item-details">
                <h5>{{ $item->product->name }}</h5>
                <p>{{ $item->product->brand->name ?? 'Unbranded' }}</p>
                <p style="color: var(--red); font-weight: 600; margin-top: .5rem">₱{{ number_format($item->product->price, 2) }}</p>
              </div>
              <div style="text-align: right">
                <div class="item-meta-row">
                  <div class="quantity-control">
                    <form action="{{ route('customer.cart.update-qty') }}" method="post" style="display: flex; align-items: center; gap: .25rem">
                      @csrf
                      <input type="hidden" name="cart_item_id" value="{{ $item->cart_item_id }}">
                      <button type="submit" name="action" value="increase" class="qty-btn" title="Increase quantity">+</button>
                      <span class="qty-display">{{ $item->quantity }}</span>
                      <button type="submit" name="action" value="decrease" class="qty-btn" title="Decrease quantity">−</button>
                    </form>
                  </div>
                  <div class="cart-item-price">₱{{ number_format($item->product->price * $item->quantity, 2) }}</div>
                </div>
                <form action="{{ route('customer.cart.remove') }}" method="post" style="margin-top: .5rem">
                  @csrf
                  <input type="hidden" name="cart_item_id" value="{{ $item->cart_item_id }}">
                  <button type="submit" class="remove-btn" title="Remove from cart">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </div>
          @endforeach
        </div>

        <div class="col-lg-4">
          <div class="order-summary">
            <label class="summary-title">Order Summary</label>
            <div class="summary-row">
              <span>Subtotal</span>
              <span id="summary-subtotal">₱{{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="summary-row">
              <span>Shipping</span>
              <span id="summary-shipping">₱{{ number_format($shipping, 2) }}</span>
            </div>
            <div class="summary-row total">
              <span>Total</span>
              <span id="summary-total">₱{{ number_format($total, 2) }}</span>
            </div>
            <form action="{{ route('customer.checkout.index') }}" method="get" id="checkout-selection-form">
              <div id="selected-cart-item-inputs"></div>
              <button type="submit" class="btn-checkout" id="proceed-checkout-btn">Proceed to Checkout</button>
            </form>
            <a href="{{ route('customer.shop.index') }}" class="btn-continue-shopping">Continue Shopping</a>
          </div>
        </div>
      </div>
    @else
      <div class="empty-cart">
        <div class="empty-cart-icon">🛒</div>
        <h3>Your cart is empty</h3>
        <p>Looks like you haven't added any items yet.</p>
        <a href="{{ route('customer.shop.index') }}" class="btn-submit" style="display: inline-block; text-decoration: none">Continue Shopping</a>
      </div>
    @endif
  </div>
</section>

@push('scripts')
<script>
  const itemCheckboxes = document.querySelectorAll('.cart-item-select');
  const selectedInputsContainer = document.getElementById('selected-cart-item-inputs');
  const proceedCheckoutBtn = document.getElementById('proceed-checkout-btn');
  const summarySubtotal = document.getElementById('summary-subtotal');
  const summaryShipping = document.getElementById('summary-shipping');
  const summaryTotal = document.getElementById('summary-total');
  const shippingAmount = @json($shipping);

  if (selectedInputsContainer && proceedCheckoutBtn && summarySubtotal && summaryShipping && summaryTotal && itemCheckboxes.length) {
    function formatPeso(value) {
      return '₱' + Number(value).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function syncSelectedItems() {
      const selectedItems = Array.from(itemCheckboxes).filter((checkbox) => checkbox.checked);

      selectedInputsContainer.innerHTML = '';
      selectedItems.forEach((checkbox) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'selected_cart_item_ids[]';
        input.value = checkbox.value;
        selectedInputsContainer.appendChild(input);
      });

      const subtotal = selectedItems.reduce((sum, checkbox) => {
        return sum + Number(checkbox.dataset.lineTotal || 0);
      }, 0);
      const shipping = subtotal > 0 ? shippingAmount : 0;
      const total = subtotal + shipping;

      summarySubtotal.textContent = formatPeso(subtotal);
      summaryShipping.textContent = formatPeso(shipping);
      summaryTotal.textContent = formatPeso(total);
      proceedCheckoutBtn.disabled = selectedItems.length === 0;
    }

    itemCheckboxes.forEach((checkbox) => {
      checkbox.addEventListener('change', syncSelectedItems);
    });

    syncSelectedItems();
  }
</script>
@endpush

@endsection
