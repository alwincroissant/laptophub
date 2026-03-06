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
  <h2 class="mb-3" style="font-size:1.25rem">Address Book</h2>

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
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <h5 class="mb-3">Add Address</h5>
          <form action="{{ route('customer.account.addresses.store') }}" method="post">
            @csrf
            <div class="mb-2"><input class="form-control" type="text" name="label" placeholder="Label (Home/Office)" value="{{ old('label') }}"></div>
            <div class="mb-2"><input class="form-control" type="text" name="recipient_name" placeholder="Recipient Name" value="{{ old('recipient_name', auth()->user()->full_name) }}" required></div>
            <div class="mb-2"><input class="form-control" type="text" name="phone" placeholder="Phone" value="{{ old('phone', auth()->user()->contact_number) }}" required></div>
            <div class="mb-2"><input class="form-control" type="text" name="region" placeholder="Region" value="{{ old('region') }}" required></div>
            <div class="mb-2"><input class="form-control" type="text" name="city" placeholder="City" value="{{ old('city') }}" required></div>
            <div class="mb-2"><input class="form-control" type="text" name="postal_code" placeholder="Postal Code" value="{{ old('postal_code') }}" required></div>
            <div class="mb-3"><input class="form-control" type="text" name="street_address" placeholder="Street Address" value="{{ old('street_address') }}" required></div>
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
          <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
              <div class="d-flex justify-content-between">
                <strong>{{ $address->label ?: 'Address' }}</strong>
                @if($address->is_default)
                  <span class="badge text-bg-danger">Default</span>
                @endif
              </div>
              <div class="small mt-2">{{ $address->recipient_name }} | {{ $address->phone }}</div>
              <div class="small text-muted">{{ $address->street_address }}, {{ $address->city }}, {{ $address->region }} {{ $address->postal_code }}</div>

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
                <summary style="cursor:pointer;font-size:.85rem;color:#1a3a5c">Edit address</summary>
                <form action="{{ route('customer.account.addresses.update', $address->address_id) }}" method="post" class="mt-3">
                  @csrf
                  @method('PUT')
                  <div class="mb-2"><input class="form-control form-control-sm" type="text" name="label" value="{{ $address->label }}" placeholder="Label"></div>
                  <div class="mb-2"><input class="form-control form-control-sm" type="text" name="recipient_name" value="{{ $address->recipient_name }}" required></div>
                  <div class="mb-2"><input class="form-control form-control-sm" type="text" name="phone" value="{{ $address->phone }}" required></div>
                  <div class="mb-2"><input class="form-control form-control-sm" type="text" name="region" value="{{ $address->region }}" required></div>
                  <div class="mb-2"><input class="form-control form-control-sm" type="text" name="city" value="{{ $address->city }}" required></div>
                  <div class="mb-2"><input class="form-control form-control-sm" type="text" name="postal_code" value="{{ $address->postal_code }}" required></div>
                  <div class="mb-2"><input class="form-control form-control-sm" type="text" name="street_address" value="{{ $address->street_address }}" required></div>
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
