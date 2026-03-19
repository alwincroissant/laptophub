@extends('layouts.base')

@section('title', $product->name . ' - LaptopHub')

@push('styles')
<style>
    :root {
      --ink:     #0c0c0c;
      --paper:   #f5f1ea;
      --cream:   #ede8df;
      --red:     #c0392b;
      --red-dk:  #962d22;
      --blue:    #1a3a5c;
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
    }

    .page-header h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.8rem, 4vw, 2.8rem);
      margin-bottom: .5rem;
    }

    .detail-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 8px;
      overflow: hidden;
    }

    /* ── CSS GALLERY ── */
    .css-gallery { width: 100%; margin-bottom: 1rem; }
    .gallery-main {
      position: relative; width: 100%; padding-bottom: 100%;
      background: var(--cream); border-radius: 8px; overflow: hidden;
      border: 1px solid var(--border);
    }
    .gallery-main img.main-img {
      position: absolute; top: 0; left: 0; width: 100%; height: 100%;
      object-fit: contain; opacity: 0; transition: opacity 0.3s ease; z-index: 1;
      background: #fff;
    }
    .gallery-main .fallback {
      position: absolute; top: 0; left: 0; width: 100%; height: 100%;
      display: flex; align-items: center; justify-content: center;
      font-size: 3.5rem; color: var(--muted);
    }
    .gallery-radio { display: none; }

    @for ($i = 0; $i < 20; $i++)
      #img-{{ $i }}:checked ~ .gallery-main #main-img-{{ $i }} { opacity: 1; z-index: 2; }
      #img-{{ $i }}:checked ~ .gallery-thumbs [for="img-{{ $i }}"] {
        opacity: 1; border-color: var(--red); box-shadow: 0 0 0 1.5px var(--red);
      }
    @endfor

    .gallery-thumbs {
      display: flex; gap: .75rem; overflow-x: auto; padding-bottom: .5rem; margin-top: 1rem;
    }
    .gallery-thumbs::-webkit-scrollbar { display: none; }
    .gallery-thumbs { -ms-overflow-style: none; scrollbar-width: none; }
    .gallery-thumbs .thumb-label {
      cursor: pointer; display: block; width: 80px; height: 80px;
      flex-shrink: 0; border: 1px solid var(--border); border-radius: 6px;
      overflow: hidden; opacity: 0.6; transition: opacity 0.2s, border-color 0.2s;
      background: #fff;
    }
    .gallery-thumbs .thumb-label img { width: 100%; height: 100%; object-fit: cover; }
    .gallery-thumbs .thumb-label:hover { opacity: .9; }

    .detail-body {
      padding: 1.5rem;
    }

    .meta {
      font-size: .78rem;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: var(--muted);
      margin-bottom: .5rem;
    }

    .title {
      font-size: 1.6rem;
      font-weight: 700;
      margin-bottom: .75rem;
    }

    .price {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      color: var(--red);
      margin-bottom: 1rem;
    }

    .desc {
      font-size: .92rem;
      color: #2c2c2c;
      line-height: 1.65;
      margin-bottom: 1rem;
      white-space: pre-line;
    }

    .info-item {
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: .75rem;
      background: #fff;
    }

    .info-label {
      font-size: .72rem;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: var(--muted);
      margin-bottom: .35rem;
    }

    .info-value {
      font-size: .9rem;
      font-weight: 600;
    }

    .availability {
      display: inline-block;
      font-size: .78rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      padding: .35rem .6rem;
      border-radius: 3px;
      margin-bottom: 1rem;
      font-weight: 600;
    }

    .availability.in {
      background: #d1e7dd;
      color: #0a3622;
    }

    .availability.out {
      background: #f8d7da;
      color: #842029;
    }

    .actions {
      display: flex;
      gap: .75rem;
      flex-wrap: wrap;
    }

    .btn-back {
      background: transparent;
      color: var(--blue);
      border: 1px solid var(--blue);
      border-radius: 4px;
      padding: .75rem 1rem;
      text-decoration: none;
      font-size: .85rem;
      font-weight: 600;
    }

    .btn-add-cart {
      background: var(--red);
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: .75rem 1rem;
      font-size: .85rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: .4rem;
    }

    .btn-auth-required {
      background: linear-gradient(180deg, #d24536 0%, #bf3629 100%);
      color: #fff;
      border: 1px solid #b63226;
      border-radius: 4px;
      padding: .75rem 1rem;
      font-size: .84rem;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: transform .12s ease, box-shadow .12s ease, filter .12s ease;
    }

    .btn-auth-required:hover {
      color: #fff;
      filter: brightness(.98);
      transform: translateY(-1px);
      box-shadow: 0 6px 14px rgba(192, 57, 43, .22);
    }

    .auth-cart-note {
      margin: 0;
      font-size: .76rem;
      color: var(--muted);
      line-height: 1.35;
    }

    .qty-input {
      width: 78px;
      border: 1px solid var(--border);
      border-radius: 4px;
      padding: .72rem;
      font-size: .85rem;
    }

    .reviews-wrap {
      margin-top: 1rem;
    }

    .review-summary {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1rem;
    }

    .review-summary h3 {
      font-size: 1.05rem;
      margin-bottom: .2rem;
    }

    .review-stars {
      color: #b8860b;
      font-size: .95rem;
      letter-spacing: .03em;
    }

    .review-count {
      font-size: .82rem;
      color: var(--muted);
      margin-left: .45rem;
    }

    .review-form-card,
    .review-list-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1rem;
    }

    .review-list-item + .review-list-item {
      border-top: 1px solid #ece7de;
      margin-top: .9rem;
      padding-top: .9rem;
    }

    .review-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: .6rem;
      margin-bottom: .3rem;
      flex-wrap: wrap;
    }

    .review-author {
      font-size: .85rem;
      font-weight: 600;
    }

    .review-date {
      font-size: .75rem;
      color: var(--muted);
    }

    .review-title {
      font-size: .88rem;
      font-weight: 700;
      margin-bottom: .35rem;
    }

    .review-body {
      font-size: .84rem;
      color: #343a40;
      line-height: 1.55;
      margin: 0;
      white-space: pre-line;
    }

    .review-note {
      margin-top: .55rem;
      font-size: .78rem;
      color: var(--muted);
    }

