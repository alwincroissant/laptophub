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



    <div class="table-card">
        <div class="card-header">
            <h5>Review List</h5>
            <span class="text-muted" style="font-size:.78rem">{{ number_format($reviews->count()) }} total results</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0" id="reviewsTable">
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
                        <td>
                            <div style="font-weight: 500;">{{ $review->product->name ?? 'Unknown product' }}</div>
                            @if($review->product)
                                <a href="{{ route('customer.shop.show', $review->product_id) }}" class="btn btn-sm btn-outline-primary mt-1" target="_blank" style="padding: 0.15rem 0.4rem; font-size: 0.72rem;">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>View Product
                                </a>
                            @endif
                        </td>
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
                                <a href="{{ route('admin.review.edit', $review->review_id) }}" class="btn btn-sm btn-outline-primary" title="Edit review">
                                    <i class="bi bi-pencil"></i>
                                </a>
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


    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<style>
    /* Styling for the custom DataTables wrapper */
    .dataTables_wrapper .row {
        align-items: center;
        margin-bottom: 0.5rem;
        padding: 0 1rem;
    }
    .dataTables_wrapper .dataTables_paginate {
        padding: 0.5rem 1rem;
    }
    .dataTables_wrapper .dataTables_info {
        padding: 1rem;
    }
    /* Ensure action icons align properly */
    .table td { vertical-align: middle; }
    
    .status-badge {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        letter-spacing: 0.5px;
    }
    .badge-delivered { background-color: #d1fae5; color: #065f46; border: 1px solid #10b981; }
    .badge-cancelled { background-color: #fce7f3; color: #9d174d; border: 1px solid #ec4899; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#reviewsTable').DataTable({
            "pageLength": 10,
            "order": [], // Default no predefined DB sort override
            "language": {
                "search": "",
                "searchPlaceholder": "Search reviews..."
            }
        });

        // Add custom filters right into the DataTables search container
        var visibilityFilter = $('<select class="form-select form-select-sm ms-2 d-inline-block w-auto"><option value="">All Visibilities</option><option value="Visible">Visible</option><option value="Hidden">Hidden</option></select>');
        var ratingFilter = $('<select class="form-select form-select-sm ms-2 d-inline-block w-auto"><option value="">All Ratings</option><option value="★★★★★">5 Stars</option><option value="★★★★☆">4 Stars</option><option value="★★★☆☆">3 Stars</option><option value="★★☆☆☆">2 Stars</option><option value="★☆☆☆☆">1 Star</option></select>');

        $('.dataTables_filter').addClass('d-flex justify-content-end align-items-center mb-3');
        $('.dataTables_filter label').addClass('mb-0 me-2');
        $('.dataTables_filter').append(ratingFilter).append(visibilityFilter);

        visibilityFilter.on('change', function() {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            // Visibility is column index 5
            table.column(5).search(val ? '^' + val + '$' : '', true, false).draw();
        });

        ratingFilter.on('change', function() {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            // Exact string match for stars in Column 4
            table.column(4).search(val ? '^' + val + '$' : '', true, false).draw();
        });
    });
</script>
@endpush
