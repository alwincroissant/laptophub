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

  .profile-photo-card {
    border: 1px solid #e9e2d8;
    border-radius: 10px;
    background: #fff;
    padding: 1rem;
    margin-bottom: 1rem;
  }

  .profile-picture-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: .85rem;
    border: 1px solid #efe9df;
    border-radius: 8px;
    background: #fcfbf9;
  }

  .profile-picture-preview {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    border: 1px solid #d8d2c8;
    object-fit: cover;
    background: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    color: #7a756c;
    overflow: hidden;
  }

  .profile-picture-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .profile-picture-meta {
    font-size: .8rem;
    color: #6c757d;
    margin-top: .35rem;
  }

  .profile-picture-left {
    display: flex;
    align-items: center;
    gap: .9rem;
  }

  .profile-picture-title {
    font-size: .88rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    margin-bottom: .2rem;
  }

  .profile-picture-name {
    font-size: .92rem;
    color: #343a40;
    margin: 0;
  }

  .profile-picture-form {
    width: 100%;
  }

  .profile-picture-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .65rem;
  }

  .profile-file-input {
    max-width: 320px;
  }

  @media (max-width: 900px) {
    .account-grid {
      grid-template-columns: 1fr;
    }

    .profile-picture-wrap {
      flex-direction: column;
      align-items: flex-start;
    }

    .profile-file-input {
      max-width: 100%;
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

  <form action="{{ route('customer.account.profile.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="profile-photo-card">
      <div class="profile-picture-wrap">
        <div class="profile-picture-left">
          <div class="profile-picture-preview" aria-hidden="true">
            @if(auth()->user()->profile_image_url)
              <img src="{{ auth()->user()->profile_image_url }}" alt="{{ auth()->user()->full_name }} profile photo">
            @else
              <i class="bi bi-person"></i>
            @endif
          </div>
          <div>
            <div class="profile-picture-title">Profile Photo</div>
            <p class="profile-picture-name">{{ auth()->user()->full_name }}</p>
            <div class="profile-picture-meta">JPG, PNG, or WebP up to 2MB.</div>
          </div>
        </div>
        <div class="profile-picture-actions">
          <input type="file" name="profile_image" class="form-control profile-file-input @error('profile_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
          @error('profile_image')<div class="invalid-feedback" style="display:block;">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>

    <div class="account-grid">
      <div class="panel-card" id="profile">
        <div class="panel-head"><i class="bi bi-person-lines-fill"></i>Profile Details</div>
        <div class="panel-body">
            <div class="mb-3">
              <label class="form-label">First Name</label>
              <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', auth()->user()->first_name) }}" required>
              @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="form-label">Last Name</label>
              <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', auth()->user()->last_name) }}" required>
              @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}" required>
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="form-label">Contact Number</label>
              <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror" value="{{ old('contact_number', auth()->user()->contact_number) }}">
              @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
      </div>

      <div class="panel-card" id="security">
        <div class="panel-head"><i class="bi bi-shield-lock-fill"></i>Security</div>
        <div class="panel-body">
            <div class="mb-3">
              <label class="form-label">Current Password</label>
              <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
              @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
              <div class="form-text text-muted" style="font-size: .75rem;">Required only when updating password</div>
            </div>
            <div class="mb-3">
              <label class="form-label">New Password</label>
              <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
              @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="form-label">Confirm New Password</label>
              <input type="password" name="new_password_confirmation" class="form-control">
            </div>
        </div>
      </div>
    </div>
    
    <div class="mt-4 d-flex justify-content-end gap-2">
      <a href="{{ route('customer.shop.index') }}" class="btn btn-outline-secondary">Back</a>
      <button type="submit" class="btn btn-danger">Save Profile</button>
    </div>
  </form>

  <div class="panel-card mt-3 border-danger" id="danger-zone" style="border-color:#f5c2c7;background:#fffafb">
    <div class="panel-head text-danger" style="border-bottom-color:#f5c2c7;color:#842029"><i class="bi bi-exclamation-triangle-fill"></i>Danger Zone</div>
    <div class="panel-body">
      <h6 style="font-weight:700;margin-bottom:.2rem">Deactivate Account</h6>
      <p style="font-size:.85rem;color:var(--muted);margin-bottom:1rem">Deactivating your account will immediately log you out and prevent further sign-ins. Your past orders will remain in our system for record-keeping, but your profile will be inaccessible until you contact support to reactivate.</p>
      <form action="{{ route('customer.account.deactivate') }}" method="post" onsubmit="return confirm('Are you sure you want to deactivate your account? You will be immediately logged out and unable to access your profile.')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger">Deactivate Account</button>
      </form>
    </div>
  </div>
</div>
@endsection
