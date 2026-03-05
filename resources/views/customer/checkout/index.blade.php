@extends('layouts.base')

@section('title', 'Checkout - LaptopHub')

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

    /* ── CHECKOUT STEPS ── */
    .checkout-steps {
      display: flex;
      justify-content: space-around;
      margin-bottom: 3rem;
      position: relative;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .checkout-step {
      display: flex;
      flex-direction: column;
      align-items: center;
      z-index: 2;
    }

    .step-number {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background: var(--border);
      color: var(--ink);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      margin-bottom: .5rem;
      font-size: .9rem;
    }

    .checkout-step.active .step-number {
      background: var(--red);
      color: #fff;
    }

    .step-label {
      font-size: .8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .08em;
    }

    /* ── FORM SECTIONS ── */
    .checkout-section {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 1px 0 rgba(0, 0, 0, .02);
    }

    .section-title {
      font-size: .95rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-bottom: 1.5rem;
      display: block;
      border-bottom: 1px solid var(--border);
      padding-bottom: .75rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      font-size: .75rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      font-weight: 600;
      color: var(--muted);
      margin-bottom: .4rem;
    }

    .form-control {
      width: 100%;
      background: #fff;
      border: 1.5px solid var(--border);
      border-radius: 4px;
      padding: .7rem 1rem;
      font-size: .88rem;
      color: var(--ink);
      font-family: 'Libre Franklin', sans-serif;
      transition: border-color .15s;
    }

    .form-control:focus {
      border-color: var(--blue);
      outline: none;
      box-shadow: 0 0 0 3px rgba(26, 58, 92, .08);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .form-group.full {
      grid-column: 1 / -1;
    }

    /* ── PAYMENT OPTIONS ── */
    .payment-options {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .payment-option {
      border: 1.5px solid var(--border);
      border-radius: 4px;
      padding: 1rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: .75rem;
      transition: border-color .15s, background .15s;
      min-height: 56px;
    }

    .payment-option:hover {
      border-color: var(--blue);
      background: rgba(26, 58, 92, .02);
    }

    .payment-option input[type="radio"] {
      cursor: pointer;
      margin: 0;
      flex-shrink: 0;
    }

    .payment-option label {
      cursor: pointer;
      font-weight: 600;
      font-size: .85rem;
      margin: 0;
      display: flex;
      align-items: center;
      gap: .4rem;
      line-height: 1.35;
    }

    /* ── ORDER SUMMARY ── */
    .order-summary {
      background: var(--cream);
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    .summary-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: .75rem;
      font-size: .9rem;
    }

    .summary-item.total {
      border-top: 1px solid var(--border);
      padding-top: .75rem;
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--red);
    }

    /* ── BUTTONS ── */
    .btn-submit {
      width: 100%;
      background: var(--red);
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 1rem;
      font-size: .9rem;
      font-weight: 600;
      letter-spacing: .05em;
      cursor: pointer;
      transition: background .15s;
      font-family: 'Libre Franklin', sans-serif;
      display: block;
      text-align: center;
      text-decoration: none;
      line-height: 1.2;
    }

    .btn-submit:hover {
      background: var(--red-dk);
    }

    .btn-back {
      background: transparent;
      color: var(--blue);
      border: 1px solid var(--blue);
      margin-top: 1rem;
    }

    .btn-back:hover {
      background: rgba(26, 58, 92, .05);
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

    .terms-row {
      display: flex;
      align-items: flex-start;
      gap: .75rem;
      margin-bottom: 1rem;
    }

    .terms-row input[type="checkbox"] {
      margin-top: .25rem;
      flex-shrink: 0;
    }

    .terms-label {
      text-transform: none;
      font-weight: 400;
      font-size: .85rem;
      margin: 0;
      line-height: 1.5;
      color: var(--ink);
    }

    @media (max-width: 992px) {
      .checkout-section,
      .order-summary {
        padding: 1.25rem;
      }

      .payment-options,
      .form-row {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 576px) {
      .page-header {
        padding: 2rem 0;
      }

      .checkout-steps {
        justify-content: flex-start;
      }

      .step-label {
        font-size: .72rem;
      }
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
    <a href="{{ route('customer.orders.index') }}" class="nav-pill outline">Orders</a>
    <form action="{{ route('logout') }}" method="post" class="m-0">
      @csrf
      <button type="submit" class="nav-pill solid" style="border:none;cursor:pointer">Sign Out</button>
    </form>
  </div>
</nav>

<div class="page-header">
  <div class="container">
    <h1>Checkout</h1>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <!-- Progress Steps -->
    <div class="checkout-steps">
      <div class="checkout-step active">
        <div class="step-number">1</div>
        <div class="step-label">Shipping</div>
      </div>
      <div class="checkout-step">
        <div class="step-number">2</div>
        <div class="step-label">Payment</div>
      </div>
      <div class="checkout-step">
        <div class="step-number">3</div>
        <div class="step-label">Confirm</div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-8">
        <!-- Shipping Information -->
        <form action="{{ route('customer.checkout.process') }}" method="post" id="checkoutForm">
          @csrf
          @if(isset($selectedCartItemIds) && $selectedCartItemIds->count())
            @foreach($selectedCartItemIds as $selectedCartItemId)
              <input type="hidden" name="selected_cart_item_ids[]" value="{{ $selectedCartItemId }}">
            @endforeach
          @endif

          <div class="checkout-section">
            <label class="section-title">Shipping Address</label>
            
            <div class="form-row">
              <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control" value="{{ auth()->user()->full_name }}" required>
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" name="phone" class="form-control" value="{{ auth()->user()->contact_number }}" required>
              </div>
              <div class="form-group">
                <label>Region</label>
                <input type="text" name="region" class="form-control" placeholder="e.g., Metro Manila" required>
              </div>
            </div>

            <div class="form-group full">
              <label>Street Address</label>
              <input type="text" name="street_address" class="form-control" placeholder="House number, street, subdivision" required>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>City/Municipality</label>
                <input type="text" name="city" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Postal Code</label>
                <input type="text" name="postal_code" class="form-control" placeholder="e.g., 1200" required>
              </div>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="checkout-section">
            <label class="section-title">Payment Method</label>
            
            <div class="payment-options">
              <div class="payment-option">
                <input type="radio" id="method-cod" name="payment_method" value="1" checked>
                <label for="method-cod">
                  <i class="bi bi-cash-coin"></i> Cash on Delivery
                </label>
              </div>
              <div class="payment-option">
                <input type="radio" id="method-online" name="payment_method" value="2">
                <label for="method-online">
                  <i class="bi bi-credit-card"></i> Online Payment
                </label>
              </div>
            </div>

            <div id="onlinePaymentFields" style="display: none;">
              <div class="form-row">
                <div class="form-group">
                  <label>Cardholder Name</label>
                  <input type="text" name="card_name" class="form-control">
                </div>
                <div class="form-group">
                  <label>Card Number</label>
                  <input type="text" name="card_number" class="form-control" placeholder="1234 5678 9012 3456">
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>Expiry Date</label>
                  <input type="text" name="card_expiry" class="form-control" placeholder="MM/YY">
                </div>
                <div class="form-group">
                  <label>CVV</label>
                  <input type="text" name="card_cvv" class="form-control" placeholder="123">
                </div>
              </div>
            </div>
          </div>

          <!-- Terms & Conditions -->
          <div class="terms-row">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms" class="terms-label">
              I agree to the <a href="#" style="color: var(--blue)">Terms and Conditions</a> and <a href="#" style="color: var(--blue)">Privacy Policy</a>
            </label>
          </div>

          <button type="submit" class="btn-submit">Complete Purchase</button>
          <a href="{{ route('customer.cart.index') }}" class="btn-submit btn-back">Back to Cart</a>
        </form>
      </div>

      <!-- Order Summary -->
      <div class="col-lg-4">
        <div class="order-summary">
          <h6 class="summary-title">Order Summary</h6>
          
          @foreach($cartItems as $item)
            <div class="summary-item">
              <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
              <span>₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
            </div>
          @endforeach

          <div style="border-top: 1px solid var(--border); margin-top: 1rem; padding-top: 1rem">
            <div class="summary-item">
              <span>Subtotal</span>
              <span>₱{{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="summary-item">
              <span>Shipping</span>
              <span>₱{{ number_format($shipping, 2) }}</span>
            </div>
            <div class="summary-item">
              <span>Tax (12%)</span>
              <span>₱{{ number_format($tax, 2) }}</span>
            </div>
            <div class="summary-item total">
              <span>Total</span>
              <span>₱{{ number_format($total, 2) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
  const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
  const onlinePaymentFields = document.getElementById('onlinePaymentFields');

  paymentMethodRadios.forEach(radio => {
    radio.addEventListener('change', function() {
      if (this.value === '2') {
        onlinePaymentFields.style.display = 'block';
        document.querySelector('input[name="card_name"]').required = true;
      } else {
        onlinePaymentFields.style.display = 'none';
        document.querySelector('input[name="card_name"]').required = false;
      }
    });
  });
</script>
@endpush

@endsection
