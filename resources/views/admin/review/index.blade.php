@extends('layouts.admin')

@section('title', 'LaptopHub - Review Moderation')
@section('active_nav', 'review')
@section('page_title', 'Reviews')
@section('page_subtitle', 'Moderate customer reviews and visibility')

@section('admin_content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card red">
                <i class="bi bi-chat-square-text icon"></i>
                <div class="label">Total Reviews</div>
                <div class="value">{{ number_format($metrics['total']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card green">
                <i class="bi bi-eye icon"></i>
                <div class="label">Visible</div>
                <div class="value">{{ number_format($metrics['shown']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card blue">
                <i class="bi bi-eye-slash icon"></i>
                <div class="label">Hidden</div>
                <div class="value">{{ number_format($metrics['hidden']) }}</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card gold">
                <i class="bi bi-star-fill icon"></i>
                <div class="label">Avg Rating</div>
                <div class="value">{{ number_format($metrics['avg_rating'], 1) }}</div>
            </div>
        </div>
    </div>

    <div class="filter-card mb-3">
        <form method="GET" action="{{ route('admin.review.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-6">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Product, customer, order #, title, or review text">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label">Visibility</label>
                <select name="visibility" class="form-select">
                    <option value="all" {{ $visibility === 'all' ? 'selected' : '' }}>All</option>
                    <option value="shown" {{ $visibility === 'shown' ? 'selected' : '' }}>Visible</option>
                    <option value="hidden" {{ $visibility === 'hidden' ? 'selected' : '' }}>Hidden</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Rating</label>
                <select name="rating" class="form-select">
                    <option value="0" {{ (int) $rating === 0 ? 'selected' : '' }}>All</option>
                    <option value="5" {{ (int) $rating === 5 ? 'selected' : '' }}>5 stars</option>
                    <option value="4" {{ (int) $rating === 4 ? 'selected' : '' }}>4 stars</option>
                    <option value="3" {{ (int) $rating === 3 ? 'selected' : '' }}>3 stars</option>
                    <option value="2" {{ (int) $rating === 2 ? 'selected' : '' }}>2 stars</option>
                    <option value="1" {{ (int) $rating === 1 ? 'selected' : '' }}>1 star</option>
                </select>
            </div>
            <div class="col-12 col-md-1 d-flex">
                <button class="btn btn-dark w-100" type="submit">Go</button>
            </div>
        </form>
    </div>

    <div class="table-card">
        <div class="card-header">
            <h5>Review List</h5>
            <span class="text-muted" style="font-size:.78rem">{{ number_format($reviews->total()) }} total results</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th>Review</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Order</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($reviews as $review)
                    <tr>
                        <td style="min-width:280px">
                            <div style="font-weight:600">{{ $review->title ?: 'No title' }}</div>
                            <div class="text-muted" style="font-size:.78rem;line-height:1.45">
                                {{ \Illuminate\Support\Str::limit((string) ($review->body ?: 'No review text provided.'), 120) }}
                            </div>
                        </td>
                        <td>{{ $review->product->name ?? 'Unknown product' }}</td>
                        <td>
                            <div>{{ $review->user->full_name ?? 'Unknown user' }}</div>
                            <div class="text-muted" style="font-size:.76rem">{{ $review->user->email ?? 'No email' }}</div>
                        </td>
                        <td>
                            @if($review->orderItem)
                                <span class="mono-id">#{{ $review->orderItem->order_id }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <span style="color:#b8860b">{{ str_repeat('★', (int) $review->rating) . str_repeat('☆', 5 - (int) $review->rating) }}</span>
                        </td>
                        <td>
                            @if($review->is_visible)
                                <span class="status-badge badge-delivered">Visible</span>
                            @else
                                <span class="status-badge badge-cancelled">Hidden</span>
                            @endif
                        </td>
                        <td>{{ optional($review->created_at)->format('M d, Y h:i A') ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.review.toggle-visibility', $review->review_id) }}" method="POST" style="margin:0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $review->is_visible ? 'btn-outline-secondary' : 'btn-outline-success' }}" title="{{ $review->is_visible ? 'Hide review' : 'Show review' }}">
                                        <i class="bi {{ $review->is_visible ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                    </button>
                                </form>

                                <form action="{{ route('admin.review.destroy', $review->review_id) }}" method="POST" style="margin:0" onsubmit="return confirm('Delete this review permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete review">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No reviews found for this filter.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($reviews->hasPages())
            <div class="p-3 border-top">
                {{ $reviews->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
