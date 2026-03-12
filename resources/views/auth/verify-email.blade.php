@extends('layouts.base')

@section('title', 'Email Verification | LaptopHub')

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

    body {
      font-family: 'Libre Franklin', sans-serif;
      background: var(--paper);
      color: var(--ink);
      overflow-x: hidden;
    }

    /* Navbar styles */
    .navbar {
      background: #000;
      border-bottom: 1px solid var(--red);
      padding: 1rem 2rem;
      position: sticky;
      top: 0;
      z-index: 1000;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar-brand {
      font-family: 'Playfair Display', serif;
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--white);
      text-decoration: none;
      letter-spacing: -1px;
    }

    .navbar-brand span { color: var(--red); }

    .nav-pill {
      display: inline-block;
      padding: 0.5rem 1.2rem;
      border-radius: 6px;
      font-size: 0.85rem;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.2s ease;
      border: 1px solid #333;
      color: var(--white);
      background-color: #000;
    }

    .nav-pill.outline {
      background: transparent;
      color: var(--white);
    }

    .nav-pill.outline:hover {
      background: #333;
      border-color: #555;
    }

    .nav-pill.solid {
      background: var(--red);
      color: #fff;
      border-color: var(--red);
    }

    .nav-pill.solid:hover {
      background: var(--red-dk);
      border-color: var(--red-dk);
    }

    /* Verification container */
    .verification-container {
      min-height: calc(100vh - 200px);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
      margin-top: 80px;
    }

    .verification-card {
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 2px 16px rgba(0,0,0,0.09);
      padding: 0;
      border: 1px solid var(--border);
      max-width: 500px;
      width: 100%;
      overflow: hidden;
    }

    .verification-header {
      background: linear-gradient(135deg, var(--red) 0%, var(--blue) 100%);
      color: #fff;
      padding: 2.5rem 2rem;
      text-align: center;
    }

    .verification-header .brand {
      font-family: 'Playfair Display', serif;
      font-size: 2.1rem;
      font-weight: 700;
      letter-spacing: -1px;
      margin-bottom: 0.5rem;
    }

    .verification-header .brand span {
      color: var(--gold);
    }

    .verification-header .welcome {
      font-size: 1.1rem;
      opacity: 0.95;
    }

    .verification-body {
      padding: 2.5rem 2rem;
      text-align: center;
    }

    .verification-title {
      font-size: 1.3rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: var(--red);
      font-family: 'Playfair Display', serif;
    }

    .verification-message {
      font-size: 1.05rem;
      margin-bottom: 2rem;
      color: var(--ink);
      line-height: 1.6;
    }

    .form-label {
      display: block;
      text-align: left;
      font-weight: 500;
      margin-bottom: 0.5rem;
      color: var(--ink);
    }

    .form-control {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid var(--border);
      border-radius: 6px;
      font-size: 1rem;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
      outline: 0;
      border-color: var(--blue);
      box-shadow: 0 0 0 0.2rem rgba(26, 58, 92, 0.25);
    }

    .btn-verify {
      display: inline-block;
      background: linear-gradient(90deg, var(--red) 0%, var(--blue) 100%);
      color: #fff !important;
      font-weight: 600;
      font-size: 1.08rem;
      padding: 0.875rem 2.25rem;
      border-radius: 6px;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      letter-spacing: .03em;
    }

    .btn-verify:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(192,57,43,0.3);
    }

    .alert {
      padding: 0.75rem 1rem;
      margin-bottom: 1.5rem;
      border-radius: 6px;
      border: 1px solid transparent;
    }

    .alert-success {
      color: #0f5132;
      background-color: #d1e7dd;
      border-color: #badbcc;
    }

    .alert-danger {
      color: #842029;
      background-color: #f8d7da;
      border-color: #f5c2c7;
    }

    .verification-footer {
      font-size: 0.9rem;
      color: var(--muted);
      margin-top: 2rem;
      text-align: center;
    }

    .verification-footer a {
      color: var(--blue);
      text-decoration: none;
    }

    .verification-footer a:hover {
      text-decoration: underline;
    }
</style>
@endpush

@section('content')
<!-- ════════════════════════════════════
     NAVBAR
════════════════════════════════════ -->
<nav class="navbar d-flex align-items-center justify-content-between">
  <a href="{{ route('index') }}" class="navbar-brand">Laptop<span>Hub</span></a>
  <div class="d-flex gap-2 align-items-center">
    @auth
      <a href="{{ route('index') }}" class="nav-pill outline d-none d-md-inline">Home</a>
      <a href="{{ route('customer.shop.index') }}" class="nav-pill outline d-none d-md-inline">Shop</a>
      <a href="{{ route('customer.cart.index') }}" class="nav-pill outline d-none d-md-inline">Cart</a>
      <a href="{{ route('customer.orders.index') }}" class="nav-pill outline d-none d-md-inline">Orders</a>
    @else
      <a href="{{ route('index') }}" class="nav-pill outline d-none d-md-inline">Home</a>
      <a href="{{ route('customer.shop.index') }}" class="nav-pill outline d-none d-md-inline">Shop</a>
    @endauth
    @guest
      <a href="#login"    class="nav-pill outline">Log In</a>
      <a href="#register" class="nav-pill solid">Register</a>
    @endguest
    @auth
      @include('customer.partials.account-dropdown')
    @endauth
  </div>
</nav>

<div class="verification-container">
  <div class="verification-card">
    <div class="verification-header">
      <div class="brand">Laptop<span>Hub</span></div>
      <div class="welcome">Welcome to LaptopHub!</div>
    </div>
    <div class="verification-body">
      <div class="verification-title">Email Verification Required</div>
      <div class="verification-message">
        Thank you for registering!<br>
        Before proceeding, please check your email for a verification link.<br>
        If you did not receive the email, enter your email address below to request another.
      </div>
      
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif
      
      <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required>
        </div>
        <button type="submit" class="btn-verify">Resend Verification Email</button>
      </form>
      
      <div class="verification-footer">
        If you did not create an account, no further action is required.<br><br>
        <a href="{{ route('index') }}">← Back to Home</a>
      </div>
    </div>
  </div>
</div>
@endsection
