@extends('layouts.admin')

@section('title', 'LaptopHub - Inventory Stock Report')
@section('active_nav', 'inventory_report')
@section('page_title', 'Inventory Stock Report')
@section('page_subtitle', 'Live valuation and aggregated tracking of all catalog units')

@section('admin_content')
<div class="row mb-5 g-4">
    <div class="col-md-6 col-lg-8">
        <div class="card text-white shadow-lg border-0 h-100" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
            <div class="card-body p-5 position-relative overflow-hidden">
                <i class="bi bi-box-seam position-absolute text-white" style="font-size: 8rem; right: -20px; bottom: -20px; opacity: 0.1; transform: rotate(10deg);"></i>
                <div class="position-relative z-index-1">
                    <h6 class="text-uppercase mb-2" style="color: rgba(255,255,255,0.8); letter-spacing: 1.5px; font-weight: 600;">Total Inventory Value</h6>
                    <h1 class="display-4 fw-bolder mb-0 text-white shadow-sm">P{{ number_format($totalStockValue, 2) }}</h1>
                    <p class="mt-3 mb-0" style="color: rgba(255,255,255,0.7); font-size: 0.9rem; font-weight: 500;">
                        <i class="bi bi-calculator me-1"></i> Estimated value of all current warehouse stock
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="card text-dark shadow-lg border-0 h-100" style="background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);">
            <div class="card-body p-4 d-flex flex-column justify-content-center text-center">
                <i class="bi bi-upc-scan mb-3 text-dark" style="font-size: 3rem; opacity: 0.8;"></i>
                <h2 class="display-5 fw-bold mb-1">{{ count($products) }}</h2>
                <h6 class="text-uppercase" style="color: rgba(0,0,0,0.6); letter-spacing: 1px; font-weight: 600;">Active SKU Models</h6>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h5 class="mb-0" style="font-weight:600">Current Inventory Levels</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-nowrap">
            <thead class="table-light">
                <tr>
                    <th>Product Model Name</th>
                    <th>Category Mapping</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Current Stock</th>
                    <th class="text-end">Total Value</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                @php
                    $lineValue = $product->price * $product->stock_qty;
                    $isLow = $product->stock_qty <= $product->low_stock_threshold;
                @endphp
                <tr {!! $isLow ? 'style="background-color: #fff9f9;"' : '' !!}>
                    <td>
                        <div style="font-weight:500;">{{ $product->name }}</div>
                        <div style="font-size: .75rem; color: #777;">Brand Core: {{ $product->brand_id }}</div>
                    </td>
                    <td><span class="badge bg-light text-dark border">{{ $product->category->category_name ?? 'N/A' }}</span></td>
                    <td class="text-end">P{{ number_format($product->price, 2) }}</td>
                    <td class="text-end {!! $isLow ? 'text-danger fw-bold' : '' !!}">{{ number_format($product->stock_qty) }}</td>
                    <td class="text-end" style="color:var(--accent2); font-weight:600">P{{ number_format($lineValue, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">No products available in the database.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
