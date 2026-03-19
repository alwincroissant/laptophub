@extends('layouts.admin')

@section('title', 'LaptopHub - Expense Reports')
@section('active_nav', 'expenses')
@section('page_title', 'Capital Expense Report')
@section('page_subtitle', 'Overview of product allocation and historical restocking costs.')

@section('topbar_actions')
    <a href="{{ route('admin.restock.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Restocks</a>
@endsection

@section('admin_content')

    <!-- Filter Bar -->
    <div class="filter-card mb-4 p-3 bg-white border rounded shadow-sm">
        <form method="GET" action="{{ route('admin.reports.expenses') }}" class="row g-2 align-items-end">
            <div class="col-6 col-md-3">
                <label class="form-label text-uppercase text-muted" style="letter-spacing: 1px; font-weight:600; font-size: 0.7rem;">From Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control form-control-sm">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label text-uppercase text-muted" style="letter-spacing: 1px; font-weight:600; font-size: 0.7rem;">To Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control form-control-sm">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label text-uppercase text-muted" style="letter-spacing: 1px; font-weight:600; font-size: 0.7rem;">Product</label>
                <select name="product_id" class="form-select form-select-sm">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->product_id }}" {{ request('product_id') == $product->product_id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-8 col-md-2">
                <label class="form-label text-uppercase text-muted" style="letter-spacing: 1px; font-weight:600; font-size: 0.7rem;">Supplier</label>
                <select name="supplier_id" class="form-select form-select-sm">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->supplier_id }}" {{ request('supplier_id') == $supplier->supplier_id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4 col-md-1 d-flex gap-1 justify-content-end">
                <button class="btn btn-dark btn-sm w-100" type="submit" title="Filter"><i class="bi bi-funnel"></i></button>
                <a href="{{ route('admin.reports.expenses') }}" class="btn btn-outline-secondary btn-sm" title="Clear Filters"><i class="bi bi-x"></i></a>
            </div>
        </form>
    </div>

    <!-- Expenses Metric Ribbon -->
    <div class="row align-items-stretch mb-5">
        <div class="col-lg-5 mb-4 mb-lg-0">
            <div class="card justify-content-center shadow-lg border-0 text-white h-100" style="background: linear-gradient(135deg, #cb2d3e 0%, #ef473a 100%);">
                <div class="card-body position-relative overflow-hidden p-5 d-flex flex-column justify-content-center">
                    <i class="bi bi-piggy-bank position-absolute text-white" style="font-size: 10rem; right: -15px; top: -20px; opacity: 0.1;"></i>
                    <div class="position-relative z-index-1">
                        <h6 class="text-uppercase mb-2 text-white-50" style="letter-spacing: 1.5px; font-weight: 600;">Gross Capital Expenditure</h6>
                        <h1 class="display-4 fw-bolder mb-0 text-white shadow-sm">₱{{ number_format($totalExpenses, 2) }}</h1>
                        <p class="mt-3 mb-0 text-white-50" style="font-size: 0.85rem; font-weight: 500;">
                            <i class="bi bi-cart-check me-1"></i> Total spent on supplier restocks
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">Top 10 Application Expenses (By Product)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle table-borderless table-striped">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th style="font-size: 0.85rem; padding-left: 1.5rem;">Product Model</th>
                                    <th class="text-end" style="font-size: 0.85rem; padding-right: 1.5rem;">Allocated Capital</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($expensesPerProduct as $expense)
                                    <tr>
                                        <td style="padding-left: 1.5rem; font-size:.95rem;"><strong>{{ $expense->product_name }}</strong></td>
                                        <td class="text-end text-success fw-bold" style="padding-right: 1.5rem; font-size:.95rem;">₱{{ number_format($expense->total_cost, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-5 text-muted">
                                            <i class="bi bi-clipboard-data fs-2 mb-2 d-block opacity-50"></i>
                                            No expense data dynamically matches these filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
