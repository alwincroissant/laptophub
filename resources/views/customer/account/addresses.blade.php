@extends('layouts.base')

@section('title', 'Manage Addresses - LaptopHub')

@push('styles')
<style>
  :root {
    --ink: #0c0c0c;
    --paper: #f5f1ea;
    --red: #c0392b;
    --red-dk: #962d22;
    --blue: #1a3a5c;
    --border: #d8d2c8;
  }

  body { background: var(--paper); }

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
    margin-bottom: 1.5rem;
  }

  .page-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    margin-bottom: .35rem;
  }

  .address-shell { margin-top: 0; }

  .section-card {
    border: 1px solid #e9e2d8;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 8px 22px rgba(14, 18, 25, .06);
  }

  .section-head {
    padding: 1rem 1.1rem;
    border-bottom: 1px solid #efe9df;
  }

  .section-title {
    margin: 0;
    font-size: .95rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: #222;
  }

  .section-subtitle {
    margin: .3rem 0 0;
    font-size: .82rem;
    color: #6c757d;
  }

  .section-body {
    padding: 1rem 1.1rem 1.1rem;
  }

  .field-label {
    display: block;
    font-size: .76rem;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: #5f5a51;
    margin-bottom: .4rem;
  }

  .address-line {
    margin: 0;
    font-size: .84rem;
    color: #4d4d4d;
    line-height: 1.5;
  }

  .address-line + .address-line {
    margin-top: .3rem;
  }

  .address-line strong {
    color: #1f1f1f;
    min-width: 78px;
    display: inline-block;
  }

  .edit-summary {
    cursor: pointer;
    font-size: .82rem;
    color: #1a3a5c;
    font-weight: 600;
  }
</style>
@endpush

@section('content')
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
    <h1>Manage Addresses</h1>
    <p style="margin:0;color:rgba(255,255,255,.8)">Save, update, and set your default delivery address</p>
  </div>
</div>

