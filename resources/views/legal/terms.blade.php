@extends('layouts.base')

@section('title', 'LaptopHub - Terms and Conditions')

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
        <a href="{{ route('legal.terms') }}" class="active">Terms</a>
        <a href="{{ route('legal.privacy') }}">Privacy</a>
      </nav>
    </div>
  </header>

  <div class="container">
    <section class="legal-hero">
      <div class="legal-kicker">Legal</div>
      <h1 class="legal-title">Terms and Conditions</h1>
      <div class="legal-updated">Last updated: March 6, 2026</div>
      <p class="legal-summary">
        These terms explain how purchasing, account use, and platform access work in LaptopHub. Please read them before creating an account or placing orders.
      </p>
    </section>

    <div class="legal-grid">
      <div class="legal-wrap">

      <p>
        These Terms and Conditions govern your use of the LaptopHub website, services, and purchases. By creating an account,
        placing an order, or using this platform, you agree to these terms.
      </p>

      <h2 id="eligibility">1. Eligibility and Account Responsibility</h2>
      <ul>
        <li>You must provide accurate and up-to-date account information.</li>
        <li>You are responsible for maintaining the confidentiality of your login credentials.</li>
        <li>You are responsible for activity under your account unless unauthorized access is reported promptly.</li>
      </ul>

      <h2 id="orders">2. Orders and Payments</h2>
      <ul>
        <li>All orders are subject to product availability and order confirmation.</li>
        <li>Prices are shown in Philippine Peso (PHP) and may change without prior notice.</li>
        <li>We reserve the right to cancel or limit orders in cases of pricing errors, fraud risk, or stock inconsistencies.</li>
      </ul>

      <h2 id="shipping">3. Shipping and Delivery</h2>
      <ul>
        <li>Delivery timelines are estimates and may vary based on location, courier operations, and force majeure events.</li>
        <li>Ownership and risk pass to the customer upon successful delivery to the provided shipping address.</li>
      </ul>

      <h2 id="returns">4. Returns, Replacements, and Refunds</h2>
      <ul>
        <li>Requests must comply with the return window and product condition requirements.</li>
        <li>Items damaged by misuse, unauthorized repair, or normal wear may not qualify for return or replacement.</li>
        <li>Approved refunds are processed using the original payment method when possible.</li>
      </ul>

      <h2 id="prohibited">5. Prohibited Use</h2>
      <p>
        You agree not to misuse the platform, interfere with website operations, submit false information, or engage in unlawful,
        abusive, or fraudulent activities.
      </p>

      <h2 id="ip">6. Intellectual Property</h2>
      <p>
        Content, branding, design assets, and software on this site are owned by LaptopHub or its licensors and are protected
        by applicable intellectual property laws.
      </p>

      <h2 id="liability">7. Limitation of Liability</h2>
      <p>
        To the extent permitted by law, LaptopHub is not liable for indirect, incidental, or consequential damages resulting from
        use of the platform, delayed service, or third-party service interruptions.
      </p>

      <h2 id="changes">8. Changes to These Terms</h2>
      <p>
        We may update these terms from time to time. Continued use of the platform after updates indicates acceptance of the revised terms.
      </p>

      <h2 id="contact">9. Contact</h2>
      <p>
        For concerns about these Terms and Conditions, contact LaptopHub support through the official channels listed on the website.
      </p>
      </div>

      <aside class="legal-aside" aria-label="On this page">
        <h3>On This Page</h3>
        <a href="#eligibility">Eligibility and Account</a>
        <a href="#orders">Orders and Payments</a>
        <a href="#shipping">Shipping and Delivery</a>
        <a href="#returns">Returns and Refunds</a>
        <a href="#prohibited">Prohibited Use</a>
        <a href="#ip">Intellectual Property</a>
        <a href="#liability">Liability</a>
        <a href="#changes">Changes to Terms</a>
        <a href="#contact">Contact</a>
      </aside>
    </div>
  </div>
@endsection