</style>
@endpush

@section('content')
<nav class="navbar d-flex align-items-center justify-content-between">
  <a href="{{ route('index') }}" class="navbar-brand">Laptop<span>Hub</span></a>
  <div class="d-flex gap-2 align-items-center">
    <a href="{{ route('index') }}" class="nav-pill outline">Home</a>
    <a href="{{ route('customer.shop.index') }}" class="nav-pill solid">Shop</a>
    @auth
      <a href="{{ route('customer.cart.index') }}" class="nav-pill outline">Cart</a>
      <a href="{{ route('customer.orders.index') }}" class="nav-pill outline">Orders</a>
      @include('customer.partials.account-dropdown')
    @else
      <a href="{{ route('index') }}#login" class="nav-pill outline">Log In</a>
      <a href="{{ route('index') }}#register" class="nav-pill solid">Register</a>
    @endauth
  </div>
</nav>

<div class="page-header">
  <div class="container">
    <h1>Product Information</h1>
    <p>View complete details before adding to cart</p>
  </div>
</div>

<div class="container mt-4">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger mb-0">{{ $errors->first() }}</div>
  @endif
</div>

<section class="py-5">
  <div class="container">
    <div class="detail-card row g-0">
      <div class="col-12 col-md-5 p-4 border-end-md pb-0">
        @php
          $allImages = collect();
          if ($product->image_url) $allImages->push($product->image_url);
          if ($product->images) {
              foreach($product->images as $img) $allImages->push($img->image_url);
          }
        @endphp

        <div class="css-gallery">
          @if($allImages->isNotEmpty())
            @foreach($allImages as $index => $imgUrl)
              <input type="radio" name="gallery" id="img-{{ $index }}" class="gallery-radio" {{ $index === 0 ? 'checked' : '' }}>
            @endforeach

            <div class="gallery-main">
              @foreach($allImages as $index => $imgUrl)
                <img src="{{ $imgUrl }}" class="main-img" id="main-img-{{ $index }}" alt="{{ $product->name }}">
              @endforeach
            </div>

            @if($allImages->count() > 1)
            <div class="gallery-thumbs">
              @foreach($allImages as $index => $imgUrl)
                <label for="img-{{ $index }}" class="thumb-label">
                  <img src="{{ $imgUrl }}" alt="Thumbnail {{ $index }}">
                </label>
              @endforeach
            </div>
            @endif
          @else
            <div class="gallery-main" style="margin-bottom:0">
              <div class="fallback"><i class="bi bi-image"></i></div>
            </div>
          @endif
        </div>
      </div>

      <div class="col-12 col-md-7 detail-body">
        <div class="meta">{{ $product->brand->name ?? 'Unbranded' }} • {{ $product->category->name ?? 'Uncategorized' }}</div>
        <h2 class="title">{{ $product->name }}</h2>
        <div class="price">₱{{ number_format($product->price, 2) }}</div>

        <div class="availability {{ $product->stock_qty > 0 ? 'in' : 'out' }}">
          {{ $product->stock_qty > 0 ? 'In Stock' : 'Out of Stock' }}
        </div>

        <div class="desc">{{ $product->description ?  : 'No description available.' }}</div>

        @if($product->compatibility)
          <div class="info-item" style="margin-bottom:1.25rem">
            <div class="info-label">Compatibility</div>
            <div class="info-value" style="font-weight:500">{{ $product->compatibility }}</div>
          </div>
        @endif

        <div class="actions">
          <a href="{{ route('customer.shop.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Back to Shop</a>

          @if($product->stock_qty > 0)
            @auth
              <form action="{{ route('customer.cart.add') }}" method="post" class="d-flex gap-2 align-items-center">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_qty }}" class="qty-input">
                <button type="submit" class="btn-add-cart"><i class="bi bi-cart-plus"></i> Add to Cart</button>
              </form>
            @else
              <a href="{{ route('index') }}#login" class="btn-auth-required">Log In to Add to Cart</a>
              <p class="auth-cart-note">Guest browsing is enabled. Sign in to continue to cart and checkout.</p>
            @endauth
          @else
            <span style="font-size:.85rem;color:var(--muted)">Out of stock</span>
          @endif
        </div>
      </div>
    </div>

    <div class="reviews-wrap" id="reviews">
      @php
        $avgRating = (float) ($product->visible_reviews_avg ?? 0);
        $reviewCount = (int) ($product->visible_reviews_count ?? 0);
        $filledStars = (int) round(max(0, min(5, $avgRating)));
        $stars = str_repeat('★', $filledStars) . str_repeat('☆', 5 - $filledStars);
      @endphp

      <div class="review-summary">
        <h3>Customer Reviews</h3>
        <div>
          <span class="review-stars">{{ $stars }}</span>
          <span class="review-count">
            {{ $reviewCount > 0 ? number_format($avgRating, 1) . ' out of 5 from ' . $reviewCount . ' review(s)' : 'No reviews yet for this product.' }}
          </span>
        </div>
      </div>

      @auth
        @if(($eligibleReviewItems ?? collect())->isNotEmpty())
          <div class="review-form-card">
            <h4 style="font-size:.95rem;margin-bottom:.75rem">Write a Review</h4>
            <form action="{{ route('customer.shop.reviews.store', $product->product_id) }}" method="post">
              @csrf
              <div class="mb-2">
                <label class="form-label" for="order-item-id" style="font-size:.78rem;letter-spacing:.04em;text-transform:uppercase;color:var(--muted)">Delivered Order Item</label>
                <select name="order_item_id" id="order-item-id" class="form-select form-select-sm" required>
                  @foreach($eligibleReviewItems as $eligibleItem)
                    <option value="{{ $eligibleItem->order_item_id }}" @selected((int) ($selectedOrderItemId ?? 0) === (int) $eligibleItem->order_item_id)>
                      Order #{{ $eligibleItem->order_id }} - {{ optional($eligibleItem->order->placed_at)->format('M d, Y') ?? 'Delivered order' }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="mb-2">
                <label class="form-label" for="review-rating" style="font-size:.78rem;letter-spacing:.04em;text-transform:uppercase;color:var(--muted)">Rating</label>
                <select name="rating" id="review-rating" class="form-select form-select-sm" required>
                  <option value="5">5 - Excellent</option>
                  <option value="4">4 - Very Good</option>
                  <option value="3">3 - Good</option>
                  <option value="2">2 - Fair</option>
                  <option value="1">1 - Poor</option>
                </select>
              </div>
              <div class="mb-2">
                <label class="form-label" for="review-title" style="font-size:.78rem;letter-spacing:.04em;text-transform:uppercase;color:var(--muted)">Title (optional)</label>
                <input type="text" id="review-title" name="title" class="form-control form-control-sm" maxlength="150" placeholder="Short headline for your review">
              </div>
              <div class="mb-2">
                <label class="form-label" for="review-body" style="font-size:.78rem;letter-spacing:.04em;text-transform:uppercase;color:var(--muted)">Review (optional)</label>
                <textarea id="review-body" name="body" class="form-control form-control-sm" rows="3" maxlength="1500" placeholder="Share your experience with this product."></textarea>
              </div>
              <button type="submit" class="btn-add-cart">Submit Review</button>
            </form>
            <p class="review-note">You can only review items from orders marked Delivered.</p>
          </div>
        @else
          <div class="review-form-card">
            <h4 style="font-size:.95rem;margin-bottom:.35rem">Write a Review</h4>
            <p class="review-note mb-0">You can submit a review after this product is marked Delivered in your orders.</p>
          </div>
        @endif
      @else
        <div class="review-form-card">
          <h4 style="font-size:.95rem;margin-bottom:.35rem">Write a Review</h4>
          <p class="review-note mb-0">Please <a href="{{ route('index') }}#login">log in</a> and complete a purchase to leave a review.</p>
        </div>
      @endauth

      <div class="review-list-card">
        @forelse(($reviews ?? collect()) as $review)
          <div class="review-list-item">
            @if($editReview && (int) $editReview->review_id === (int) $review->review_id)
              {{-- Inline edit form for this review --}}
              <div class="review-meta" style="margin-bottom:.6rem">
                <div class="review-author d-flex align-items-center mb-1">
                  {{ $review->user->full_name ?? 'Customer' }}
                  <span class="badge bg-success bg-opacity-10 text-success border border-success ms-2 fw-normal d-flex align-items-center" style="font-size:0.65rem; padding: 0.2rem 0.4rem; letter-spacing: 0.03em;"><i class="bi bi-patch-check-fill me-1"></i>Verified Purchase</span>
                  <span style="font-size:.75rem;color:var(--muted);font-weight:400;margin-left:.4rem;">— Editing</span>
                </div>
                <div class="review-date">{{ optional($review->created_at)->format('M d, Y') }}</div>
              </div>
              <form action="{{ route('customer.shop.reviews.update', [$product->product_id, $editReview->review_id]) }}" method="post">
                @csrf
                @method('PUT')
                <div style="margin-bottom:.5rem">
                  <label class="form-label" for="edit-rating" style="font-size:.78rem;letter-spacing:.04em;text-transform:uppercase;color:var(--muted)">Rating</label>
                  <select name="rating" id="edit-rating" class="form-select form-select-sm" required>
                    <option value="5" {{ old('rating', $editReview->rating) == 5 ? 'selected' : '' }}>5 - Excellent</option>
                    <option value="4" {{ old('rating', $editReview->rating) == 4 ? 'selected' : '' }}>4 - Very Good</option>
                    <option value="3" {{ old('rating', $editReview->rating) == 3 ? 'selected' : '' }}>3 - Good</option>
                    <option value="2" {{ old('rating', $editReview->rating) == 2 ? 'selected' : '' }}>2 - Fair</option>
                    <option value="1" {{ old('rating', $editReview->rating) == 1 ? 'selected' : '' }}>1 - Poor</option>
                  </select>
                </div>
                <div style="margin-bottom:.5rem">
                  <label class="form-label" for="edit-title" style="font-size:.78rem;letter-spacing:.04em;text-transform:uppercase;color:var(--muted)">Title (optional)</label>
                  <input type="text" id="edit-title" name="title" class="form-control form-control-sm" maxlength="150" value="{{ old('title', $editReview->title) }}">
                </div>
                <div style="margin-bottom:.5rem">
                  <label class="form-label" for="edit-body" style="font-size:.78rem;letter-spacing:.04em;text-transform:uppercase;color:var(--muted)">Review (optional)</label>
                  <textarea id="edit-body" name="body" class="form-control form-control-sm" rows="3" maxlength="1500">{{ old('body', $editReview->body) }}</textarea>
                </div>
                <div class="d-flex gap-2">
                  <button type="submit" class="btn-add-cart">Save Changes</button>
                  <a href="{{ route('customer.shop.show', $product->product_id) }}#reviews" class="btn-back" style="padding:.55rem .9rem;font-size:.82rem">Cancel</a>
                </div>
              </form>
            @else
              {{-- Normal review display --}}
              <div class="review-meta">
                <div class="review-author d-flex align-items-center mb-1">
                  {{ $review->user->full_name ?? 'Customer' }}
                  <span class="badge bg-success bg-opacity-10 text-success border border-success ms-2 fw-normal d-flex align-items-center" style="font-size:0.65rem; padding: 0.2rem 0.4rem; letter-spacing: 0.03em;"><i class="bi bi-patch-check-fill me-1"></i>Verified Purchase</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span class="review-date">{{ optional($review->created_at)->format('M d, Y') }}</span>
                  @auth
                    @if((int) $review->user_id === (int) Auth::id())
                      <a href="{{ route('customer.shop.show', $product->product_id) }}?edit_review={{ $review->review_id }}#reviews" style="font-size:.85rem;color:var(--blue);text-decoration:none;font-weight:600" title="Edit Review"><i class="bi bi-pencil-square"></i></a>
                      <form action="{{ route('customer.shop.reviews.destroy', [$product->product_id, $review->review_id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete this review?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-danger ms-1" style="font-size:.85rem;text-decoration:none;font-weight:600" title="Delete Review"><i class="bi bi-trash"></i></button>
                      </form>
                    @endif
                  @endauth
                </div>
              </div>
              <div class="review-stars" style="font-size:.82rem;margin-bottom:.3rem">
                {{ str_repeat('★', (int) $review->rating) . str_repeat('☆', 5 - (int) $review->rating) }}
              </div>
              @if($review->title)
                <div class="review-title">{{ $review->title }}</div>
              @endif
              @if($review->body)
                <p class="review-body">{{ $review->body }}</p>
              @endif
            @endif
          </div>
        @empty
          <p class="review-note mb-0">No reviews yet. Be the first to share feedback after completing a purchase.</p>
        @endforelse
      </div>
    </div>
  </div>
</section>
@endsection
