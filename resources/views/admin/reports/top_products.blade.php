@extends('layouts.admin')

@section('title', 'LaptopHub - Top Products Report')
@section('active_nav', 'top_products')
@section('page_title', 'Top Products Report')
@section('page_subtitle', 'System-wide best sellers aggregated from finalized orders exclusively')

@section('admin_content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4 bg-light text-center" style="border-radius: 6px;">
        <i class="bi bi-star text-warning fs-3 d-block mb-3"></i>
        <h5 class="fw-bold mb-2">Most Popular Products</h5>
        <p class="mb-0 text-muted mx-auto" style="max-width: 600px; font-size:.9rem; line-height:1.6">
            A quick overview of your best selling items. This ranking automatically surfaces your most popular products based on completed sales volume.
        </p>
    </div>
</div>

<div class="card mb-4 shadow-sm border-0">
    <div class="card-body bg-light">
        <form action="{{ route('admin.reports.top-products') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label" style="font-weight:600; font-size:.85rem">Date Placed (From)</label>
                <input type="date" class="form-control" name="start_date" value="{{ $startDate ?? '' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-weight:600; font-size:.85rem">Date Placed (To)</label>
                <input type="date" class="form-control" name="end_date" value="{{ $endDate ?? '' }}">
            </div>
            <div class="col-md-4">
                <label class="form-label" style="font-weight:600; font-size:.85rem">Product Category</label>
                <select name="category_id" class="form-select">
                    <option value="">All Categories Collection</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->category_id }}" {{ ($categoryId == $cat->category_id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100" title="Filter"><i class="bi bi-search"></i></button>
                <a href="{{ route('admin.reports.top-products') }}" class="btn btn-outline-secondary" title="Clear"><i class="bi bi-x"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-dark text-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h5 class="mb-0" style="font-weight:600"><i class="bi bi-trophy me-2 text-warning"></i> Best Sellers by Category</h5>
        <span class="badge bg-secondary border border-light text-light px-3 py-2" style="font-weight:500;">Top 20 Models Limit</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-nowrap">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;" class="text-center">Rank</th>
                    <th>Product Model Name</th>
                    <th>Category Mapping</th>
                    <th class="text-end">Units Sold</th>
                    <th class="text-end">Total Gross Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProducts as $index => $item)
                <tr>
                    <td class="text-center">
                        @if($index === 0)
                            <span class="badge rounded-circle bg-warning text-dark" style="width:28px;height:28px;line-height:18px;font-size:.9rem; border:2px solid #dfa510">1</span>
                        @elseif($index === 1)
                            <span class="badge rounded-circle bg-secondary text-white" style="width:28px;height:28px;line-height:18px;font-size:.9rem; border:2px solid #888">2</span>
                        @elseif($index === 2)
                            <span class="badge rounded-circle text-white" style="background:#cd7f32; width:28px;height:28px;line-height:18px;font-size:.9rem; border:2px solid #a05c1d">3</span>
                        @else
                            <span style="font-weight: 600; color:#888;">{{ $index + 1 }}</span>
                        @endif
                    </td>
                    <td>
                        <strong style="font-size:.9rem;">{{ $item->product_name }}</strong>
                    </td>
                    <td><span class="badge bg-light text-dark border">{{ $item->category_name ?? 'Uncategorized' }}</span></td>
                    <td class="text-end">
                        <span class="badge bg-dark text-white px-2 py-1 fs-6">{{ number_format($item->total_sold) }}</span>
                    </td>
                    <td class="text-end" style="color:var(--accent2); font-weight:700; font-size:.95rem">
                        P{{ number_format($item->total_revenue, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">Insufficient sales volume to map aggregation sets.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
