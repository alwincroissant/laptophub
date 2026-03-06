@extends('layouts.base')

@section('title', 'LaptopHub - Privacy Policy')

@push('styles')
<style>
  :root {
    --legal-ink: #0c0c0c;
    --legal-paper: #f7f4ef;
    --legal-cream: #ede8df;
    --legal-border: #d9d2c6;
    --legal-accent: #c0392b;
    --legal-muted: #6d6760;
  }

  body {
    background: radial-gradient(circle at 0% 0%, #f4eee3 0, var(--legal-paper) 45%, #f7f4ef 100%);
  }

  .legal-header {
    background: var(--legal-ink);
    border-bottom: 3px solid var(--legal-accent);
  }
  .legal-header-inner {
    min-height: 66px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
  }
  .legal-brand {
    color: #fff;
    text-decoration: none;
    font-family: 'Playfair Display', serif;
    font-size: 1.45rem;
    line-height: 1;
  }
  .legal-brand span {
    color: var(--legal-accent);
  }
  .legal-nav {
    display: flex;
    align-items: center;
    gap: .9rem;
    flex-wrap: wrap;
  }
  .legal-nav a {
    color: rgba(255,255,255,.78);
    text-decoration: none;
    font-size: .8rem;
    letter-spacing: .08em;
    text-transform: uppercase;
  }
  .legal-nav a:hover,
  .legal-nav a.active {
    color: #fff;
  }
  .legal-hero {
    margin-top: 1.5rem;
    background: linear-gradient(120deg, #161616 0%, #1f1f1f 58%, #2d2a26 100%);
    border: 1px solid #2b2622;
    border-radius: 10px;
    padding: 1.5rem 1.6rem;
    color: rgba(255,255,255,.86);
  }

  .legal-kicker {
    font-size: .68rem;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: rgba(255,255,255,.58);
    margin-bottom: .45rem;
    font-weight: 600;
  }

  .legal-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 240px;
    gap: 1rem;
    margin: 1rem 0 3rem;
  }

  .legal-wrap {
    background: #fff;
    border: 1px solid var(--legal-border);
    border-radius: 10px;
    padding: 1.5rem 1.6rem;
    box-shadow: 0 14px 26px rgba(28, 23, 18, .06);
  }
  .legal-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.1rem;
    margin-bottom: .4rem;
    color: #fff;
  }
  .legal-updated {
    color: rgba(255,255,255,.65);
    font-size: .85rem;
  }
  .legal-summary {
    margin-top: .7rem;
    margin-bottom: 0;
    font-size: .9rem;
    color: rgba(255,255,255,.78);
    max-width: 820px;
    line-height: 1.6;
  }

  .legal-wrap > p:first-of-type {
    margin-top: 0;
    padding: .8rem .9rem;
    border: 1px dashed var(--legal-border);
    background: var(--legal-paper);
    border-radius: 6px;
  }

  .legal-wrap h2 {
    font-size: 1.06rem;
    margin-top: 1.45rem;
    margin-bottom: .5rem;
    font-weight: 700;
    color: #181512;
    border-left: 3px solid var(--legal-accent);
    padding-left: .55rem;
  }
  .legal-wrap p,
  .legal-wrap li {
    font-size: .92rem;
    line-height: 1.65;
    color: #2d2a26;
  }
  .legal-wrap ul {
    padding-left: 1.2rem;
    margin-bottom: .75rem;
  }

  .legal-aside {
    position: sticky;
    top: 1rem;
    align-self: start;
    background: #fff;
    border: 1px solid var(--legal-border);
    border-radius: 10px;
    padding: .9rem;
  }

  .legal-aside h3 {
    margin: 0 0 .55rem;
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: var(--legal-muted);
  }

  .legal-aside a {
    display: block;
    color: #2d2a26;
    text-decoration: none;
    font-size: .82rem;
    padding: .35rem .4rem;
    border-radius: 4px;
  }

  .legal-aside a:hover {
    background: var(--legal-cream);
  }

  @media (max-width: 992px) {
    .legal-grid {
      grid-template-columns: 1fr;
    }

    .legal-aside {
      position: static;
    }
  }
</style>
@endpush

@section('content')
  <header class="legal-header">
    <div class="container legal-header-inner">
      <a href="{{ route('index') }}" class="legal-brand">Laptop<span>Hub</span></a>
      <nav class="legal-nav" aria-label="Legal navigation">
        <a href="{{ route('index') }}">Home</a>
        <a href="{{ route('legal.terms') }}">Terms</a>
        <a href="{{ route('legal.privacy') }}" class="active">Privacy</a>
      </nav>
    </div>
  </header>

  <div class="container">
    <section class="legal-hero">
      <div class="legal-kicker">Legal</div>
      <h1 class="legal-title">Privacy Policy</h1>
      <div class="legal-updated">Last updated: March 6, 2026</div>
      <p class="legal-summary">
        This policy explains what personal data LaptopHub collects, why it is used, and how it is protected while you browse or purchase.
      </p>
    </section>

    <div class="legal-grid">
      <div class="legal-wrap">

      <p>
        This Privacy Policy explains how LaptopHub collects, uses, and protects your personal information when you use our platform.
      </p>

      <h2 id="collect">1. Information We Collect</h2>
      <ul>
        <li>Account details such as name, email address, and contact number.</li>
        <li>Order and transaction information needed to process purchases.</li>
        <li>Shipping and billing details you provide during checkout.</li>
        <li>Technical data such as device/browser details and basic usage logs.</li>
      </ul>

      <h2 id="use">2. How We Use Your Data</h2>
      <ul>
        <li>To create and manage your account.</li>
        <li>To process orders, payments, deliveries, and support requests.</li>
        <li>To improve platform functionality, reliability, and security.</li>
        <li>To comply with legal and regulatory obligations.</li>
      </ul>

      <h2 id="sharing">3. Data Sharing</h2>
      <p>
        We may share information with trusted service providers (such as payment processors and delivery partners) only to the extent
        required to operate our services. We do not sell personal data.
      </p>

      <h2 id="retention">4. Data Retention</h2>
      <p>
        We retain personal data only for as long as needed for business operations, legal compliance, dispute resolution, and security.
      </p>

      <h2 id="security">5. Data Security</h2>
      <p>
        We apply reasonable administrative, technical, and organizational safeguards to protect your data from unauthorized access,
        disclosure, or misuse.
      </p>

      <h2 id="rights">6. Your Rights</h2>
      <ul>
        <li>You may request access to or correction of your personal information.</li>
        <li>You may request account deactivation, subject to legal and operational retention requirements.</li>
      </ul>

      <h2 id="updates">7. Policy Updates</h2>
      <p>
        We may update this Privacy Policy from time to time. Material changes will be reflected on this page with an updated revision date.
      </p>

      <h2 id="contact">8. Contact</h2>
      <p>
        For privacy concerns or requests related to your personal data, contact LaptopHub support through official contact channels.
      </p>
      </div>

      <aside class="legal-aside" aria-label="On this page">
        <h3>On This Page</h3>
        <a href="#collect">Information We Collect</a>
        <a href="#use">How We Use Your Data</a>
        <a href="#sharing">Data Sharing</a>
        <a href="#retention">Data Retention</a>
        <a href="#security">Data Security</a>
        <a href="#rights">Your Rights</a>
        <a href="#updates">Policy Updates</a>
        <a href="#contact">Contact</a>
      </aside>
    </div>
  </div>
@endsection
