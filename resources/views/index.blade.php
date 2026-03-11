@extends('layouts.base')

@section('title', 'LaptopHub — Your Tech, Delivered')

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

    /* ── KEYFRAMES ── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(28px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to   { opacity: 1; }
    }
    @keyframes slideRight {
      from { transform: translateX(-40px); opacity: 0; }
      to   { transform: translateX(0);     opacity: 1; }
    }
    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(-2deg); }
      50%       { transform: translateY(-12px) rotate(-2deg); }
    }
    @keyframes pulse-ring {
      0%   { box-shadow: 0 0 0 0 rgba(192,57,43,.3); }
      70%  { box-shadow: 0 0 0 14px rgba(192,57,43,0); }
      100% { box-shadow: 0 0 0 0 rgba(192,57,43,0); }
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
    .nav-pill.solid { background: var(--red); color: #fff; }
    .nav-pill.solid:hover { background: var(--red-dk); }

    /* ── HERO ── */
    .hero {
      min-height: 100vh;
      display: grid;
      grid-template-columns: 1fr 1fr;
      position: relative;
      overflow: hidden;
      margin-top: 66px;
    }

    .hero-left {
      background: var(--blue);
      padding: 5rem 4rem 4rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }
    .hero-left::before {
      content: '';
      position: absolute;
      top: -120px; right: -120px;
      width: 400px; height: 400px;
      border-radius: 50%;
      background: rgba(192,57,43,.12);
      pointer-events: none;
    }
    .hero-left::after {
      content: '';
      position: absolute;
      bottom: -80px; left: -80px;
      width: 300px; height: 300px;
      border-radius: 50%;
      background: rgba(255,255,255,.04);
      pointer-events: none;
    }

    .hero-eyebrow {
      font-size: .7rem;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--red);
      margin-bottom: 1.25rem;
      font-weight: 600;
      animation: slideRight .7s ease both;
    }

    .hero-headline {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2.8rem, 5vw, 4.5rem);
      line-height: 1.05;
      color: #fff;
      margin-bottom: 1.5rem;
      animation: slideRight .7s .1s ease both;
    }
    .hero-headline em { font-style: italic; color: var(--red); }

    .hero-sub {
      font-size: 1rem;
      color: rgba(255,255,255,.65);
      line-height: 1.7;
      max-width: 400px;
      margin-bottom: 2.5rem;
      font-weight: 300;
      animation: slideRight .7s .2s ease both;
    }

    .hero-ctas {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      animation: fadeUp .7s .35s ease both;
    }
    .btn-hero-primary {
      background: var(--red);
      color: #fff;
      padding: .85rem 2rem;
      border-radius: 3px;
      font-weight: 600;
      font-size: .9rem;
      letter-spacing: .04em;
      text-decoration: none;
      border: 2px solid var(--red);
      animation: pulse-ring 2.5s .8s infinite;
      transition: background .15s;
    }
    .btn-hero-primary:hover { background: var(--red-dk); color: #fff; border-color: var(--red-dk); }
    .btn-hero-secondary {
      background: transparent;
      color: #fff;
      padding: .85rem 2rem;
      border-radius: 3px;
      font-weight: 500;
      font-size: .9rem;
      letter-spacing: .04em;
      text-decoration: none;
      border: 2px solid rgba(255,255,255,.3);
      transition: border-color .15s;
    }
    .btn-hero-secondary:hover { border-color: #fff; color: #fff; }

    .hero-stats {
      display: flex;
      gap: 2.5rem;
      margin-top: 3rem;
      padding-top: 2rem;
      border-top: 1px solid rgba(255,255,255,.12);
      animation: fadeIn .8s .5s ease both;
    }
    .hero-stat-num {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      color: #fff;
      line-height: 1;
    }
    .hero-stat-label {
      font-size: .7rem;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: rgba(255,255,255,.45);
      margin-top: .25rem;
    }

    /* ── HERO RIGHT (FORM PANEL) ── */
    .hero-right {
      background: var(--paper);
      display: flex;
      align-items: center;

      justify-content: center;
      padding: 5rem 3rem 3rem;
      position: relative;
    }

    /* diagonal divider */
    .hero-right::before {
      content: '';
      position: absolute;
      top: 0; bottom: 0; left: -36px;
      width: 72px;
      background: var(--paper);
      transform: skewX(-4deg);
      z-index: 1;
    }

    .form-panel {
      width: 100%;
      max-width: 420px;
      position: relative;
      z-index: 2;
      animation: fadeUp .8s .3s ease both;
    }

    /* ── TAB SWITCHING via :target ── */
    .tab-nav {
      display: flex;
      background: var(--cream);
      border: 1px solid var(--border);
      border-radius: 5px;
      padding: 4px;
      margin-bottom: 2rem;
    }
    .tab-nav a {
      flex: 1;
      text-align: center;
      padding: .6rem;
      font-size: .85rem;
      font-weight: 500;
      border-radius: 3px;
      color: var(--muted);
      text-decoration: none;
      transition: background .2s, color .2s;
    }
    .tab-nav a:hover { color: var(--ink); }

    /* Default: login pane visible, register hidden */
    .pane-login    { display: block; }
    .pane-register { display: none; }

    /* Default active tab = login */
    .tab-login-link    { background: var(--ink); color: #fff !important; }
    .tab-register-link { background: transparent; }

    /* When #register is the target, show register pane + flip active tab */
    body:has(#register:target) .pane-register { display: block; }
    body:has(#register:target) .pane-login    { display: none; }
    body:has(#register:target) .tab-register-link { background: var(--ink); color: #fff !important; }
    body:has(#register:target) .tab-login-link    { background: transparent; color: var(--muted) !important; }

    /* When #login is the target (or nothing targeted), show login pane */
    body:has(#login:target) .pane-login    { display: block; }
    body:has(#login:target) .pane-register { display: none; }
    body:has(#login:target) .tab-login-link    { background: var(--ink); color: #fff !important; }
    body:has(#login:target) .tab-register-link { background: transparent; color: var(--muted) !important; }

    /* ── FORM ELEMENTS ── */
    .form-label {
      font-size: .72rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      font-weight: 600;
      color: var(--muted);
      margin-bottom: .4rem;
    }
    .form-control {
      background: #fff;
      border: 1.5px solid var(--border);
      border-radius: 4px;
      padding: .7rem 1rem;
      font-size: .88rem;
      color: var(--ink);
      font-family: 'Libre Franklin', sans-serif;
      transition: border-color .15s, box-shadow .15s;
    }
    .form-control:focus {
      border-color: var(--blue);
      box-shadow: 0 0 0 3px rgba(26,58,92,.12);
      outline: none;
    }
    .input-icon { position: relative; }
    .input-icon i {
      position: absolute;
      right: .9rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
      font-size: .9rem;
    }
    .input-icon .form-control { padding-right: 2.5rem; }

    .btn-submit {
      width: 100%;
      background: var(--red);
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: .85rem;
      font-size: .9rem;
      font-weight: 600;
      letter-spacing: .05em;
      cursor: pointer;
      transition: background .15s, box-shadow .15s;
      font-family: 'Libre Franklin', sans-serif;
    }
    .btn-submit:hover {
      background: var(--red-dk);
      box-shadow: 0 4px 14px rgba(192,57,43,.35);
    }

    .divider-or {
      display: flex;
      align-items: center;
      gap: .75rem;
      margin: 1.25rem 0;
      font-size: .72rem;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .08em;
    }
    .divider-or::before, .divider-or::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--border);
    }

    .form-footer {
      font-size: .77rem;
      color: var(--muted);
      text-align: center;
      margin-top: 1.25rem;
    }
    .form-footer a { color: var(--blue); text-decoration: underline; }
    .auth-alert {
      border: 1px solid #f5c2c7;
      background: #f8d7da;
      color: #842029;
      border-radius: 4px;
      padding: .65rem .8rem;
      font-size: .78rem;
      margin-bottom: 1rem;
    }
    .auth-success {
      border: 1px solid #badbcc;
      background: #d1e7dd;
      color: #0f5132;
      border-radius: 4px;
      padding: .65rem .8rem;
      font-size: .78rem;
      margin-bottom: 1rem;
    }
    .field-error {
      color: var(--red-dk);
      font-size: .72rem;
      margin-top: .35rem;
    }

    .form-panel-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.6rem;
      margin-bottom: .35rem;
      color: var(--ink);
    }
    .form-panel-sub {
      font-size: .82rem;
      color: var(--muted);
      margin-bottom: 1.5rem;
    }

    /* ── FLOATING LAPTOP CARD ── */
    .laptop-card {
      position: absolute;
      bottom: 2.5rem;
      right: 2.5rem;
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: .9rem 1.1rem;
      display: flex;
      align-items: center;
      gap: .75rem;
      box-shadow: 0 8px 30px rgba(0,0,0,.1);
      animation: float 4s ease-in-out infinite;
      z-index: 5;
      max-width: 220px;
    }
    .laptop-card .lc-icon {
      width: 38px; height: 38px;
      background: var(--red);
      border-radius: 6px;
      display: flex; align-items: center; justify-content: center;
      color: #fff;
      font-size: 1.1rem;
      flex-shrink: 0;
    }
    .laptop-card .lc-name { font-size: .78rem; font-weight: 600; }
    .laptop-card .lc-price { font-size: .72rem; color: var(--red); font-weight: 600; }
    .laptop-card .lc-sub { font-size: .68rem; color: var(--muted); }

    /* ── TRUST BAND ── */
    .trust-band {
      background: var(--cream);
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      padding: 2.5rem 0;
    }
    .trust-item {
      display: flex;
      align-items: flex-start;
      gap: .9rem;
    }
    .trust-icon {
      width: 44px; height: 44px;
      border-radius: 6px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.2rem;
      flex-shrink: 0;
    }
    .trust-item h6 { font-size: .85rem; font-weight: 600; margin-bottom: .2rem; }
    .trust-item p  { font-size: .78rem; color: var(--muted); line-height: 1.5; margin: 0; }

    /* ── CATEGORIES ── */
    .section-label {
      font-size: .68rem;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--red);
      font-weight: 600;
      margin-bottom: .5rem;
    }
    .section-heading {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.8rem, 3vw, 2.6rem);
      line-height: 1.1;
      margin-bottom: 1rem;
    }

    .category-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 2rem 1.5rem;
      text-align: center;
      text-decoration: none;
      color: var(--ink);
      display: block;
      transition: border-color .2s, box-shadow .2s, transform .2s;
      position: relative;
      overflow: hidden;
    }
    .category-card::after {
      content: '';
      position: absolute;
      bottom: 0; left: 0; right: 0;
      height: 3px;
      background: var(--red);
      transform: scaleX(0);
      transition: transform .2s;
      transform-origin: left;
    }
    .category-card:hover {
      border-color: var(--red);
      box-shadow: 0 6px 24px rgba(0,0,0,.08);
      transform: translateY(-3px);
      color: var(--ink);
    }
    .category-card:hover::after { transform: scaleX(1); }
    .category-card .cat-icon {
      font-size: 2.2rem;
      margin-bottom: .75rem;
      display: block;
    }
    .category-card h5 { font-size: .92rem; font-weight: 600; margin-bottom: .25rem; }
    .category-card p  { font-size: .75rem; color: var(--muted); margin: 0; }

    /* ── BRANDS ── */
    .brand-pill {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 40px;
      padding: .6rem 1.5rem;
      font-size: .82rem;
      font-weight: 600;
      color: var(--ink);
      text-decoration: none;
      transition: border-color .15s, color .15s;
      white-space: nowrap;
    }
    .brand-pill:hover { border-color: var(--blue); color: var(--blue); }

    /* ── FEATURED PRODUCT ── */
    .product-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 6px;
      overflow: hidden;
      transition: box-shadow .2s, transform .2s;
      text-decoration: none;
      color: var(--ink);
      display: block;
    }
    .product-card:hover {
      box-shadow: 0 8px 28px rgba(0,0,0,.1);
      transform: translateY(-4px);
      color: var(--ink);
    }
    .product-card .img-area {
      background: var(--cream);
      padding: 2rem;
      text-align: center;
      font-size: 4rem;
      border-bottom: 1px solid var(--border);
    }
    .product-card .img-area.has-image {
      padding: .65rem;
    }
    .product-card .img-area img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 4px;
      display: block;
    }
    .product-card .card-body { padding: 1.25rem; }
    .product-card .brand-tag {
      font-size: .65rem;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--muted);
      font-weight: 600;
      margin-bottom: .35rem;
    }
    .product-card h5 { font-size: .9rem; font-weight: 600; margin-bottom: .5rem; }
    .product-card .price {
      font-family: 'Playfair Display', serif;
      font-size: 1.25rem;
      color: var(--red);
    }
    .product-card .stars { color: var(--gold); font-size: .8rem; }
    .product-card .badge-new {
      font-size: .6rem;
      background: var(--red);
      color: #fff;
      padding: .2em .55em;
      border-radius: 3px;
      text-transform: uppercase;
      letter-spacing: .07em;
      vertical-align: middle;
    }

    /* ── CTA SECTION ── */
    .cta-section {
      background: var(--blue);
      padding: 5rem 0;
      position: relative;
      overflow: hidden;
    }
    .cta-section::before {
      content: '';
      position: absolute;
      top: -100px; right: -100px;
      width: 500px; height: 500px;
      border-radius: 50%;
      background: rgba(192,57,43,.1);
    }
    .cta-section::after {
      content: '';
      position: absolute;
      bottom: -80px; left: 10%;
      width: 300px; height: 300px;
      border-radius: 50%;
      background: rgba(255,255,255,.04);
    }
    .cta-section h2 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2rem, 4vw, 3rem);
      color: #fff;
      line-height: 1.1;
    }
    .cta-section p { color: rgba(255,255,255,.65); font-size: .95rem; }

    /* ── RESPONSIVE ── */
    @media (max-width: 991px) {
      .hero {
        grid-template-columns: 1fr;
        min-height: auto;
      }
      .hero-left { padding: 4rem 2rem 3rem; }
      .hero-right { padding: 3rem 2rem 3rem; }
      .hero-right::before { display: none; }
      .laptop-card { display: none; }
      .hero-stats { gap: 1.5rem; }
    }

    @media (max-width: 575px) {
      .hero-left { padding: 3rem 1.5rem 2.5rem; }
      .hero-ctas { flex-direction: column; }
      .btn-hero-primary, .btn-hero-secondary { text-align: center; }
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

<!-- :target anchors — placed as siblings BEFORE .hero so CSS sibling selector works -->
<span id="login"></span>
<span id="register"></span>

<!-- ════════════════════════════════════
     HERO
════════════════════════════════════ -->
<section class="hero">

  <!-- LEFT: COPY -->
  <div class="hero-left">
    <div class="hero-eyebrow">Philippines' Premier Laptop Store</div>
    <h1 class="hero-headline">
      Power Your<br/>
      <em>World</em> With<br/>
      The Right Tech.
    </h1>
    <p class="hero-sub">
      Browse hundreds of laptops, components &amp; accessories from the brands you trust — with fast delivery, verified reviews, and unbeatable prices.
    </p>
    <div class="hero-ctas">
      @guest
        <a href="#register" class="btn-hero-primary">
          <i class="bi bi-person-plus me-2"></i>Create Free Account
        </a>
      @endguest
      @auth
        <a href="{{ route('customer.shop.index') }}" class="btn-hero-primary">
          <i class="bi bi-bag-check me-2"></i>Continue Shopping
        </a>
      @endauth
      <a href="{{ route('customer.shop.index') }}" class="btn-hero-secondary">
        Browse Shop <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
    <div class="hero-stats">
      <div>
        <div class="hero-stat-num">348+</div>
        <div class="hero-stat-label">Products</div>
      </div>
      <div>
        <div class="hero-stat-num">4.9★</div>
        <div class="hero-stat-label">Avg. Rating</div>
      </div>
      <div>
        <div class="hero-stat-num">4.9K</div>
        <div class="hero-stat-label">Customers</div>
      </div>
    </div>
  </div>

  <!-- RIGHT: AUTH FORMS -->
  <div class="hero-right">

    @guest
    <div class="form-panel">

      @if (session('success'))
        <div class="auth-success">{{ session('success') }}</div>
      @endif

      @if (session('error'))
        <div class="auth-alert">{{ session('error') }}</div>
      @endif

      <!-- Tab Nav -->
      <div class="tab-nav">
        <a href="#login"    class="tab-login-link">Log In</a>
        <a href="#register" class="tab-register-link">Register</a>
      </div>

      <!-- LOGIN PANE (default visible) -->
      <div class="pane-login">
        <div class="form-panel-title">Welcome back.</div>
        <div class="form-panel-sub">Sign in to your LaptopHub account.</div>

        <form action="{{ route('login') }}" method="post">
          @csrf
          @if ($errors->login->any())
            <div class="auth-alert">{{ $errors->login->first() }}</div>
          @endif
          <div class="mb-3">
            <label class="form-label" for="login-email">Email Address</label>
            <div class="input-icon">
              <input type="email" id="login-email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}"/>
              <i class="bi bi-envelope"></i>
            </div>
            @error('email', 'login')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label" for="login-pass">Password</label>
            <div class="input-icon">
              <input type="password" id="login-pass" name="password" class="form-control" placeholder="••••••••"/>
              <i class="bi bi-lock"></i>
            </div>
            @error('password', 'login')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember" name="remember" @checked(old('remember'))/>
              <label class="form-check-label" for="remember" style="font-size:.8rem">Remember me</label>
            </div>
            <a href="#" style="font-size:.8rem;color:var(--blue)">Forgot password?</a>
          </div>
          <button type="submit" class="btn-submit">
            <i class="bi bi-box-arrow-in-right me-2"></i>Log In
          </button>
          <div class="divider-or">or</div>
          <div class="form-footer">
            Don't have an account?
            <a href="#register" style="color:var(--blue)">Register here</a>
          </div>
        </form>
      </div>

      <!-- REGISTER PANE (hidden by default) -->
      <div class="pane-register">
        <div class="form-panel-title">Join LaptopHub.</div>
        <div class="form-panel-sub">Create your free account in seconds.</div>

        <form action="{{ route('register') }}" method="post">
          @csrf
          @if ($errors->register->any())
            <div class="auth-alert">{{ $errors->register->first() }}</div>
          @endif
          <div class="mb-3">
            <label class="form-label" for="reg-name">Full Name</label>
            <div class="input-icon">
              <input type="text" id="reg-name" name="full_name" class="form-control" placeholder="Maria Santos" value="{{ old('full_name') }}"/>
              <i class="bi bi-person"></i>
            </div>
            @error('full_name', 'register')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label" for="reg-email">Email Address</label>
            <div class="input-icon">
              <input type="email" id="reg-email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}"/>
              <i class="bi bi-envelope"></i>
            </div>
            @error('email', 'register')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label" for="reg-phone">Contact Number <span style="font-size:.65rem;color:var(--muted)">(optional)</span></label>
            <div class="input-icon">
              <input type="tel" id="reg-phone" name="contact_number" class="form-control" placeholder="+63 9XX XXX XXXX" value="{{ old('contact_number') }}"/>
              <i class="bi bi-phone"></i>
            </div>
            @error('contact_number', 'register')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label" for="reg-pass">Password</label>
            <div class="input-icon">
              <input type="password" id="reg-pass" name="password" class="form-control" placeholder="Min. 8 characters"/>
              <i class="bi bi-lock"></i>
            </div>
            @error('password', 'register')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="terms" name="terms" @checked(old('terms'))/>
              <label class="form-check-label" for="terms" style="font-size:.78rem">
                I agree to the <a href="{{ route('legal.terms') }}" style="color:var(--blue)" target="_blank" rel="noopener noreferrer">Terms of Service</a> &amp; <a href="{{ route('legal.privacy') }}" style="color:var(--blue)" target="_blank" rel="noopener noreferrer">Privacy Policy</a>
              </label>
            </div>
            @error('terms', 'register')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>
          <button type="submit" class="btn-submit">
            <i class="bi bi-person-check me-2"></i>Create Account
          </button>
          <div class="divider-or">or</div>
          <div class="form-footer">
            Already have an account?
            <a href="#login" style="color:var(--blue)">Log in here</a>
          </div>
        </form>
      </div>

    </div><!-- /form-panel -->
    @endguest

    @auth
    <div class="form-panel">
      <div class="form-panel-title">Welcome back.</div>
      <div class="form-panel-sub">Signed in as {{ auth()->user()->full_name }}.</div>

      @if (session('success'))
        <div class="auth-success">{{ session('success') }}</div>
      @endif

      @if (session('error'))
        <div class="auth-alert">{{ session('error') }}</div>
      @endif

      <div style="background:var(--cream);border:1px solid var(--border);border-radius:5px;padding:1rem;margin-bottom:1rem">
        <div style="font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);font-weight:600;margin-bottom:.6rem">Customer View</div>
        <div style="font-size:.86rem;color:var(--ink);line-height:1.6">
          You are logged in. Use quick actions below to continue browsing products.
        </div>
      </div>

      <a href="{{ route('customer.shop.index') }}" class="btn-submit" style="display:block;text-align:center;text-decoration:none;margin-bottom:.75rem">
        <i class="bi bi-grid me-2"></i>Browse Shop
      </a>
      <a href="{{ route('customer.orders.index') }}" class="btn-submit" style="display:block;text-align:center;text-decoration:none;margin-bottom:1rem;background:var(--blue)">
        <i class="bi bi-receipt me-2"></i>My Orders
      </a>

      <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit" class="btn-submit" style="background:transparent;color:var(--ink);border:1.5px solid var(--border)">
          <i class="bi bi-box-arrow-right me-2"></i>Sign Out
        </button>
      </form>
    </div>
    @endauth
  </div><!-- /hero-right -->
</section>

<!-- ════════════════════════════════════
     TRUST BAND
════════════════════════════════════ -->
<section class="trust-band">
  <div class="container">
    <div class="row g-4 justify-content-center">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="trust-item">
          <div class="trust-icon" style="background:#d1e7dd;color:#0a3622"><i class="bi bi-shield-check"></i></div>
          <div>
            <h6>Verified Products</h6>
            <p>Every listing is authenticated. Buy with full confidence every time.</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="trust-item">
          <div class="trust-icon" style="background:#cfe2ff;color:#084298"><i class="bi bi-truck"></i></div>
          <div>
            <h6>Fast Delivery</h6>
            <p>Same-day dispatch on in-stock items. COD &amp; online payment supported.</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="trust-item">
          <div class="trust-icon" style="background:#fff3cd;color:#856404"><i class="bi bi-star-half"></i></div>
          <div>
            <h6>Verified Reviews</h6>
            <p>Only confirmed buyers can leave reviews — no fake ratings, ever.</p>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="trust-item">
          <div class="trust-icon" style="background:#f8d7da;color:#842029"><i class="bi bi-headset"></i></div>
          <div>
            <h6>24/7 Support</h6>
            <p>Our team is always on hand to help you before and after your purchase.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════
     CATEGORIES
════════════════════════════════════ -->
<section class="py-5">
  <div class="container py-4">
    <div class="row mb-4">
      <div class="col-lg-6">
        <div class="section-label">Shop by Category</div>
        <h2 class="section-heading">Everything You<br/>Need in One Place.</h2>
        <p style="color:var(--muted);font-size:.9rem;max-width:420px">
          From ultrabooks to gaming rigs, RAM upgrades to M.2 drives — your one-stop Philippine tech store.
        </p>
      </div>
    </div>
    <div class="row g-3">
      @php
        $categoryIconMap = [
          'laptop' => '💻',
          'gaming' => '🎮',
          'batter' => '🔋',
          'storage' => '💾',
          'ram' => '🧠',
          'accessor' => '🖥️',
        ];
      @endphp
      @forelse(($featuredCategories ?? collect()) as $category)
        @php
          $categoryName = strtolower((string) $category->name);
          $categoryIcon = '📦';

          foreach ($categoryIconMap as $keyword => $icon) {
              if (str_contains($categoryName, $keyword)) {
                  $categoryIcon = $icon;
                  break;
              }
          }

          $categoryHref = auth()->check()
              ? route('customer.shop.index', ['category' => [(int) $category->category_id]])
              : route('customer.shop.index', ['category' => [(int) $category->category_id]]);

          $categoryCount = (int) ($category->active_products_count ?? 0);
        @endphp
        <div class="col-6 col-md-4 col-lg-2">
          <a href="{{ $categoryHref }}" class="category-card">
            <span class="cat-icon">{{ $categoryIcon }}</span>
            <h5>{{ $category->name }}</h5>
            <p>{{ number_format($categoryCount) }} {{ $categoryCount === 1 ? 'product' : 'products' }}</p>
          </a>
        </div>
      @empty
        <div class="col-12">
          <p style="font-size:.85rem;color:var(--muted)">No active categories available yet.</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- ════════════════════════════════════
     BRANDS
════════════════════════════════════ -->
<section class="py-4" style="background:var(--cream);border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
  <div class="container">
    <div class="text-center mb-3" style="font-size:.7rem;letter-spacing:.15em;text-transform:uppercase;color:var(--muted);font-weight:600">Brands We Carry</div>
    <div class="d-flex flex-wrap gap-2 justify-content-center">
      @forelse(($featuredBrands ?? collect()) as $brand)
        <a
          href="{{ route('customer.shop.search', ['q' => $brand->name]) }}"
          class="brand-pill"
        >
          {{ $brand->name }}
        </a>
      @empty
        <span style="font-size:.82rem;color:var(--muted)">No active brands available yet.</span>
      @endforelse
    </div>
  </div>
</section>

<!-- ════════════════════════════════════
     FEATURED PRODUCTS
════════════════════════════════════ -->
<section class="py-5">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <div class="section-label">Hand-picked for You</div>
        <h2 class="section-heading mb-0">Featured Products</h2>
      </div>
      <a
        href="{{ route('customer.shop.index') }}"
        style="font-size:.83rem;color:var(--blue);text-decoration:underline"
      >
        View all →
      </a>
    </div>
    <div class="row g-3">
      @forelse(($featuredProducts ?? collect()) as $product)
        @php
          $rating = max(0, min(5, (float) $product->avg_rating));
          $ratingRounded = (int) round($rating);
          $ratingStars = str_repeat('★', $ratingRounded) . str_repeat('☆', 5 - $ratingRounded);
          $isNew = \Illuminate\Support\Carbon::parse($product->created_at)->greaterThan(now()->subDays(30));
          $isLow = (int) $product->stock_qty <= (int) $product->low_stock_threshold;
          $productHref = auth()->check()
              ? route('customer.shop.show', $product->product_id)
              : route('customer.shop.show', $product->product_id);
        @endphp
        <div class="col-12 col-sm-6 col-lg-3">
          <a href="{{ $productHref }}" class="product-card">
            <div class="img-area {{ $product->image_url ? 'has-image' : '' }}">
              @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
              @else
                <i class="bi bi-laptop" aria-hidden="true"></i>
              @endif
            </div>
            <div class="card-body">
              <div class="brand-tag">
                {{ $product->brand_name }}
                @if($isNew)
                  <span class="badge-new">New</span>
                @elseif($isLow)
                  <span class="badge-new" style="background:var(--gold)">Low</span>
                @endif
              </div>
              <h5>{{ $product->name }}</h5>
              <div class="stars mb-1" title="{{ number_format($rating, 1) }} out of 5">
                {{ $ratingStars }}
                <span style="font-size:.72rem;color:var(--muted)">({{ number_format((int) $product->review_count) }})</span>
              </div>
              <div class="price">₱{{ number_format((float) $product->price, 2) }}</div>
            </div>
          </a>
        </div>
      @empty
        <div class="col-12">
          <div style="border:1px dashed var(--border);background:#fff;border-radius:6px;padding:1.25rem;text-align:center;color:var(--muted)">
            No featured products available yet.
          </div>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- ════════════════════════════════════
     CTA SECTION
════════════════════════════════════ -->
<section class="cta-section">
  <div class="container text-center position-relative" style="z-index:2">
    <div class="section-label" style="color:rgba(255,255,255,.5)">Ready to upgrade?</div>
    <h2 class="mb-3">
      Your Perfect Laptop<br/>
      Is One Click Away.
    </h2>
    <p class="mb-4">Join thousands of Filipino customers who trust LaptopHub for their tech needs.</p>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
      <a href="#register" class="btn-hero-primary">
        <i class="bi bi-person-plus me-2"></i>Create Free Account
      </a>
      <a href="#login" class="btn-hero-secondary">
        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
      </a>
    </div>
  </div>
</section>

@endsection

@push('scripts')
@if ($errors->register->any())
<script>
  window.location.hash = 'register';
</script>
@endif
@endpush