@extends('layouts.admin')

@section('title', 'LaptopHub - Supplier Details')
@section('active_nav', 'supplier')
@section('page_title', 'Supplier Profile')
@section('page_subtitle', 'Viewing supplier details and managed catalogue mapping.')

@section('topbar_actions')
    <a href="{{ route('admin.supplier.edit', $supplier) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square me-1"></i>Edit Supplier</a>
    <a href="{{ route('admin.supplier.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to List</a>
@endsection

@section('admin_content')
    <div class="row">
        <!-- Supplier Meta -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-building me-2"></i>{{ $supplier->name }}</h6>
                    @if ($supplier->deleted_at)
                        <span class="badge bg-danger">Trashed</span>
                    @elseif ($supplier->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-warning text-dark">Inactive</span>
                    @endif
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted" style="font-weight: 600; font-size:.85rem">Contact</dt>
                        <dd class="col-sm-8">{{ $supplier->contact_name ?: 'System Default / Unset' }}</dd>

                        <dt class="col-sm-4 text-muted" style="font-weight: 600; font-size:.85rem">Email</dt>
                        <dd class="col-sm-8">
                            @if($supplier->contact_email)
                                <a href="mailto:{{ $supplier->contact_email }}">{{ $supplier->contact_email }}</a>
                            @else
                                <span class="text-muted">No Email</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4 text-muted" style="font-weight: 600; font-size:.85rem">Phone</dt>
                        <dd class="col-sm-8">{{ $supplier->contact_phone ?: 'No Phone Number' }}</dd>

                        <dt class="col-sm-4 text-muted" style="font-weight: 600; font-size:.85rem">Address</dt>
                        <dd class="col-sm-8 mb-0">{{ $supplier->address ?: 'No Address Registered' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Catalogue Table -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Provided Products</h5>
                    <span class="text-muted" style="font-size:.78rem">Supplier catalog includes {{ $supplier->products->count() }} active items</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Title</th>
                                    <th>Price</th>
                                    <th class="text-center">Current Stock</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supplier->products as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($product->image_url)
                                                    <img src="{{ Storage::url($product->image_url) }}" alt="Thumbnail" class="bg-light rounded me-3" style="width:40px;height:40px;object-fit:contain;">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                        <i class="bi bi-box text-muted"></i>
                                                    </div>
                                                @endif
                                                <strong>{{ $product->name }}</strong>
                                            </div>
                                        </td>
                                        <td>₱{{ number_format($product->price, 2) }}</td>
                                        <td class="text-center">
                                            @if($product->stock_qty <= 0)
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @elseif($product->stock_qty <= $product->low_stock_threshold)
                                                <span class="badge bg-warning text-dark">{{ $product->stock_qty }} left</span>
                                            @else
                                                <span class="badge bg-success">{{ $product->stock_qty }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.product.show', $product->product_id) }}" class="btn btn-sm btn-outline-secondary" title="View Product"><i class="bi bi-chevron-right"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-box-seam fs-2 mb-2"></i><br>
                                            This supplier currently has no attached active products.
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
