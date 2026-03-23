@extends('layouts.admin')

@section('title', 'LaptopHub — Restocks')
@section('active_nav', 'restock')
@section('page_title', 'Restocks')
@section('page_subtitle', 'Record product inventory restocks and monitor log history.')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-index.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css"/>
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
                    <form action="{{ route('admin.restock.store') }}" method="POST" id="restockForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label" style="font-size:.85rem; font-weight:600;">Supplier <span class="text-danger">*</span></label>
                            <select id="supplier_id" name="supplier_id" class="form-select" required onchange="window.location=this.value ? '{{ route('admin.restock.index') }}?supplier_id=' + this.value : '{{ route('admin.restock.index') }}'">
                                <option value="">Select a supplier...</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}" {{ request('supplier_id') == $supplier->supplier_id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">Select a supplier first to filter available products.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-size:.85rem; font-weight:600;">Product <span class="text-danger">*</span></label>
                            <select name="product_id" id="product_id" class="form-select" required>
                                <option value="">Select a product...</option>
                                @if($selectedSupplierId && count($products) > 0)
                                    @foreach($products as $product)
                                        <option value="{{ $product->product_id }}" {{ old('product_id') == $product->product_id ? 'selected' : '' }}>{{ $product->name }} (In Stock: {{ $product->stock_qty }})</option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Please select a supplier first</option>
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-size:.85rem; font-weight:600;">Transaction Type <span class="text-danger">*</span></label>
                            <select name="transaction_type" id="transaction_type" class="form-select" required>
                                <option value="add">➕ Add / Restock (Stock ↑, Cost deducted)</option>
                                <option value="adjust">⚖️ Adjust (Stock ±, No revenue impact)</option>
                                <option value="remove">➖ Remove (Stock ↓, Cost refunded)</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label" style="font-size:.85rem; font-weight:600;">Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="{{ old('quantity') }}" required>
                            </div>

                            <div class="col-6 mb-3">
                                <label class="form-label" style="font-size:.85rem; font-weight:600;">Unit Cost (₱) <span class="text-danger">*</span></label>
                                <input type="number" name="unit_cost" id="unit_cost" class="form-control" step="0.01" min="0" value="{{ old('unit_cost') }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" style="font-size:.85rem; font-weight:600;">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
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

            <div class="table-card mt-3">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Restock History</h5>
                    <div id="table-buttons"></div>
                </div>
                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-hover mb-0 align-middle w-100', 'id' => 'restocksTable']) !!}
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                var table = window.LaravelDataTables["restocksTable"];
                table.buttons().container().appendTo('#table-buttons');
            }, 300);
        });
    </script>
@endpush
@endsection

