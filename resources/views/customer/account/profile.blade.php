@extends('layouts.base')

@section('title', 'Profile Settings - LaptopHub')

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

  body {
    background: var(--paper);
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
    margin-bottom: 1.5rem;
  }

  .page-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    margin-bottom: .35rem;
  }

  .account-shell {
    max-width: 920px;
    margin-top: 0;
  }

  .account-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: .75rem;
    margin-bottom: 1rem;
  }

  .account-heading {
    font-size: 1.55rem;
    font-weight: 700;
    margin: 0;
  }

  .account-sub {
    margin: 0;
    color: #6c757d;
    font-size: .92rem;
  }

  .account-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }

  .panel-card {
    border: 1px solid #e9e2d8;
    border-radius: 10px;
    background: #fff;
  }

  .panel-head {
    display: flex;
    align-items: center;
    gap: .5rem;
    border-bottom: 1px solid #efe9df;
    padding: .85rem 1rem;
    font-size: .88rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
  }

  .panel-body {
    padding: 1rem;
  }

  @media (max-width: 900px) {
    .account-grid {
      grid-template-columns: 1fr;
    }
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
    <h1>Account Settings</h1>
    <p style="margin:0;color:rgba(255,255,255,.8)">Manage your profile and security details</p>
  </div>
</div>

<div class="container py-5 account-shell">
  <div class="account-header">
    <div>
      <h2 class="account-heading">Profile Center</h2>
      <p class="account-sub">Manage your profile info and security in one place.</p>
    </div>
    <a href="{{ route('customer.account.addresses') }}" class="btn btn-outline-secondary btn-sm">Manage Addresses</a>
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

  <div class="account-grid">
    <div class="panel-card" id="profile">
      <div class="panel-head"><i class="bi bi-person-lines-fill"></i>Profile Details</div>
      <div class="panel-body">
        <form action="{{ route('customer.account.profile.update') }}" method="post">
          @csrf
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" value="{{ old('full_name', auth()->user()->full_name) }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', auth()->user()->contact_number) }}">
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-danger">Save Profile</button>
            <a href="{{ route('customer.shop.index') }}" class="btn btn-outline-secondary">Back</a>
          </div>
        </form>
      </div>
    </div>

    <div class="panel-card" id="security">
      <div class="panel-head"><i class="bi bi-shield-lock-fill"></i>Security</div>
      <div class="panel-body">
        <form action="{{ route('customer.account.password.update') }}" method="post">
          @csrf
          <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="new_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-danger">Update Password</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