<div class="container py-5 address-shell">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
      <h2 class="mb-1" style="font-size:1.3rem">Address Book</h2>
      <p class="mb-0" style="font-size:.88rem;color:#6c757d">Keep your delivery details updated for faster checkout.</p>
    </div>
    <a href="{{ route('customer.account.profile') }}" class="btn btn-outline-secondary btn-sm">Back to Profile</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <div class="row g-4">
    <div class="col-lg-6">
      <div class="section-card">
        <div class="section-head">
          <h5 class="section-title">Add New Address</h5>
          <p class="section-subtitle">Enter complete and accurate details for delivery.</p>
        </div>
        <div class="section-body">
          <form action="{{ route('customer.account.addresses.store') }}" method="post">
            @csrf
            <div class="mb-3">
              <label class="field-label" for="new-label">Address Label</label>
              <input id="new-label" class="form-control" type="text" name="label" placeholder="Home, Office, Condo" value="{{ old('label') }}">
            </div>
            <div class="mb-3">
              <label class="field-label" for="new-recipient">Recipient Name</label>
              <input id="new-recipient" class="form-control" type="text" name="recipient_name" placeholder="Full name of receiver" value="{{ old('recipient_name', auth()->user()->full_name) }}" required>
            </div>
            <div class="mb-3">
              <label class="field-label" for="new-phone">Phone Number</label>
              <input id="new-phone" class="form-control" type="text" name="phone" placeholder="09XX XXX XXXX" value="{{ old('phone', auth()->user()->contact_number) }}" required>
            </div>
            <div class="mb-3">
              <label class="field-label" for="new-region">Region / Province</label>
              <input id="new-region" class="form-control" type="text" name="region" placeholder="e.g. Metro Manila" value="{{ old('region') }}" required>
            </div>
            <div class="mb-3">
              <label class="field-label" for="new-city">City / Municipality</label>
              <input id="new-city" class="form-control" type="text" name="city" placeholder="e.g. Quezon City" value="{{ old('city') }}" required>
            </div>
            <div class="mb-3">
              <label class="field-label" for="new-postal">Postal Code</label>
              <input id="new-postal" class="form-control" type="text" name="postal_code" placeholder="e.g. 1100" value="{{ old('postal_code') }}" required>
            </div>
            <div class="mb-3">
              <label class="field-label" for="new-street">Street Address</label>
              <input id="new-street" class="form-control" type="text" name="street_address" placeholder="House/Unit, street, barangay" value="{{ old('street_address') }}" required>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefault">
              <label class="form-check-label" for="isDefault">Set as default</label>
            </div>
            <button class="btn btn-danger" type="submit">Save Address</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="d-grid gap-3">
        @forelse($addresses as $address)
          <div class="section-card">
            <div class="section-body">
              <div class="d-flex justify-content-between">
                <strong>{{ $address->label ?: 'Address' }}</strong>
                @if($address->is_default)
                  <span class="badge text-bg-danger">Default</span>
                @endif
              </div>
              <div class="mt-2">
                <p class="address-line"><strong>Recipient:</strong> {{ $address->recipient_name }}</p>
                <p class="address-line"><strong>Phone:</strong> {{ $address->phone }}</p>
                <p class="address-line"><strong>Address:</strong> {{ $address->street_address }}, {{ $address->city }}, {{ $address->region }} {{ $address->postal_code }}</p>
              </div>

              <div class="d-flex gap-2 mt-3 flex-wrap">
                @if(!$address->is_default)
                  <form action="{{ route('customer.account.addresses.default', $address->address_id) }}" method="post">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger" type="submit">Set Default</button>
                  </form>
                @endif
                <form action="{{ route('customer.account.addresses.destroy', $address->address_id) }}" method="post" onsubmit="return confirm('Delete this address? This cannot be undone.')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" type="submit" title="Delete address" aria-label="Delete address">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>

              <details class="mt-3">
                <summary class="edit-summary">Edit this address</summary>
                <form action="{{ route('customer.account.addresses.update', $address->address_id) }}" method="post" class="mt-3">
                  @csrf
                  @method('PUT')
                  <div class="mb-2">
                    <label class="field-label" for="label-{{ $address->address_id }}">Address Label</label>
                    <input id="label-{{ $address->address_id }}" class="form-control form-control-sm" type="text" name="label" value="{{ $address->label }}" placeholder="Home, Office, Condo">
                  </div>
                  <div class="mb-2">
                    <label class="field-label" for="recipient-{{ $address->address_id }}">Recipient Name</label>
                    <input id="recipient-{{ $address->address_id }}" class="form-control form-control-sm" type="text" name="recipient_name" value="{{ $address->recipient_name }}" required>
                  </div>
                  <div class="mb-2">
                    <label class="field-label" for="phone-{{ $address->address_id }}">Phone Number</label>
                    <input id="phone-{{ $address->address_id }}" class="form-control form-control-sm" type="text" name="phone" value="{{ $address->phone }}" required>
                  </div>
                  <div class="mb-2">
                    <label class="field-label" for="region-{{ $address->address_id }}">Region / Province</label>
                    <input id="region-{{ $address->address_id }}" class="form-control form-control-sm" type="text" name="region" value="{{ $address->region }}" required>
                  </div>
                  <div class="mb-2">
                    <label class="field-label" for="city-{{ $address->address_id }}">City / Municipality</label>
                    <input id="city-{{ $address->address_id }}" class="form-control form-control-sm" type="text" name="city" value="{{ $address->city }}" required>
                  </div>
                  <div class="mb-2">
                    <label class="field-label" for="postal-{{ $address->address_id }}">Postal Code</label>
                    <input id="postal-{{ $address->address_id }}" class="form-control form-control-sm" type="text" name="postal_code" value="{{ $address->postal_code }}" required>
                  </div>
                  <div class="mb-2">
                    <label class="field-label" for="street-{{ $address->address_id }}">Street Address</label>
                    <input id="street-{{ $address->address_id }}" class="form-control form-control-sm" type="text" name="street_address" value="{{ $address->street_address }}" required>
                  </div>
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="is_default" value="1" id="default-{{ $address->address_id }}" {{ $address->is_default ? 'checked' : '' }}>
                    <label class="form-check-label small" for="default-{{ $address->address_id }}">Set as default</label>
                  </div>
                  <button class="btn btn-sm btn-danger" type="submit">Save Changes</button>
                </form>
              </details>
            </div>
          </div>
        @empty
          <div class="alert alert-info">No addresses yet.</div>
        @endforelse

      </div>
    </div>
  </div>
</div>
@endsection
