<div style="font-weight: 500;">{{ $review->product->name ?? 'Unknown product' }}</div>
@if($review->product)
    <a href="{{ route('customer.shop.show', $review->product_id) }}" class="btn btn-sm btn-outline-primary mt-1" target="_blank" style="padding: 0.15rem 0.4rem; font-size: 0.72rem;">
        <i class="bi bi-box-arrow-up-right me-1"></i>View Product
    </a>
@endif
