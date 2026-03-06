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
@php
  $selectedPaymentMethod = (int) old('payment_method', request()->query('payment_method', 1));
  $activeAddressId = (int) old('address_id', $selectedAddressId ?? 0);
@endphp

<!-- NAVBAR -->
<nav class="navbar d-flex align-items-center justify-content-between">
  <a href="{{ route('index') }}" class="navbar-brand">Laptop<span>Hub</span></a>
  <div class="d-flex gap-2 align-items-center">
    <a href="{{ route('index') }}" class="nav-pill outline">Home</a>
    <a href="{{ route('customer.shop.index') }}" class="nav-pill outline">Shop</a>
    <a href="{{ route('customer.cart.index') }}" class="nav-pill outline">Cart</a>
    <a href="{{ route('customer.orders.index') }}" class="nav-pill outline">Orders</a>
    @include('customer.partials.account-dropdown')
  </div>
</nav>

<div class="page-header">
  <div class="container">
    <h1>Checkout</h1>
  </div>
</div>

<section class="py-5">
  <div class="container">
    @if(session('success'))
      <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger mb-3">{{ session('error') }}</div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger mb-3">{{ $errors->first() }}</div>
    @endif

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
        <div class="checkout-section">
          <label class="section-title">Shipping Address</label>

          @if(isset($addresses) && $addresses->count())
            <div style="display:grid;gap:.75rem">
              @foreach($addresses as $address)
                <div style="border:1px solid var(--border);border-radius:6px;padding:.85rem;background:#fff">
                  <div style="display:grid;grid-template-columns:minmax(0,1fr) auto;gap:1rem;align-items:start;">
                    <div style="font-size:.84rem;line-height:1.55;flex:1">
                      <strong>{{ $address->label ?: 'Saved Address' }}</strong>
                      @if($address->is_default)
                        <span style="font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;color:var(--red);margin-left:.35rem">Default</span>
                      @endif
                      @if($activeAddressId === (int) $address->address_id)
                        <span style="font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;color:var(--blue);margin-left:.35rem">Selected</span>
                      @endif
                      <br>
                      {{ $address->recipient_name }}
                      <br>
                      {{ $address->phone }}
                      <br>
                      {{ $address->street_address }}, {{ $address->city }}, {{ $address->region }} {{ $address->postal_code }}
                    </div>

                    <div style="display:flex;gap:.45rem;align-items:center;justify-content:flex-end;flex-wrap:wrap;align-self:start;">
                      @if($activeAddressId !== (int) $address->address_id)
                        <form action="{{ route('customer.checkout.index') }}" method="get" style="margin:0">
                          @foreach($selectedCartItemIds as $selectedCartItemId)
                            <input type="hidden" name="selected_cart_item_ids[]" value="{{ $selectedCartItemId }}">
                          @endforeach
                          <input type="hidden" name="payment_method" value="{{ $selectedPaymentMethod }}">
                          <input type="hidden" name="selected_address_id" value="{{ $address->address_id }}">
                          <button type="submit" class="btn-submit" style="padding:.45rem .65rem;font-size:.72rem;letter-spacing:.04em;width:auto" title="Use this address" aria-label="Use this address">
                            <i class="bi bi-check2-circle"></i>
                          </button>
                        </form>
                      @endif

                      @if(!$address->is_default)
                        <form action="{{ route('customer.checkout.addresses.default', $address->address_id) }}" method="post" style="margin:0">
                          @csrf
                          @foreach($selectedCartItemIds as $selectedCartItemId)
                            <input type="hidden" name="selected_cart_item_ids[]" value="{{ $selectedCartItemId }}">
                          @endforeach
                          <input type="hidden" name="payment_method" value="{{ $selectedPaymentMethod }}">
                          <button type="submit" class="btn-submit" style="padding:.45rem .65rem;font-size:.72rem;letter-spacing:.04em;width:auto" title="Set as default address" aria-label="Set as default address">
                            <i class="bi bi-star"></i>
                          </button>
                        </form>
                      @endif

                      <form action="{{ route('customer.checkout.addresses.delete', $address->address_id) }}" method="post" style="margin:0" onsubmit="return confirm('Remove this address?')">
                        @csrf
                        @foreach($selectedCartItemIds as $selectedCartItemId)
                          <input type="hidden" name="selected_cart_item_ids[]" value="{{ $selectedCartItemId }}">
                        @endforeach
                        <input type="hidden" name="payment_method" value="{{ $selectedPaymentMethod }}">
                        <button type="submit" class="btn-submit btn-back" style="padding:.45rem .65rem;font-size:.72rem;letter-spacing:.04em;width:auto;margin-top:0" title="Delete this address" aria-label="Delete this address">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>

                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p style="font-size:.86rem;color:var(--muted);margin-bottom:0">No saved addresses yet. Add one below to continue checkout.</p>
          @endif
        </div>

        <div class="checkout-section">
          <label class="section-title">Add New Address</label>

          <form action="{{ route('customer.checkout.addresses.store') }}" method="post">
            @csrf
            @foreach($selectedCartItemIds as $selectedCartItemId)
              <input type="hidden" name="selected_cart_item_ids[]" value="{{ $selectedCartItemId }}">
            @endforeach
            <input type="hidden" name="payment_method" value="{{ $selectedPaymentMethod }}">

            <div class="form-row">
              <div class="form-group">
                <label>Label (Home, Office, etc.)</label>
                <input type="text" name="label" class="form-control" value="{{ old('label') }}">
              </div>
              <div class="form-group">
                <label>Recipient Name</label>
                <input type="text" name="recipient_name" class="form-control" value="{{ old('recipient_name', auth()->user()->full_name) }}" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->contact_number) }}" required>
              </div>
              <div class="form-group">
                <label>Region</label>
                <input type="text" name="region" class="form-control" value="{{ old('region') }}" required>
              </div>
            </div>

            <div class="form-group full">
              <label>Street Address</label>
              <input type="text" name="street_address" class="form-control" value="{{ old('street_address') }}" required>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>City/Municipality</label>
                <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
              </div>
              <div class="form-group">
                <label>Postal Code</label>
                <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}" required>
              </div>
            </div>

            <div class="terms-row" style="margin-bottom:1rem">
              <input type="checkbox" id="set-default-address" name="set_default" value="1">
              <label for="set-default-address" class="terms-label">Set as default shipping address</label>
            </div>

            <button type="submit" class="btn-submit" style="width:auto;padding:.7rem 1rem">Save Address</button>
          </form>
        </div>

        <form action="{{ route('customer.checkout.process') }}" method="post" id="checkoutForm">
          @csrf
          @foreach($selectedCartItemIds as $selectedCartItemId)
            <input type="hidden" name="selected_cart_item_ids[]" value="{{ $selectedCartItemId }}">
          @endforeach
          <input type="hidden" name="address_id" value="{{ $activeAddressId }}">

          <div class="checkout-section">
            <label class="section-title">Payment Method</label>

            <div class="payment-options">
              <div class="payment-option">
                <input type="radio" id="method-cod" name="payment_method" value="1" {{ $selectedPaymentMethod === 1 ? 'checked' : '' }}>
                <label for="method-cod">
                  <i class="bi bi-cash-coin"></i> Cash on Delivery
                </label>
              </div>
              <div class="payment-option">
                <input type="radio" id="method-online" name="payment_method" value="2" {{ $selectedPaymentMethod === 2 ? 'checked' : '' }}>
                <label for="method-online">
                  <i class="bi bi-credit-card"></i> Online Payment
                </label>
              </div>
            </div>

            @if($selectedPaymentMethod === 2)
              <div>
                <div class="form-row">
                  <div class="form-group">
                    <label>Cardholder Name</label>
                    <input type="text" name="card_name" class="form-control" value="{{ old('card_name') }}">
                  </div>
                  <div class="form-group">
                    <label>Card Number</label>
                    <input type="text" name="card_number" class="form-control" placeholder="1234 5678 9012 3456" value="{{ old('card_number') }}">
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label>Expiry Date</label>
                    <input type="text" name="card_expiry" class="form-control" placeholder="MM/YY" value="{{ old('card_expiry') }}">
                  </div>
                  <div class="form-group">
                    <label>CVV</label>
                    <input type="text" name="card_cvv" class="form-control" placeholder="123" value="{{ old('card_cvv') }}">
                  </div>
                </div>
              </div>
            @endif
          </div>

          <div class="terms-row">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms" class="terms-label">
              I agree to the <a href="{{ route('legal.terms') }}" style="color: var(--blue)" target="_blank" rel="noopener noreferrer">Terms and Conditions</a> and <a href="{{ route('legal.privacy') }}" style="color: var(--blue)" target="_blank" rel="noopener noreferrer">Privacy Policy</a>
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
  document.querySelectorAll('input[name="payment_method"]').forEach((radio) => {
    radio.addEventListener('change', function () {
      const url = new URL(window.location.href);
      url.searchParams.set('payment_method', this.value);
      if ({{ (int) $activeAddressId }} > 0) {
        url.searchParams.set('selected_address_id', '{{ (int) $activeAddressId }}');
      }
      window.location.href = url.toString();
    });
  });
</script>
@endpush

@endsection
