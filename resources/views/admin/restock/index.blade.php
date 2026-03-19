@extends('layouts.admin')

@section('title', 'LaptopHub — Restocks')
@section('active_nav', 'restock')
@section('page_title', 'Restocks')
@section('page_subtitle', 'Record product inventory restocks and monitor log history.')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-index.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    @if(strtolower(auth()->user()->role->role_name ?? '') === 'admin')
        <a href="{{ route('admin.reports.expenses') }}" class="btn btn-info btn-sm text-white"><i class="bi bi-graph-up-arrow me-1"></i>View Expense Report</a>
    @endif
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
@endsection

@section('admin_content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <!-- Record Restock Form -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">Record Restock</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.restock.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" style="font-size:.85rem; font-weight:600;">Transaction Type</label>
                            <select name="transaction_type" class="form-select" required>
                                <option value="add">Add Stock (Restock)</option>
                                <option value="subtract">Remove Stock (Correction)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-size:.85rem; font-weight:600;">Product</label>
                            <select name="product_id" class="form-select" required>
                                <option value="">Select a product...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->product_id }}">{{ $product->name }} (In Stock: {{ $product->stock_qty }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-size:.85rem; font-weight:600;">Supplier</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">Select a supplier...</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label" style="font-size:.85rem; font-weight:600;">Quantity Added</label>
                                <input type="number" name="quantity_added" class="form-control" min="1" required>
                            </div>

                            <div class="col-6 mb-3">
                                <label class="form-label" style="font-size:.85rem; font-weight:600;">Unit Cost (₱)</label>
                                <input type="number" name="unit_cost" class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" style="font-size:.85rem; font-weight:600;">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-dark w-100">Save Restock</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Restock History Table -->
        <div class="col-lg-8">
            <div class="filter-card mb-3 p-3 bg-white border rounded shadow-sm">
                <form method="GET" action="{{ route('admin.restock.index') }}" class="row g-2 align-items-end">
                    <div class="col-6 col-md-3">
                        <label class="form-label" style="font-size: .8rem;">From Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label" style="font-size: .8rem;">To Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control form-control-sm">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" style="font-size: .8rem;">Product</label>
                        <select name="product_id" class="form-select form-select-sm">
                            <option value="">All Products</option>
                            @foreach($products as $product)
                                <option value="{{ $product->product_id }}" {{ request('product_id') == $product->product_id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-8 col-md-2">
                        <label class="form-label" style="font-size: .8rem;">Supplier</label>
                        <select name="supplier_id" class="form-select form-select-sm">
                            <option value="">All Suppliers</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->supplier_id }}" {{ request('supplier_id') == $supplier->supplier_id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4 col-md-1 d-flex gap-1 justify-content-end">
                        <button class="btn btn-dark btn-sm w-100" type="submit" title="Filter"><i class="bi bi-funnel"></i></button>
                        <a href="{{ route('admin.restock.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear Filters"><i class="bi bi-x"></i></a>
                    </div>
                </form>
            </div>

            <div class="table-card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">Restock History</h5>
                    <span class="text-muted" style="font-size:.78rem">Viewing {{ $restocks->count() }} of {{ number_format($restocks->total()) }} total logs</span>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Supplier</th>
                                <th>Restocked By</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Unit Cost</th>
                                <th class="text-end">Total Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($restocks as $restock)
                                <tr>
                                    <td>{{ $restock->restocked_at->format('M d, Y') }}<br><span class="text-muted" style="font-size:.75rem">{{ $restock->restocked_at->format('h:i A') }}</span></td>
                                    <td>
                                        <strong>{{ optional($restock->product)->name ?? 'Unknown' }}</strong>
                                        @if($restock->notes)
                                            <div class="text-muted" style="font-size:.75rem; max-width: 15rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $restock->notes }}">{{ $restock->notes }}</div>
                                        @endif
                                    </td>
                                    <td>{{ optional($restock->supplier)->name ?? 'Unknown' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if(optional($restock->manager)->profile_image_url)
                                                <img src="{{ Storage::url($restock->manager->profile_image_url) }}" class="rounded-circle" style="width:24px;height:24px;object-fit:cover;">
                                            @else
                                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:24px;height:24px;font-size:.6rem;">
                                                    {{ substr(optional($restock->manager)->full_name ?? '?', 0, 1) }}
                                                </div>
                                            @endif
                                            {{ optional($restock->manager)->full_name ?? 'Unknown' }}
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        @if($restock->quantity_added < 0)
                                            <span class="badge bg-danger" style="font-size:.8rem;">{{ $restock->quantity_added }}</span>
                                        @else
                                            <span class="badge bg-success" style="font-size:.8rem;">+{{ $restock->quantity_added }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end text-muted">₱{{ number_format($restock->unit_cost, 2) }}</td>
                                    <td class="text-end fw-bold {{ $restock->quantity_added < 0 ? 'text-danger' : 'text-success' }}">
                                        @if($restock->quantity_added < 0)
                                            (₱{{ number_format(abs($restock->unit_cost * $restock->quantity_added), 2) }})
                                        @else
                                            ₱{{ number_format($restock->unit_cost * $restock->quantity_added, 2) }}
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="empty-state text-center py-5 text-muted">No restocking logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($restocks->hasPages())
                    <div class="pagination-wrap">
                        {{ $restocks->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
